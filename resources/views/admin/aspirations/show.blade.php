<x-layout.admin title="Detail Aspirasi">
    <div class="bg-white p-4 md:p-6">
        {{-- Header & Tombol Aksi --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6 border-b border-gray-200 pb-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.aspirasi.index') }}" class="p-2 text-gray-500 hover:text-prussian-blue-500 hover:bg-gray-100 transition">
                    <i class="ri-arrow-left-line text-xl"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-prussian-blue-500">Detail Aspirasi</h1>
                    <p class="text-sm text-gray-500">Tiket: <span class="font-semibold text-gray-800">{{ $aspiration->ticket_number }}</span></p>
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
                @if($aspiration->attachment_path)
                <div class="bg-white border border-gray-200 p-5">
                    <h3 class="text-md font-semibold text-gray-800 mb-4 border-b pb-2">Lampiran</h3>
                    <a href="{{ Storage::url($aspiration->attachment_path) }}" target="_blank" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-medium text-sm">
                        <i class="ri-file-download-line text-lg"></i> Lihat / Unduh Lampiran
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
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Status</span>
                            <span class="px-2.5 py-1 text-xs font-semibold 
                                @if($aspiration->status == 'baru') bg-blue-100 text-blue-800
                                @elseif($aspiration->status == 'dibaca') bg-yellow-100 text-yellow-800
                                @else bg-emerald-100 text-emerald-800 @endif">
                                {{ ucfirst($aspiration->status) }}
                            </span>
                        </div>
                        <div class="flex flex-col md:flex-row justify-between md:items-center">
                            <span class="text-gray-500">Kategori</span>
                            <span class="font-medium text-gray-800">{{ $aspiration->category->name }}</span>
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