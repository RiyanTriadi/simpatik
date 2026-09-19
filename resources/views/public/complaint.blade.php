<x-layout.main>
    <h1 class="text-lg font-semibold mb-4">
        Sampaikan pengaduan terkait dugaan pelanggaran, permasalahan layanan, fasilitas, sarana dan prasarana, maupun
        permasalahan lainnya di lingkungan institusi. Pengaduan dapat disampaikan oleh sivitas maupun masyarakat dan
        akan ditindaklanjuti sesuai dengan ketentuan yang berlaku.
    </h1>

    <div class="max-w-4xl bg-white shadow-sm p-4">
        <form action="">
            {{-- tentang pengaduan --}}
            <div>
                <h3 class="text-lg text-prussian-blue-300 font-semibold border-b pb-2 mb-4">Tentang Pengaduan</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-1">Penyampai Pengaduan</h3>
                    <div class="col-span-4 md:col-span-3">
                        <div>
                            <input type="radio" name="creator_type" id="ekternal">
                            <label for="ekternal">Pengaduan Ekternal / Masyarakat</label>
                        </div>
                        <div>
                            <input type="radio" name="creator_type" id="internal">
                            <label for="internal">Pengaduan Internal / Sivitas</label>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">Kategori Pengaduan</h3>
                    <select class="border border-gray-300 p-2" name="category" id="category">
                        <option value="">Pilih Kategori</option>
                        <option value="pelanggaran">Pelanggaran</option>
                        <option value="layanan">Permasalahan Layanan</option>
                        <option value="fasilitas">Permasalahan Fasilitas</option>
                        <option value="sarana-prasarana">Permasalahan Sarana dan Prasarana</option>
                        <option value="lainnya">Permasalahan Lainnya</option>
                    </select>
                </div>

                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">Topik Pengaduan</h3>
                    <input type="text" name="topic" id="topic" class="border border-gray-300 p-2"
                        placeholder="Masukkan topik pengaduan">
                </div>
            </div>

            {{-- detail pengaduan --}}
            <div>
                <h3 class="text-lg text-prussian-blue-300 font-semibold border-b pb-2 mb-4">Detail Pengaduan</h3>
                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">Deskripsi Pengaduan</h3>
                    <div class="col-span-4 md:col-span-3">
                        <textarea name="description" id="description" cols="80" rows="5"
                            class="border border-gray-300 p-2" placeholder="Masukkan deskripsi pengaduan"></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">Tanggal Kejadian</h3>
                    <input type="date" name="incident_date" id="incident_date"
                        class="border border-gray-300 p-2">
                </div>

                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">Lokasi Kejadian</h3>
                    <input type="text" name="location" id="location" class="border border-gray-300 p-2"
                        placeholder="Masukkan lokasi kejadian">
                </div>

                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">Unit/Bagian Terkait</h3>
                    <select class="border border-gray-300 p-2" name="category" id="category">
                        <option value="">Pilih Kategori</option>
                        <option value="pelanggaran">Pelanggaran</option>
                        <option value="layanan">Permasalahan Layanan</option>
                        <option value="fasilitas">Permasalahan Fasilitas</option>
                        <option value="sarana-prasarana">Permasalahan Sarana dan Prasarana</option>
                        <option value="lainnya">Permasalahan Lainnya</option>
                    </select>
                </div>
            </div>

            {{-- dokumen pendukung --}}
            <div>
                <h3 class="text-lg text-prussian-blue-300 font-semibold border-b pb-2 mb-4">Dokumen Pendukung</h3>
                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">Lampiran</h3>
                    <div class="col-span-4 md:col-span-3">
                        <input type="file" name="attachment" id="attachment"
                            class="border border-gray-300 p-2">
                    </div>
                </div>
            </div>


            {{-- identitas pelapor --}}
            <div>
                <h3 class="text-lg text-prussian-blue-300 font-semibold border-b pb-2 mb-4">Identitas Pelapor</h3>
                <div class="grid grid-cols-4 gap-4 mb-4">
                    <div class="col-span-4 md:col-span-1">
                        <input type="checkbox" name="anonymous" id="anonymous" class="p-2">
                        <label for="anonymous" class="text-alabaster-grey-800 text-md font-medium">Laporkan secara anonim?</label>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">Nama</h3>
                    <div class="col-span-4 md:col-span-3">
                        <input type="text" name="name" id="name"
                            class="border border-gray-300 p-2"
                            placeholder="Masukkan nama pelapor">
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">No. HP</h3>
                    <div class="col-span-4 md:col-span-3">
                        <input type="text" name="phone" id="phone"
                            class="border border-gray-300 p-2"
                            placeholder="Masukkan nomor handphone pelapor">
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-4 mb-4">
                    <h3 class="text-alabaster-grey-800 text-md font-medium col-span-4 md:col-span-1">Email</h3>
                    <div class="col-span-4 md:col-span-3">
                        <input type="text" name="email" id="email"
                            class="border border-gray-300 p-2"
                            placeholder="Masukkan email pelapor">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-orange-500 hover:bg-orange-400 text-white font-semibold py-2 px-4 cursor-pointer">
                    Kirim Pengaduan
                </button>
            </div>
        </form>
    </div>
</x-layout.main>
