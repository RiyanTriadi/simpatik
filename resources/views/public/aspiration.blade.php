<x-layout.main>
    <p class="text-lg font-semibold mb-4">
        Sampaikan aspirasimu terkait peningkatan kualitas hidup di lingkungan institusi.
    </p>

    <div class="max-w-4xl bg-white shadow-sm p-4">
        <form action="{{ route('public.aspirasi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- tentang aspirasi --}}
            <div>
                <h3 class="text-lg text-prussian-blue-300 font-semibold border-b pb-2 mb-4">Tentang Aspirasi</h3>
                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">Penyampai Aspirasi
                    </h3>
                    <div class="col-span-4 md:col-span-3">
                        <div>
                            <input type="radio" name="creator_type" id="eksternal" value="eksternal"
                                @checked(old('creator_type') === 'eksternal')>
                            <label for="eksternal">Aspirasi Ekternal / Masyarakat</label>
                        </div>
                        <div>
                            <input type="radio" name="creator_type" id="internal" value="internal"
                                @checked(old('creator_type') === 'internal')>
                            <label for="internal">Aspirasi Internal / Sivitas</label>
                        </div>
                        @error('creator_type')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">Topik Aspirasi</h3>
                    <div class="col-span-4 md:col-span-3">
                        <input type="text" name="subject" id="subject" class="border border-gray-300 p-2 w-full"
                            value="{{ old('subject') }}" placeholder="Masukkan topik Aspirasi">
                        @error('subject')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">Kategori Aspirasi
                    </h3>
                    <div class="col-span-4 md:col-span-3">
                        <select class="border border-gray-300 p-2" name="category_id" id="category_id">
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- detail Aspirasi --}}
            <div>
                <h3 class="text-lg text-prussian-blue-300 font-semibold border-b pb-2 mb-4">Isi Aspirasi</h3>
                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">Deskripsi Aspirasi
                    </h3>
                    <div class="col-span-4 md:col-span-3">
                        <textarea name="description" id="description" cols="80" rows="5" class="border border-gray-300 p-2 w-full"
                            placeholder="Masukkan deskripsi Aspirasi">{{ old('description') }}</textarea>
                        @error('description')
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
                    Kirim Aspirasi
                </button>
            </div>
        </form>
    </div>
</x-layout.main>
