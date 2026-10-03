<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateProfileRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit(string $id)
    {
        abort_unless((int) $id === auth()->id(), 403, 'Anda hanya dapat mengubah profil sendiri.');

        $user = auth()->user();

        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Update profil user yang sedang login.
     */
    public function update(UpdateProfileRequest $request, string $id)
    {
        abort_unless((int) $id === auth()->id(), 403);

        $user = auth()->user();
        $validated = $request->validated();

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image_path && Storage::disk('public')->exists($user->profile_image_path)) {
                Storage::disk('public')->delete($user->profile_image_path);
            }
            $validated['profile_image_path'] = $request->file('profile_image')
                ->store('profile-images', 'public');
        }
        unset($validated['profile_image']);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        unset($validated['current_password']);

        $user->update($validated);

        return redirect()
            ->route('admin.profile.edit', $user->id)
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function index()
    {
        abort(404);
    }
    public function create()
    {
        abort(404);
    }
    public function store()
    {
        abort(404);
    }
    public function show(string $id)
    {
        abort(404);
    }
    public function destroy(string $id)
    {
        abort(404);
    }
}