<x-layout.admin title="Tindak Lanjut Aspirasi">
    <div class="bg-white p-4">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3">
            <div>
                <h1 class="text-lg font-semibold">Tindak Lanjut Aspirasi</h1>
                <p class="text-xs text-gray-500 mt-0.5">Aspirasi baru & yang sudah dibaca menunggu tindak lanjut</p>
            </div>

            <form action="{{ role_route('aspirasi.follow-up') }}" method="GET" class="flex items-center">
                <input type="search" name="search" value="{{ request('search') }}"
                    class="h-8 border border-alabaster-grey-600 text-sm px-4 focus:outline-none focus:border-emerald-500"
                    placeholder="Cari Tiket / Topik / Pelapor" autocomplete="off">
                <button type="submit" class="bg-emerald-500 h-8 px-3 cursor-pointer text-white">
                    <i class="ri-search-line"></i>
                </button>
            </form>
        </div>

        {{-- Info Banner --}}
        @if ($aspirations->total() > 0)
            <div class="mt-4 bg-purple-50 border border-purple-200 p-3 flex items-center gap-2">
                <i class="ri-information-line text-purple-500 text-lg mt-0.5"></i>
                <p class="text-xs text-purple-700">
                    Terdapat <strong>{{ $aspirations->total() }} aspirasi</strong> yang menunggu tindak lanjut.
                    Pilih petugas melalui tombol aksi untuk menugaskan dan menandai aspirasi sebagai
                    <strong>ditindaklanjuti</strong>.
                </p>
            </div>
        @endif

        {{-- Tabel --}}
        <div class="mt-4 overflow-x-auto border border-alabaster-grey-300">
            <table class="w-full min-w-max text-sm">
                <thead class="bg-alabaster-grey-500">
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
                                            @elseif($aspiration->status == 'dibaca') bg-purple-100 text-purple-800
                                            @elseif($aspiration->status == 'ditindaklanjuti') bg-emerald-100 text-emerald-800
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
                                            modalOpen: @js($errors->has('assigned_to') && (string) old('aspiration_id') === (string) $aspiration->id),
                                            top: 0,
                                            left: 0,
                                            toggle(event) {
                                                    if (this.open) { this.open = false; return; }
                                                    const rect = event.currentTarget.getBoundingClientRect();
                                                    this.top  = rect.bottom + 4;
                                                    this.left = rect.right - 176;
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
                                            class="fixed z-[100] w-44 origin-top-right bg-white shadow-lg ring-1 ring-black ring-opacity-5 border border-gray-100">

                                            <div class="py-1">
                                                {{-- Tindak Lanjut --}}
                                                <button type="button"
                                                    @click="open = false; modalOpen = true"
                                                    class="group flex w-full items-center px-4 py-2 text-sm text-emerald-600 hover:bg-emerald-50 transition">
                                                    <i class="ri-check-double-line mr-2 text-emerald-500"></i>
                                                    Tindak Lanjut
                                                </button>

                                                {{-- Detail --}}
                                                <a href="{{ role_route('aspirasi.show', $aspiration) }}"
                                                    class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-prussian-blue-500 transition border-t border-gray-100">
                                                    <i
                                                        class="ri-eye-line mr-2 text-gray-400 group-hover:text-prussian-blue-500"></i>
                                                    Detail
                                                </a>
                                            </div>
                                        </div>
                                    </template>

                                    <template x-teleport="body">
                                        <div x-show="modalOpen" x-cloak
                                            @keydown.escape.window="modalOpen = false"
                                            @click.self="modalOpen = false"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0"
                                            x-transition:enter-end="opacity-100"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100"
                                            x-transition:leave-end="opacity-0"
                                            class="fixed inset-0 z-[110] flex items-center justify-center bg-black/50 p-4">
                                            <div role="dialog" aria-modal="true"
                                                aria-labelledby="assign-modal-title-{{ $aspiration->id }}"
                                                class="w-full max-w-md bg-white p-6 shadow-xl">
                                                <h2 id="assign-modal-title-{{ $aspiration->id }}"
                                                    class="text-lg font-semibold text-prussian-blue-500">
                                                    Tugaskan Aspirasi
                                                </h2>
                                                <p class="mt-1 text-sm text-gray-500">
                                                    Pilih petugas yang akan menangani tiket
                                                    <strong>{{ $aspiration->ticket_number }}</strong>.
                                                </p>

                                                <form action="{{ role_route('aspirasi.assign.store', $aspiration) }}"
                                                    method="POST" class="mt-5">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="aspiration_id"
                                                        value="{{ $aspiration->id }}">

                                                    <label for="assigned-to-{{ $aspiration->id }}"
                                                        class="mb-1 block text-sm font-medium text-gray-700">
                                                        Petugas
                                                    </label>
                                                    <select id="assigned-to-{{ $aspiration->id }}"
                                                        name="assigned_to" required
                                                        class="w-full border border-gray-300 bg-white px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none">
                                                        <option value="">Pilih petugas</option>
                                                        @foreach ($officers as $officer)
                                                            <option value="{{ $officer->id }}"
                                                                @selected(old('assigned_to') == $officer->id)>
                                                                {{ $officer->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('assigned_to')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror

                                                    <div class="mt-6 flex justify-end gap-2">
                                                        <button type="button" @click="modalOpen = false"
                                                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                            Batal
                                                        </button>
                                                        <button type="submit"
                                                            class="bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                                                            Tugaskan &amp; Tindak Lanjut
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-gray-500">
                                <i class="ri-checkbox-circle-line text-4xl block mb-2 text-emerald-400"></i>
                                <p class="text-sm font-medium text-gray-700">
                                    @if (request('search'))
                                        Tidak ada hasil untuk "{{ request('search') }}".
                                    @else
                                        Tidak ada aspirasi yang perlu ditindaklanjuti.
                                    @endif
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    Semua aspirasi sudah ditindaklanjuti. 🎉
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $aspirations->links('components.pagination') }}
    </div>
</x-layout.admin>