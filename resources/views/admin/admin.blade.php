@php
    function formatCurrency($value)
    {
        return $value < 0 ? '(' . number_format(abs($value), 0, ',', '.') . ')' : number_format($value, 0, ',', '.');
    }
@endphp
@extends('layouts.app')

@section('content')
    <div class="w-full">
        <!-- Dashboard Header -->
        <div class="flex items-center justify-between py-6">
            <h2 class="text-2xl font-semibold text-gray-700">Dashboard Admin</h2>
            <div class="flex space-x-4">
                <a href="{{ route('admin.users.create') }}"
                    class="flex items-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah User
                </a>
            </div>
        </div>

        <!-- Filter Status Karyawan -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-6">Filter Pencarian</h2>

            <form method="GET" action="{{ route('admin.dashboard') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <!-- Filter Karpim/Karpel -->
                    <div>
                        <label for="status_karyawan" class="block text-sm font-medium text-gray-700 mb-2">Karpim/Karpel</label>
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

                    <!-- Filter Status Lunas/Belum Lunas -->
                    <div>
                        <label for="status_lunas" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status_lunas" id="status_lunas"
                            class="border border-gray-300 rounded-lg w-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="Lunas" {{ request()->status_lunas == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="Belum Lunas" {{ request()->status_lunas == 'Belum Lunas' ? 'selected' : '' }}>
                                Belum Lunas</option>
                        </select>
                    </div>

                    <!-- Filter Tahun (from tanggal_spp) -->
                    <div>
                        <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                        <select name="tahun" id="tahun"
                            class="border border-gray-300 rounded-lg w-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih Tahun</option>
                            @foreach ($years as $year)
                                <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Bulan (from tanggal_spp) -->
                    <div>
                        <label for="bulan" class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                        <select name="bulan" id="bulan"
                            class="border border-gray-300 rounded-lg w-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih Bulan</option>
                            @foreach ([
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember',
                            ] as $bulan => $namaBulan)
                                <option value="{{ $bulan }}" {{ request('bulan') == $bulan ? 'selected' : '' }}>
                                    {{ $namaBulan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sorting Tanggal -->
                    <div>
                        <label for="sort_tanggal" class="block text-sm font-medium text-gray-700 mb-2">Sortir Tanggal</label>
                        <select name="sort_tanggal" id="sort_tanggal"
                            class="border border-gray-300 rounded-lg w-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Urutkan Tanggal</option>
                            <option value="asc" {{ request('sort_tanggal') == 'asc' ? 'selected' : '' }}>Asc (Terlama)</option>
                            <option value="desc" {{ request('sort_tanggal') == 'desc' ? 'selected' : '' }}>Desc (Terbaru)</option>
                        </select>
                    </div>
                </div>

                <!-- Tombol Filter -->
                <div class="flex justify-end mt-4">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-medium px-6 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white shadow rounded-lg overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
                <thead class="bg-blue-800 text-xs text-white uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Karpim / Karpel</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Kodefikasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">No SPP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            <a href="{{ route('admin.dashboard', ['sort_tanggal' => request('sort_tanggal') == 'asc' ? 'desc' : 'asc'] ) }}" class="flex items-center space-x-2">
                                <span>Tanggal SPP</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Nilai SHT (Rp)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Pembayaran (Rp)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Sisa SHT</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $user->lampiran->status_karyawan ?? 'Belum Di Set' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $user->lampiran->unit ?? 'Belum Di Set' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $user->code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $user->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $user->lampiran->no_spp ?? 'Belum Di Set' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ optional($user->lampiran)->tanggal_spp ? \Carbon\Carbon::parse(optional($user->lampiran)->tanggal_spp)->format('d-m-Y') : 'Belum Di Set' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right">{{ formatCurrency($user->lampiran->hutang ?? 0, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right">{{ formatCurrency($user->totalPencicilan ?? 0, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right">{{ formatCurrency($user->sisa_sht ?? 0, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-md {{ $user->status_lunas == 'Lunas' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                    {{ $user->status_lunas }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                    @if ($users->isEmpty())
                        <tr>
                            <td colspan="10" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">Tidak ada user.</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot class="bg-blue-800 text-white">
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-right text-sm font-semibold">Total</td>
                        <td class="px-6 py-4 text-sm text-right">{{ formatCurrency($totalHutang->total_hutang) }}</td>
                        <td class="px-6 py-4 text-sm text-right">{{ formatCurrency($totalPencicilan->total_pembayaran) }}</td>
                        <td class="px-6 py-4 text-sm text-right">{{ formatCurrency(abs($totalSisaSht)) }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- Pagination -->
        <div class="flex mt-4 justify-center">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
