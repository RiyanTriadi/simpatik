<x-layout.admin title="Kategori">
    <div class="bg-white p-4">

        {{-- Flash Message --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 text-sm flex items-center gap-2">
                <i class="ri-checkbox-circle-line text-lg"></i>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm flex items-center gap-2">
                <i class="ri-error-warning-line text-lg"></i>
                {{ session('error') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3">
            <h1 class="text-lg font-semibold">Master Kategori</h1>
            <div class="flex flex-wrap items-center gap-2">
                <form action="{{ route('admin.master.kategori.index') }}" method="GET" class="flex items-center">
                    <select name="type" onchange="this.form.submit()"
                        class="h-8 border border-alabaster-grey-600 text-sm px-2 bg-white focus:outline-none focus:border-emerald-500">
                        <option value="">Semua Tipe</option>
                        <option value="pengaduan" {{ request('type') == 'pengaduan' ? 'selected' : '' }}>Pengaduan
                        </option>
                        <option value="aspirasi" {{ request('type') == 'aspirasi' ? 'selected' : '' }}>Aspirasi</option>
                        <option value="keduanya" {{ request('type') == 'keduanya' ? 'selected' : '' }}>Keduanya</option>
                    </select>
                    <input type="search" name="search" value="{{ request('search') }}"
                        class="h-8 border border-alabaster-grey-600 text-sm px-4 focus:outline-none focus:border-emerald-500"
                        placeholder="Cari Kategori" autocomplete="off">
                    <button type="submit" class="bg-emerald-500 h-8 px-3 cursor-pointer text-white">
                        <i class="ri-search-line"></i>
                    </button>
                </form>

                <a href="{{ route('admin.master.kategori.create') }}"
                    class="bg-prussian-blue-500 hover:bg-prussian-blue-600 text-white text-sm h-8 px-4 flex items-center gap-1.5 transition">
                    <i class="ri-add-line"></i> Tambah Kategori
                </a>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="mt-4 overflow-x-auto border border-alabaster-grey-300">
            <table class="w-full min-w-max text-sm">
                <thead class="bg-alabaster-grey-500">
                    <tr class="border-b border-alabaster-grey-300">
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500 w-12">#</th>
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">Nama</th>
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">Tipe</th>
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">Deskripsi</th>
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">Status</th>
                        <th class="px-4 py-3 text-center font-semibold text-prussian-blue-500">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr class="border-b border-alabaster-grey-100 bg-white hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-500">
                                {{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}
                            </td>
                            <td class="px-4 py-3 font-medium text-prussian-blue-500">
                                {{ $category->name }}
                            </td>
                            <td class="px-4 py-3">
                                @if ($category->type == 'pengaduan')
                                    <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800">Pengaduan</span>
                                @elseif ($category->type == 'aspirasi')
                                    <span class="px-2 py-1 text-xs font-semibold bg-purple-100 text-purple-800">Aspirasi</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800">Keduanya</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ Str::limit($category->description, 50) ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if ($category->is_active)
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-600">
                                        <i class="ri-checkbox-circle-fill"></i> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-500">
                                        <i class="ri-close-circle-fill"></i> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-center px-4 py-3">
                                <div x-data="{ open: false }" class="relative inline-block text-left">
                                    <button @click="open = !open" @click.outside="open = false"
                                        class="cursor-pointer text-gray-500 hover:text-prussian-blue-500 focus:outline-none p-1 hover:bg-gray-100 transition">
                                        <i class="ri-more-line text-lg"></i>
                                    </button>

                                    <div x-show="open" x-cloak x-transition
                                        class="absolute right-0 z-50 mt-2 w-36 origin-top-right bg-white shadow-lg ring-1 ring-black ring-opacity-5 border border-gray-100">
                                        <div class="py-1">
                                            <a href="{{ route('admin.master.kategori.edit', $category) }}"
                                                class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-prussian-blue-500 transition">
                                                <i
                                                    class="ri-pencil-line mr-2 text-gray-400 group-hover:text-prussian-blue-500"></i>
                                                Edit
                                            </a>

                                            <form action="{{ route('admin.master.kategori.destroy', $category) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="group flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                                    <i
                                                        class="ri-delete-bin-line mr-2 text-red-400 group-hover:text-red-600"></i>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                <i class="ri-inbox-line text-3xl block mb-2"></i>
                                Belum ada data kategori.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $categories->links('components.pagination') }}
    </div>
</x-layout.admin>