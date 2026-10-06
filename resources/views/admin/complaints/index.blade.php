<x-layout.admin title="Pengaduan">
    <div class="bg-white p-4">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3">
            <div>
                <h1 class="text-lg font-semibold">Daftar Pengaduan</h1>
                <p class="text-xs text-gray-500 mt-0.5">Semua pengaduan yang masuk ke sistem</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <form action="{{ role_route('pengaduan.index') }}" method="GET"
                    class="flex flex-wrap items-center gap-2">
                    {{-- Filter Status --}}
                    <select name="status" onchange="this.form.submit()"
                        class="h-8 border border-alabaster-grey-600 text-sm px-2 bg-white focus:outline-none focus:border-emerald-500">
                        <option value="">Semua Status</option>
                        <option value="baru" {{ request('status') == 'baru' ? 'selected' : '' }}>Baru</option>
                        <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>

                    {{-- Filter Prioritas --}}
                    <select name="priority" onchange="this.form.submit()"
                        class="h-8 border border-alabaster-grey-600 text-sm px-2 bg-white focus:outline-none focus:border-emerald-500">
                        <option value="">Semua Prioritas</option>
                        <option value="rendah" {{ request('priority') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                        <option value="sedang" {{ request('priority') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="tinggi" {{ request('priority') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                        <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
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
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">Prioritas</th>
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">Pelapor</th>
                        <th class="px-4 py-3 text-center font-semibold text-prussian-blue-500">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($complaints as $complaint)
                        <tr class="border-b border-alabaster-grey-100 bg-white hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-500">
                                {{ $loop->iteration + ($complaints->currentPage() - 1) * $complaints->perPage() }}
                            </td>

                            <td class="px-4 py-3 font-medium text-prussian-blue-500">
                                {{ $complaint->ticket_number }}
                                <p class="text-xs text-gray-500">{{ $complaint->created_at->translatedFormat('d F Y') }}</p>
                            </td>

                            <td class="px-4 py-3 max-w-xs">
                                <div class="line-clamp-2" title="{{ $complaint->subject }}">
                                    {{ $complaint->subject }}
                                </div>
                            </td>

                            <td class="px-4 py-3 text-gray-600">
                                {{ $complaint->category->name }}
                            </td>

                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs font-semibold whitespace-nowrap
                                                                            @if ($complaint->status == 'baru') bg-blue-100 text-blue-800
                                                                            @elseif($complaint->status == 'diproses') bg-yellow-100 text-yellow-800
                                                                            @elseif($complaint->status == 'selesai') bg-emerald-100 text-emerald-800
                                                                            @elseif($complaint->status == 'ditolak') bg-red-100 text-red-800
                                                                            @endif">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                @if ($complaint->priority)
                                    <span
                                        class="px-2 py-1 text-xs font-semibold whitespace-nowrap
                                                                                                                        @if ($complaint->priority == 'urgent') bg-red-100 text-red-800
                                                                                                                        @elseif($complaint->priority == 'tinggi') bg-orange-100 text-orange-800
                                                                                                                        @elseif($complaint->priority == 'sedang') bg-yellow-100 text-yellow-800
                                                                                                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($complaint->priority) }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-gray-700">
                                {{ !$complaint->is_anonymous ? $complaint->reporter_name : 'Anonim' }}
                                <p class="text-xs font-semibold {{ $complaint->creator_badge_class }}">
                                    {{ $complaint->creator_label }}
                                </p>
                            </td>

                            <td class="text-center px-4 py-3">
                                <a href="{{ role_route('pengaduan.show', $complaint) }}"
                                    class="inline-flex items-center gap-1 text-xs text-gray-600 hover:text-prussian-blue-500 border border-gray-300 hover:border-prussian-blue-500 px-2.5 py-1 transition">
                                    <i class="ri-eye-line"></i>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-gray-500">
                                <i class="ri-inbox-line text-4xl block mb-2"></i>
                                <p class="text-sm">
                                    @if (request()->hasAny(['search', 'status', 'priority']))
                                        Tidak ada pengaduan yang cocok dengan filter.
                                        <a href="{{ role_route('pengaduan.index') }}"
                                            class="text-emerald-600 hover:underline ml-1">Reset filter</a>
                                    @else
                                        Belum ada data pengaduan.
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $complaints->links('components.pagination') }}
    </div>
</x-layout.admin>