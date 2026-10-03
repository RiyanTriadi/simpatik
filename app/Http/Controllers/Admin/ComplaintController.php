<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;

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

    public function show(string $id)
    {
        $complaint = Complaint::with(['category', 'officer.unit'])->findOrFail($id);

        if (auth()->user()->role === 'petugas' && $complaint->assigned_to !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke pengaduan ini.');
        }

        return view('admin.complaints.show', compact('complaint'));
    }

    public function update(Request $request, string $id)
    {
        $complaint = Complaint::findOrFail($id);

        if (auth()->user()->role === 'petugas' && $complaint->assigned_to !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'sometimes|in:baru,diproses,selesai,ditolak',
            'priority' => 'sometimes|in:rendah,sedang,tinggi,urgent',
        ]);

        $complaint->update($validated);

        return redirect()->back()->with('success', 'Pengaduan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->delete();

        return redirect()->route(role_prefix() . '.pengaduan.index')
            ->with('success', 'Pengaduan berhasil dihapus.');
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
            'assigned_to' => ['nullable', 'exists:users,id'],
        ]);

        if (!empty($validated['assigned_to'])) {
            $officer = User::find($validated['assigned_to']);
            if ($officer->role !== User::ROLE_PETUGAS) {
                return redirect()->back()->with('error', 'User yang dipilih bukan seorang petugas.');
            }
        }

        $complaint->update([
            'assigned_to' => $validated['assigned_to'] ?? null,
            'assigned_at' => !empty($validated['assigned_to']) ? now() : null,
        ]);

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