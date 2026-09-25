<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">


    <title>{{ $title ?? 'SIMPATIK' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.9.1/fonts/remixicon.css">

</head>

<body class="bg-gray-100 text-gray-800">


    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-prussian-blue-600 text-white transition-transform duration-300 lg:static lg:translate-x-0 -translate-x-full">

            {{-- Logo --}}
            <div class="flex h-16 shrink-0 items-center px-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center bg-orange-500">
                        <span class="text-lg font-bold text-black">S</span>
                    </div>
                    <h1 class="text-lg font-bold">SIMPATIK PANEL</h1>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 space-y-1 overflow-y-auto p-4">
                <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                    Menu Utama
                </p>

                {{-- Dashboard --}}
                <a href="#" {{-- TODO: route('admin.dashboard') --}}
                    class="flex items-center gap-3 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2.5 text-sm font-medium">
                    <i class="ri-dashboard-line"></i>
                    Dashboard
                </a>

                {{-- Kotak Masuk --}}
                @php $groupInbox = request()->routeIs('admin.inbox.*'); @endphp
                <div>
                    <button type="button" data-submenu-toggle
                        class="submenu-toggle flex w-full items-center justify-between gap-3 {{ $groupInbox ? 'bg-gray-700 text-white' : 'transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2.5 text-sm font-medium">
                        <span class="flex items-center gap-3">
                            <i class="ri-inbox-line"></i>
                            Kotak Masuk
                        </span>
                        <i
                            class="ri-arrow-down-s-line submenu-chevron transition-transform duration-300 {{ $groupInbox ? 'rotate-180' : '' }}"></i>
                    </button>
                    <div
                        class="submenu-panel grid transition-[grid-template-rows] duration-300 ease-in-out {{ $groupInbox ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]' }}">
                        <div class="overflow-hidden">
                            <div class="mt-1 space-y-1 pl-4">
                                <a href="#" {{-- TODO: route('admin.inbox.index') --}}
                                    class="flex items-center gap-3 {{ request()->routeIs('admin.inbox.index') ? 'text-white' : 'text-alabaster-grey-600 transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                    <i class="ri-list-unordered"></i>
                                    Semua Tiket
                                </a>
                                <a href="#" {{-- TODO: route('admin.inbox.unassigned') --}}
                                    class="flex items-center gap-3 {{ request()->routeIs('admin.inbox.unassigned') ? 'text-white' : 'text-alabaster-grey-600 transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                    <i class="ri-user-unfollow-line"></i>
                                    Belum Di-assign
                                </a>
                                <a href="#" {{-- TODO: route('admin.inbox.processing') --}}
                                    class="flex items-center gap-3 {{ request()->routeIs('admin.inbox.processing') ? 'text-white' : 'text-alabaster-grey-600 transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                    <i class="ri-loader-4-line"></i>
                                    Sedang Diproses
                                </a>
                                <a href="#" {{-- TODO: route('admin.inbox.completed') --}}
                                    class="flex items-center gap-3 {{ request()->routeIs('admin.inbox.completed') ? 'text-white' : 'text-alabaster-grey-600 transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                    <i class="ri-checkbox-circle-line"></i>
                                    Selesai
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pengaduan --}}
                @php $groupPengaduan = request()->routeIs('admin.pengaduan.*') || request()->routeIs('admin.pengaduan.*'); @endphp
                <div>
                    <button type="button" data-submenu-toggle
                        class="submenu-toggle flex w-full items-center justify-between gap-3 {{ $groupPengaduan ? 'bg-gray-700 text-white' : 'transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2.5 text-sm font-medium">
                        <span class="flex items-center gap-3">
                            <i class="ri-file-list-3-line"></i>
                            Pengaduan
                        </span>
                        <i
                            class="ri-arrow-down-s-line submenu-chevron transition-transform duration-300 {{ $groupPengaduan ? 'rotate-180' : '' }}"></i>
                    </button>
                    <div
                        class="submenu-panel grid transition-[grid-template-rows] duration-300 ease-in-out {{ $groupPengaduan ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]' }}">
                        <div class="overflow-hidden">
                            <div class="mt-1 space-y-1 pl-4">
                                <a href="{{ route('admin.pengaduan.index') }}" 
                                    class="flex items-center gap-3 {{ request()->routeIs('admin.pengaduan.index') ? 'text-white' : 'text-alabaster-grey-600 transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                    <i class="ri-file-list-line"></i>
                                    Semua Pengaduan
                                </a>
                                <a href="#" {{-- TODO: route('admin.pengaduan.verifikasi') --}}
                                    class="flex items-center gap-3 {{ request()->routeIs('admin.pengaduan.verifikasi') ? 'text-white' : 'text-alabaster-grey-600 transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                    <i class="ri-shield-check-line"></i>
                                    Verifikasi
                                </a>
                                <a href="#" {{-- TODO: route('admin.pengaduan.assign') --}}
                                    class="flex items-center gap-3 {{ request()->routeIs('admin.pengaduan.assign') ? 'text-white' : 'text-alabaster-grey-600 transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                    <i class="ri-user-add-line"></i>
                                    Assign ke Petugas
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Aspirasi --}}
                @php $groupAspirasi = request()->routeIs('admin.aspirasi.*') || request()->routeIs('admin.aspirasi.*'); @endphp
                <div>
                    <button type="button" data-submenu-toggle
                        class="submenu-toggle flex w-full items-center justify-between gap-3 {{ $groupAspirasi ? 'bg-gray-700 text-white' : 'transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2.5 text-sm font-medium">
                        <span class="flex items-center gap-3">
                            <i class="ri-message-2-line"></i>
                            Aspirasi
                        </span>
                        <i
                            class="ri-arrow-down-s-line submenu-chevron transition-transform duration-300 {{ $groupAspirasi ? 'rotate-180' : '' }}"></i>
                    </button>
                    <div
                        class="submenu-panel grid transition-[grid-template-rows] duration-300 ease-in-out {{ $groupAspirasi ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]' }}">
                        <div class="overflow-hidden">
                            <div class="mt-1 space-y-1 pl-4">
                                <a href="{{ route('admin.aspirasi.index') }}" {{-- TODO: route('admin.aspirasi.index') --}}
                                    class="flex items-center gap-3 {{ request()->routeIs('admin.aspirasi.index') ? 'text-white' : 'text-alabaster-grey-600 transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                    <i class="ri-chat-3-line"></i>
                                    Semua Aspirasi
                                </a>
                                <a href="#" {{-- TODO: route('admin.aspirasi.tindak-lanjut') --}}
                                    class="flex items-center gap-3 {{ request()->routeIs('admin.aspirasi.tindak-lanjut') ? 'text-white' : 'text-alabaster-grey-600 transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                    <i class="ri-flag-2-line"></i>
                                    Tindak Lanjut
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Laporan --}}
                @php $groupLaporan = request()->routeIs('admin.laporan.*'); @endphp
                <div>
                    <button type="button" data-submenu-toggle
                        class="submenu-toggle flex w-full items-center justify-between gap-3 {{ $groupLaporan ? 'bg-gray-700 text-white' : 'transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2.5 text-sm font-medium">
                        <span class="flex items-center gap-3">
                            <i class="ri-bar-chart-2-line"></i>
                            Laporan
                        </span>
                        <i
                            class="ri-arrow-down-s-line submenu-chevron transition-transform duration-300 {{ $groupLaporan ? 'rotate-180' : '' }}"></i>
                    </button>
                    <div
                        class="submenu-panel grid transition-[grid-template-rows] duration-300 ease-in-out {{ $groupLaporan ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]' }}">
                        <div class="overflow-hidden">
                            <div class="mt-1 space-y-1 pl-4">
                                <a href="#" {{-- TODO: route('admin.laporan.statistik') --}}
                                    class="flex items-center gap-3 {{ request()->routeIs('admin.laporan.statistik') ? 'text-white' : 'text-alabaster-grey-600 transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                    <i class="ri-bar-chart-box-line"></i>
                                    Statistik Tiket
                                </a>
                                <a href="#" {{-- TODO: route('admin.laporan.kategori') --}}
                                    class="flex items-center gap-3 {{ request()->routeIs('admin.laporan.kategori') ? 'text-white' : 'text-alabaster-grey-600 transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                    <i class="ri-pie-chart-line"></i>
                                    Per Kategori
                                </a>
                                <a href="#" {{-- TODO: route('admin.laporan.unit-kerja') --}}
                                    class="flex items-center gap-3 {{ request()->routeIs('admin.laporan.unit-kerja') ? 'text-white' : 'text-alabaster-grey-600 transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                    <i class="ri-building-line"></i>
                                    Per Unit Kerja
                                </a>
                                <a href="#" {{-- TODO: route('admin.laporan.petugas') --}}
                                    class="flex items-center gap-3 {{ request()->routeIs('admin.laporan.petugas') ? 'text-white' : 'text-alabaster-grey-600 transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                    <i class="ri-user-star-line"></i>
                                    Per Petugas
                                </a>
                                <a href="#" {{-- TODO: route('admin.laporan.export') --}}
                                    class="flex items-center gap-3 {{ request()->routeIs('admin.laporan.export') ? 'text-white' : 'text-alabaster-grey-600 transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                    <i class="ri-download-2-line"></i>
                                    Export (PDF/Excel)
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Manajemen User --}}
                @php $groupUser = request()->routeIs('admin.user.*'); @endphp
                <div>
                    <button type="button" data-submenu-toggle
                        class="submenu-toggle flex w-full items-center justify-between gap-3 {{ $groupUser ? 'bg-gray-700 text-white' : 'transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2.5 text-sm font-medium">
                        <span class="flex items-center gap-3">
                            <i class="ri-team-line"></i>
                            Manajemen User
                        </span>
                        <i
                            class="ri-arrow-down-s-line submenu-chevron transition-transform duration-300 {{ $groupUser ? 'rotate-180' : '' }}"></i>
                    </button>
                    <div
                        class="submenu-panel grid transition-[grid-template-rows] duration-300 ease-in-out {{ $groupUser ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]' }}">
                        <div class="overflow-hidden">
                            <div class="mt-1 space-y-1 pl-4">
                                <a href="#" {{-- TODO: route('admin.user.index') --}}
                                    class="flex items-center gap-3 {{ request()->routeIs('admin.user.index') ? 'text-white' : 'text-alabaster-grey-600 transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                    <i class="ri-group-line"></i>
                                    Semua User
                                </a>
                                <a href="#" {{-- TODO: route('admin.user.petugas') --}}
                                    class="flex items-center gap-3 {{ request()->routeIs('admin.user.petugas') ? 'text-white' : 'text-alabaster-grey-600 transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                    <i class="ri-user-settings-line"></i>
                                    Petugas
                                </a>
                                <a href="#" {{-- TODO: route('admin.user.staff') --}}
                                    class="flex items-center gap-3 {{ request()->routeIs('admin.user.staff') ? 'text-white' : 'text-alabaster-grey-600 transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                    <i class="ri-user-3-line"></i>
                                    Staff
                                </a>
                                <a href="#" {{-- TODO: route('admin.user.administrator') --}}
                                    class="flex items-center gap-3 {{ request()->routeIs('admin.user.administrator') ? 'text-white' : 'text-alabaster-grey-600 transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                    <i class="ri-admin-line"></i>
                                    Administrator
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Master Data --}}
                @php $groupMaster = request()->routeIs('admin.master.*'); @endphp
                <div>
                    <button type="button" data-submenu-toggle
                        class="submenu-toggle flex w-full items-center justify-between gap-3 {{ $groupMaster ? 'bg-gray-700 text-white' : 'transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2.5 text-sm font-medium">
                        <span class="flex items-center gap-3">
                            <i class="ri-database-2-line"></i>
                            Master Data
                        </span>
                        <i
                            class="ri-arrow-down-s-line submenu-chevron transition-transform duration-300 {{ $groupMaster ? 'rotate-180' : '' }}"></i>
                    </button>
                    <div
                        class="submenu-panel grid transition-[grid-template-rows] duration-300 ease-in-out {{ $groupMaster ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]' }}">
                        <div class="overflow-hidden">
                            <div class="mt-1 space-y-1 pl-4">
                                <a href="#" {{-- TODO: route('admin.master.kategori') --}}
                                    class="flex items-center gap-3 {{ request()->routeIs('admin.master.kategori') ? 'text-white' : 'text-alabaster-grey-600 transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                    <i class="ri-price-tag-3-line"></i>
                                    Kategori
                                </a>
                                <a href="#" {{-- TODO: route('admin.master.unit-kerja') --}}
                                    class="flex items-center gap-3 {{ request()->routeIs('admin.master.unit-kerja') ? 'text-white' : 'text-alabaster-grey-600 transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                    <i class="ri-building-2-line"></i>
                                    Unit Kerja
                                </a>
                                <a href="#" {{-- TODO: route('admin.master.prioritas') --}}
                                    class="flex items-center gap-3 {{ request()->routeIs('admin.master.prioritas') ? 'text-white' : 'text-alabaster-grey-600 transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                    <i class="ri-star-line"></i>
                                    Prioritas
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pengaturan --}}
                @php $groupSetting = request()->routeIs('admin.pengaturan.*'); @endphp
                <div>
                    <button type="button" data-submenu-toggle
                        class="submenu-toggle flex w-full items-center justify-between gap-3 {{ $groupSetting ? 'bg-gray-700 text-white' : 'transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2.5 text-sm font-medium">
                        <span class="flex items-center gap-3">
                            <i class="ri-settings-3-line"></i>
                            Pengaturan
                        </span>
                        <i
                            class="ri-arrow-down-s-line submenu-chevron transition-transform duration-300 {{ $groupSetting ? 'rotate-180' : '' }}"></i>
                    </button>
                    <div
                        class="submenu-panel grid transition-[grid-template-rows] duration-300 ease-in-out {{ $groupSetting ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]' }}">
                        <div class="overflow-hidden">
                            <div class="mt-1 space-y-1 pl-4">
                                <a href="#" {{-- TODO: route('admin.pengaturan.umum') --}}
                                    class="flex items-center gap-3 {{ request()->routeIs('admin.pengaturan.umum') ? 'text-white' : 'text-alabaster-grey-600 transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                    <i class="ri-settings-4-line"></i>
                                    Pengaturan Umum
                                </a>
                                <a href="#" {{-- TODO: route('admin.pengaturan.notifikasi') --}}
                                    class="flex items-center gap-3 {{ request()->routeIs('admin.pengaturan.notifikasi') ? 'text-white' : 'text-alabaster-grey-600 transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                    <i class="ri-notification-3-line"></i>
                                    Notifikasi
                                </a>
                                <a href="#" {{-- TODO: route('admin.pengaturan.template-email') --}}
                                    class="flex items-center gap-3 {{ request()->routeIs('admin.pengaturan.template-email') ? 'text-white' : 'text-alabaster-grey-600 transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                    <i class="ri-mail-settings-line"></i>
                                    Template Email
                                </a>
                                <a href="#" {{-- TODO: route('admin.pengaturan.log-aktivitas') --}}
                                    class="flex items-center gap-3 {{ request()->routeIs('admin.pengaturan.log-aktivitas') ? 'text-white' : 'text-alabaster-grey-600 transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm">
                                    <i class="ri-history-line"></i>
                                    Log Aktivitas
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Akun --}}
                <p class="mb-2 mt-4 px-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                    Akun
                </p>
                <a href="#" {{-- TODO: route('admin.profil') --}}
                    class="flex items-center gap-3 {{ request()->routeIs('admin.profil') ? 'text-white' : 'transition text-alabaster-grey-600 hover:bg-gray-700 hover:text-white' }} px-3 py-2.5 text-sm font-medium">
                    <i class="ri-user-line"></i>
                    Profil Saya
                </a>
            </nav>
        </aside>


        {{-- Overlay Mobile --}}
        <div id="sidebar-overlay" class="fixed inset-0 z-40 hidden bg-black/50 lg:hidden"></div>


        {{-- Main Area --}}
        <div class="flex min-w-0 flex-1 flex-col">

            {{-- Header --}}
            <header
                class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4 shadow-sm sm:px-6">

                <div class="flex items-center gap-4">
                    {{-- Mobile Menu --}}
                    <button id="sidebar-toggle" type="button" class="p-2 text-gray-600 hover:bg-gray-100 lg:hidden">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center bg-orange-500">
                            <span class="text-lg font-bold text-black">RT</span>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">
                            UNIVERSITASKU
                        </h2>
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
                    <p>Sistem Informasi Manajemen Pengaduan, Aspirasi, dan Tindak Lanjut Sivitas</p>
                </div>
            </footer>

        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Sidebar Script --}}
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const toggle = document.getElementById('sidebar-toggle');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        toggle?.addEventListener('click', openSidebar);
        overlay?.addEventListener('click', closeSidebar);

        // Submenu toggle dengan animasi (CSS grid-template-rows 0fr <-> 1fr)
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
