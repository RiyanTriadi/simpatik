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
                    <h1 class="text-lg font-bold">SIMPATIK</h1>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 space-y-1 overflow-y-auto p-4">
                <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                    Menu Utama
                </p>

                <a href="{{ route('public.index') }}"
                    class="flex items-center gap-3 {{ request()->routeIs('public.index') ? 'text-black bg-orange-500' : 'transition hover:bg-gray-700 hover:text-white' }} px-3 py-2.5 text-sm font-medium ">
                    <i class="ri-home-3-line"></i>
                    Beranda
                </a>

                <a href="{{ route('public.pengaduan.index') }}"
                    class="flex items-center gap-3 {{ request()->routeIs('public.pengaduan.index') ? 'text-black bg-orange-500' : 'transition hover:bg-gray-700 hover:text-white' }} px-3 py-2.5 text-sm font-medium">
                    <i class="ri-file-list-line"></i>
                    Pengaduan
                </a>

                <a href="{{ route('public.aspirasi.index') }}"
                    class="flex items-center gap-3 {{ request()->routeIs('public.aspirasi.index') ? 'text-black bg-orange-500' : 'transition hover:bg-gray-700 hover:text-white' }} px-3 py-2.5 text-sm font-medium">
                    <i class="ri-message-2-line"></i>
                    Aspirasi
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
                @if (session('success'))
                    <div id="toast-success" role="alert"
                        class="fixed top-20 right-5 z-50 flex items-center gap-3 bg-green-600 text-white px-4 py-3 shadow-lg opacity-0 translate-x-4 transition-all duration-300">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ session('success') }}</span>
                        <button type="button" onclick="document.getElementById('toast-success')?.remove()"
                            class="ml-2 text-white/80 hover:text-white cursor-pointer">&times;</button>
                    </div>
                @endif
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

        (function() {
            const toast = document.getElementById('toast-success');
            if (!toast) return;

            requestAnimationFrame(() => {
                toast.classList.remove('opacity-0', 'translate-x-4');
            });

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-x-4');
                setTimeout(() => toast.remove(), 300);
            }, 10000);
        })();

        (function() {
            const anonymousCheckbox = document.getElementById('is_anonymous');
            const reporterFields = [
                document.getElementById('reporter_name'),
                document.getElementById('reporter_phone'),
                document.getElementById('reporter_email'),
            ];

            function toggleReporterFields() {
                const isAnonymous = anonymousCheckbox.checked;

                reporterFields.forEach((field) => {
                    if (!field) return;
                    field.disabled = isAnonymous;
                    field.classList.toggle('bg-gray-100', isAnonymous);
                    field.classList.toggle('cursor-not-allowed', isAnonymous);
                });
            }

            if (anonymousCheckbox) {
                anonymousCheckbox.addEventListener('change', toggleReporterFields);
                toggleReporterFields();
            }
        })();
    </script>


</body>

</html>
