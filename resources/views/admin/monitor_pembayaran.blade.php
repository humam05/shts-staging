@extends('layouts.app')

@section('content')
    <div class="w-full">
        <!-- Filter Status Karyawan -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Filter Pencarian</h2>
            <form method="GET" action="{{ route('admin.dashboard') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <!-- Filter Unit -->
                <div>
                    <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                    <select name="unit" id="unit" class="border border-gray-300 rounded w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Unit</option>
                        @foreach ($units as $unitItem)
                            <option value="{{ $unitItem->unit }}" {{ request('unit') == $unitItem->unit ? 'selected' : '' }}>
                                {{ $unitItem->unit }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Tahun Pensiun -->
                <div>
                    <label for="tahun_pensiun" class="block text-sm font-medium text-gray-700 mb-2">Tahun Pembayaran</label>
                    <select name="tahun_pensiun" id="tahun_pensiun" class="border border-gray-300 rounded w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Tahun Pembayaran</option>
                        @foreach (range(date('Y'), date('Y') + 5) as $year)
                            <option value="{{ $year }}" {{ request('tahun_pensiun') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Status Lunas/Belum Lunas -->
                <div>
                    <label for="status_lunas" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status_lunas" id="status_lunas" class="border border-gray-300 rounded w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="Lunas" {{ request('status_lunas') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="Belum Lunas" {{ request('status_lunas') == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                    </select>
                </div>

                <!-- Tombol Filter -->
                <div class="flex items-end justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white shadow rounded-lg overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
                <thead class="bg-blue-800 text-xs text-white uppercase tracking-wider">
                    <!-- Header kedua: Penanda Kolom Bulan dan Total Pertahun -->
                    <tr>
                        <th colspan="3" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider whitespace-nowrap border border-white">Data Umum</th>
                        <th colspan="12" class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider border border-white">Bulan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider border border-white">Total Pertahun</th>
                    </tr>
                    <!-- Header pertama: Data Umum (Nik, Nama, No SPP) -->
                    <tr class="bg-blue-700">
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider whitespace-nowrap border border-white">Nik</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider whitespace-nowrap border border-white">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider whitespace-nowrap border border-white">No SPP</th>

                        <!-- Kolom Bulan (Januari - Desember) di bawah header data umum -->
                        @foreach (range(1, 12) as $month)
                            <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider whitespace-nowrap border border-white">
                                {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                            </th>
                        @endforeach
                        <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider whitespace-nowrap border border-white">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @if (!$dataUsers->isEmpty())
                        @foreach ($dataUsers as $data)
                            <tr class="hover:bg-gray-50 transition duration-200">
                                <td class="px-6 py-4 text-sm text-gray-700 border border-grey-300">{{ $data['user']->code }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700 border border-grey-300">{{ $data['user']->nama }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700 border border-grey-300">{{ $data['user']->lampiran->no_spp ?? 'Belum Di Set' }}</td>

                                <!-- Menampilkan total pencicilan per bulan -->
                                @foreach ($data['total_pencicilan_per_bulan'] as $totalBulan)
                                    <td class="px-6 py-4 text-sm text-gray-700 text-right border border-grey-300">
                                        {{ number_format($totalBulan, 2, ',', '.') }}
                                    </td>
                                @endforeach

                                <!-- Menampilkan total pencicilan pertahun -->
                                <td class="px-6 py-4 text-sm text-gray-700 text-right">
                                    {{ number_format($data['total_pencicilan_pertahun'], 2, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="16" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data user.</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot class="bg-gray-100">
                    <tr>
                        <th colspan="3" class="px-6 py-3 text-center text-sm font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap border border-gray-300">Total</th>
                        <!-- Total per bulan -->
                        @foreach ($bulanList as $bulan)
                            <th class="px-6 py-3 text-right text-sm font-medium text-gray-700 uppercase tracking-wider whitespace-nowrap border border-gray-300">
                                {{ number_format($totalPerBulan[$bulan], 2, ',', '.') }}
                            </th>
                        @endforeach
                        <!-- Total pertahun -->
                        <th class="px-6 py-3 text-right text-sm font-medium text-gray-700 uppercase tracking-wider whitespace-nowrap border border-gray-300">
                            {{ number_format($totalPertahun, 2, ',', '.') }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
