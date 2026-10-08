<x-layout.admin title="Aspirasi">
    <div class="bg-white p-4" x-data="{ confirmOpen: false, confirmAction: '', confirmStatus: '', confirmLabel: '' }" @open-aspiration-confirm.window="confirmAction = $event.detail.action; confirmStatus = $event.detail.status; confirmLabel = $event.detail.label; confirmOpen = true">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3">
            <div>
                <h1 class="text-lg font-semibold">Daftar Aspirasi</h1>
                <p class="text-xs text-gray-500 mt-0.5">Semua aspirasi yang masuk ke sistem</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <form action="{{ role_route('aspirasi.index') }}" method="GET"
                    class="flex flex-wrap items-center gap-2">
                    {{-- Filter Status --}}
                    <select name="status" onchange="this.form.submit()"
                        class="h-8 border border-alabaster-grey-600 text-sm px-2 bg-white focus:outline-none focus:border-emerald-500">
                        <option value="">Semua Status</option>
                        <option value="baru" {{ request('status') == 'baru' ? 'selected' : '' }}>Baru</option>
                        <option value="ditindaklanjuti" {{ request('status') == 'ditindaklanjuti' ? 'selected' : '' }}>Ditindaklanjuti</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>

                    {{-- Search --}}
                    <div class="flex">
                        <input type="search" name="search" value="{{ request('search') }}"
                            class="h-8 border border-alabaster-grey-600 text-sm px-4 focus:outline-none focus:border-emerald-500"
                            placeholder="Cari Tiket / Topik / Pelapor" autocomplete="off">
                        <button type="submit" class="bg-emerald-500 h-8 px-3 cursor-pointer text-white">
                            <i class="ri-search-line"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="mt-4 overflow-x-auto border border-alabaster-grey-300">
            <table class="w-full min-w-max text-sm">
                <thead class="bg-prussian-blue-100">
                    <tr class="border-b border-alabaster-grey-300">
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500 w-12">#</th>
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">No Tiket</th>
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">Topik</th>
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">Kategori</th>
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">Pelapor</th>
                        <th class="px-4 py-3 text-center font-semibold text-prussian-blue-500">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($aspirations as $aspiration)
                        <tr class="border-b border-alabaster-grey-100 bg-white hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-500">
                                {{ $loop->iteration + ($aspirations->currentPage() - 1) * $aspirations->perPage() }}
                            </td>

                            <td class="px-4 py-3 font-medium text-prussian-blue-500">
                                {{ $aspiration->ticket_number }}
                                <p class="text-xs text-gray-500">{{ $aspiration->created_at->translatedFormat('d F Y') }}
                                </p>
                            </td>

                            <td class="px-4 py-3 max-w-xs">
                                <div class="line-clamp-2" title="{{ $aspiration->subject }}">
                                    {{ $aspiration->subject }}
                                </div>
                            </td>

                            <td class="px-4 py-3 text-gray-600">
                                {{ $aspiration->category->name }}
                            </td>

                            {{-- STATUS BADGE --}}
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs font-semibold whitespace-nowrap
                                                @if ($aspiration->status == 'baru') bg-blue-100 text-blue-800
                                                @elseif($aspiration->status == 'ditindaklanjuti') bg-purple-100 text-purple-800
                                                @elseif($aspiration->status == 'selesai') bg-emerald-100 text-emerald-800
                                                @elseif($aspiration->status == 'ditolak') bg-red-100 text-red-800
                                                @endif">
                                    {{ ucfirst($aspiration->status) }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-gray-700">
                                {{ !$aspiration->is_anonymous ? $aspiration->reporter_name : 'Anonim' }}
                                <p class="text-xs font-semibold {{ $aspiration->creator_badge_class }}">
                                    {{ $aspiration->creator_label }}
                                </p>
                            </td>

                            {{-- AKSI --}}
                            <td class="text-center px-4 py-3">
                                <div x-data="{
                                                    open: false,
                                                    top: 0,
                                                    left: 0,
                                                    toggle(event) {
                                                        if (this.open) { this.open = false; return; }
                                                        const rect = event.currentTarget.getBoundingClientRect();
                                                        this.top  = rect.bottom + 4;
                                                        this.left = rect.right - 144;
                                                        this.open = true;
                                                    }
                                                }" @scroll.window="open = false" @resize.window="open = false">

                                    <button @click="toggle($event)"
                                        class="cursor-pointer text-gray-500 hover:text-prussian-blue-500 focus:outline-none p-1 hover:bg-gray-100 transition">
                                        <i class="ri-more-line text-lg"></i>
                                    </button>

                                    <template x-teleport="body">
                                        <div x-show="open" x-cloak @click.outside="open = false"
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-75"
                                            x-transition:leave-start="opacity-100 scale-100"
                                            x-transition:leave-end="opacity-0 scale-95"
                                            :style="`top: ${top}px; left: ${left}px;`"
                                            class="fixed z-[100] w-36 origin-top-right bg-white shadow-lg ring-1 ring-black ring-opacity-5 border border-gray-100">

                                            <div class="py-1">
                                                @if ($aspiration->status === \App\Models\Aspiration::STATUS_BARU)
                                                    <button type="button" @click="$dispatch('open-aspiration-confirm', { action: '{{ role_route('aspirasi.update', $aspiration) }}', status: 'ditindaklanjuti', label: 'Tindak Lanjut' }); open = false"
                                                        class="group flex w-full items-center px-4 py-2 text-sm text-emerald-600 hover:bg-emerald-50 transition">
                                                        <i class="ri-play-circle-line mr-2 text-gray-400 group-hover:text-emerald-600"></i>
                                                        Tindak Lanjut
                                                    </button>
                                                @endif

                                                <a href="{{ role_route('aspirasi.show', $aspiration) }}"
                                                    class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-prussian-blue-500 transition">
                                                    <i class="ri-eye-line mr-2 text-gray-400 group-hover:text-prussian-blue-500"></i>
                                                    Detail
                                                </a>

                                            </div>
                                        </div>
                                    </template>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-gray-500">
                                <i class="ri-inbox-line text-4xl block mb-2"></i>
                                <p class="text-sm">
                                    @if (request()->hasAny(['search', 'status']))
                                        Tidak ada aspirasi yang cocok dengan filter.
                                        <a href="{{ role_route('aspirasi.index') }}"
                                            class="text-emerald-600 hover:underline ml-1">Reset filter</a>
                                    @else
                                        Belum ada data aspirasi.
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $aspirations->links('components.pagination') }}

        <template x-teleport="body">
            <div x-show="confirmOpen" x-cloak x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @keydown.escape.window="confirmOpen = false" @click.self="confirmOpen = false" class="fixed inset-0 z-[120] flex items-center justify-center bg-black/50 p-4">
                <div role="dialog" aria-modal="true" class="modal-panel w-full max-w-sm bg-white p-6 shadow-xl">
                    <h2 class="text-lg font-semibold text-gray-800"><span x-text="confirmLabel"></span> Tiket?</h2>
                    <p class="mt-2 text-sm text-gray-600">Pastikan tindakan sesuai. Status tiket akan diperbarui.</p>
                    <form :action="confirmAction" method="POST" class="mt-6 flex justify-end gap-2">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" :value="confirmStatus">
                        <button type="button" @click="confirmOpen = false" class="border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Batal</button>
                        <button type="submit" class="bg-prussian-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-prussian-blue-700">Ya, <span x-text="confirmLabel"></span></button>
                    </form>
                </div>
            </div>
        </template>
    </div>
</x-layout.admin>