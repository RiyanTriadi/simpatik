<x-layout.admin title="Dashboard">
    <div class="space-y-4">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">

            <div class="bg-white border border-gray-200 p-4 hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pengaduan</p>
                        <p class="text-2xl md:text-3xl font-bold text-prussian-blue-500 mt-1">{{ $stats['complaints_total'] }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 flex items-center justify-center">
                        <i class="ri-file-list-3-line text-blue-500 text-xl"></i>
                    </div>
                </div>
                @if ($stats['today_complaints'] > 0)
                    <p class="text-xs text-emerald-600 mt-2 flex items-center gap-1">
                        <i class="ri-arrow-up-line"></i>
                        +{{ $stats['today_complaints'] }} hari ini
                    </p>
                @else
                    <p class="text-xs text-gray-400 mt-2">Belum ada tiket hari ini</p>
                @endif
            </div>

            <div class="bg-white border border-gray-200 p-4 hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Aspirasi</p>
                        <p class="text-2xl md:text-3xl font-bold text-purple-600 mt-1">{{ $stats['aspirations_total'] }}</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 flex items-center justify-center">
                        <i class="ri-message-2-line text-purple-500 text-xl"></i>
                    </div>
                </div>
                @if ($stats['today_aspirations'] > 0)
                    <p class="text-xs text-emerald-600 mt-2 flex items-center gap-1">
                        <i class="ri-arrow-up-line"></i>
                        +{{ $stats['today_aspirations'] }} hari ini
                    </p>
                @else
                    <p class="text-xs text-gray-400 mt-2">Belum ada aspirasi hari ini</p>
                @endif
            </div>

            <div class="bg-white border border-gray-200 p-4 hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Sedang Diproses</p>
                        <p class="text-2xl md:text-3xl font-bold text-yellow-600 mt-1">{{ $stats['complaints_processing'] }}</p>
                    </div>
                    <div class="w-10 h-10 bg-yellow-100 flex items-center justify-center">
                        <i class="ri-loader-4-line text-yellow-500 text-xl"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-2">Pengaduan aktif</p>
            </div>

            <div class="bg-white border border-gray-200 p-4 hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Belum Di-assign</p>
                        <p class="text-2xl md:text-3xl font-bold text-orange-600 mt-1">{{ $stats['unassigned'] }}</p>
                    </div>
                    <div class="w-10 h-10 bg-orange-100 flex items-center justify-center">
                        <i class="ri-user-unfollow-line text-orange-500 text-xl"></i>
                    </div>
                </div>
                @if ($stats['unassigned'] > 0)
                    <a href="{{ role_route('pengaduan.assign') }}"
                        class="text-xs text-orange-600 hover:text-orange-700 font-medium mt-2 flex items-center gap-1">
                        <i class="ri-arrow-right-line"></i>
                        Assign sekarang
                    </a>
                @else
                    <p class="text-xs text-emerald-600 mt-2 flex items-center gap-1">
                        <i class="ri-checkbox-circle-line"></i> Semua sudah di-assign
                    </p>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="bg-white p-3 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500">Pengaduan Baru</p>
                    <p class="text-lg font-bold text-gray-800">{{ $stats['complaints_new'] }}</p>
                </div>
                <i class="ri-alarm-warning-line text-blue-400 text-2xl"></i>
            </div>
            <div class="bg-white p-3 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500">Pengaduan Selesai</p>
                    <p class="text-lg font-bold text-gray-800">{{ $stats['complaints_completed'] }}</p>
                </div>
                <i class="ri-checkbox-circle-line text-emerald-400 text-2xl"></i>
            </div>
            <div class="bg-white p-3 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500">Aspirasi Baru</p>
                    <p class="text-lg font-bold text-gray-800">{{ $stats['aspirations_new'] }}</p>
                </div>
                <i class="ri-notification-3-line text-purple-400 text-2xl"></i>
            </div>
            <div class="bg-white p-3 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500">Ditindaklanjuti</p>
                    <p class="text-lg font-bold text-gray-800">{{ $stats['aspirations_follow_up'] }}</p>
                </div>
                <i class="ri-flag-2-line text-teal-400 text-2xl"></i>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            <div class="lg:col-span-2 bg-white border border-gray-200 p-4 md:p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">Tren Tiket {{ now()->year }}</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Jumlah pengaduan & aspirasi per bulan</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 bg-blue-500"></span>
                            <span class="text-gray-600">Pengaduan</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 bg-purple-500"></span>
                            <span class="text-gray-600">Aspirasi</span>
                        </div>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <div class="bg-white border border-gray-200 p-4 md:p-5">
                <h3 class="text-sm font-semibold text-gray-800 mb-1">Top 5 Kategori</h3>
                <p class="text-xs text-gray-500 mb-4">Kategori dengan tiket terbanyak</p>

                @forelse ($topCategories as $cat)
                    @php
                        $max = $topCategories->first()->total ?? 1;
                        $percentage = $max > 0 ? round(($cat->total / $max) * 100) : 0;
                    @endphp
                    <div class="mb-3">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs text-gray-700 truncate pr-2">{{ $cat->name }}</span>
                            <span class="text-xs font-semibold text-gray-800 whitespace-nowrap">{{ $cat->total }}</span>
                        </div>
                        <div class="h-1.5 bg-gray-100 overflow-hidden">
                            <div class="h-full bg-emerald-500 transition-all" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-400">
                        <i class="ri-inbox-line text-2xl block mb-1"></i>
                        <p class="text-xs">Belum ada data kategori</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            <div class="bg-white border border-gray-200 p-4 md:p-5">
                <h3 class="text-sm font-semibold text-gray-800 mb-1">Status Pengaduan</h3>
                <p class="text-xs text-gray-500 mb-4">Distribusi status tiket pengaduan</p>

                <div class="h-48 flex items-center justify-center">
                    <canvas id="statusChart"></canvas>
                </div>

                <div class="mt-4 space-y-1.5">
                    @foreach ([
                        'baru'     => ['label' => 'Baru',     'color' => 'bg-blue-500'],
                        'diproses' => ['label' => 'Diproses', 'color' => 'bg-yellow-500'],
                        'selesai'  => ['label' => 'Selesai',  'color' => 'bg-emerald-500'],
                        'ditolak'  => ['label' => 'Ditolak',  'color' => 'bg-red-500'],
                    ] as $key => $item)
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full {{ $item['color'] }}"></span>
                                <span class="text-gray-600">{{ $item['label'] }}</span>
                            </div>
                            <span class="font-semibold text-gray-800">{{ $complaintStatusData[$key] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white border border-gray-200 p-4 md:p-5">
                <h3 class="text-sm font-semibold text-gray-800 mb-1">Status Aspirasi</h3>
                <p class="text-xs text-gray-500 mb-4">Distribusi status aspirasi</p>

                <div class="h-48 flex items-center justify-center">
                    <canvas id="aspirationChart"></canvas>
                </div>

                <div class="mt-4 space-y-1.5">
                    @foreach ([
                        'baru'            => ['label' => 'Baru',            'color' => 'bg-blue-500'],
                        'dibaca'          => ['label' => 'Dibaca',          'color' => 'bg-purple-500'],
                        'ditindaklanjuti' => ['label' => 'Ditindaklanjuti', 'color' => 'bg-emerald-500'],
                    ] as $key => $item)
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full {{ $item['color'] }}"></span>
                                <span class="text-gray-600">{{ $item['label'] }}</span>
                            </div>
                            <span class="font-semibold text-gray-800">{{ $aspirationStatusData[$key] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white border border-gray-200 p-4 md:p-5">
                <h3 class="text-sm font-semibold text-gray-800 mb-1">Aksi Cepat</h3>
                <p class="text-xs text-gray-500 mb-4">Menu yang sering diakses</p>

                <div class="space-y-2">
                    @if(in_array(auth()->user()->role, ['admin', 'staff']))
                    <a href="{{ role_route('pengaduan.verification') }}"
                        class="flex items-center gap-3 p-3 border border-gray-200 hover:border-emerald-500 hover:bg-emerald-50 transition group">
                        <div class="w-9 h-9 bg-emerald-100 flex items-center justify-center group-hover:bg-emerald-500 transition">
                            <i class="ri-shield-check-line text-emerald-600 group-hover:text-white transition"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800">Verifikasi Pengaduan</p>
                            <p class="text-xs text-gray-500">{{ $stats['complaints_new'] }} menunggu</p>
                        </div>
                        <i class="ri-arrow-right-s-line text-gray-400 group-hover:text-emerald-500 transition"></i>
                    </a>
                    @endif

                    @if(in_array(auth()->user()->role, ['admin', 'staff']))
                    <a href="{{ role_route('pengaduan.assign') }}"
                        class="flex items-center gap-3 p-3 border border-gray-200 hover:border-orange-500 hover:bg-orange-50 transition group">
                        <div class="w-9 h-9 bg-orange-100 flex items-center justify-center group-hover:bg-orange-500 transition">
                            <i class="ri-user-add-line text-orange-600 group-hover:text-white transition"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800">Assign ke Petugas</p>
                            <p class="text-xs text-gray-500">{{ $stats['unassigned'] }} belum di-assign</p>
                        </div>
                        <i class="ri-arrow-right-s-line text-gray-400 group-hover:text-orange-500 transition"></i>
                    </a>
                    @endif

                    @if(in_array(auth()->user()->role, ['admin', 'staff']))
                    <a href="{{ role_route('aspirasi.follow-up') }}"
                        class="flex items-center gap-3 p-3 border border-gray-200 hover:border-purple-500 hover:bg-purple-50 transition group">
                        <div class="w-9 h-9 bg-purple-100 flex items-center justify-center group-hover:bg-purple-500 transition">
                            <i class="ri-flag-2-line text-purple-600 group-hover:text-white transition"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800">Tindak Lanjut Aspirasi</p>
                            <p class="text-xs text-gray-500">Tindak lanjut aspirasi baru</p>
                        </div>
                        <i class="ri-arrow-right-s-line text-gray-400 group-hover:text-purple-500 transition"></i>
                    </a>
                    @endif
                    
                    @if(auth()->user()->role === 'petugas')
                    <p class="text-sm text-gray-500">Aksi cepat hanya tersedia untuk Staff & Admin.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200">
            <div class="flex items-center justify-between p-4 border-b border-gray-100">
                <div>
                    <h3 class="text-sm font-semibold text-gray-800">Tiket Terbaru</h3>
                    <p class="text-xs text-gray-500 mt-0.5">8 tiket terakhir dari pengaduan & aspirasi</p>
                </div>
                <a href="{{ role_route('inbox.index') }}"
                    class="text-xs text-emerald-600 hover:text-emerald-700 font-medium flex items-center gap-1">
                    Lihat Semua
                    <i class="ri-arrow-right-line"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-max text-sm">
                    <thead class="bg-gray-50">
                        <tr class="border-b border-gray-200">
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">No Tiket</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Tipe</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Topik</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Waktu</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentTickets as $ticket)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-prussian-blue-500">
                                    {{ $ticket->ticket_number }}
                                </td>

                                <td class="px-4 py-3">
                                    @if ($ticket->type === 'complaint')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-700">
                                            <i class="ri-file-list-3-line"></i> Pengaduan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium bg-purple-100 text-purple-700">
                                            <i class="ri-message-2-line"></i> Aspirasi
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 max-w-xs">
                                    <p class="line-clamp-1 text-gray-800" title="{{ $ticket->subject }}">
                                        {{ $ticket->subject }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $ticket->category->name ?? '-' }}</p>
                                </td>

                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 text-xs font-semibold whitespace-nowrap
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

                                <td class="px-4 py-3 text-xs text-gray-600">
                                    <p>{{ $ticket->created_at->diffForHumans() }}</p>
                                    <p class="text-gray-400">{{ $ticket->created_at->translatedFormat('d M Y, H:i') }}</p>
                                </td>

                                <td class="text-center px-4 py-3">
                                    <a href="{{ $ticket->type === 'complaint'
                                        ? role_route('pengaduan.show', $ticket->ticket_number)
                                        : role_route('aspirasi.show', $ticket->ticket_number) }}"
                                        class="inline-flex items-center gap-1 text-xs text-gray-600 hover:text-prussian-blue-500 border border-gray-300 hover:border-prussian-blue-500 px-2.5 py-1 transition">
                                        <i class="ri-eye-line"></i>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                                    <i class="ri-inbox-line text-3xl block mb-2"></i>
                                    <p class="text-sm">Belum ada tiket terbaru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Konfigurasi global Chart.js
            Chart.defaults.font.family = "'Figtree', system-ui, sans-serif";
            Chart.defaults.font.size = 11;
            Chart.defaults.color = '#6b7280';

            const trendCtx = document.getElementById('trendChart');
            if (trendCtx) {
                new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: @json($trendLabels),
                        datasets: [
                            {
                                label: 'Pengaduan',
                                data: @json($trendComplaints),
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.35,
                                fill: true,
                                pointRadius: 3,
                                pointHoverRadius: 5,
                                borderWidth: 2,
                            },
                            {
                                label: 'Aspirasi',
                                data: @json($trendAspirations),
                                borderColor: '#a855f7',
                                backgroundColor: 'rgba(168, 85, 247, 0.1)',
                                tension: 0.35,
                                fill: true,
                                pointRadius: 3,
                                pointHoverRadius: 5,
                                borderWidth: 2,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                padding: 10,
                                cornerRadius: 4,
                                titleFont: { size: 12, weight: 'bold' },
                                bodyFont: { size: 11 },
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { stepSize: 1, precision: 0 },
                                grid: { color: '#f3f4f6', drawBorder: false }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

            const statusCtx = document.getElementById('statusChart');
            if (statusCtx) {
                new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Baru', 'Diproses', 'Selesai', 'Ditolak'],
                        datasets: [{
                            data: [
                                {{ $complaintStatusData['baru'] }},
                                {{ $complaintStatusData['diproses'] }},
                                {{ $complaintStatusData['selesai'] }},
                                {{ $complaintStatusData['ditolak'] }},
                            ],
                            backgroundColor: ['#3b82f6', '#eab308', '#10b981', '#ef4444'],
                            borderWidth: 0,
                            spacing: 2,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                padding: 10,
                                cornerRadius: 4,
                            }
                        }
                    }
                });
            }

            const aspirationCtx = document.getElementById('aspirationChart');
            if (aspirationCtx) {
                new Chart(aspirationCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Baru', 'Dibaca', 'Ditindaklanjuti'],
                        datasets: [{
                            data: [
                                {{ $aspirationStatusData['baru'] }},
                                {{ $aspirationStatusData['dibaca'] }},
                                {{ $aspirationStatusData['ditindaklanjuti'] }},
                            ],
                            backgroundColor: ['#3b82f6', '#a855f7', '#10b981'],
                            borderWidth: 0,
                            spacing: 2,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                padding: 10,
                                cornerRadius: 4,
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-layout.admin>