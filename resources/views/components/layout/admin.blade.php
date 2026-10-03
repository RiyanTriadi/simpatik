<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ $title ?? 'SIMPATIK' }}</title>

    <script>
        (function () {
            try {
                if (localStorage.getItem('sidebar-collapsed') === 'true') {
                    document.documentElement.classList.add('sidebar-collapsed');
                }
            } catch (e) { }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.9.1/fonts/remixicon.css">

</head>

<body class="bg-gray-100 text-gray-800" x-data="{
    collapsed: document.documentElement.classList.contains('sidebar-collapsed'),
    mobileOpen: false,

    toggleSidebar() {
        if (window.matchMedia('(min-width: 1024px)').matches) {
            this.collapsed = !this.collapsed;
            document.documentElement.classList.toggle('sidebar-collapsed', this.collapsed);
            try {
                localStorage.setItem('sidebar-collapsed', this.collapsed ? 'true' : 'false');
            } catch (e) {}
        } else {
            this.mobileOpen = !this.mobileOpen;
        }
    }
}">

    <x-toast />

    <div class="flex min-h-screen">

        {{-- Overlay Mobile --}}
        <div x-show="mobileOpen" x-cloak @click="mobileOpen = false" class="fixed inset-0 z-40 bg-black/50 lg:hidden">
        </div>

        {{-- Sidebar --}}
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-prussian-blue-600 text-white transition-all duration-300 lg:static lg:translate-x-0"
            :class="{
            '-translate-x-full': !mobileOpen,
            'translate-x-0': mobileOpen,
        }">

            {{-- Logo --}}
            <div class="flex h-16 shrink-0 items-center justify-start px-6">
                <h1 class="sidebar-label text-lg font-bold">SIMPATIK PANEL</h1>
                <span class="sidebar-icon w-full text-center text-xl font-bold">S</span>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 space-y-1 p-4" :class="collapsed ? 'overflow-visible' : 'overflow-y-auto'">

                <p class="sidebar-title mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                    Menu Utama
                </p>

                @php $role = auth()->user()->role ?? 'petugas'; @endphp

                {{-- ============ DASHBOARD (semua role) ============ --}}
                <div class="nav-item-group relative">
                    <a href="{{ role_route('dashboard') }}"
                        class="nav-item flex items-center gap-3 {{ request()->routeIs('*.dashboard') ? 'bg-gray-700 text-white' : 'text-alabaster-grey-600 transition hover:bg-gray-700 hover:text-white' }} px-3 py-2.5 text-sm font-medium cursor-pointer">
                        <i class="ri-dashboard-line text-lg"></i>
                        <span class="sidebar-label">Dashboard</span>
                    </a>
                    <div class="nav-flyout absolute left-full top-0 z-[60] pl-2">
                        <div class="whitespace-nowrap bg-gray-900 px-3 py-2 text-sm text-white shadow-lg">Dashboard
                        </div>
                    </div>
                </div>

                {{-- ============ KOTAK MASUK (semua role) ============ --}}
                <div class="nav-item-group relative">
                    <a href="{{ role_route('inbox.index') }}"
                        class="nav-item flex items-center gap-3 {{ request()->routeIs('*.inbox.*') ? 'bg-gray-700 text-white' : 'text-alabaster-grey-600 transition hover:bg-gray-700 hover:text-white' }} px-3 py-2.5 text-sm font-medium cursor-pointer">
                        <i class="ri-inbox-line"></i>
                        <span class="sidebar-label">Kotak Masuk</span>
                    </a>
                    <div class="nav-flyout absolute left-full top-0 z-[60] pl-2">
                        <div class="whitespace-nowrap bg-gray-900 px-3 py-2 text-sm text-white shadow-lg">Kotak Masuk
                        </div>
                    </div>
                </div>

                {{-- ============ PENGADUAN (semua role, menu beda) ============ --}}
                @php $groupPengaduan = request()->routeIs('*.pengaduan.*'); @endphp
                <div class="nav-item-group relative">
                    <button type="button" data-submenu-toggle
                        class="nav-item submenu-toggle flex w-full items-center justify-between gap-3 {{ $groupPengaduan ? 'bg-gray-700 text-white' : 'transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2.5 text-sm font-medium cursor-pointer">
                        <span class="flex items-center gap-3">
                            <i class="ri-file-list-3-line text-lg"></i>
                            <span class="sidebar-label">Pengaduan</span>
                        </span>
                        <i
                            class="ri-arrow-down-s-line sidebar-chevron submenu-chevron transition-all duration-300 {{ $groupPengaduan ? 'rotate-180' : '' }}"></i>
                    </button>
                    <div
                        class="submenu-panel grid transition-[grid-template-rows] duration-300 ease-in-out {{ $groupPengaduan ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]' }}">
                        <div class="overflow-hidden">
                            <div class="mt-1 space-y-1 pl-4">
                                @if ($role === 'petugas')
                                    {{-- Petugas: Tugas Saya --}}
                                    <a href="{{ role_route('pengaduan.index') }}"
                                        class="flex items-center gap-3 {{ request()->routeIs('*.pengaduan.index') ? 'text-white' : 'text-alabaster-grey-600 transition hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                        <i class="ri-task-line"></i>
                                        Tugas Saya
                                    </a>
                                @else
                                    {{-- Admin & Staff --}}
                                    <a href="{{ role_route('pengaduan.index') }}"
                                        class="flex items-center gap-3 {{ request()->routeIs('*.pengaduan.index') ? 'text-white' : 'text-alabaster-grey-600 transition hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                        <i class="ri-file-list-line"></i>
                                        Semua Pengaduan
                                    </a>
                                    <a href="{{ role_route('pengaduan.verification') }}"
                                        class="flex items-center gap-3 {{ request()->routeIs('*.pengaduan.verification') ? 'text-white' : 'text-alabaster-grey-600 transition hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                        <i class="ri-shield-check-line"></i>
                                        Verifikasi
                                    </a>
                                    <a href="{{ role_route('pengaduan.assign') }}"
                                        class="flex items-center gap-3 {{ request()->routeIs('*.pengaduan.assign') ? 'text-white' : 'text-alabaster-grey-600 transition hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                        <i class="ri-user-add-line"></i>
                                        Assign ke Petugas
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ ASPIRASI ============ --}}
                @php $groupAspirasi = request()->routeIs('*.aspirasi.*'); @endphp
                <div class="nav-item-group relative">
                    <button type="button" data-submenu-toggle
                        class="nav-item submenu-toggle flex w-full items-center justify-between gap-3 {{ $groupAspirasi ? 'bg-gray-700 text-white' : 'transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2.5 text-sm font-medium cursor-pointer">
                        <span class="flex items-center gap-3">
                            <i class="ri-message-2-line text-lg"></i>
                            <span class="sidebar-label">Aspirasi</span>
                        </span>
                        <i
                            class="ri-arrow-down-s-line sidebar-chevron submenu-chevron transition-all duration-300 {{ $groupAspirasi ? 'rotate-180' : '' }}"></i>
                    </button>
                    <div
                        class="submenu-panel grid transition-[grid-template-rows] duration-300 ease-in-out {{ $groupAspirasi ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]' }}">
                        <div class="overflow-hidden">
                            <div class="mt-1 space-y-1 pl-4">
                                @if ($role === 'petugas')
                                    <a href="{{ role_route('aspirasi.index') }}"
                                        class="flex items-center gap-3 {{ request()->routeIs('*.aspirasi.index') ? 'text-white' : 'text-alabaster-grey-600 transition hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                        <i class="ri-task-line"></i>
                                        Tugas Saya
                                    </a>
                                @else
                                    <a href="{{ role_route('aspirasi.index') }}"
                                        class="flex items-center gap-3 {{ request()->routeIs('*.aspirasi.index') ? 'text-white' : 'text-alabaster-grey-600 transition hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                        <i class="ri-chat-3-line"></i>
                                        Semua Aspirasi
                                    </a>
                                    <a href="{{ role_route('aspirasi.follow-up') }}"
                                        class="flex items-center gap-3 {{ request()->routeIs('*.aspirasi.follow-up') ? 'text-white' : 'text-alabaster-grey-600 transition hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                        <i class="ri-flag-2-line"></i>
                                        Tindak Lanjut
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KHUSUS STAFF: Lihat Petugas --}}
                @if ($role === 'staff')
                    <div class="nav-item-group relative">
                        <a href="{{ role_route('petugas.index') }}"
                            class="nav-item flex items-center gap-3 {{ request()->routeIs('*.petugas.*') ? 'bg-gray-700 text-white' : 'text-alabaster-grey-600 transition hover:bg-gray-700 hover:text-white' }} px-3 py-2.5 text-sm font-medium cursor-pointer">
                            <i class="ri-team-line text-lg"></i>
                            <span class="sidebar-label">Daftar Petugas</span>
                        </a>
                    </div>
                @endif

                {{-- KHUSUS ADMIN --}}
                @if ($role === 'admin')
                    {{-- Manajemen User --}}
                    <div class="nav-item-group relative">
                        <a href="{{ role_route('users.index') }}"
                            class="nav-item flex items-center gap-3 {{ request()->routeIs('*.users.*') ? 'bg-gray-700 text-white' : 'text-alabaster-grey-600 transition hover:bg-gray-700 hover:text-white' }} px-3 py-2.5 text-sm font-medium cursor-pointer">
                            <i class="ri-group-line text-lg"></i>
                            <span class="sidebar-label">Manajemen User</span>
                        </a>
                    </div>

                    {{-- Master Data --}}
                    @php $groupMaster = request()->routeIs('*.master.*'); @endphp
                    <div class="nav-item-group relative">
                        <button type="button" data-submenu-toggle
                            class="nav-item submenu-toggle flex w-full items-center justify-between gap-3 {{ $groupMaster ? 'bg-gray-700 text-white' : 'transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2.5 text-sm font-medium cursor-pointer">
                            <span class="flex items-center gap-3">
                                <i class="ri-database-2-line text-lg"></i>
                                <span class="sidebar-label">Master Data</span>
                            </span>
                            <i
                                class="ri-arrow-down-s-line sidebar-chevron submenu-chevron transition-all duration-300 {{ $groupMaster ? 'rotate-180' : '' }}"></i>
                        </button>
                        <div
                            class="submenu-panel grid transition-[grid-template-rows] duration-300 ease-in-out {{ $groupMaster ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]' }}">
                            <div class="overflow-hidden">
                                <div class="mt-1 space-y-1 pl-4">
                                    <a href="{{ role_route('master.kategori.index') }}"
                                        class="flex items-center gap-3 {{ request()->routeIs('*.master.kategori.*') ? 'text-white' : 'text-alabaster-grey-600 transition hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                        <i class="ri-price-tag-3-line"></i> Kategori
                                    </a>
                                    <a href="{{ role_route('master.unit-kerja.index') }}"
                                        class="flex items-center gap-3 {{ request()->routeIs('*.master.unit-kerja.*') ? 'text-white' : 'text-alabaster-grey-600 transition hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                        <i class="ri-building-2-line"></i> Unit Kerja
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </nav>
        </aside>

        {{-- Main Area --}}
        <div class="flex min-w-0 flex-1 flex-col">

            {{-- Header --}}
            <header
                class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4 shadow-sm sm:px-6">

                {{-- Kiri: Toggle Sidebar --}}
                <div>
                    <button @click="toggleSidebar" type="button"
                        class="cursor-pointer bg-orange-500 p-2 text-alabaster-grey-200 hover:bg-orange-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                {{-- Kanan: Profile Dropdown --}}
                <div x-data="{ profileOpen: false }" class="relative">
                    <button @click="profileOpen = !profileOpen" @click.outside="profileOpen = false" type="button"
                        class="flex items-center gap-2.5 hover:bg-gray-50 px-2 py-1.5 transition cursor-pointer">

                        {{-- Avatar --}}
                        @if (auth()->user()->profile_image_path)
                            <img src="{{ Storage::url(auth()->user()->profile_image_path) }}"
                                alt="{{ auth()->user()->name }}"
                                class="w-9 h-9 rounded-full object-cover border border-gray-200">
                        @else
                            <div
                                class="w-9 h-9 rounded-full bg-prussian-blue-500 flex items-center justify-center text-white font-semibold text-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif

                        {{-- Nama & Role (hidden di mobile) --}}
                        <div class="hidden sm:block text-left">
                            <p class="text-sm font-medium text-gray-800 leading-tight">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 leading-tight">{{ ucfirst(auth()->user()->role) }}</p>
                        </div>

                        {{-- Chevron --}}
                        <i class="ri-arrow-down-s-line text-gray-400 text-lg transition-transform"
                            :class="profileOpen ? 'rotate-180' : ''"></i>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div x-show="profileOpen" x-cloak x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 z-50 mt-2 w-56 origin-top-right bg-white shadow-lg ring-1 ring-black ring-opacity-5 border border-gray-100">

                        {{-- Header info --}}
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>

                        {{-- Menu --}}
                        <div class="py-1">
                            <a href="{{ role_route('profile.edit', auth()->id()) }}"
                                class="group flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-prussian-blue-500 transition">
                                <i class="ri-user-line text-gray-400 group-hover:text-prussian-blue-500"></i>
                                Profil Saya
                            </a>

                            <form action="{{ route('logout') }}" method="POST"
                                onsubmit="return confirm('Yakin ingin keluar dari akun ini?');">
                                @csrf
                                <button type="submit"
                                    class="group flex w-full items-center gap-2.5 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition border-t border-gray-100">
                                    <i class="ri-logout-box-line text-red-400 group-hover:text-red-600"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Content --}}
            <main class="flex-1 p-4 sm:p-6">
                {{ $slot }}
            </main>

            {{-- Footer --}}
            <footer class="border-t border-gray-200 bg-white px-6 py-4">
                <div class="flex flex-col items-center justify-between gap-2 text-sm text-gray-500 sm:flex-row">
                    <p>&copy; {{ date('Y') }} Riyan Triadi. All rights reserved.</p>
                </div>
            </footer>

        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Submenu Toggle Script (tetap pakai vanilla JS) --}}
    <script>
        document.querySelectorAll('.submenu-toggle').forEach((btn) => {
            btn.addEventListener('click', () => {
                const panel = btn.nextElementSibling;
                const chevron = btn.querySelector('.submenu-chevron');
                const isOpen = panel.classList.contains('grid-rows-[1fr]');

                if (isOpen) {
                    panel.classList.remove('grid-rows-[1fr]');
                    panel.classList.add('grid-rows-[0fr]');
                } else {
                    panel.classList.remove('grid-rows-[0fr]');
                    panel.classList.add('grid-rows-[1fr]');
                }

                chevron.classList.toggle('rotate-180');
            });
        });
    </script>

</body>

</html>