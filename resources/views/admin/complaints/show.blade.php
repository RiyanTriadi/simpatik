<x-layout.admin title="Detail Pengaduan">
    <div class="bg-white p-4 md:p-6">
        {{-- Header & Tombol Aksi --}}
        <div
            class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6 border-b border-gray-200 pb-4">
            <div class="flex items-center gap-3">
                <a href="{{ role_route('pengaduan.index') }}"
                    class="p-2 text-gray-500 hover:text-prussian-blue-500 hover:bg-gray-100 transition">
                    <i class="ri-arrow-left-line text-xl"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-prussian-blue-500">Detail Pengaduan</h1>
                    <p class="text-sm text-gray-500">Tiket: <span
                            class="font-semibold text-gray-800">{{ $complaint->ticket_number }}</span></p>
                </div>
            </div>
        </div>

        {{-- Konten Utama --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Kolom Kiri: Informasi Pengaduan --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Topik & Deskripsi --}}
                <div class="bg-alabaster-grey-100 p-5 border border-alabaster-grey-300">
                    <h2 class="text-lg font-semibold text-prussian-blue-500 mb-2">{{ $complaint->subject }}</h2>
                    <div class="prose max-w-none text-gray-700 whitespace-pre-line text-sm leading-relaxed">
                        {{ $complaint->description }}
                    </div>
                </div>

                {{-- Detail Kejadian --}}
                <div class="bg-white border border-gray-200 p-5">
                    <h3 class="text-md font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Kejadian</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500 mb-1">Tanggal Kejadian</p>
                            <p class="font-medium text-gray-800">
                                {{ \Carbon\Carbon::parse($complaint->incident_date)->translatedFormat('d F Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500 mb-1">Lokasi Kejadian</p>
                            <p class="font-medium text-gray-800">{{ $complaint->incident_location ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Lampiran --}}
                @if ($complaint->attachment_path)
                    <div class="bg-white border border-gray-200 p-5">
                        <h3 class="text-md font-semibold text-gray-800 mb-4 border-b pb-2">Lampiran</h3>
                        <a href="{{ Storage::url($complaint->attachment_path) }}" target="_blank"
                            class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-medium text-sm">
                            <i class="ri-file-download-line text-lg"></i> Lihat / Unduh Lampiran
                        </a>
                    </div>
                @endif
            </div>

            {{-- Kolom Kanan: Metadata & Pelapor --}}
            <div class="space-y-6">
                {{-- Status & Prioritas --}}
                <div class="bg-white border border-gray-200 p-5">
                    <h3 class="text-md font-semibold text-gray-800 mb-4 border-b pb-2">Status & Prioritas</h3>
                    <div class="space-y-3 text-sm">

                        {{-- ==================== STATUS ==================== --}}
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Status</span>
                            <div x-data="{ open: false }" class="relative">
                                <div class="flex items-center gap-1.5">
                                    <span class="px-2.5 py-1 text-xs font-semibold 
                        @if ($complaint->status == 'baru') bg-blue-100 text-blue-800
                        @elseif($complaint->status == 'diproses') bg-yellow-100 text-yellow-800
                        @elseif($complaint->status == 'selesai') bg-emerald-100 text-emerald-800
                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($complaint->status) }}
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
                                    class="absolute right-0 z-50 mt-2 w-40 origin-top-right bg-white shadow-lg ring-1 ring-black ring-opacity-5 border border-gray-100">
                                    <div class="py-1">
                                        @foreach (['baru' => 'Baru', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'] as $value => $label)
                                            <form action="{{ role_route('pengaduan.update', $complaint->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="{{ $value }}">
                                                <button type="submit"
                                                    class="group flex w-full items-center gap-2 px-4 py-2 text-sm text-left transition
                                                {{ $complaint->status == $value ? 'bg-gray-50 font-semibold text-prussian-blue-500' : 'text-gray-700 hover:bg-gray-100' }}">
                                                    @if ($complaint->status == $value)
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

                        {{-- Prioritas --}}
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Prioritas</span>
                            <div x-data="{ open: false }" class="relative">
                                <div class="flex items-center gap-1.5">
                                    <span class="px-2.5 py-1 text-xs font-semibold 
                                        @if ($complaint->priority == 'urgent') bg-red-100 text-red-800
                                        @elseif($complaint->priority == 'tinggi') bg-orange-100 text-orange-800
                                        @elseif($complaint->priority == 'sedang') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($complaint->priority) }}
                                    </span>
                                    <button @click="open = !open" @click.outside="open = false"
                                        class="text-gray-400 hover:text-prussian-blue-500 focus:outline-none transition"
                                        title="Ubah Prioritas">
                                        <i class="ri-pencil-line text-base"></i>
                                    </button>
                                </div>

                                {{-- Dropdown Pilihan Prioritas --}}
                                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    class="absolute right-0 z-50 mt-2 w-40 origin-top-right bg-white shadow-lg ring-1 ring-black ring-opacity-5 border border-gray-100">
                                    <div class="py-1">
                                        @foreach (['rendah' => 'Rendah', 'sedang' => 'Sedang', 'tinggi' => 'Tinggi', 'urgent' => 'Urgent'] as $value => $label)
                                            <form action="{{ role_route('pengaduan.update', $complaint->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="priority" value="{{ $value }}">
                                                <button type="submit"
                                                    class="group flex w-full items-center gap-2 px-4 py-2 text-sm text-left transition
                                                {{ $complaint->priority == $value ? 'bg-gray-50 font-semibold text-prussian-blue-500' : 'text-gray-700 hover:bg-gray-100' }}">
                                                    @if ($complaint->priority == $value)
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
                            <span class="font-medium text-gray-800">{{ $complaint->category->name }}</span>
                        </div>
                    </div>
                </div>

                {{-- Informasi Pelapor --}}
                <div class="bg-white border border-gray-200 p-5">
                    <h3 class="text-md font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Pelapor</h3>
                    @if ($complaint->is_anonymous)
                        <div class="flex items-center gap-2 text-gray-500 text-sm">
                            <i class="ri-user-ghost-line text-lg"></i>
                            <span>Pelapor bersifat <strong>Anonim</strong></span>
                        </div>
                    @else
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center gap-2 text-gray-700">
                                <i class="ri-user-line text-gray-400"></i>
                                <span class="font-medium">{{ $complaint->reporter_name }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-700">
                                <i class="ri-phone-line text-gray-400"></i>
                                <span>{{ $complaint->reporter_phone ?? '-' }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-700">
                                <i class="ri-mail-line text-gray-400"></i>
                                <span>{{ $complaint->reporter_email ?? '-' }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Petugas yang Menangani --}}
                <div class="bg-white border border-gray-200 p-5">
                    <h3 class="text-md font-semibold text-gray-800 mb-4 border-b pb-2">Petugas Penanganan</h3>
                    @if ($complaint->officer)
                        <div class="flex items-center gap-3">
                            @if ($complaint->officer->profile_image_path)
                                <img src="{{ Storage::url($complaint->officer->profile_image_path) }}"
                                    class="w-12 h-12 rounded-full object-cover border border-gray-200">
                            @else
                                <div class="w-12 h-12 rounded-full bg-prussian-blue-500 flex items-center justify-center text-white font-semibold text-lg">
                                    {{ strtoupper(substr($complaint->officer->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="text-sm">
                                <p class="font-medium text-gray-800">{{ $complaint->officer->name }}</p>
                                <p class="text-xs text-gray-500">{{ $complaint->officer->unit->name ?? 'Tanpa Unit' }}</p>
                                @if ($complaint->assigned_at)
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        Di-assign {{ $complaint->assigned_at->diffForHumans() }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-2 text-gray-500 text-sm">
                            <i class="ri-user-unfollow-line text-lg"></i>
                            <span>Belum di-assign ke petugas</span>
                        </div>
                    @endif
                </div>

                {{-- Waktu Lapor --}}
                <div class="bg-white border border-gray-200 p-5">
                    <h3 class="text-md font-semibold text-gray-800 mb-4 border-b pb-2">Waktu Lapor</h3>
                    <div class="text-sm text-gray-700">
                        <p>{{ $complaint->created_at->translatedFormat('l, d F Y - H:i') }} WIB</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.admin>