<x-layout.admin title="Manajemen User">
    <div x-data="{ deleteOpen: false, deleteAction: '', deleteName: '' }" class="bg-white p-4">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3">
            <h1 class="text-lg font-semibold">Manajemen User</h1>
            <div class="flex flex-wrap items-center gap-2">
                <form action="{{ role_route('users.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                    <select name="role" onchange="this.form.submit()"
                        class="h-8 border border-alabaster-grey-600 text-sm px-2 bg-white focus:outline-none focus:border-emerald-500">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="petugas" {{ request('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                    </select>

                    <select name="unit_id" onchange="this.form.submit()"
                        class="h-8 border border-alabaster-grey-600 text-sm px-2 bg-white focus:outline-none focus:border-emerald-500">
                        <option value="">Semua Unit</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>

                    <div class="flex">
                        <input type="search" name="search" value="{{ request('search') }}"
                        class="h-8 border border-alabaster-grey-600 text-sm px-4 focus:outline-none focus:border-emerald-500"
                        placeholder="Cari Nama / Email / Telepon" autocomplete="off">
                        <button type="submit" class="bg-emerald-500 h-8 px-3 cursor-pointer text-white">
                            <i class="ri-search-line"></i>
                        </button>
                    </div>
                </form>

                <a href="{{ role_route('users.create') }}"
                    class="bg-prussian-blue-300 hover:bg-prussian-blue-400 text-white text-sm h-8 px-4 flex items-center gap-1.5 transition">
                    <i class="ri-add-line"></i> Tambah User
                </a>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="mt-4 overflow-x-auto border border-alabaster-grey-300">
            <table class="w-full min-w-max text-sm">
                <thead class="bg-alabaster-grey-500">
                    <tr class="border-b border-alabaster-grey-300">
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500 w-12">#</th>
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">User</th>
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">Role</th>
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">Unit Kerja</th>
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">Kontak</th>
                        <th class="px-4 py-3 text-center font-semibold text-prussian-blue-500">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="border-b border-alabaster-grey-100 bg-white hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-500">
                                {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($user->profile_image_path)
                                        <img src="{{ Storage::url($user->profile_image_path) }}"
                                            alt="{{ $user->name }}"
                                            class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-prussian-blue-500 flex items-center justify-center text-white font-semibold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-prussian-blue-500">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                @if ($user->role == 'admin')
                                    <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800">Admin</span>
                                @elseif ($user->role == 'staff')
                                    <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800">Staff</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold bg-emerald-100 text-emerald-800">Petugas</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-gray-600">
                                {{ $user->unit->name ?? '-' }}
                            </td>

                            <td class="px-4 py-3 text-gray-600">
                                {{ $user->phone ?? '-' }}
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
                                    @scroll.window="open = false"
                                    @resize.window="open = false">

                                    <button @click="toggle($event)"
                                        class="cursor-pointer text-gray-500 hover:text-prussian-blue-500 focus:outline-none p-1 hover:bg-gray-100 transition">
                                        <i class="ri-more-line text-lg"></i>
                                    </button>

                                    <template x-teleport="body">
                                        <div x-show="open" x-cloak
                                            @click.outside="open = false"
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-75"
                                            x-transition:leave-start="opacity-100 scale-100"
                                            x-transition:leave-end="opacity-0 scale-95"
                                            :style="`top: ${top}px; left: ${left}px;`"
                                            class="fixed z-[100] w-36 origin-top-right bg-white shadow-lg ring-1 ring-black ring-opacity-5 border border-gray-100">

                                            <div class="py-1">
                                                {{-- Link Edit --}}
                                                <a href="{{ role_route('users.edit', $user) }}"
                                                    class="group flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-prussian-blue-500 transition">
                                                    <i class="ri-pencil-line mr-2 text-gray-400 group-hover:text-prussian-blue-500"></i>
                                                    Edit
                                                </a>

                                                {{-- Form Hapus --}}
                                                <button type="button"
                                                    @click="
                                                        deleteAction = {{ Js::from(role_route('users.destroy', $user)) }};
                                                        deleteName = {{ Js::from($user->name) }};
                                                        deleteOpen = true;
                                                        open = false;
                                                    "
                                                        {{ auth()->id() === $user->id ? 'disabled' : '' }}
                                                        class="group flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                                        <i class="ri-delete-bin-line mr-2 text-red-400 group-hover:text-red-600"></i>
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
                                Belum ada data user.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $users->links('components.pagination') }}

        <template x-teleport="body">
            <div x-show="deleteOpen" x-cloak @keydown.escape.window="deleteOpen = false"
                @click.self="deleteOpen = false"
                class="fixed inset-0 z-[120] flex items-center justify-center bg-black/50 p-4">
                <div role="dialog" aria-modal="true" aria-labelledby="delete-user-title"
                    class="w-full max-w-sm bg-white p-6 shadow-xl">
                    <h2 id="delete-user-title" class="text-lg font-semibold text-gray-800">
                        Hapus User?
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        User <strong x-text="deleteName"></strong> akan dihapus. Tindakan ini tidak dapat dibatalkan.
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

    </div>
</x-layout.admin>