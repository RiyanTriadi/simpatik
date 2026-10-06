<x-layout.admin title="Detail Aspirasi">
    <div class="bg-white p-4 md:p-6">
        {{-- Header & Tombol Aksi --}}
        <div
            class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6 border-b border-gray-200 pb-4">
            <div class="flex items-center gap-3">
                <a href="{{ role_route('aspirasi.index') }}"
                    class="p-2 text-gray-500 hover:text-prussian-blue-500 hover:bg-gray-100 transition">
                    <i class="ri-arrow-left-line text-xl"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-prussian-blue-500">Detail Aspirasi</h1>
                    <p class="text-sm text-gray-500">Tiket: <span
                            class="font-semibold text-gray-800">{{ $aspiration->ticket_number }}</span></p>
                </div>
            </div>
        </div>

        {{-- Konten Utama --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Kolom Kiri: Informasi Aspirasi --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-alabaster-grey-100 p-5 border border-alabaster-grey-300">
                    <h2 class="text-lg font-semibold text-prussian-blue-500 mb-2">{{ $aspiration->subject }}</h2>
                    <div class="prose max-w-none text-gray-700 whitespace-pre-line text-sm leading-relaxed">
                        {{ $aspiration->description }}
                    </div>
                </div>

                {{-- Lampiran --}}
                @if ($aspiration->attachment_path)
                    <div class="bg-white border border-gray-200 p-5">
                        <h3 class="text-md font-semibold text-gray-800 mb-4 border-b pb-2">Lampiran</h3>
                        @php($attachmentExtension = strtolower(pathinfo($aspiration->attachment_path, PATHINFO_EXTENSION)))
                        @if (in_array($attachmentExtension, ['jpg', 'jpeg', 'png'], true))
                            <a href="{{ Storage::url($aspiration->attachment_path) }}" target="_blank"
                                rel="noopener noreferrer">
                                <img src="{{ Storage::url($aspiration->attachment_path) }}"
                                    alt="Lampiran aspirasi {{ $aspiration->ticket_number }}"
                                    class="max-h-[32rem] w-full object-contain border border-gray-200 bg-gray-50">
                            </a>
                        @else
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <i class="ri-file-text-line text-xl text-gray-400"></i>
                                <a href="{{ Storage::url($aspiration->attachment_path) }}" target="_blank"
                                    rel="noopener noreferrer" class="text-emerald-600 hover:text-emerald-700">
                                    Lihat lampiran dokumen
                                </a>
                            </div>
                        @endif
                        <a href="{{ role_route('aspirasi.attachment.download', $aspiration) }}"
                            class="mt-4 inline-flex items-center gap-2 bg-emerald-600 px-3 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                            <i class="ri-download-line"></i> Download Lampiran
                        </a>
                    </div>
                @endif
            </div>

            {{-- Kolom Kanan: Metadata & Pelapor --}}
            <div class="space-y-6">
                {{-- Status --}}
                <div class="bg-white border border-gray-200 p-5">
                    <h3 class="text-md font-semibold text-gray-800 mb-4 border-b pb-2">Status</h3>
                    <div class="space-y-3 text-sm">

                        {{-- Status --}}
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Status</span>
                            <div x-data="{ open: false }" class="relative">
                                <div class="flex items-center gap-1.5">
                                    <span class="px-2.5 py-1 text-xs font-semibold 
                                        @if ($aspiration->status == 'baru') bg-blue-100 text-blue-800
                                        @elseif($aspiration->status == 'dibaca') bg-yellow-100 text-yellow-800
                                        @else bg-emerald-100 text-emerald-800 @endif">
                                        {{ ucfirst($aspiration->status) }}
                                    </span>
                                    <button @click="open = !open" @click.outside="open = false"
                                        class="text-gray-400 hover:text-prussian-blue-500 focus:outline-none transition"
                                        title="Ubah Status">
                                        <i class="ri-pencil-line text-base"></i>
                                    </button>
                                </div>

                                {{-- Dropdown Pilihan Status --}}
                                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    class="absolute right-0 z-50 mt-2 w-44 origin-top-right bg-white shadow-lg ring-1 ring-black ring-opacity-5 border border-gray-100">
                                    <div class="py-1">
                                        @foreach (['baru' => 'Baru', 'dibaca' => 'Dibaca', 'ditindaklanjuti' => 'Ditindaklanjuti'] as $value => $label)
                                                <form action="{{ role_route('aspirasi.update', $aspiration) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="{{ $value }}">
                                                    <button type="submit"
                                                        class="group flex w-full items-center gap-2 px-4 py-2 text-sm text-left transition
                                                        {{ $aspiration->status == $value ? 'bg-gray-50 font-semibold text-prussian-blue-500' : 'text-gray-700 hover:bg-gray-100' }}">
                                                        @if ($aspiration->status == $value)
                                                            <i class="ri-check-line text-emerald-500"></i>
                                                        @else
                                                            <i class="ri-circle-line text-gray-300"></i>
                                                        @endif
                                                        {{ $label }}
                                                    </button>
                                                </form>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Kategori --}}
                        <div class="flex flex-col md:flex-row justify-between md:items-center">
                            <span class="text-gray-500">Kategori</span>
                            <span class="font-medium text-gray-800">{{ $aspiration->category->name ?? '-' }}</span>
                        </div>
                        <div class="flex flex-col md:flex-row justify-between md:items-center">
                            <span class="text-gray-500">Jenis Penyampai</span>
                            <span class="font-medium text-gray-800">{{ $aspiration->creator_label }}</span>
                        </div>
                    </div>
                </div>

                {{-- Informasi Pelapor --}}
                <div class="bg-white border border-gray-200 p-5">
                    <h3 class="text-md font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Pelapor</h3>
                    @if($aspiration->is_anonymous)
                        <div class="flex items-center gap-2 text-gray-500 text-sm">
                            <i class="ri-user-ghost-line text-lg"></i>
                            <span>Pelapor bersifat <strong>Anonim</strong></span>
                        </div>
                    @else
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center gap-2 text-gray-700">
                                <i class="ri-user-line text-gray-400"></i>
                                <span class="font-medium">{{ $aspiration->reporter_name }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-700">
                                <i class="ri-phone-line text-gray-400"></i>
                                <span>{{ $aspiration->reporter_phone ?? '-' }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-700">
                                <i class="ri-mail-line text-gray-400"></i>
                                <span>{{ $aspiration->reporter_email ?? '-' }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Petugas yang Menangani --}}
                <div class="bg-white border border-gray-200 p-5">
                    <h3 class="text-md font-semibold text-gray-800 mb-4 border-b pb-2">Petugas Penanganan</h3>
                    @if ($aspiration->officer)
                        <div class="text-sm">
                            <p class="font-medium text-gray-800">{{ $aspiration->officer->name }}</p>
                            <p class="text-xs text-gray-500">{{ $aspiration->officer->unit->name ?? 'Tanpa Unit' }}</p>
                            @if ($aspiration->assigned_at)
                                <p class="mt-0.5 text-xs text-gray-400">
                                    Di-assign {{ $aspiration->assigned_at->diffForHumans() }}
                                </p>
                            @endif
                        </div>
                    @else
                        <div class="flex items-center gap-2 text-gray-500 text-sm">
                            <i class="ri-user-unfollow-line text-lg"></i>
                            <span>Belum di-assign ke petugas</span>
                        </div>
                    @endif

                    @if (in_array(auth()->user()->role, ['admin', 'staff'], true))
                        <form action="{{ role_route('aspirasi.assign.store', $aspiration) }}" method="POST"
                            class="mt-5 border-t border-gray-100 pt-4" x-data="{
                                officerOpen: false,
                                officerSearch: {{ Js::from($aspiration->officer?->name ?? '') }},
                                selectedOfficer: {{ Js::from((string) old('assigned_to', $aspiration->assigned_to)) }},
                                officers: {{ Js::from($officers->map(fn ($officer) => ['id' => (string) $officer->id, 'name' => $officer->name, 'unit' => $officer->unit->name ?? 'Tanpa Unit'])) }},
                                get filteredOfficers() { return this.officers.filter((officer) => `${officer.name} ${officer.unit}`.toLowerCase().includes(this.officerSearch.toLowerCase())); },
                                chooseOfficer(officer) { this.selectedOfficer = officer.id; this.officerSearch = officer.name; this.officerOpen = false; }
                            }">
                            @csrf
                            @method('PUT')
                            <label for="assigned-to-aspiration" class="mb-1 block text-sm font-medium text-gray-700">
                                Assign Petugas
                            </label>
                            <input type="hidden" name="assigned_to" :value="selectedOfficer">
                            <input type="hidden" name="officer_search" :value="officerSearch">
                            <div class="relative">
                                <input id="assigned-to-aspiration" type="search" x-model="officerSearch"
                                    @focus="officerOpen = true" @input="selectedOfficer = ''; officerOpen = true"
                                    @click.outside="officerOpen = false" placeholder="Cari nama atau unit petugas..."
                                    class="w-full border border-gray-300 bg-white px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none">
                                <div x-show="officerOpen" x-cloak class="absolute z-20 mt-1 max-h-48 w-full overflow-y-auto border border-gray-200 bg-white shadow-lg">
                                    <template x-for="officer in filteredOfficers" :key="officer.id">
                                        <button type="button" @click="chooseOfficer(officer)" class="block w-full px-3 py-2 text-left text-sm hover:bg-emerald-50">
                                            <span class="block" x-text="officer.name"></span>
                                            <span class="block text-xs text-gray-500" x-text="officer.unit"></span>
                                        </button>
                                    </template>
                                    <p x-show="filteredOfficers.length === 0" class="px-3 py-2 text-sm text-gray-500">Petugas tidak ditemukan.</p>
                                </div>
                            </div>
                            @error('assigned_to')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <button type="submit"
                                class="mt-3 inline-flex items-center gap-2 bg-prussian-blue-500 px-3 py-2 text-sm font-medium text-white hover:bg-prussian-blue-600">
                                <i class="ri-user-shared-line"></i> Simpan Petugas
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Waktu Lapor --}}
                <div class="bg-white border border-gray-200 p-5">
                    <h3 class="text-md font-semibold text-gray-800 mb-4 border-b pb-2">Waktu Lapor</h3>
                    <div class="text-sm text-gray-700">
                        <p>{{ $aspiration->created_at->translatedFormat('l, d F Y - H:i') }} WIB</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.admin>
