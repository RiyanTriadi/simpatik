<x-layout.admin title="Kotak Masuk">
    <div x-data="{
            filterOpen: false,
            assignOpen: false,
            statusOpen: false,
            priorityOpen: false,
            selected: {
                id: null, ticket: '', subject: '', type: '',
                assigned_to: null, status: '', priority: ''
            },
            openAssign(data)   { this.selected = data; this.assignOpen = true; },
            openStatus(data)   { this.selected = data; this.statusOpen = true; },
            openPriority(data) { this.selected = data; this.priorityOpen = true; }
        }" class="bg-white p-4">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
            <div>
                <h1 class="text-lg font-semibold">Kotak Masuk</h1>
                <p class="text-xs text-gray-500 mt-0.5">Semua tiket pengaduan & aspirasi dalam satu tempat</p>
            </div>

            <form action="{{ role_route('inbox.index') }}" method="GET" class="flex items-center gap-2">
                @foreach (['type', 'status', 'category_id', 'priority', 'date_range', 'date_from', 'date_to', 'assigned_to'] as $key)
                    @if (request($key))
                        <input type="hidden" name="{{ $key }}" value="{{ request($key) }}">
                    @endif
                @endforeach

                <div class="flex">
                    <input type="search" name="search" value="{{ request('search') }}"
                        class="h-8 border border-alabaster-grey-600 text-sm px-4 focus:outline-none focus:border-emerald-500"
                        placeholder="Cari Tiket / Topik" autocomplete="off">
                    <button type="submit" class="bg-emerald-500 h-8 px-3 cursor-pointer text-white">
                        <i class="ri-search-line"></i>
                    </button>
                </div>

                <button type="button" @click="filterOpen = !filterOpen"
                    class="h-8 px-3 text-sm border flex items-center gap-1.5 transition
                        {{ request()->hasAny(['type', 'status', 'category_id', 'priority', 'date_range', 'assigned_to']) ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : 'border-alabaster-grey-600 text-gray-700 hover:bg-gray-50' }}">
                    <i class="ri-filter-3-line"></i> Filter
                </button>
            </form>
        </div>

        {{-- QUICK FILTER CHIPS --}}
        @php
            $currentStatus = request('status', 'semua');
            $currentType = request('type', 'semua');
            $chips = [
                ['key' => 'semua', 'label' => 'Semua', 'count' => $counts['all'], 'class' => 'prussian-blue'],
                ['key' => 'baru', 'label' => 'Baru', 'count' => $counts['baru'], 'class' => 'blue'],
                ['key' => 'diproses', 'label' => 'Diproses', 'count' => $counts['diproses'], 'class' => 'yellow'],
                ['key' => 'selesai', 'label' => 'Selesai', 'count' => $counts['selesai'], 'class' => 'emerald'],
                ['key' => 'ditolak', 'label' => 'Ditolak', 'count' => $counts['ditolak'], 'class' => 'red'],
                ['key' => 'dibaca', 'label' => 'Dibaca', 'count' => $counts['dibaca'], 'class' => 'purple'],
                ['key' => 'ditindaklanjuti', 'label' => 'Ditindaklanjuti', 'count' => $counts['ditindaklanjuti'], 'class' => 'teal'],
            ];
        @endphp

        <div class="mt-4 flex flex-wrap gap-2">
            @foreach ($chips as $chip)
                @php
                    $isActive = $currentStatus === $chip['key'];
                    $activeClass = "border-{$chip['class']}-500 bg-{$chip['class']}-50 text-{$chip['class']}-700";
                    $inactiveClass = "border-gray-200 text-gray-600 hover:bg-gray-50";
                    $url = role_route('inbox.index', array_merge(
                        request()->except(['status', 'page']),
                        $chip['key'] === 'semua' ? [] : ['status' => $chip['key']]
                    ));
                @endphp
                <a href="{{ $url }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium border transition {{ $isActive ? $activeClass : $inactiveClass }}">
                    {{ $chip['label'] }}
                    <span
                        class="px-1.5 py-0.5 rounded-full text-[10px] {{ $isActive ? "bg-{$chip['class']}-500 text-white" : 'bg-gray-100 text-gray-600' }}">
                        {{ $chip['count'] }}
                    </span>
                </a>
            @endforeach
        </div>

        {{-- FILTER PANEL --}}
        <div x-show="filterOpen" x-cloak x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            class="mt-3 border border-gray-200 bg-gray-50 p-4">
            <form action="{{ role_route('inbox.index') }}" method="GET" class="space-y-3">
                @if (request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                @if (request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif

                <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    {{-- Tipe --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Tipe</label>
                        <select name="type"
                            class="w-full h-9 border border-gray-300 text-sm px-2 bg-white focus:outline-none focus:border-emerald-500">
                            <option value="">Semua Tipe</option>
                            <option value="complaint" {{ request('type') == 'complaint' ? 'selected' : '' }}>Pengaduan
                            </option>
                            <option value="aspiration" {{ request('type') == 'aspiration' ? 'selected' : '' }}>Aspirasi
                            </option>
                        </select>
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Kategori</label>
                        <select name="category_id"
                            class="w-full h-9 border border-gray-300 text-sm px-2 bg-white focus:outline-none focus:border-emerald-500">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Prioritas --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Prioritas <span
                                class="text-gray-400">(Pengaduan)</span></label>
                        <select name="priority"
                            class="w-full h-9 border border-gray-300 text-sm px-2 bg-white focus:outline-none focus:border-emerald-500">
                            <option value="">Semua Prioritas</option>
                            <option value="rendah" {{ request('priority') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                            <option value="sedang" {{ request('priority') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="tinggi" {{ request('priority') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                            <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>

                    {{-- Petugas --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Petugas <span
                                class="text-gray-400">(Pengaduan)</span></label>
                        <select name="assigned_to"
                            class="w-full h-9 border border-gray-300 text-sm px-2 bg-white focus:outline-none focus:border-emerald-500">
                            <option value="">Semua Petugas</option>
                            <option value="unassigned" {{ request('assigned_to') == 'unassigned' ? 'selected' : '' }}>—
                                Belum Di-assign —</option>
                            <option value="assigned" {{ request('assigned_to') == 'assigned' ? 'selected' : '' }}>— Sudah
                                Di-assign —</option>
                            @foreach ($officers as $officer)
                                <option value="{{ $officer->id }}" {{ request('assigned_to') == $officer->id ? 'selected' : '' }}>
                                    {{ $officer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tanggal --}}
                    <div x-data="{ range: '{{ request('date_range', '') }}' }">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Rentang Tanggal</label>
                        <select name="date_range" x-model="range"
                            class="w-full h-9 border border-gray-300 text-sm px-2 bg-white focus:outline-none focus:border-emerald-500">
                            <option value="">Semua Waktu</option>
                            <option value="today">Hari Ini</option>
                            <option value="7days">7 Hari Terakhir</option>
                            <option value="30days">30 Hari Terakhir</option>
                            <option value="custom">Custom</option>
                        </select>

                        <div x-show="range === 'custom'" x-cloak x-transition class="grid grid-cols-2 gap-1 mt-1">
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                class="w-full h-8 border border-gray-300 text-xs px-1 focus:outline-none focus:border-emerald-500">
                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                class="w-full h-8 border border-gray-300 text-xs px-1 focus:outline-none focus:border-emerald-500">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-200">
                    <a href="{{ role_route('inbox.index') }}"
                        class="text-xs text-gray-600 hover:text-gray-800 px-3 py-1.5">
                        Reset Filter
                    </a>
                    <button type="submit"
                        class="bg-emerald-500 hover:bg-emerald-600 text-white text-xs px-4 py-1.5 flex items-center gap-1.5 transition">
                        <i class="ri-check-line"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- TABEL --}}
        <div class="mt-4 overflow-x-auto border border-alabaster-grey-300">
            <table class="w-full min-w-max text-sm">
                <thead class="bg-prussian-blue-100">
                    <tr class="border-b border-alabaster-grey-300">
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500 w-12">#</th>
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">No Tiket</th>
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">Tipe</th>
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">Topik</th>
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">Prioritas</th>
                        <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">Petugas</th>
                        <th class="px-4 py-3 text-center font-semibold text-prussian-blue-500">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                                                                                    <tr class="border-b border-alabaster-grey-100 bg-white hover:bg-gray-50">
                                                                                        <td class="px-4 py-3 text-gray-500">
                                                                                            {{ $loop->iteration + ($tickets->currentPage() - 1) * $tickets->perPage() }}
                                                                                        </td>

                                                                                        <td class="px-4 py-3 font-medium text-prussian-blue-500">
                                                                                            {{ $ticket->ticket_number }}
                                                                                        </td>

                                                                                        {{-- TIPE --}}
                                                                                        <td class="px-4 py-3">
                                                                                            @if ($ticket->type === 'complaint')
                                                                                                <span
                                                                                                    class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800">
                                                                                                    <i class="ri-file-list-3-line"></i> Pengaduan
                                                                                                </span>
                                                                                            @else
                                                                                                <span
                                                                                                    class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold bg-purple-100 text-purple-800">
                                                                                                    <i class="ri-message-2-line"></i> Aspirasi
                                                                                                </span>
                                                                                            @endif
                                                                                        </td>

                                                                                        <td class="px-4 py-3 max-w-xs">
                                                                                            <p class="line-clamp-1" title="{{ $ticket->subject }}">{{ $ticket->subject }}</p>
                                                                                            <p class="text-xs text-gray-500 mt-0.5">{{ $ticket->category_name ?? '-' }}</p>
                                                                                        </td>

                                                                                        {{-- STATUS --}}
                                                                                        <td class="px-4 py-3">
                                                                                            <span class="px-2 py-1 text-xs font-semibold whitespace-nowrap
                                                                                                                                                                    @if ($ticket->status == 'baru') bg-blue-100 text-blue-800
                                                                                                                                                                    @elseif($ticket->status == 'diproses') bg-yellow-100 text-yellow-800
                                                                                                                                                                    @elseif($ticket->status == 'selesai') bg-emerald-100 text-emerald-800
                                                                                                                                                                    @elseif($ticket->status == 'ditolak') bg-red-100 text-red-800
                                                                                                                                                                    @elseif($ticket->status == 'dibaca') bg-purple-100 text-purple-800
                                                                                                                                                                    @elseif($ticket->status == 'ditindaklanjuti') bg-teal-100 text-teal-800
                                                                                                                                                                    @endif">
                                                                                                {{ ucfirst($ticket->status) }}
                                                                                            </span>
                                                                                        </td>

                                                                                        {{-- PRIORITAS --}}
                                                                                        <td class="px-4 py-3">
                                                                                            @if ($ticket->priority)
                                                                                                <span
                                                                                                    class="px-2 py-1 text-xs font-semibold whitespace-nowrap
                                                                                                                                                                            @if ($ticket->priority == 'urgent') bg-red-100 text-red-800
                                                                                                                                                                            @elseif($ticket->priority == 'tinggi') bg-orange-100 text-orange-800
                                                                                                                                                                            @elseif($ticket->priority == 'sedang') bg-yellow-100 text-yellow-800
                                                                                                                                                                            @else bg-gray-100 text-gray-800 @endif">
                                                                                                    {{ ucfirst($ticket->priority) }}
                                                                                                </span>
                                                                                            @else
                                                                                                <span class="text-xs text-gray-400">—</span>
                                                                                            @endif
                                                                                        </td>

                                                                                        {{-- PETUGAS --}}
                                                                                        <td class="px-4 py-3">
                                                                                            @if ($ticket->officer_name)
                                                                                                <div class="flex items-center gap-2">
                                                                                                    @if ($ticket->officer_image)
                                                                                                        <img src="{{ Storage::url($ticket->officer_image) }}"
                                                                                                            class="w-7 h-7 rounded-full object-cover border border-gray-200">
                                                                                                    @else
                                                                                                        <div
                                                                                                            class="w-7 h-7 rounded-full bg-prussian-blue-500 flex items-center justify-center text-white text-xs font-semibold">
                                                                                                            {{ strtoupper(substr($ticket->officer_name, 0, 1)) }}
                                                                                                        </div>
                                                                                                    @endif
                                                                                                    <p class="text-xs font-medium text-gray-700">{{ $ticket->officer_name }}</p>
                                                                                                </div>
                                                                                            @elseif ($ticket->type === 'complaint')
                                                                                                <span class="inline-flex items-center gap-1 text-xs text-gray-400">
                                                                                                    <i class="ri-user-unfollow-line"></i> Belum di-assign
                                                                                                </span>
                                                                                            @else
                                                                                                <span class="text-xs text-gray-400">—</span>
                                                                                            @endif
                                                                                        </td>

                                                                                        {{-- AKSI --}}
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
                                                                                                        class="fixed z-[100] w-44 origin-top-right bg-white shadow-lg ring-1 ring-black ring-opacity-5 border border-gray-100">

                                                                                                        <div class="py-1">
                                                                                                            {{-- DETAIL --}}
                                                                                                            <a href="{{ $ticket->type === 'complaint'
                        ? role_route('pengaduan.show', $ticket->ticket_number)
                        : role_route('aspirasi.show', $ticket->ticket_number) }}"
                                                                                                                class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-prussian-blue-500 transition">
                                                                                                                <i
                                                                                                                    class="ri-eye-line mr-2 text-gray-400 group-hover:text-prussian-blue-500"></i>
                                                                                                                Lihat Detail
                                                                                                            </a>

                                                                                                            {{-- ASSIGN (semua tiket) --}}
                                                                                                            <button type="button" @click="
                                                                                                                                                                                        openAssign({
                                                                                                                                                                                            id: {{ $ticket->id }},
                                                                                                                                                                                            ticket: '{{ $ticket->ticket_number }}',
                                                                                                                                                                                            subject: {{ Js::from($ticket->subject) }},
                                                                                                                                                                                            type: '{{ $ticket->type }}',
                                                                                                                                                                                            assigned_to: {{ $ticket->assigned_to ?? 'null' }}
                                                                                                                                                                                        });
                                                                                                                                                                                        open = false;
                                                                                                                                                                                    "
                                                                                                                class="group flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-prussian-blue-500 transition">
                                                                                                                <i
                                                                                                                    class="ri-user-add-line mr-2 text-gray-400 group-hover:text-prussian-blue-500"></i>
                                                                                                                Assign Petugas
                                                                                                            </button>

                                                                                                            {{-- UBAH STATUS --}}
                                                                                                            <button type="button" @click="
                                                                                                                                                                                        openStatus({
                                                                                                                                                                                            id: {{ $ticket->id }},
                                                                                                                                                                                            ticket: '{{ $ticket->ticket_number }}',
                                                                                                                                                                                            subject: {{ Js::from($ticket->subject) }},
                                                                                                                                                                                            type: '{{ $ticket->type }}',
                                                                                                                                                                                            status: '{{ $ticket->status }}'
                                                                                                                                                                                        });
                                                                                                                                                                                        open = false;
                                                                                                                                                                                    "
                                                                                                                class="group flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-prussian-blue-500 transition">
                                                                                                                <i
                                                                                                                    class="ri-loop-right-line mr-2 text-gray-400 group-hover:text-prussian-blue-500"></i>
                                                                                                                Ubah Status
                                                                                                            </button>

                                                                                                            {{-- UBAH PRIORITAS (hanya complaint) --}}
                                                                                                            @if ($ticket->type === 'complaint')
                                                                                                                <button type="button"
                                                                                                                    @click="
                                                                                                                                                                                                openPriority({
                                                                                                                                                                                                    id: {{ $ticket->id }},
                                                                                                                                                                                                    ticket: '{{ $ticket->ticket_number }}',
                                                                                                                                                                                                    subject: {{ Js::from($ticket->subject) }},
                                                                                                                                                                                                    type: 'complaint',
                                                                                                                                                                                                    priority: '{{ $ticket->priority ?? '' }}'
                                                                                                                                                                                                });
                                                                                                                                                                                                open = false;
                                                                                                                                                                                            "
                                                                                                                    class="group flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-prussian-blue-500 transition">
                                                                                                                    <i
                                                                                                                        class="ri-flag-2-line mr-2 text-gray-400 group-hover:text-prussian-blue-500"></i>
                                                                                                                    Ubah Prioritas
                                                                                                                </button>
                                                                                                            @endif
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </template>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-gray-500">
                                <i class="ri-inbox-line text-4xl block mb-2"></i>
                                <p class="text-sm">Tidak ada tiket yang cocok dengan filter.</p>
                                @if (request()->hasAny(['search', 'status', 'type', 'category_id', 'priority', 'date_range', 'assigned_to']))
                                    <a href="{{ role_route('inbox.index') }}"
                                        class="text-xs text-emerald-600 hover:underline mt-1 inline-block">
                                        Reset semua filter
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $tickets->links('components.pagination') }}

        {{-- MODAL ASSIGN --}}
        <div x-show="assignOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50" @click="assignOpen = false"></div>
            <div class="relative bg-white w-full max-w-md shadow-xl">
                <div class="flex items-center justify-between p-5 border-b border-gray-200">
                    <div>
                        <h2 class="text-lg font-bold text-prussian-blue-500">Assign Petugas</h2>
                        <p class="text-xs text-gray-500" x-text="`Tiket: ${selected.ticket}`"></p>
                    </div>
                    <button @click="assignOpen = false" class="text-gray-400 hover:text-gray-600">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>

                <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">
                    <p class="text-sm text-gray-700 line-clamp-2" x-text="selected.subject"></p>
                </div>

                <form :action="selected.type === 'complaint'
                        ? `{{ url(role_prefix() . '/pengaduan') }}/${selected.ticket}/assign`
                        : `{{ url(role_prefix() . '/aspirasi') }}/${selected.ticket}/assign`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Petugas <span
                                class="text-red-500">*</span></label>
                        <select name="assigned_to" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">-- Pilih Petugas --</option>
                            @foreach ($officers as $officer)
                                <option value="{{ $officer->id }}" :selected="selected.assigned_to === {{ $officer->id }}">
                                    {{ $officer->name }}{{ $officer->unit ? ' — ' . $officer->unit->name : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end gap-2 px-5 py-4 border-t border-gray-200 bg-gray-50">
                        <button type="button" @click="assignOpen = false"
                            class="px-4 py-2 text-sm text-gray-600 border border-gray-300 hover:bg-gray-100 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 text-sm font-medium transition flex items-center gap-2">
                            <i class="ri-save-line"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL UBAH STATUS --}}
        <div x-show="statusOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50" @click="statusOpen = false"></div>
            <div class="relative bg-white w-full max-w-md shadow-xl">
                <div class="flex items-center justify-between p-5 border-b border-gray-200">
                    <div>
                        <h2 class="text-lg font-bold text-prussian-blue-500">Ubah Status</h2>
                        <p class="text-xs text-gray-500" x-text="`Tiket: ${selected.ticket}`"></p>
                    </div>
                    <button @click="statusOpen = false" class="text-gray-400 hover:text-gray-600">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>

                <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">
                    <p class="text-sm text-gray-700 line-clamp-2" x-text="selected.subject"></p>
                </div>

                <form :action="selected.type === 'complaint'
                        ? `{{ url(role_prefix() . '/pengaduan') }}/${selected.ticket}`
                        : `{{ url(role_prefix() . '/aspirasi') }}/${selected.ticket}`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-5 space-y-2">

                        {{-- Status untuk Complaint --}}
                        <template x-if="selected.type === 'complaint'">
                            <div class="space-y-2">
                                @foreach ([
                                        'baru' => ['label' => 'Baru', 'desc' => 'Tiket baru masuk', 'color' => 'blue'],
                                        'diproses' => ['label' => 'Diproses', 'desc' => 'Sedang ditangani', 'color' => 'yellow'],
                                        'selesai' => ['label' => 'Selesai', 'desc' => 'Sudah diselesaikan', 'color' => 'emerald'],
                                        'ditolak' => ['label' => 'Ditolak', 'desc' => 'Tidak valid', 'color' => 'red'],
                                    ] as $value => $opt)
                                                <label class="cursor-pointer block">
                                                    <input type="radio" name="status" value="{{ $value }}" x-model="selected.status"
                                                        class="peer sr-only">
                                                    <div class="flex items-center gap-3 border-2 border-gray-200 p-3 transition
                                                                            peer-checked:border-{{ $opt['color'] }}-500 peer-checked:bg-{{ $opt['color'] }}-50
                                                                            hover:border-{{ $opt['color'] }}-300">
                                                        <span class="w-3 h-3 rounded-full bg-{{ $opt['color'] }}-500"></span>
                                                        <div>
                                                            <p class="text-sm font-semibold text-gray-800">{{ $opt['label'] }}</p>
                                                            <p class="text-xs text-gray-500">{{ $opt['desc'] }}</p>
                                                        </div>
                                                    </div>
                                                </label>
                                @endforeach
                            </div>
                        </template>

                        {{-- Status untuk Aspiration --}}
                        <template x-if="selected.type === 'aspiration'">
                            <div class="space-y-2">
                                @foreach ([
                                        'baru' => ['label' => 'Baru', 'desc' => 'Aspirasi baru masuk', 'color' => 'blue'],
                                        'dibaca' => ['label' => 'Dibaca', 'desc' => 'Sudah dibaca admin', 'color' => 'purple'],
                                        'ditindaklanjuti' => ['label' => 'Ditindaklanjuti', 'desc' => 'Sudah ditindaklanjuti', 'color' => 'emerald'],
                                    ] as $value => $opt)
                                                <label class="cursor-pointer block">
                                                    <input type="radio" name="status" value="{{ $value }}" x-model="selected.status"
                                                        class="peer sr-only">
                                                    <div class="flex items-center gap-3 border-2 border-gray-200 p-3 transition
                                                                            peer-checked:border-{{ $opt['color'] }}-500 peer-checked:bg-{{ $opt['color'] }}-50
                                                                            hover:border-{{ $opt['color'] }}-300">
                                                        <span class="w-3 h-3 rounded-full bg-{{ $opt['color'] }}-500"></span>
                                                        <div>
                                                            <p class="text-sm font-semibold text-gray-800">{{ $opt['label'] }}</p>
                                                            <p class="text-xs text-gray-500">{{ $opt['desc'] }}</p>
                                                        </div>
                                                    </div>
                                                </label>
                                @endforeach
                            </div>
                        </template>
                    </div>
                    <div class="flex justify-end gap-2 px-5 py-4 border-t border-gray-200 bg-gray-50">
                        <button type="button" @click="statusOpen = false"
                            class="px-4 py-2 text-sm text-gray-600 border border-gray-300 hover:bg-gray-100 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 text-sm font-medium transition flex items-center gap-2">
                            <i class="ri-save-line"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL UBAH PRIORITAS --}}
        <div x-show="priorityOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50" @click="priorityOpen = false"></div>
            <div class="relative bg-white w-full max-w-md shadow-xl">
                <div class="flex items-center justify-between p-5 border-b border-gray-200">
                    <div>
                        <h2 class="text-lg font-bold text-prussian-blue-500">Ubah Prioritas</h2>
                        <p class="text-xs text-gray-500" x-text="`Tiket: ${selected.ticket}`"></p>
                    </div>
                    <button @click="priorityOpen = false" class="text-gray-400 hover:text-gray-600">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>

                <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">
                    <p class="text-sm text-gray-700 line-clamp-2" x-text="selected.subject"></p>
                </div>

                <form :action="`{{ url(role_prefix() . '/pengaduan') }}/${selected.ticket}`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-5 grid grid-cols-2 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="rendah" x-model="selected.priority"
                                class="peer sr-only">
                            <div
                                class="flex items-center gap-2 border-2 border-gray-200 px-3 py-2 transition peer-checked:border-gray-500 peer-checked:bg-gray-50 hover:border-gray-300">
                                <i class="ri-arrow-down-line text-gray-500 text-lg"></i>
                                <span class="text-sm font-medium text-gray-700">Rendah</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="sedang" x-model="selected.priority"
                                class="peer sr-only">
                            <div
                                class="flex items-center gap-2 border-2 border-gray-200 px-3 py-2 transition peer-checked:border-yellow-500 peer-checked:bg-yellow-50 hover:border-yellow-300">
                                <i class="ri-subtract-line text-yellow-500 text-lg"></i>
                                <span class="text-sm font-medium text-gray-700">Sedang</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="tinggi" x-model="selected.priority"
                                class="peer sr-only">
                            <div
                                class="flex items-center gap-2 border-2 border-gray-200 px-3 py-2 transition peer-checked:border-orange-500 peer-checked:bg-orange-50 hover:border-orange-300">
                                <i class="ri-arrow-up-line text-orange-500 text-lg"></i>
                                <span class="text-sm font-medium text-gray-700">Tinggi</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="urgent" x-model="selected.priority"
                                class="peer sr-only">
                            <div
                                class="flex items-center gap-2 border-2 border-gray-200 px-3 py-2 transition peer-checked:border-red-500 peer-checked:bg-red-50 hover:border-red-300">
                                <i class="ri-alarm-warning-line text-red-500 text-lg"></i>
                                <span class="text-sm font-medium text-gray-700">Urgent</span>
                            </div>
                        </label>
                    </div>
                    <div class="flex justify-end gap-2 px-5 py-4 border-t border-gray-200 bg-gray-50">
                        <button type="button" @click="priorityOpen = false"
                            class="px-4 py-2 text-sm text-gray-600 border border-gray-300 hover:bg-gray-100 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 text-sm font-medium transition flex items-center gap-2">
                            <i class="ri-save-line"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-layout.admin>