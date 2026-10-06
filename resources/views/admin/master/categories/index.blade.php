<x-layout.admin title="Kategori">
    <div x-data="{
            createOpen: false,
            editOpen: false,
            deleteOpen: false,
            deleteAction: '',
            deleteName: '',
            editData: { id: null, name: '', type: '', description: '', is_active: true }
        }" class="bg-white p-4">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3">
            <h1 class="text-lg font-semibold">Master Kategori</h1>
            <div class="flex flex-wrap items-center gap-2">
                <form action="{{ role_route('master.kategori.index') }}" method="GET"
                    class="flex items-center gap-2   ">
                    <select name="type" onchange="this.form.submit()"
                        class="h-8 border border-alabaster-grey-600 text-sm px-2 bg-white focus:outline-none focus:border-emerald-500">
                        <option value="">Semua Tipe</option>
                        <option value="pengaduan" {{ request('type') == 'pengaduan' ? 'selected' : '' }}>Pengaduan
                        </option>
                        <option value="aspirasi" {{ request('type') == 'aspirasi' ? 'selected' : '' }}>Aspirasi</option>
                        <option value="keduanya" {{ request('type') == 'keduanya' ? 'selected' : '' }}>Keduanya</option>
                    </select>
                    <div class="flex">
                        <input type="search" name="search" value="{{ request('search') }}"
                            class="h-8 border border-alabaster-grey-600 text-sm px-4 focus:outline-none focus:border-emerald-500"
                            placeholder="Cari Kategori" autocomplete="off">
                        <button type="submit" class="bg-emerald-500 h-8 px-3 cursor-pointer text-white">
                            <i class="ri-search-line"></i>
                        </button>
                    </div>

                </form>

                <button @click="createOpen = true"
                    class="bg-prussian-blue-300 hover:bg-prussian-blue-400 text-white text-sm h-8 px-4 flex items-center gap-1.5 transition cursor-pointer">
                    <i class="ri-add-line"></i> Tambah Kategori
                </button>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="mt-4 overflow-x-auto border border-alabaster-grey-300">
            <table class="w-full min-w-max text-sm">
                <thead class="bg-prussian-blue-100">
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

                                                                                                        <td class="px-4 py-3 font-medium text-prussian-blue-500">{{ $category->name }}</td>

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
                                                                                                            <div x-data="{
                                                                                                                                                                                                                            open: false,
                                                                                                                                                                                                                            top: 0,
                                                                                                                                                                                                                            left: 0,
                                                                                                                                                                                                                            toggle(event) {
                                                                                                                                                                                                                                if (this.open) { this.open = false; return; }
                                                                                                                                                                                                                                const rect = event.currentTarget.getBoundingClientRect();
                                                                                                                                                                                                                                this.top  = rect.bottom + 4;
                                                                                                                                                                                                                                this.left = rect.right - 144;
                                                                                                                                                                                                                                this.open = true;
                                                                                                                                                                                                                            }
                                                                                                                                                                                                                        }"
                                                                                                                @scroll.window="open = false" @resize.window="open = false">

                                                                                                                <button @click="toggle($event)"
                                                                                                                    class="cursor-pointer text-gray-500 hover:text-prussian-blue-500 focus:outline-none p-1 hover:bg-gray-100 transition">
                                                                                                                    <i class="ri-more-line text-lg"></i>
                                                                                                                </button>

                                                                                                                <template x-teleport="body">
                                                                                                                    <div x-show="open" x-cloak @click.outside="open = false"
                                                                                                                        x-transition:enter="transition ease-out duration-100"
                                                                                                                        x-transition:enter-start="opacity-0 scale-95"
                                                                                                                        x-transition:enter-end="opacity-100 scale-100"
                                                                                                                        x-transition:leave="transition ease-in duration-75"
                                                                                                                        x-transition:leave-start="opacity-100 scale-100"
                                                                                                                        x-transition:leave-end="opacity-0 scale-95"
                                                                                                                        :style="`top: ${top}px; left: ${left}px;`"
                                                                                                                        class="fixed z-[100] w-36 origin-top-right bg-white shadow-lg ring-1 ring-black ring-opacity-5 border border-gray-100">

                                                                                                                        <div class="py-1">
                                                                                                                            <button type="button"
                                                                                                                                @click="
                                                                                                                                                                                                                                            editData = {{ Js::from([
                            'id' => $category->id,
                            'name' => $category->name,
                            'type' => $category->type,
                            'description' => $category->description,
                            'is_active' => (bool) $category->is_active,
                        ]) }};
                                                                                                                                                                                                                                            editOpen = true;
                                                                                                                                                                                                                                            open = false;
                                                                                                                                                                                                                                        "
                                                                                                                                class="group flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-prussian-blue-500 transition">
                                                                                                                                <i
                                                                                                                                    class="ri-pencil-line mr-2 text-gray-400 group-hover:text-prussian-blue-500"></i>
                                                                                                                                Edit
                                                                                                                            </button>

                                                                                                                            <button type="button"
                                                                                                                                @click="
                                                                                                                                                                                                                                            deleteAction = {{ Js::from(role_route('master.kategori.destroy', $category)) }};
                                                                                                                                                                                                                                            deleteName = {{ Js::from($category->name) }};
                                                                                                                                                                                                                                            deleteOpen = true;
                                                                                                                                                                                                                                            open = false;
                                                                                                                                                                                                                                        "
                                                                                                                                class="group flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                                                                                                                <i
                                                                                                                                    class="ri-delete-bin-line mr-2 text-red-400 group-hover:text-red-600"></i>
                                                                                                                                Hapus
                                                                                                                            </button>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </template>
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

        <template x-teleport="body">
            <div x-show="deleteOpen" x-cloak @keydown.escape.window="deleteOpen = false"
                @click.self="deleteOpen = false"
                class="fixed inset-0 z-[120] flex items-center justify-center bg-black/50 p-4">
                <div role="dialog" aria-modal="true" aria-labelledby="delete-category-title"
                    class="w-full max-w-sm bg-white p-6 shadow-xl">
                    <h2 id="delete-category-title" class="text-lg font-semibold text-gray-800">
                        Hapus Kategori?
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Kategori <strong x-text="deleteName"></strong> akan dihapus. Tindakan ini tidak dapat
                        dibatalkan.
                    </p>
                    <form :action="deleteAction" method="POST" class="mt-6 flex justify-end gap-2">
                        @csrf
                        @method('DELETE')
                        <button type="button" @click="deleteOpen = false"
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </template>

        {{-- Modal Create --}}
        <div x-show="createOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50" @click="createOpen = false"></div>
            <div class="relative bg-white w-full max-w-lg shadow-xl">
                {{-- Header --}}
                <div class="flex items-center justify-between p-5 border-b border-gray-200">
                    <div>
                        <h2 class="text-lg font-bold text-prussian-blue-500">Tambah Kategori</h2>
                        <p class="text-xs text-gray-500">Buat kategori baru untuk pengaduan atau aspirasi</p>
                    </div>
                    <button @click="createOpen = false" class="text-gray-400 hover:text-gray-600 transition">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>

                {{-- Form --}}
                <form action="{{ role_route('master.kategori.store') }}" method="POST">
                    @csrf
                    <div class="p-5 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500"
                                placeholder="Contoh: Sarana dan Prasarana Kampus">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe <span
                                    class="text-red-500">*</span></label>
                            <select name="type" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">-- Pilih Tipe --</option>
                                <option value="pengaduan" {{ old('type') == 'pengaduan' ? 'selected' : '' }}>Pengaduan
                                </option>
                                <option value="aspirasi" {{ old('type') == 'aspirasi' ? 'selected' : '' }}>Aspirasi
                                </option>
                                <option value="keduanya" {{ old('type') == 'keduanya' ? 'selected' : '' }}>Keduanya
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="description" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500"
                                placeholder="Keterangan singkat tentang kategori ini">{{ old('description') }}</textarea>
                        </div>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" checked
                                class="rounded border-gray-300 text-emerald-500 focus:ring-emerald-500">
                            <span class="text-sm text-gray-700">Aktifkan kategori ini</span>
                        </label>
                    </div>

                    {{-- Footer --}}
                    <div class="flex items-center justify-end gap-2 px-5 py-4 border-t border-gray-200 bg-gray-50">
                        <button type="button" @click="createOpen = false"
                            class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 hover:bg-gray-100 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 text-sm font-medium transition flex items-center gap-2">
                            <i class="ri-save-line"></i> Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Edit --}}
        <div x-show="editOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50" @click="editOpen = false"></div>
            <div class="relative bg-white w-full max-w-lg shadow-xl">
                {{-- Header --}}
                <div class="flex items-center justify-between p-5 border-b border-gray-200">
                    <div>
                        <h2 class="text-lg font-bold text-prussian-blue-500">Edit Kategori</h2>
                        <p class="text-xs text-gray-500">Perbarui data kategori</p>
                    </div>
                    <button @click="editOpen = false" class="text-gray-400 hover:text-gray-600 transition">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>

                {{-- Form Edit --}}
                <form :action="`{{ url('admin/master/kategori') }}/${editData.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-5 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="editData.name" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe <span
                                    class="text-red-500">*</span></label>
                            <select name="type" x-model="editData.type" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="pengaduan">Pengaduan</option>
                                <option value="aspirasi">Aspirasi</option>
                                <option value="keduanya">Keduanya</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="description" rows="3" x-model="editData.description"
                                class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                        </div>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" x-model="editData.is_active"
                                class="rounded border-gray-300 text-emerald-500 focus:ring-emerald-500">
                            <span class="text-sm text-gray-700">Aktifkan kategori ini</span>
                        </label>
                    </div>

                    {{-- Footer --}}
                    <div class="flex items-center justify-end gap-2 px-5 py-4 border-t border-gray-200 bg-gray-50">
                        <button type="button" @click="editOpen = false"
                            class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 hover:bg-gray-100 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 text-sm font-medium transition flex items-center gap-2">
                            <i class="ri-save-line"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-layout.admin>