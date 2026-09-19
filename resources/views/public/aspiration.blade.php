<x-layout.main>
    <h1 class="text-lg font-semibold mb-4">
        Sampaikan aspirasimu terkait peningkatan kualitas hidup di lingkungan institusi.
    </h1>


    <div class="max-w-4xl bg-white shadow-sm p-4">
        <form action="">
            {{-- tentang aspirasi --}}
            <div>
                <h3 class="text-lg text-prussian-blue-300 font-semibold border-b pb-2 mb-4">Tentang Aspirasi</h3>
                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">Penyampai Aspirasi</h3>
                    <div class="col-span-4 md:col-span-3">
                        <div>
                            <input type="radio" name="creator_type" id="ekternal">
                            <label for="ekternal">Aspirasi Ekternal / Masyarakat</label>
                        </div>
                        <div>
                            <input type="radio" name="creator_type" id="internal">
                            <label for="internal">Aspirasi Internal / Sivitas</label>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">Topik Aspirasi</h3>
                    <input type="text" name="topic" id="topic" class="border border-gray-300 p-2"
                        placeholder="Masukkan topik Aspirasi">
                </div>

                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">Kategori Aspirasi</h3>
                    <select class="min-w-[300px] border border-gray-300 p-2" name="category" id="category">
                        <option value="">Pilih Kategori</option>
                        <option value="pelanggaran">Pelanggaran</option>
                        <option value="layanan">Permasalahan Layanan</option>
                        <option value="fasilitas">Permasalahan Fasilitas</option>
                        <option value="sarana-prasarana">Permasalahan Sarana dan Prasarana</option>
                        <option value="lainnya">Permasalahan Lainnya</option>
                    </select>
                </div>
            </div>

            {{-- detail Aspirasi --}}
            <div>
                <h3 class="text-lg text-prussian-blue-300 font-semibold border-b pb-2 mb-4">Isi Aspirasi</h3>
                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium">Deskripsi Aspirasi</h3>
                    <div>
                        <textarea name="description" id="description" cols="80" rows="5"
                            class="min-w-[300px] border border-gray-300 p-2" placeholder="Masukkan deskripsi Aspirasi"></textarea>
                    </div>
                </div>
            </div>

            {{-- dokumen pendukung --}}
            <div>
                <h3 class="text-lg text-prussian-blue-300 font-semibold border-b pb-2 mb-4">Dokumen Pendukung</h3>
                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium">Lampiran</h3>
                    <input type="file" name="attachment" id="attachment"
                        class="min-w-[300px] border border-gray-300 p-2">
                </div>
            </div>


            {{-- identitas pelapor --}}
            <div>
                <h3 class="text-lg text-prussian-blue-300 font-semibold border-b pb-2 mb-4">Identitas Pelapor</h3>
                <div class="grid grid-cols-4 gap-4 gap-4 mb-4">
                    <div class="col-span-4 md:col-span-1">
                        <input type="checkbox" name="anonymous" id="anonymous" class="p-2">
                        <label for="anonymous" class="text-alabaster-grey-800 text-md font-medium">Laporkan secara
                            anonim?</label>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">Nama</h3>
                    <div class="col-span-4 md:col-span-3">
                        <input type="text" name="name" id="name" class="min-w-[300px] border border-gray-300 p-2"
                            placeholder="Masukkan nama pelapor">
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium">No. HP</h3>
                    <input type="text" name="phone" id="phone" class="min-w-[300px] border border-gray-300 p-2"
                        placeholder="Masukkan nomor handphone pelapor">
                </div>

                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium">Email</h3>
                    <input type="text" name="email" id="email"
                        class="min-w-[300px] border border-gray-300 p-2" placeholder="Masukkan email pelapor">
                </div>
            </div>

            <div class="grid grid-cols-4 gap-4 justify-end">
                <button type="submit"
                    class="bg-orange-500 hover:bg-orange-400 text-white font-semibold py-2 px-4 cursor-pointer">
                    Kirim Aspirasi
                </button>
            </div>
        </form>
    </div>
</x-layout.main>
