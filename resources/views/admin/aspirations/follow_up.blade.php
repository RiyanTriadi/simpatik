<x-layout.admin title="Tindak Lanjut Aspirasi">
    <div class="bg-white p-4">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
            <h1 class="text-lg font-semibold">Tindak Lanjut Aspirasi</h1>
            <form action="{{ route('admin.aspirasi.follow-up') }}" method="GET" class="flex items-center">
                <input type="search" name="search" value="{{ request('search') }}"
                    class="h-8 border border-alabaster-grey-600 text-sm px-4" placeholder="Cari Aspirasi"
                    autocomplete="off">
                <button type="submit" class="bg-emerald-500 h-8 px-4 cursor-pointer"><i
                        class="ri-search-line"></i></button>
            </form>
        </div>

        <div class="mt-4 overflow-x-auto border border-alabaster-grey-300">
            <table class="w-full min-w-max text-sm">
                <thead class="bg-alabaster-grey-500">
                    <tr class="border-b border-alabaster-grey-300">
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">No Tiket</th>
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">Topik</th>
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">Kategori</th>
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">Status</th>
                        <th class="px-4 py-3 text-center font-semibold text-prussian-blue-500">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($aspirations as $aspiration)
                        <tr class="border-b border-alabaster-grey-100 bg-white">
                            <td class="px-4 py-3 font-medium text-prussian-blue-500">
                                {{ $aspiration->ticket_number }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $aspiration->subject }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $aspiration->category->name }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $aspiration->status }}
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
                                            this.left = rect.right - 176;
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
                                            class="fixed z-[100] w-44 origin-top-right bg-white shadow-lg ring-1 ring-black ring-opacity-5 border border-gray-100">

                                            <div class="py-1">
                                                <form action="{{ route('admin.aspirasi.update', $aspiration->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="{{ \App\Models\Aspiration::STATUS_DITINDAKLANJUTI }}">
                                                    <button type="submit"
                                                        class="group flex w-full items-center px-4 py-2 text-sm text-emerald-600 hover:bg-emerald-50 transition">
                                                        <i class="ri-check-line mr-2 text-emerald-500"></i>
                                                        Tindak Lanjut
                                                    </button>
                                                </form>

                                                <a href="{{ route('admin.aspirasi.show', $aspiration->id) }}"
                                                    class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-prussian-blue-500 transition border-t border-gray-100">
                                                    <i class="ri-eye-line mr-2 text-gray-400 group-hover:text-prussian-blue-500"></i>
                                                    Detail
                                                </a>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">Tidak ada aspirasi yang perlu
                                ditindaklanjuti.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $aspirations->links('components.pagination') }}
    </div>
</x-layout.admin>