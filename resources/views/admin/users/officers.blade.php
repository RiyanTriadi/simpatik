<x-layout.admin title="Daftar Petugas">
    <div class="bg-white p-4">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3">
            <div>
                <h1 class="text-lg font-semibold">Daftar Petugas</h1>
                <p class="text-xs text-gray-500 mt-0.5">Lihat daftar petugas beserta beban tugas aktifnya</p>
            </div>

            <form action="{{ role_route('petugas.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                {{-- Filter Unit --}}
                <select name="unit_id" onchange="this.form.submit()"
                    class="h-8 border border-alabaster-grey-600 text-sm px-2 bg-white focus:outline-none focus:border-emerald-500">
                    <option value="">Semua Unit</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->name }}
                        </option>
                    @endforeach
                </select>

                {{-- Search --}}
                <div class="flex">
                    <input type="search" name="search" value="{{ request('search') }}"
                        class="h-8 border border-alabaster-grey-600 text-sm px-4 focus:outline-none focus:border-emerald-500"
                        placeholder="Cari Nama / Email" autocomplete="off">
                    <button type="submit" class="bg-emerald-500 h-8 px-3 cursor-pointer text-white">
                        <i class="ri-search-line"></i>
                    </button>
                </div>
            </form>
        </div>

        {{-- Info Total --}}
        @if ($users->total() > 0)
            <p class="text-xs text-gray-500 mt-3">
                Menampilkan <strong>{{ $users->firstItem() }}</strong>–<strong>{{ $users->lastItem() }}</strong>
                dari <strong>{{ $users->total() }}</strong> petugas
            </p>
        @endif

        {{-- Grid Petugas --}}
        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
            @forelse ($users as $officer)
                <div class="border border-gray-200 p-4 hover:shadow-md hover:border-emerald-300 transition">
                    <div class="flex items-start gap-3">
                        {{-- Avatar --}}
                        @if ($officer->profile_image_path)
                            <img src="{{ Storage::url($officer->profile_image_path) }}" alt="{{ $officer->name }}"
                                class="w-14 h-14 rounded-full object-cover border border-gray-200 flex-shrink-0">
                        @else
                            <div
                                class="w-14 h-14 rounded-full bg-prussian-blue-500 flex items-center justify-center text-white font-bold text-xl flex-shrink-0">
                                {{ strtoupper(substr($officer->name, 0, 1)) }}
                            </div>
                        @endif

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate" title="{{ $officer->name }}">
                                {{ $officer->name }}
                            </p>
                            <p class="text-xs text-gray-500 truncate mt-0.5" title="{{ $officer->email }}">
                                <i class="ri-mail-line text-gray-400"></i> {{ $officer->email }}
                            </p>

                            @if ($officer->phone)
                                <p class="text-xs text-gray-500 truncate mt-0.5">
                                    <i class="ri-phone-line text-gray-400"></i> {{ $officer->phone }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Unit & Beban Tugas --}}
                    <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between gap-2">
                        <div class="flex items-center gap-1.5 text-xs text-gray-600 min-w-0">
                            <i class="ri-building-line text-gray-400"></i>
                            <span class="truncate">{{ $officer->unit->name ?? 'Tanpa Unit' }}</span>
                        </div>

                        @php
                            $taskCount = $officer->active_tasks ?? 0;
                            $taskColor = $taskCount === 0
                                ? 'bg-gray-100 text-gray-600'
                                : ($taskCount <= 3
                                    ? 'bg-emerald-100 text-emerald-700'
                                    : ($taskCount <= 6
                                        ? 'bg-yellow-100 text-yellow-700'
                                        : 'bg-red-100 text-red-700'));
                        @endphp
                        <span class="px-2 py-0.5 text-xs font-semibold whitespace-nowrap {{ $taskColor }}"
                            title="Jumlah tugas aktif (baru + diproses)">
                            {{ $taskCount }} tugas
                        </span>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-gray-500">
                    <i class="ri-user-search-line text-4xl block mb-2 text-gray-300"></i>
                    <p class="text-sm font-medium">
                        @if (request()->hasAny(['search', 'unit_id']))
                            Tidak ada petugas yang cocok dengan filter.
                            <a href="{{ role_route('petugas.index') }}" class="text-emerald-600 hover:underline ml-1">Reset
                                filter</a>
                        @else
                            Belum ada petugas terdaftar.
                        @endif
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Hubungi administrator untuk menambah petugas.</p>
                </div>
            @endforelse
        </div>

        {{ $users->links('components.pagination') }}
    </div>
</x-layout.admin>