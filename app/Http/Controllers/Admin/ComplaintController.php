<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::with(['category', 'officer.unit']);

        if (auth()->user()->role === 'petugas') {
            $query->where('assigned_to', auth()->id());
        }

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('subject', 'LIKE', $searchTerm)
                    ->orWhere('reporter_name', 'LIKE', $searchTerm);
            });
        }

        $complaints = $query->latest()->paginate(10)->withQueryString();

        return view('admin.complaints.index', compact('complaints'));
    }

    public function show(Complaint $complaint)
    {
        $complaint->load(['category', 'officer.unit']);

        if (auth()->user()->role === 'petugas' && $complaint->assigned_to !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke pengaduan ini.');
        }

        $officers = collect();
        if (in_array(auth()->user()->role, [User::ROLE_ADMIN, User::ROLE_STAFF], true)) {
            $officers = User::query()
                ->where('role', User::ROLE_PETUGAS)
                ->with('unit')
                ->orderBy('name')
                ->get(['id', 'name', 'unit_id']);
        }

        return view('admin.complaints.show', compact('complaint', 'officers'));
    }

    public function downloadAttachment(Complaint $complaint)
    {
        if (auth()->user()->role === User::ROLE_PETUGAS && $complaint->assigned_to !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke pengaduan ini.');
        }

        $path = $complaint->attachment_path;

        abort_if(!$path || !Storage::disk('public')->exists($path), 404);

        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return Storage::disk('public')->download(
            $path,
            $complaint->ticket_number . ($extension ? '.' . $extension : '')
        );
    }

    public function update(Request $request, Complaint $complaint)
    {
        if (auth()->user()->role === 'petugas' && $complaint->assigned_to !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'sometimes|in:baru,diproses,selesai,ditolak',
            'priority' => 'sometimes|in:rendah,sedang,tinggi,urgent',
        ]);

        $complaint->update($validated);

        User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_STAFF])
            ->each(fn (User $user) => $user->notify(new SystemNotification(
                'Status pengaduan berubah',
                "Status {$complaint->ticket_number} diperbarui menjadi {$complaint->status}.",
                route($user->role . '.pengaduan.show', $complaint->ticket_number),
            )));

        return redirect()->back()->with('success', 'Pengaduan berhasil diperbarui.');
    }

    public function verification(Request $request)
    {
        $query = Complaint::with(['category', 'officer.unit'])
            ->where('status', Complaint::STATUS_BARU);

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('subject', 'LIKE', $searchTerm)
                    ->orWhere('reporter_name', 'LIKE', $searchTerm);
            });
        }

        $complaints = $query->latest()->paginate(10)->withQueryString();

        return view('admin.complaints.verification', compact('complaints'));
    }

    public function assign(Request $request)
    {
        $query = Complaint::with(['category', 'officer.unit'])
            ->whereIn('status', [Complaint::STATUS_BARU, Complaint::STATUS_DIPROSES]);

        if ($request->filled('assignment')) {
            if ($request->assignment === 'unassigned') {
                $query->whereNull('assigned_to');
            } elseif ($request->assignment === 'assigned') {
                $query->whereNotNull('assigned_to');
            }
        }

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('subject', 'LIKE', $searchTerm)
                    ->orWhere('ticket_number', 'LIKE', $searchTerm)
                    ->orWhere('reporter_name', 'LIKE', $searchTerm);
            });
        }

        $complaints = $query->latest()->paginate(10)->withQueryString();

        $officers = User::where('role', User::ROLE_PETUGAS)
            ->with('unit')
            ->orderBy('name')
            ->get();

        return view('admin.complaints.assign', compact('complaints', 'officers'));
    }

    public function assignStore(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'assigned_to' => [
                'nullable',
                Rule::exists('users', 'id')->where('role', User::ROLE_PETUGAS),
            ],
        ]);

        if ($request->filled('officer_search') && empty($validated['assigned_to'])) {
            return redirect()->back()
                ->withErrors(['assigned_to' => 'Pilih petugas dari daftar yang tersedia.'])
                ->withInput();
        }

        $complaint->update([
            'assigned_to' => $validated['assigned_to'] ?? null,
            'assigned_at' => !empty($validated['assigned_to']) ? now() : null,
        ]);

        if (!empty($validated['assigned_to'])) {
            $officer = User::find($validated['assigned_to']);
            $officer?->notify(new SystemNotification(
                'Pengaduan ditugaskan',
                "Anda mendapat tugas untuk tiket {$complaint->ticket_number}.",
                route(User::ROLE_PETUGAS . '.pengaduan.show', $complaint->ticket_number),
            ));
        }

        return redirect()->back()->with(
            'success',
            !empty($validated['assigned_to'])
            ? 'Pengaduan berhasil di-assign ke petugas.'
            : 'Assign pengaduan berhasil dibatalkan.'
        );
    }

    public function create()
    {
        abort(404);
    }
    public function store(Request $request)
    {
        abort(404);
    }
    public function edit(string $id)
    {
        abort(404);
    }
}
