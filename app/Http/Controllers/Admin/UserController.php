<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('unit');

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $users->where(function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', $searchTerm)
                    ->orWhere('email', 'LIKE', $searchTerm)
                    ->orWhere('phone', 'LIKE', $searchTerm);
            });
        }

        if ($request->filled('role')) {
            $users->where('role', $request->role);
        }

        if ($request->filled('unit_id')) {
            $users->where('unit_id', $request->unit_id);
        }

        $users = $users->latest()->paginate(10)->withQueryString();
        $units = Unit::active()->orderBy('name')->get();

        return view('admin.users.index', compact('users', 'units'));
    }

    public function create()
    {
        $units = Unit::active()->orderBy('name')->get();
        return view('admin.users.create', compact('units'));
    }

    public function edit(User $user)
    {
        $units = Unit::active()->orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'units'));
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('profile_image')) {
            $validated['profile_image_path'] = $request->file('profile_image')
                ->store('profile-images', 'public');
        }

        unset($validated['profile_image']);

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image_path && Storage::disk('public')->exists($user->profile_image_path)) {
                Storage::disk('public')->delete($user->profile_image_path);
            }
            $validated['profile_image_path'] = $request->file('profile_image')
                ->store('profile-images', 'public');
        }

        unset($validated['profile_image']);

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->profile_image_path && Storage::disk('public')->exists($user->profile_image_path)) {
            Storage::disk('public')->delete($user->profile_image_path);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }



    public function show(User $user)
    {
        abort(404);
    }
}