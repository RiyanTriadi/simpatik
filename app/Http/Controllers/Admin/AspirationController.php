<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aspiration;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AspirationController extends Controller
{
    public function index(Request $request)
    {
        $query = Aspiration::with('category');
        $user = auth()->user();

        if ($user && $user->role === 'petugas') {
            $query->where('assigned_to', $user->id);
        }

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('subject', 'LIKE', $searchTerm)
                    ->orWhere('reporter_name', 'LIKE', $searchTerm);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $aspirations = $query->latest()->paginate(10)->withQueryString();

        return view('admin.aspirations.index', compact('aspirations'));
    }

    public function show(Aspiration $aspiration)
    {
        $aspiration->load(['category', 'officer.unit']);

        if (auth()->user()->role === User::ROLE_PETUGAS && $aspiration->assigned_to !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke aspirasi ini.');
        }

        $officers = collect();
        if (in_array(auth()->user()->role, [User::ROLE_ADMIN, User::ROLE_STAFF], true)) {
            $officers = User::query()
                ->where('role', User::ROLE_PETUGAS)
                ->with('unit')
                ->orderBy('name')
                ->get(['id', 'name', 'unit_id']);
        }

        return view('admin.aspirations.show', compact('aspiration', 'officers'));
    }

    public function downloadAttachment(Aspiration $aspiration)
    {
        if (auth()->user()->role === User::ROLE_PETUGAS && $aspiration->assigned_to !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke aspirasi ini.');
        }

        $path = $aspiration->attachment_path;

        abort_if(!$path || !Storage::disk('public')->exists($path), 404);

        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return Storage::disk('public')->download(
            $path,
            $aspiration->ticket_number . ($extension ? '.' . $extension : '')
        );
    }

    public function update(Request $request, Aspiration $aspiration)
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:baru,dibaca,ditindaklanjuti,ditolak',
        ]);

        $aspiration->update($validated);

        User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_STAFF])
            ->each(fn (User $user) => $user->notify(new SystemNotification(
                'Status aspirasi berubah',
                "Status {$aspiration->ticket_number} diperbarui menjadi {$aspiration->status}.",
                route($user->role . '.aspirasi.show', $aspiration->ticket_number),
            )));

        return redirect()->back()->with('success', 'Status aspirasi berhasil diperbarui.');
    }

    public function assignStore(Request $request, Aspiration $aspiration)
    {
        $validated = $request->validate([
            'assigned_to' => [
                'required',
                Rule::exists('users', 'id')->where('role', User::ROLE_PETUGAS),
            ],
        ]);

        $aspiration->update([
            'assigned_to' => $validated['assigned_to'],
            'assigned_at' => now(),
            'status' => Aspiration::STATUS_DITINDAKLANJUTI,
        ]);

        $officer = User::find($validated['assigned_to']);
        $officer?->notify(new SystemNotification(
            'Aspirasi ditugaskan',
            "Anda mendapat tugas untuk tiket {$aspiration->ticket_number}.",
            route(User::ROLE_PETUGAS . '.aspirasi.show', $aspiration->ticket_number),
        ));

        return redirect()->back()->with('success', 'Aspirasi berhasil di-assign ke petugas.');
    }

    public function followUp(Request $request)
    {
        $query = Aspiration::with('category')
            ->whereIn('status', [Aspiration::STATUS_BARU, Aspiration::STATUS_DIBACA]);

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('subject', 'LIKE', $searchTerm)
                    ->orWhere('reporter_name', 'LIKE', $searchTerm);
            });
        }

        $aspirations = $query->latest()->paginate(10)->withQueryString();
        $officers = User::query()
            ->where('role', User::ROLE_PETUGAS)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.aspirations.follow_up', compact('aspirations', 'officers'));
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
