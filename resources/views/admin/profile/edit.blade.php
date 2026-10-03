<x-layout.admin title="Profil Saya">
    <div class="max-w-3xl mx-auto space-y-4">

        {{-- Header --}}
        <div>
            <h1 class="text-xl font-bold text-prussian-blue-500">Profil Saya</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola informasi akun Anda</p>
        </div>

        {{-- Card: Foto & Info Utama --}}
        <div class="bg-white border border-gray-200 p-6">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">

                {{-- Avatar --}}
                <div class="flex-shrink-0">
                    @if ($user->profile_image_path)
                        <img src="{{ Storage::url($user->profile_image_path) }}"
                            alt="{{ $user->name }}"
                            class="w-24 h-24 rounded-full object-cover border-4 border-gray-100">
                    @else
                        <div class="w-24 h-24 rounded-full bg-prussian-blue-500 flex items-center justify-center text-white font-bold text-3xl border-4 border-gray-100">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 text-center sm:text-left">
                    <h2 class="text-lg font-bold text-gray-800">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>

                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-2">
                        {{-- Role Badge --}}
                        @if ($user->role == 'admin')
                            <span class="px-2.5 py-1 text-xs font-semibold bg-red-100 text-red-800">Admin</span>
                        @elseif ($user->role == 'staff')
                            <span class="px-2.5 py-1 text-xs font-semibold bg-blue-100 text-blue-800">Staff</span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-semibold bg-emerald-100 text-emerald-800">Petugas</span>
                        @endif

                        {{-- Unit Badge --}}
                        @if ($user->unit)
                            <span class="px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-700 flex items-center gap-1">
                                <i class="ri-building-line"></i>
                                {{ $user->unit->name }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Card: Form Update --}}
        <div class="bg-white border border-gray-200">
            <div class="border-b border-gray-200 p-4">
                <h3 class="text-sm font-semibold text-gray-800">Informasi Pribadi</h3>
                <p class="text-xs text-gray-500 mt-0.5">Perbarui data profil Anda</p>
            </div>

            <form action="{{ role_route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="p-5 space-y-5">

                    {{-- Foto Profil --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                        <input type="file" name="profile_image" accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 file:mr-3 file:py-1 file:px-3 file:border-0 file:text-xs file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 @error('profile_image') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, WEBP. Maks 2MB. Biarkan kosong jika tidak ingin mengubah.</p>
                        @error('profile_image')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email & Phone --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 @error('phone') border-red-500 @enderror"
                                placeholder="08123456789">
                            @error('phone')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Section Password --}}
                <div class="border-t border-gray-200 p-4 bg-gray-50">
                    <h3 class="text-sm font-semibold text-gray-800">Ubah Kata Sandi</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Kosongkan jika tidak ingin mengubah kata sandi</p>
                </div>

                <div class="p-5 space-y-4 pt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password"
                            class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 @error('current_password') border-red-500 @enderror"
                            placeholder="Masukkan kata sandi saat ini">
                        @error('current_password')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi Baru</label>
                            <input type="password" name="password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 @error('password') border-red-500 @enderror"
                                placeholder="Min 8 karakter">
                            @error('password')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" name="password_confirmation"
                                class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500"
                                placeholder="Ulangi kata sandi baru">
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-2 px-5 py-4 border-t border-gray-200 bg-gray-50">
                    <a href="{{ role_route('dashboard') }}"
                        class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 hover:bg-gray-100 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2 text-sm font-medium transition flex items-center gap-2">
                        <i class="ri-save-line"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-layout.admin>