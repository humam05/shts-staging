@php
    function formatCurrency($value)
    {
        return $value < 0 ? '(' . number_format(abs($value), 0, ',', '.') . ')' : number_format($value, 0, ',', '.');
    }
@endphp
@extends('layouts.app')

@section('content')
    <div class="w-full">
        <!-- Filter Status Karyawan -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-6">Filter Pencarian</h2>

            <form method="GET" action="{{ route('admin.dashboard') }}" class="space-y-4">
                <!-- Filter Utama -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <!-- Filter Status Karyawan -->
                    <div>
                        <label for="status_karyawan"
                            class="block text-sm font-medium text-gray-700 mb-2">Karpim/Karpel</label>
                        <select name="status" id="status_karyawan"
                            class="border border-gray-300 rounded-lg w-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua</option>
                            @foreach ($status as $statusItem)
                                <option value="{{ $statusItem->status_karyawan }}"
                                    {{ request('status') == $statusItem->status_karyawan ? 'selected' : '' }}>
                                    {{ $statusItem->status_karyawan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Unit -->
                    <div>
                        <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                        <select name="unit" id="unit"
                            class="border border-gray-300 rounded-lg w-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih Unit</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->unit }}" {{ request('unit') == $unit->unit ? 'selected' : '' }}>
                                    {{ $unit->unit }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Status Lunas -->
                    <div>
                        <label for="status_lunas" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status_lunas" id="status_lunas"
                            class="border border-gray-300 rounded-lg w-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="Lunas" {{ request('status_lunas') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="Belum Lunas" {{ request('status_lunas') == 'Belum Lunas' ? 'selected' : '' }}>
                                Belum Lunas</option>
                        </select>
                    </div>
                </div>

                <!-- Filter Tambahan (Disembunyikan Secara Default) -->
                <div id="extra-filters" class="hidden mt-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <!-- Filter Periode SPP -->
                        <div>
                            <label for="awal_spp" class="block text-sm font-medium text-gray-700 mb-2">Awal Periode
                                SPP</label>
                            <input type="month" name="awal_spp" id="awal_spp" value="{{ request('awal_spp') }}"
                                class="border border-gray-300 rounded-lg w-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="akhir_spp" class="block text-sm font-medium text-gray-700 mb-2">Akhir Periode
                                SPP</label>
                            <input type="month" name="akhir_spp" id="akhir_spp" value="{{ request('akhir_spp') }}"
                                class="border border-gray-300 rounded-lg w-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Filter Pensiun -->
                        <div>
                            <label for="awal_pensiun" class="block text-sm font-medium text-gray-700 mb-2">Awal Periode
                                Pensiun</label>
                            <input type="month" name="awal_pensiun" id="awal_pensiun"
                                class="border border-gray-300 rounded-lg w-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="akhir_pensiun" class="block text-sm font-medium text-gray-700 mb-2">Akhir Periode
                                Pensiun</label>
                            <input type="month" name="akhir_pensiun" id="akhir_pensiun"
                                class="border border-gray-300 rounded-lg w-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Sorting Tanggal -->
                        <div>
                            <label for="sort_tanggal" class="block text-sm font-medium text-gray-700 mb-2">Sortir
                                Tanggal</label>
                            <select name="sort_tanggal" id="sort_tanggal"
                                class="border border-gray-300 rounded-lg w-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Urutkan Tanggal</option>
                                <option value="asc" {{ request('sort_tanggal') == 'asc' ? 'selected' : '' }}>Asc
                                    (Terlama)</option>
                                <option value="desc" {{ request('sort_tanggal') == 'desc' ? 'selected' : '' }}>Desc
                                    (Terbaru)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex justify-between items-center mt-4">
                    <button type="button" id="toggle-extra-filters" class="text-blue-500 hover:underline">Tampilkan Filter
                        Lainnya</button>
                    <div>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-medium px-6 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Filter
                        </button>
                        <button type="submit" name="export" value="excel"
                            class="bg-green-500 hover:bg-green-600 text-white font-medium px-6 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            <i class="fas fa-download"></i> Export to Excel
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white shadow rounded-lg overflow-x-auto">
            <table class="w-full bg-white border border-gray-200 rounded-lg shadow-md mx-4" id="tableData">
                <thead class="bg-blue-800 text-xs text-white uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Karpim /
                            Karpel</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">NIK</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Kodefikasi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">No SPP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Tanggal SPP
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Tanggal
                            Pensiun</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Nilai SHT
                            (Rp)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Pembayaran
                            (Rp)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Sisa SHT
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Status
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $user->status_karyawan ?? 'Belum Di Set' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $user->unit ?? 'Belum Di Set' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Belum Di Set</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $user->code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $user->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $user->no_spp ?? 'Belum Di Set' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ optional($user)->tanggal_spp ? \Carbon\Carbon::parse(optional($user)->tanggal_spp)->format('d-m-Y') : 'Belum Di Set' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Belum Di Set</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right">
                                {{ formatCurrency($user->hutang ?? 0, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right">
                                {{ formatCurrency($user->total_pembayaran ?? 0, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right">
                                {{ formatCurrency($user->sisa_sht ?? 0, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-md {{ $user->sisa_sht == 0 ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                    {{ $user->sisa_sht > 0 ? 'Belum Lunas' : 'Lunas' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                    @if ($users->isEmpty())
                        <tr>
                            <td colspan="10" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">Tidak
                                ada user.</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot class="bg-blue-800 text-white">
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-right text-sm font-semibold">Total</td>
                        <td class="px-6 py-4 text-sm text-right">{{ formatCurrency($totalHutang) }}</td>
                        <td class="px-6 py-4 text-sm text-right">{{ formatCurrency($totalPencicilan) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-right">{{ formatCurrency(abs($totalSisaSht)) }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- Pagination -->
        <div class="flex mt-4 justify-center">
            {{-- {{ $users->appends(request()->query())->links() }} --}}
        </div>
    </div>
@endsection

@push('js')
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $('#tableData').DataTable({
            "lengthMenu": [10, 25, 50],
            "pageLength": 25
        });
    </script>
    <script>
        document.getElementById('toggle-extra-filters').addEventListener('click', function() {
            const extraFilters = document.getElementById('extra-filters');
            const isHidden = extraFilters.classList.contains('hidden');
            extraFilters.classList.toggle('hidden', !isHidden);
            this.textContent = isHidden ? 'Sembunyikan Filter Lainnya' : 'Tampilkan Filter Lainnya';
        });

        document.getElementById('awal_pensiun').disabled = true;
        document.getElementById('akhir_pensiun').disabled = true;
    </script>
@endpush
