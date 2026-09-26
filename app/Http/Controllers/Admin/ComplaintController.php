<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $complaints = Complaint::latest();

        if (request('search')) {
            $searchTerm = '%' . $request->search . '%';

            $complaints->where(function ($query) use ($searchTerm) {
                $query->where('subject', 'LIKE', $searchTerm)->orWhere('reporter_name', 'LIKE', $searchTerm);
            });
        }

        $complaints = $complaints->paginate(10)->withQueryString();
        return view('admin.complaints.index', compact('complaints'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $complaint = Complaint::with('category')->findOrFail($id);

        return view('admin.complaints.show', compact('complaint'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->update($request->only('status'));
        return redirect()->back()->with('success', 'Status pengaduan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function verification(Request $request)
    {
        $complaints = Complaint::where('status', Complaint::STATUS_BARU)->latest();

        if ($request->has('search')) {
            $searchTerm = '%' . $request->search . '%';
            $complaints->where(function ($query) use ($searchTerm) {
                $query->where('subject', 'LIKE', $searchTerm)
                    ->orWhere('reporter_name', 'LIKE', $searchTerm);
            });
        }

        $complaints = $complaints->paginate(10)->withQueryString();
        return view('admin.complaints.verification', compact('complaints'));
    }
}
