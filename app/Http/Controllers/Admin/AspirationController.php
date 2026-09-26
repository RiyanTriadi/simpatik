<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aspiration;
use Illuminate\Http\Request;

class AspirationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $aspirations = Aspiration::latest();

        if (request('search')) {
            $searchTerm = '%' . $request->search . '%';

            $aspirations->where(function ($query) use ($searchTerm) {
                $query->where('subject', 'LIKE', $searchTerm)->orWhere('reporter_name', 'LIKE', $searchTerm);
            });
        }

        $aspirations = $aspirations->paginate(10)->withQueryString();
        return view('admin.aspirations.index', compact('aspirations'));
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
        $aspiration = Aspiration::with('category')->findOrFail($id);

        return view('admin.aspirations.show', compact('aspiration'));
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
        $aspiration = Aspiration::findOrFail($id);
        $aspiration->update($request->only('status'));
        return redirect()->back()->with('success', 'Status aspirasi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function followUp(Request $request)
    {
        $aspirations = Aspiration::whereIn('status', [Aspiration::STATUS_BARU, Aspiration::STATUS_DIBACA])->latest();

        if ($request->has('search')) {
            $searchTerm = '%' . $request->search . '%';
            $aspirations->where(function ($query) use ($searchTerm) {
                $query->where('subject', 'LIKE', $searchTerm)
                    ->orWhere('reporter_name', 'LIKE', $searchTerm);
            });
        }

        $aspirations = $aspirations->paginate(10)->withQueryString();
        return view('admin.aspirations.follow_up', compact('aspirations'));
    }
}
