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
        $users = User::with('unit')->where('role', '!=', User::ROLE_ADMIN);

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $users->where(function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', $searchTerm)
                    ->orWhere('email', 'LIKE', $searchTerm)
                    ->orWhere('phone', 'LIKE', $searchTerm);
            });
        }

        if ($request->filled('role')) {
            $users->where('role', $request->role)->where('role', '!=', User::ROLE_ADMIN);
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
        abort_if($user->role === User::ROLE_ADMIN, 404);
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
        } elseif ($request->filled('profile_image_data')) {
            $validated['profile_image_path'] = $this->storeProfileImageData($request->string('profile_image_data')->toString());
        }

        unset($validated['profile_image']);
        unset($validated['profile_image_data']);

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        abort_if($user->role === User::ROLE_ADMIN, 404);
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
            $validated['profile_image_path'] = $request->file('profile_image')->store('profile-images', 'public');
        } elseif ($request->filled('profile_image_data') && !$user->profile_image_path) {
            $validated['profile_image_path'] = $this->storeProfileImageData($request->string('profile_image_data')->toString());
        }

        unset($validated['profile_image']);
        unset($validated['profile_image_data']);

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->role === User::ROLE_ADMIN) {
            return redirect()->back()->with('error', 'Data admin tidak dikelola melalui daftar user.');
        }
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->profile_image_path && Storage::disk('public')->exists($user->profile_image_path)) {
            Storage::disk('public')->delete($user->profile_image_path);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    public function officers(Request $request)
    {
        $query = User::where('role', User::ROLE_PETUGAS)
            ->with('unit')
            ->withCount([
                'assignedComplaints as active_tasks' => function ($q) {
                    $q->whereIn('status', [
                        \App\Models\Complaint::STATUS_BARU,
                        \App\Models\Complaint::STATUS_DIPROSES,
                    ]);
                }
            ]);

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', $searchTerm)
                    ->orWhere('email', 'LIKE', $searchTerm);
            });
        }

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        $users = $query->orderBy('name')->paginate(12)->withQueryString();

        $units = Unit::active()->orderBy('name')->get();

        return view('admin.users.officers', compact('users', 'units'));
    }

    public function show(User $user)
    {
        abort(404);
    }

    private function storeProfileImageData(string $data): string
    {
        if (!preg_match('/^data:image\/(jpeg|png|webp);base64,(.+)$/', $data, $matches)) {
            abort(422, 'Format foto profil tidak valid.');
        }

        $contents = base64_decode($matches[2], true);
        if ($contents === false || strlen($contents) > 2 * 1024 * 1024) {
            abort(422, 'Ukuran foto profil maksimal 2MB.');
        }

        $mime = (new \finfo(FILEINFO_MIME_TYPE))->buffer($contents);
        abort_unless(in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true), 422, 'Format foto profil tidak valid.');

        $extension = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
        $path = 'profile-images/' . uniqid('', true) . '.' . $extension;
        abort_unless(Storage::disk('public')->put($path, $contents), 422, 'Foto profil gagal disimpan.');

        return $path;
    }
}
