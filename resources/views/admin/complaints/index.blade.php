<x-layout.admin title="Pengaduan">
    <h1 class="text-xl font-bold">Daftar Pengaduan</h1>

    <div class="mt-4 overflow-x-auto border border-alabaster-grey-300">
        <table class="w-full min-w-max text-sm">
            <thead class="bg-alabaster-grey-500">
                <tr class="border-b border-alabaster-grey-300">
                    <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">
                        <input type="checkbox">
                    </th>

                    <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">
                        No Tiket
                    </th>

                    <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">
                        Topik
                    </th>

                    <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">
                        Kategori
                    </th>

                    <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">
                        Unit
                    </th>

                    <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">
                        Status
                    </th>

                    <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">
                        Prioritas
                    </th>

                    <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">
                        Pelapor
                    </th>

                    <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">
                        Petugas
                    </th>

                    <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">
                        Tanggal Kejadian
                    </th>

                    <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">
                        Tanggal Lapor
                    </th>

                    <th class="px-4 py-3 text-left font-semibold text-prussian-blue-500">
                        Aksi
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach ($complaints as $complaint)
                    <tr class="border-b border-alabaster-grey-100 bg-white">
                        <td class="px-4 py-3">
                            <input type="checkbox">
                        </td>

                        <td class="px-4 py-3 font-medium text-prussian-blue-500">
                            {{ $complaint->ticket_number }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $complaint->subject }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $complaint->category->name }}
                        </td>

                        <td class="px-4 py-3">
                            -
                        </td>

                        <td class="px-4 py-3">
                            {{ $complaint->status }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $complaint->priority }}
                        </td>

                        <td class="px-4 py-3">
                            {{ !$complaint->is_anonymous ? $complaint->reporter_name : 'Anonim' }}
                        </td>

                        <td class="px-4 py-3">
                            -
                        </td>

                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::parse($complaint->incident_date)->translatedFormat('d F Y') }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $complaint->created_at->translatedFormat('d F Y') }}
                        </td>

                        <td class="text-center px-4 py-3">
                            <button class="cursor-pointer">
                                <i class="ri-more-line"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layout.admin>
