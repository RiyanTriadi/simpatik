<x-layout.main>
    <p class="text-lg font-semibold mb-4">
        Sampaikan pengaduan terkait dugaan pelanggaran, permasalahan layanan, fasilitas, sarana dan prasarana, maupun
        permasalahan lainnya di lingkungan institusi. Pengaduan dapat disampaikan oleh sivitas maupun masyarakat dan
        akan ditindaklanjuti sesuai dengan ketentuan yang berlaku.
    </p>

    <div class="max-w-4xl bg-white shadow-sm p-4">
        <form action="{{ route('public.pengaduan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- tentang pengaduan --}}
            <div>
                <h3 class="text-lg text-prussian-blue-300 font-semibold border-b pb-2 mb-4">Tentang Pengaduan</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-1">Penyampai Pengaduan</h3>
                    <div class="col-span-4 md:col-span-3">
                        <div>
                            <input type="radio" name="creator_type" id="eksternal" value="eksternal"
                                @checked(old('creator_type') === 'eksternal')>
                            <label for="eksternal">Pengaduan Ekternal / Masyarakat</label>
                        </div>
                        <div>
                            <input type="radio" name="creator_type" id="internal" value="internal"
                                @checked(old('creator_type') === 'internal')>
                            <label for="internal">Pengaduan Internal / Sivitas</label>
                        </div>
                        @error('creator_type')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">Kategori Pengaduan
                    </h3>
                    <div class="col-span-4 md:col-span-3" x-data="categorySearch({{ Js::from($categories->map(fn ($category) => ['id' => $category->id, 'name' => $category->name])) }}, '{{ old('category_id') }}')">
                        <input type="hidden" name="category_id" x-model="selectedId">
                        <div class="relative">
                            <input type="search" x-model="search" @input="selectedId = ''" @focus="open = true" @click.outside="open = false"
                                placeholder="Cari kategori..." class="border border-gray-300 p-2 w-full" autocomplete="off">
                            <div x-show="open" x-cloak class="absolute z-20 mt-1 w-full border border-gray-200 bg-white shadow-lg max-h-48 overflow-y-auto">
                                <template x-for="category in filtered" :key="category.id">
                                    <button type="button" @click="select(category)" class="block w-full px-3 py-2 text-left text-sm hover:bg-orange-50" x-text="category.name"></button>
                                </template>
                                <p x-show="filtered.length === 0" class="px-3 py-2 text-sm text-gray-500">Kategori tidak ditemukan.</p>
                            </div>
                        </div>
                        @error('category_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">Topik Pengaduan
                    </h3>
                    <div class="col-span-4 md:col-span-3">
                        <input type="text" name="subject" id="subject" class="border border-gray-300 p-2 w-full"
                            value="{{ old('subject') }}" placeholder="Masukkan topik pengaduan">
                        @error('subject')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- detail pengaduan --}}
            <div>
                <h3 class="text-lg text-prussian-blue-300 font-semibold border-b pb-2 mb-4">Detail Pengaduan</h3>
                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">Deskripsi Pengaduan
                    </h3>
                    <div class="col-span-4 md:col-span-3">
                        <textarea name="description" id="description" cols="80" rows="5" class="border border-gray-300 p-2 w-full"
                            placeholder="Masukkan deskripsi pengaduan">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">Tanggal Kejadian
                    </h3>
                    <div class="col-span-4 md:col-span-3">
                        <input type="date" name="incident_date" id="incident_date" class="border border-gray-300 p-2"
                            max="{{ date('Y-m-d') }}" value="{{ old('incident_date') }}">
                        @error('incident_date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">Lokasi Kejadian
                    </h3>
                    <div class="col-span-4 md:col-span-3">
                        <input type="text" name="incident_location" id="incident_location"
                            class="border border-gray-300 p-2 w-full" value="{{ old('incident_location') }}"
                            placeholder="Masukkan lokasi kejadian">
                        @error('incident_location')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>

            {{-- dokumen pendukung --}}
            <div>
                <h3 class="text-lg text-prussian-blue-300 font-semibold border-b pb-2 mb-4">Dokumen Pendukung</h3>
                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">Lampiran</h3>
                    <div class="col-span-4 md:col-span-3">
                        <input type="file" name="attachment" id="attachment" class="border border-gray-300 p-2">
                        <p class="text-xs text-gray-500 mt-1">Format JPG, JPEG, PNG, atau PDF. Maks. 2 MB.</p>
                        @error('attachment')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>


            {{-- identitas pelapor --}}
            <div>
                <h3 class="text-lg text-prussian-blue-300 font-semibold border-b pb-2 mb-4">Identitas Pelapor</h3>
                <div class="grid grid-cols-4 gap-4 mb-4">
                    <div class="col-span-4 md:col-span-1">
                        <input type="checkbox" name="is_anonymous" id="is_anonymous" value="1" class="p-2"
                            @checked(old('is_anonymous'))>
                        <label for="is_anonymous" class="text-alabaster-grey-800 text-md font-medium">Laporkan secara
                            anonim?</label>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">Nama</h3>
                    <div class="col-span-4 md:col-span-3">
                        <input type="text" name="reporter_name" id="reporter_name"
                            class="border border-gray-300 p-2 w-full" value="{{ old('reporter_name') }}"
                            placeholder="Masukkan nama pelapor">
                        @error('reporter_name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">No. HP</h3>
                    <div class="col-span-4 md:col-span-3">
                        <input type="tel" name="reporter_phone" id="reporter_phone"
                            class="border border-gray-300 p-2 w-full" value="{{ old('reporter_phone') }}"
                            placeholder="Masukkan nomor handphone pelapor">
                        @error('reporter_phone')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">Email</h3>
                    <div class="col-span-4 md:col-span-3">
                        <input type="email" name="reporter_email" id="reporter_email"
                            class="border border-gray-300 p-2 w-full" value="{{ old('reporter_email') }}"
                            placeholder="Masukkan email pelapor">
                        @error('reporter_email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit"
                    class="bg-orange-500 hover:bg-orange-400 text-white font-semibold py-2 px-4 cursor-pointer">
                    Kirim Pengaduan
                </button>
            </div>
        </form>
    </div>
    <script>
        function categorySearch(categories, selectedId) {
            const selected = categories.find((category) => String(category.id) === String(selectedId));
            return {
                categories,
                search: selected?.name || '',
                selectedId: selectedId || '',
                open: false,
                get filtered() {
                    return this.categories.filter((category) => category.name.toLowerCase().includes(this.search.toLowerCase()));
                },
                select(category) {
                    this.selectedId = category.id;
                    this.search = category.name;
                    this.open = false;
                },
            };
        }
    </script>
</x-layout.main>
