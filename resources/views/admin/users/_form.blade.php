@csrf
@if (isset($user))
    @method('PUT')
@endif

<div class="space-y-5">

    {{-- Profile Image --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
        <div class="flex items-center gap-4">
            {{-- Preview Foto --}}
            @if (isset($user) && $user->profile_image_path)
                <img src="{{ Storage::url($user->profile_image_path) }}"
                    alt="{{ $user->name }}"
                    class="w-16 h-16 object-cover border border-gray-200 flex-shrink-0">
            @else
                <div class="w-16 h-16 bg-prussian-blue-500 flex items-center justify-center text-white font-semibold text-xl flex-shrink-0">
                    {{ isset($user) ? strtoupper(substr($user->name, 0, 1)) : '?' }}
                </div>
            @endif

            <div class="flex-1">
                <input type="file" name="profile_image" accept="image/*"
                    class="w-full px-3 py-2 border border-gray-300 text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 file:mr-3 file:py-1 file:px-3 file:border-0 file:text-xs file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 @error('profile_image') border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500">
                    Format: JPG, PNG, WEBP. Maks 2MB.
                    @isset($user)
                        <span class="text-orange-500">Biarkan kosong jika tidak ingin mengubah foto.</span>
                    @endisset
                </p>
                @error('profile_image')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Nama --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required
            class="w-full px-3 py-2 border border-gray-300 text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 @error('name') border-red-500 @enderror"
            placeholder="Contoh: Budi Santoso">
        @error('name')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Email --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
            class="w-full px-3 py-2 border border-gray-300 text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 @error('email') border-red-500 @enderror"
            placeholder="user@universitas.ac.id">
        @error('email')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Password + Confirm --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Kata Sandi
                @if (!isset($user))
                    <span class="text-red-500">*</span>
                @else
                    <span class="text-xs text-gray-500 font-normal">(kosongkan jika tidak diubah)</span>
                @endif
            </label>
            <input type="password" name="password" {{ isset($user) ? '' : 'required' }}
                class="w-full px-3 py-2 border border-gray-300 text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 @error('password') border-red-500 @enderror"
                placeholder="{{ isset($user) ? 'Sandi baru...' : 'Min 8 karakter' }}">
            @error('password')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Konfirmasi Kata Sandi
                @if (!isset($user))
                    <span class="text-red-500">*</span>
                @endif
            </label>
            <input type="password" name="password_confirmation" {{ isset($user) ? '' : 'required' }}
                class="w-full px-3 py-2 border border-gray-300 text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500"
                placeholder="Ulangi sandi">
        </div>
    </div>

    {{-- Role + Unit --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
            <select name="role" required
                class="w-full px-3 py-2 border border-gray-300 text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 @error('role') border-red-500 @enderror">
                <option value="">-- Pilih Role --</option>
                <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="staff" {{ old('role', $user->role ?? '') == 'staff' ? 'selected' : '' }}>Staff</option>
                <option value="petugas" {{ old('role', $user->role ?? '') == 'petugas' ? 'selected' : '' }}>Petugas</option>
            </select>
            @error('role')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Unit Kerja</label>
            <select name="unit_id"
                class="w-full px-3 py-2 border border-gray-300 text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 @error('unit_id') border-red-500 @enderror">
                <option value="">-- Tanpa Unit --</option>
                @foreach ($units as $unit)
                    <option value="{{ $unit->id }}" {{ old('unit_id', $user->unit_id ?? '') == $unit->id ? 'selected' : '' }}>
                        {{ $unit->name }}
                    </option>
                @endforeach
            </select>
            @error('unit_id')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Phone --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
        <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
            class="w-full px-3 py-2 border border-gray-300 text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 @error('phone') border-red-500 @enderror"
            placeholder="08123456789">
        @error('phone')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

</div>

{{-- Tombol Aksi --}}
<div class="mt-8 flex items-center justify-end gap-2 border-t border-gray-200 pt-5">
    <a href="{{ route('admin.users.index') }}"
        class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 hover:bg-gray-100 transition">
        Batal
    </a>
    <button type="submit"
        class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2 text-sm font-medium transition flex items-center gap-2">
        <i class="ri-save-line"></i>
        {{ isset($user) ? 'Simpan Perubahan' : 'Simpan User' }}
    </button>
</div>