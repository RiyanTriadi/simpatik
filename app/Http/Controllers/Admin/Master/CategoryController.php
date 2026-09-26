<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query();

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $categories->where('name', 'LIKE', $searchTerm);
        }

        if ($request->filled('type')) {
            $categories->where('type', $request->type);
        }

        $categories = $categories->latest()->paginate(10)->withQueryString();

        return view('admin.master.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.master.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'type' => ['required', Rule::in([Category::TYPE_KEDUANYA, Category::TYPE_PENGADUAN, Category::TYPE_ASPIRASI])],
            'description' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Category::create($validated);

        return redirect()->route('admin.master.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function show(Category $category)
    {
        return redirect()->route('admin.master.kategori.edit', $category);
    }

    public function edit(Category $category)
    {
        return view('admin.master.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($category->id)],
            'type' => ['required', Rule::in([Category::TYPE_KEDUANYA, Category::TYPE_PENGADUAN, Category::TYPE_ASPIRASI])],
            'description' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $category->update($validated);

        return redirect()->route('admin.master.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        // Cek apakah kategori masih dipakai
        if ($category->complaints()->count() > 0 || $category->aspirations()->count() > 0) {
            return redirect()->back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh pengaduan atau aspirasi.');
        }

        $category->delete();

        return redirect()->route('admin.master.kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}