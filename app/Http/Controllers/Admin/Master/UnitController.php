<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $units = Unit::query();

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $units->where(function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', $searchTerm)->orWhere('code', 'LIKE', $searchTerm);
            });
        }

        if ($request->filled('status')) {
            $units->where('is_active', $request->status === 'active');
        }

        $units = $units->latest()->paginate(10)->withQueryString();

        return view('admin.master.units.index', compact('units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:units,name',
            'code' => 'nullable|string|max:50|unique:units,code',
            'description' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Unit::create($validated);

        return redirect()->route('admin.master.unit-kerja.index')->with('success', 'Unit kerja berhasil ditambahkan.');
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('units', 'name')->ignore($unit->id)],
            'code' => ['nullable', 'string', 'max:50', Rule::unique('units', 'code')->ignore($unit->id)],
            'description' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $unit->update($validated);

        return redirect()->route('admin.master.unit-kerja.index')
            ->with('success', 'Unit kerja berhasil diperbarui.');
    }

    public function destroy(Unit $unit)
    {
        if ($unit->users()->count() > 0) {
            return redirect()->back()->with('error', 'Unit kerja tidak dapat dihapus karena masih memiliki user terkait.');
        }

        $unit->delete();

        return redirect()->route('admin.master.unit-kerja.index')->with('success', 'Unit kerja berhasil dihapus.');
    }

    public function create()
    {
        abort(404);
    }
    public function show(Unit $unit)
    {
        abort(404);
    }
    public function edit(Unit $unit)
    {
        abort(404);
    }
}