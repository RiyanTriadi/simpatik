<x-layout.admin title="Assign ke Petugas">
    <div x-data="{
            modalOpen: false,
            selectedComplaint: { id: null, ticket: '', subject: '', assigned_to: null },
            officerSearch: '',
            selectedOfficer: '',
            officerOpen: false,
            officers: {{ Js::from($officers->map(fn ($officer) => ['id' => $officer->id, 'name' => $officer->name, 'unit' => $officer->unit->name ?? 'Tanpa Unit'])) }},
            get filteredOfficers() {
                return this.officers.filter((officer) => `${officer.name} ${officer.unit}`.toLowerCase().includes(this.officerSearch.toLowerCase()));
            },
            chooseOfficer(officer) {
                this.selectedOfficer = officer.id;
                this.officerSearch = `${officer.name} - ${officer.unit}`;
            }
        }" class="bg-white p-4">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3">
            <div>
                <h1 class="text-lg font-semibold">Assign Pengaduan ke Petugas</h1>
                <p class="text-xs text-gray-500 mt-0.5">Tugaskan pengaduan kepada petugas yang tersedia</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <form action="{{ role_route('pengaduan.assign') }}" method="GET" class="flex flex-wrap items-center gap-2">
                    <select name="assignment" onchange="this.form.submit()"
                        class="h-8 border border-alabaster-grey-600 text-sm px-2 bg-white focus:outline-none focus:border-emerald-500">
                        <option value="">Semua</option>
                        <option value="unassigned" {{ request('assignment') == 'unassigned' ? 'selected' : '' }}>Belum Di-assign</option>
                        <option value="assigned" {{ request('assignment') == 'assigned' ? 'selected' : '' }}>Sudah Di-assign</option>
                    </select>

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

        {{-- Ringkasan --}}
        <div class="grid grid-cols-2 gap-3 mt-4">
            <div class="border border-gray-200 p-3 bg-blue-50/50">
                <p class="text-xs text-gray-500">Belum Di-assign</p>
                <p class="text-lg font-bold text-prussian-blue-500">
                    {{ \App\Models\Complaint::whereNull('assigned_to')
    ->whereNotIn('status', [\App\Models\Complaint::STATUS_SELESAI, \App\Models\Complaint::STATUS_DITOLAK])
    ->count() }}
                </p>
            </div>
            <div class="border border-gray-200 p-3 bg-emerald-50/50">
                <p class="text-xs text-gray-500">Sudah Di-assign</p>
                <p class="text-lg font-bold text-emerald-600">
                    {{ \App\Models\Complaint::whereNotNull('assigned_to')
    ->whereNotIn('status', [\App\Models\Complaint::STATUS_SELESAI, \App\Models\Complaint::STATUS_DITOLAK])
    ->count() }}
                </p>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="mt-4 overflow-x-auto border border-alabaster-grey-300">
            <table class="w-full min-w-max text-sm">
                <thead class="bg-prussian-blue-100">
                    <tr class="border-b border-alabaster-grey-300">
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">No Tiket</th>
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">Topik</th>
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">Petugas</th>
                        <th class="px-4 py-3 text-center font-semibold text-prussian-blue-500">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($complaints as $complaint)
                        <tr class="border-b border-alabaster-grey-100 bg-white hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-prussian-blue-500">
                                {{ $complaint->ticket_number }}
                            </td>

                            <td class="px-4 py-3">
                                <p class="line-clamp-1">{{ $complaint->subject }}</p>
                            </td>

                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs font-semibold 
                                    @if ($complaint->status == 'baru') bg-blue-100 text-blue-800
                                    @elseif($complaint->status == 'diproses') bg-yellow-100 text-yellow-800
                                    @elseif($complaint->status == 'selesai') bg-emerald-100 text-emerald-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                @if ($complaint->officer)
                                    <div class="flex items-center gap-2">
                                        @if ($complaint->officer->profile_image_path)
                                            <img src="{{ Storage::url($complaint->officer->profile_image_path) }}"
                                                class="w-7 h-7 rounded-full object-cover border border-gray-200">
                                        @else
                                            <div class="w-7 h-7 rounded-full bg-prussian-blue-500 flex items-center justify-center text-white text-xs font-semibold">
                                                {{ strtoupper(substr($complaint->officer->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-xs font-medium text-gray-800">{{ $complaint->officer->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $complaint->officer->unit->name ?? '-' }}</p>
                                        </div>
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs text-gray-400">
                                        <i class="ri-user-unfollow-line"></i>
                                        Belum di-assign
                                    </span>
                                @endif
                            </td>

                            <td class="text-center px-4 py-3">
                                <div
                                    x-data="{
                                        open: false,
                                        top: 0,
                                        left: 0,
                                        toggle(event) {
                                            if (this.open) {
                                                this.open = false;
                                                return;
                                            }
                                            const rect = event.currentTarget.getBoundingClientRect();
                                            this.top = rect.bottom + 4;
                                            this.left = rect.right - 144;
                                            this.open = true;
                                        }
                                    }"
                                    @scroll.window="open = false"
                                    @resize.window="open = false"
                                >
                                    <button
                                        @click="toggle($event)"
                                        class="cursor-pointer text-gray-500 hover:text-prussian-blue-500 focus:outline-none p-1 hover:bg-gray-100 transition"
                                    >
                                        <i class="ri-more-line text-lg"></i>
                                    </button>

                                    <template x-teleport="body">
                                        <div
                                            x-show="open"
                                            x-cloak
                                            @click.outside="open = false"
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-75"
                                            x-transition:leave-start="opacity-100 scale-100"
                                            x-transition:leave-end="opacity-0 scale-95"
                                            :style="`top: ${top}px; left: ${left}px;`"
                                            class="fixed z-[100] w-36 origin-top-right bg-white shadow-lg ring-1 ring-black ring-opacity-5 border border-gray-100"
                                        >
                                            <div class="py-1">
                                                {{-- Assign / Ubah Petugas --}}
                                                <button
                                                    type="button"
                                                    @click="
                                                        selectedComplaint = {
                                                            id: {{ $complaint->id }},
                                                            ticket: {{ Js::from($complaint->ticket_number) }},
                                                            ticket: '{{ $complaint->ticket_number }}',
                                                            subject: {{ Js::from($complaint->subject) }},
                                                            assigned_to: {{ $complaint->assigned_to ?? 'null' }}
                                                        };
                                                        selectedOfficer = {{ $complaint->assigned_to ?? 'null' }};
                                                        officerSearch = '';
                                                        officerOpen = false;
                                                        modalOpen = true;
                                                        open = false;
                                                    "
                                                    class="group flex w-full items-center px-4 py-2 text-sm text-emerald-600 hover:bg-emerald-50 transition border-t border-gray-100"
                                                >
                                                    <i class="ri-user-add-line mr-2 text-emerald-500"></i>
                                                    {{ $complaint->officer ? 'Ubah Petugas' : 'Assign' }}
                                                </button>

                                                {{-- Detail --}}
                                                <a
                                                    href="{{ role_route('pengaduan.show', $complaint) }}"
                                                    class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-prussian-blue-500 transition"
                                                >
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
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                <i class="ri-inbox-line text-3xl block mb-2"></i>
                                Tidak ada pengaduan yang perlu di-assign.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $complaints->links('components.pagination') }}

        {{-- Modal Assign --}}
        <div x-show="modalOpen" x-cloak x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50" @click="modalOpen = false"></div>
            <div class="modal-panel relative bg-white w-full max-w-md shadow-xl">
                {{-- Header --}}
                <div class="flex items-center justify-between p-5 border-b border-gray-200">
                    <div>
                        <h2 class="text-lg font-bold text-prussian-blue-500">Assign ke Petugas</h2>
                        <p class="text-xs text-gray-500" x-text="`Tiket: ${selectedComplaint.ticket}`"></p>
                    </div>
                    <button @click="modalOpen = false" class="text-gray-400 hover:text-gray-600 transition">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>

                {{-- Info Pengaduan --}}
                <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">
                    <p class="text-sm text-gray-700 line-clamp-2" x-text="selectedComplaint.subject"></p>
                </div>

                {{-- Form --}}
                <form :action="`{{ url(role_prefix() . '/pengaduan') }}/${selectedComplaint.ticket}/assign`" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="p-5 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Pilih Petugas <span class="text-red-500">*</span>
                            </label>
                            <input type="hidden" name="assigned_to" :value="selectedOfficer">
                            <input type="hidden" name="officer_search" :value="officerSearch">
                            <div class="relative">
                                <input type="search" x-model="officerSearch" @focus="officerOpen = true" @input="selectedOfficer = ''; officerOpen = true" @click.outside="officerOpen = false"
                                    placeholder="Cari nama atau unit petugas..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                                <div x-show="officerOpen" x-cloak class="absolute z-20 mt-1 w-full max-h-48 overflow-y-auto border border-gray-200 bg-white shadow-lg">
                                    <template x-for="officer in filteredOfficers" :key="officer.id">
                                        <button type="button" @click="chooseOfficer(officer)" class="block w-full px-3 py-2 text-left text-sm hover:bg-emerald-50">
                                            <span x-text="officer.name"></span>
                                            <span class="block text-xs text-gray-500" x-text="officer.unit"></span>
                                        </button>
                                    </template>
                                    <p x-show="filteredOfficers.length === 0" class="px-3 py-2 text-sm text-gray-500">Petugas tidak ditemukan.</p>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Hanya user dengan role <strong>Petugas</strong> yang muncul di sini.
                            </p>
                        </div>

                        @if ($officers->isEmpty())
                            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-3 py-2 text-xs flex items-start gap-2">
                                <i class="ri-alert-line text-lg"></i>
                                <span>Belum ada user dengan role petugas. <a href="{{ route('admin.users.create') }}" class="underline font-semibold">Tambah petugas</a> terlebih dahulu.</span>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-between gap-2 px-5 py-4 border-t border-gray-200 bg-gray-50">
                        {{-- Tombol Unassign (hanya jika sudah di-assign) --}}
                        <template x-if="selectedComplaint.assigned_to">
                            <button type="submit"
                                form="unassign-form-{{ '' }}"
                                @click.prevent="
                                    if (confirm('Batalkan assign pengaduan ini?')) {
                                        document.getElementById('unassign-form').submit();
                                    }
                                "
                                class="text-xs text-red-600 hover:text-red-800 underline transition">
                                Batalkan Assign
                            </button>
                        </template>

                        <div class="flex items-center gap-2 ml-auto">
                            <button type="button" @click="modalOpen = false"
                                class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 hover:bg-gray-100 transition">
                                Batal
                            </button>
                            <button type="submit" {{ $officers->isEmpty() ? 'disabled' : '' }}
                                class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 text-sm font-medium transition flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="ri-save-line"></i> Simpan
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Form Terpisah untuk Unassign --}}
                <form id="unassign-form" :action="`{{ url(role_prefix() . '/pengaduan') }}/${selectedComplaint.ticket}/assign`" method="POST" class="hidden">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="assigned_to" value="">
                </form>
            </div>
        </div>

    </div>
</x-layout.admin>
