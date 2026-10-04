<x-layout.admin title="Verifikasi Pengaduan">
    <div x-data="{
            modalOpen: false,
            selected: {
                id: null,
                ticket: '',
                subject: '',
                reporter: '',
                status: '',
                priority: ''
            },
            openModal(data) {
                this.selected = data;
                this.modalOpen = true;
            }
        }" class="bg-white p-4">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3">
            <div>
                <h1 class="text-lg font-semibold">Verifikasi Pengaduan</h1>
                <p class="text-xs text-gray-500 mt-0.5">Pengaduan baru yang menunggu verifikasi</p>
            </div>

            <form action="{{ role_route('pengaduan.verification') }}" method="GET" class="flex items-center">
                <input type="search" name="search" value="{{ request('search') }}"
                    class="h-8 border border-alabaster-grey-600 text-sm px-4 focus:outline-none focus:border-emerald-500"
                    placeholder="Cari Tiket / Topik / Pelapor" autocomplete="off">
                <button type="submit" class="bg-emerald-500 h-8 px-3 cursor-pointer text-white">
                    <i class="ri-search-line"></i>
                </button>
            </form>
        </div>

        {{-- Info Banner --}}
        @if ($complaints->total() > 0)
            <div class="mt-4 bg-blue-50 border border-blue-200 p-3 flex items-center gap-2">
                <i class="ri-information-line text-blue-500 text-lg mt-0.5"></i>
                <p class="text-xs text-blue-700">
                    Terdapat <strong>{{ $complaints->total() }} pengaduan</strong> yang menunggu verifikasi.
                    Klik <strong>Verifikasi</strong> untuk menyetujui atau menolak.
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

                            <td class="px-4 py-3 text-sm text-gray-900 max-w-xs">
                                <div class="line-clamp-2" title="{{ $complaint->subject }}">
                                    {{ $complaint->subject }}
                                </div>
                            </td>

                            <td class="px-4 py-3 text-gray-600">
                                {{ $complaint->category->name }}
                            </td>

                            <td class="px-4 py-3 text-gray-700">
                                {{ !$complaint->is_anonymous ? $complaint->reporter_name : 'Anonim' }}
                                <p class="text-xs font-semibold {{ $complaint->creator_badge_class }}">
                                    {{ $complaint->creator_label }}
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
                                                {{-- Verifikasi --}}
                                                <button type="button" @click="
                                                                    openModal({
                                                                        id: {{ $complaint->id }},
                                                                        ticket: {{ Js::from($complaint->ticket_number) }},
                                                                        ticket: '{{ $complaint->ticket_number }}',
                                                                        subject: {{ Js::from($complaint->subject) }},
                                                                        reporter: {{ Js::from(!$complaint->is_anonymous ? $complaint->reporter_name : 'Anonim') }},
                                                                        status: '{{ \App\Models\Complaint::STATUS_DIPROSES }}',
                                                                        priority: '{{ $complaint->priority ?? \App\Models\Complaint::PRIORITY_SEDANG }}'
                                                                    });
                                                                    open = false;
                                                                "
                                                    class="group flex w-full items-center px-4 py-2 text-sm text-emerald-600 hover:bg-emerald-50 transition">
                                                    <i class="ri-shield-check-line mr-2 text-emerald-500"></i>
                                                    Verifikasi
                                                </button>

                                                {{-- Detail --}}
                                                <a href="{{ role_route('pengaduan.show', $complaint) }}"
                                                    class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-prussian-blue-500 transition border-t border-gray-100">
                                                    <i
                                                        class="ri-eye-line mr-2 text-gray-400 group-hover:text-prussian-blue-500"></i>
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
                            <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                                <i class="ri-checkbox-circle-line text-4xl block mb-2 text-emerald-400"></i>
                                <p class="text-sm font-medium text-gray-700">
                                    @if (request('search'))
                                        Tidak ada hasil untuk "{{ request('search') }}".
                                    @else
                                        Tidak ada pengaduan baru yang perlu diverifikasi.
                                    @endif
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    Semua pengaduan sudah diproses. 🎉
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $complaints->links('components.pagination') }}

        {{-- ==================== MODAL VERIFIKASI ==================== --}}
        <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50" @click="modalOpen = false"></div>

            <div class="relative bg-white w-full max-w-lg shadow-xl max-h-[90vh] overflow-y-auto">
                {{-- Header --}}
                <div class="flex items-center justify-between p-5 border-b border-gray-200 sticky top-0 bg-white z-10">
                    <div>
                        <h2 class="text-lg font-bold text-prussian-blue-500">Verifikasi Pengaduan</h2>
                        <p class="text-xs text-gray-500" x-text="`Tiket: ${selected.ticket}`"></p>
                    </div>
                    <button @click="modalOpen = false" class="text-gray-400 hover:text-gray-600 transition">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>

                {{-- Info Singkat --}}
                <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 space-y-1">
                    <p class="text-sm text-gray-700 line-clamp-2" x-text="selected.subject"></p>
                    <p class="text-xs text-gray-500">
                        Pelapor: <span class="font-medium" x-text="selected.reporter"></span>
                    </p>
                </div>

                {{-- Form --}}
                <form :action="`{{ url(role_prefix() . '/pengaduan') }}/${selected.ticket}`" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="p-5 space-y-5">

                        {{-- Pilihan Keputusan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Keputusan Verifikasi <span class="text-red-500">*</span>
                            </label>

                            <div class="grid grid-cols-2 gap-3">
                                {{-- Setujui --}}
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="status"
                                        value="{{ \App\Models\Complaint::STATUS_DIPROSES }}" x-model="selected.status"
                                        class="peer sr-only">
                                    <div class="border-2 border-gray-200 p-4 text-center transition
                                        peer-checked:border-emerald-500 peer-checked:bg-emerald-50
                                        hover:border-emerald-300">
                                        <i class="ri-checkbox-circle-line text-3xl text-emerald-500"></i>
                                        <p class="mt-2 text-sm font-semibold text-gray-800">Setujui</p>
                                        <p class="text-xs text-gray-500 mt-0.5">Pengaduan valid, lanjut diproses</p>
                                    </div>
                                </label>

                                {{-- Tolak --}}
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="status"
                                        value="{{ \App\Models\Complaint::STATUS_DITOLAK }}" x-model="selected.status"
                                        class="peer sr-only">
                                    <div class="border-2 border-gray-200 p-4 text-center transition
                                        peer-checked:border-red-500 peer-checked:bg-red-50
                                        hover:border-red-300">
                                        <i class="ri-close-circle-line text-3xl text-red-500"></i>
                                        <p class="mt-2 text-sm font-semibold text-gray-800">Tolak</p>
                                        <p class="text-xs text-gray-500 mt-0.5">Pengaduan tidak valid</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Prioritas (muncul jika Setujui) --}}
                        <div x-show="selected.status === '{{ \App\Models\Complaint::STATUS_DIPROSES }}'"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Prioritas Penanganan <span class="text-red-500">*</span>
                            </label>

                            <div class="grid grid-cols-2 gap-2">
                                {{-- Rendah --}}
                                <label class="cursor-pointer">
                                    <input type="radio" name="priority"
                                        value="{{ \App\Models\Complaint::PRIORITY_RENDAH }}" x-model="selected.priority"
                                        class="peer sr-only">
                                    <div class="flex items-center gap-2 border-2 border-gray-200 px-3 py-2 transition
                                        peer-checked:border-gray-500 peer-checked:bg-gray-50 hover:border-gray-300">
                                        <i class="ri-arrow-down-line text-gray-500 text-lg"></i>
                                        <span class="text-sm font-medium text-gray-700">Rendah</span>
                                    </div>
                                </label>

                                {{-- Sedang --}}
                                <label class="cursor-pointer">
                                    <input type="radio" name="priority"
                                        value="{{ \App\Models\Complaint::PRIORITY_SEDANG }}" x-model="selected.priority"
                                        class="peer sr-only">
                                    <div
                                        class="flex items-center gap-2 border-2 border-gray-200 px-3 py-2 transition
                                        peer-checked:border-yellow-500 peer-checked:bg-yellow-50 hover:border-yellow-300">
                                        <i class="ri-subtract-line text-yellow-500 text-lg"></i>
                                        <span class="text-sm font-medium text-gray-700">Sedang</span>
                                    </div>
                                </label>

                                {{-- Tinggi --}}
                                <label class="cursor-pointer">
                                    <input type="radio" name="priority"
                                        value="{{ \App\Models\Complaint::PRIORITY_TINGGI }}" x-model="selected.priority"
                                        class="peer sr-only">
                                    <div
                                        class="flex items-center gap-2 border-2 border-gray-200 px-3 py-2 transition
                                        peer-checked:border-orange-500 peer-checked:bg-orange-50 hover:border-orange-300">
                                        <i class="ri-arrow-up-line text-orange-500 text-lg"></i>
                                        <span class="text-sm font-medium text-gray-700">Tinggi</span>
                                    </div>
                                </label>

                                {{-- Urgent --}}
                                <label class="cursor-pointer">
                                    <input type="radio" name="priority"
                                        value="{{ \App\Models\Complaint::PRIORITY_URGENT }}" x-model="selected.priority"
                                        class="peer sr-only">
                                    <div class="flex items-center gap-2 border-2 border-gray-200 px-3 py-2 transition
                                        peer-checked:border-red-500 peer-checked:bg-red-50 hover:border-red-300">
                                        <i class="ri-alarm-warning-line text-red-500 text-lg"></i>
                                        <span class="text-sm font-medium text-gray-700">Urgent</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Info jika Tolak --}}
                        <div x-show="selected.status === '{{ \App\Models\Complaint::STATUS_DITOLAK }}'"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="bg-red-50 border border-red-200 p-3 flex items-start gap-2">
                            <i class="ri-alert-line text-red-500 text-lg mt-0.5"></i>
                            <p class="text-xs text-red-700">
                                Pengaduan akan ditandai sebagai <strong>ditolak</strong>. Tindakan ini tidak dapat
                                dibatalkan.
                            </p>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div
                        class="flex items-center justify-end gap-2 px-5 py-4 border-t border-gray-200 bg-gray-50 sticky bottom-0">
                        <button type="button" @click="modalOpen = false"
                            class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 hover:bg-gray-100 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 text-sm font-medium transition flex items-center gap-2">
                            <i class="ri-save-line"></i>
                            Simpan Verifikasi
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-layout.admin>