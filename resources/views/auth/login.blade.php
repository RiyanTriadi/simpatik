<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMPATIK PANEL</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.9.1/fonts/remixicon.css">
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen font-sans">

    <div class="w-full max-w-md bg-white shadow-lg overflow-hidden p-2">
        {{-- Header --}}
        <div class="bg-prussian-blue-600 p-6 text-center">
            <div class="flex justify-center mb-3">
                <div class="flex h-12 w-12 items-center justify-center bg-orange-500">
                    <span class="text-2xl font-bold text-black">S</span>
                </div>
            </div>
            <h1 class="text-xl font-bold text-white">SIMPATIK PANEL</h1>
            <p class="text-sm text-alabaster-grey-300 mt-1">Sistem Informasi Manajemen Pengaduan, Aspirasi, dan Tindak Lanjut Sivitas</p>
        </div>

        {{-- Form Login --}}
        <div class="p-6 md:p-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ri-mail-line text-gray-400"></i>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full pl-10 pr-3 py-2 border border-gray-300 text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 @error('email') border-red-500 @enderror"
                            placeholder="Masukkan Email">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ri-lock-line text-gray-400"></i>
                        </div>
                        <input id="password" type="password" name="password" required
                            class="w-full pl-10 pr-3 py-2 border border-gray-300 text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 @error('password') border-red-500 @enderror"
                            placeholder="Masukkan Kata Sandi">
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember"
                            class="rounded border-gray-300 text-emerald-500 focus:ring-emerald-500">
                        <span class="text-sm text-gray-600">Ingat Saya</span>
                    </label>
                </div>

                {{-- Tombol Submit --}}
                <button type="submit"
                    class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-medium py-2.5 px-4 transition duration-200 flex items-center justify-center gap-2">
                    <i class="ri-login-box-line"></i> Masuk ke Panel
                </button>
            </form>
        </div>

        <div class="bg-gray-50 px-6 py-4 text-center border-t border-gray-100">
            <p class="text-xs text-gray-500">&copy; {{ date('Y') }} Riyan Triadi. All rights reserved.</p>
        </div>
    </div>

</body>

</html>