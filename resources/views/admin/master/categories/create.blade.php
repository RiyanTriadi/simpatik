<x-layout.admin title="Tambah Kategori">
    <div class="bg-white p-4 md:p-6 max-w-2xl">
        <div class="flex items-center gap-3 mb-6 border-b border-gray-200 pb-4">
            <a href="{{ route('admin.master.kategori.index') }}"
                class="p-2 text-gray-500 hover:text-prussian-blue-500 hover:bg-gray-100 transition">
                <i class="ri-arrow-left-line text-xl"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-prussian-blue-500">Tambah Kategori</h1>
                <p class="text-sm text-gray-500">Buat kategori baru untuk pengaduan atau aspirasi</p>
            </div>
        </div>

        <form action="{{ route('admin.master.kategori.store') }}" method="POST">
            @include('admin.master.categories._form')
        </form>
    </div>
</x-layout.admin>