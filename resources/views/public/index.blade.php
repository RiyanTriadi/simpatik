<x-layout.main title="Beranda">
    <h1 class="text-lg font-semibold mb-4">
        Sistem Informasi Manajemen Pengaduan, Aspirasi, dan Tindak Lanjut Sivitas
    </h1>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div class="bg-white p-4 shadow-md text-center cols-span-1">
            <div>
                <img src="{{ asset('images/campus.webp') }}" alt="Campus" class="mx-auto mb-4 w-full">
            </div>
            <h2 class="text-lg font-medium mb-2">Pengaduan</h2>
            <p class="text-gray-600 mb-4 text-justify">
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ratione error, corporis sapiente labore molestias iure et laborum cumque iusto tempora impedit consequatur mollitia adipisci aliquam veniam neque optio odit, tenetur ab modi deserunt accusantium blanditiis?
            </p>
            <a href="{{ route('public.pengaduan.index') }}" class="inline-block text-white bg-orange-500 hover:bg-orange-400 font-semibold py-2 px-4">Buat Pengaduan</a>
        </div>

        <div class="bg-white p-4 shadow-md text-center cols-span-1">
            <div>
                <img src="{{ asset('images/college student.webp') }}" alt="College Student" class="mx-auto mb-4 w-full">
            </div>
            <h2 class="text-lg font-medium mb-2">Aspirasi</h2>
            <p class="text-gray-600 mb-4 text-justify">
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ratione error, corporis sapiente labore molestias iure et laborum cumque iusto tempora impedit consequatur mollitia adipisci aliquam veniam neque optio odit, tenetur ab modi deserunt accusantium blanditiis?
            </p>
            <a href="{{ route('public.aspirasi.index') }}" class="inline-block text-white bg-orange-500 hover:bg-orange-400 font-semibold py-2 px-4">Ajukan Aspirasi</a>
        </div>
    </div>
</x-layout.main>