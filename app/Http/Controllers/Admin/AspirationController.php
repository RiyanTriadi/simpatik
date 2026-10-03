<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aspiration;
use Illuminate\Http\Request;

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

    public function show(string $id)
    {
        $aspiration = Aspiration::with('category')->findOrFail($id);

        return view('admin.aspirations.show', compact('aspiration'));
    }

    public function update(Request $request, string $id)
    {
        $aspiration = Aspiration::findOrFail($id);

        $validated = $request->validate([
            'status' => 'sometimes|in:baru,dibaca,ditindaklanjuti,ditolak',
        ]);

        $aspiration->update($validated);

        return redirect()->back()->with('success', 'Status aspirasi berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $aspiration = Aspiration::findOrFail($id);
        $aspiration->delete();

        return redirect()->route(role_prefix() . '.aspirasi.index')
            ->with('success', 'Aspirasi berhasil dihapus.');
    }

    public function assignStore(Request $request, string $id)
    {
        $aspiration = Aspiration::findOrFail($id);

        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $aspiration->update([
            'assigned_to' => $validated['assigned_to'],
            'assigned_at' => now(),
            'status' => Aspiration::STATUS_DIBACA, // optional change status when assigned
        ]);

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

        return view('admin.aspirations.follow_up', compact('aspirations'));
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