@extends('layouts.app')  <!-- Jika menggunakan layout master -->

@section('content')
<div class="container mx-auto mt-8 px-4">
    <h1 class="text-3xl font-semibold mb-4 text-center">Mass Transaction Delete</h1>

    <!-- Form Filter Tahun dan Kategori -->
    <div class="mb-6 text-center">
        <form action="{{ route('admin.rekap') }}" method="GET" class="inline-block">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 justify-center">
                <!-- Filter Tahun -->
                <div>
                    <label for="year" class="block text-lg font-medium text-gray-700">Filter Tahun</label>
                    <select id="year" name="year" class="mt-1 block w-full px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="" selected>Pilih Tahun</option>
                        @foreach($years as $year)
                            <option value="{{ $year->year }}" @if(request('year') == $year->year) selected @endif>
                                {{ $year->year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Kategori (KP atau KL) -->
                <div>
                    <label for="category" class="block text-lg font-medium text-gray-700">Filter Kategori</label>
                    <select id="category" name="category" class="mt-1 block w-full px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="" selected>Pilih Kategori</option>
                        <option value="KP" @if(request('category') == 'KP') selected @endif>Karpim (KP)</option>
                        <option value="KL" @if(request('category') == 'KL') selected @endif>Karpel (KL)</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="mt-4 px-6 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700">Filter</button>
        </form>
    </div>


    <!-- Tabel Transaksi -->
    <div class="bg-white shadow-md rounded-lg mt-6">
        <table class="text-sm text-gray-600">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-800">Bulan</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-800">Tahun</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-800">Total Cicilan</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-800">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                    <tr class="border-b border-gray-200">
                        <td class="px-6 py-4">{{ $transaction->bulan }}</td>
                        <td class="px-6 py-4">{{ $transaction->year }}</td>
                        <td class="px-6 py-4">{{ number_format($transaction->total, 2, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <!-- Form untuk Hapus per Bulan dengan kategori -->
                            <form action="{{ route('admin.rekap.destroy', [$transaction->year, $transaction->bulan]) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <input name="category" value="{{ request('category') }}">
                                <button type="submit" class="text-red-500 hover:text-red-700 font-medium focus:outline-none">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @if($transactions->isEmpty())
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada data untuk filter yang dipilih.</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                @if($transactions->isNotEmpty())
                    <tr class="bg-gray-50">
                        <td colspan="2" class="px-6 py-3 font-semibold text-gray-800 text-right">Total Transaksi:</td>
                        <td class="px-6 py-3 font-semibold text-gray-800">{{ number_format($totalPerMonth, 2, ',', '.') }}</td>
                        <td class="px-6 py-3"></td> <!-- Kolom Aksi kosong -->
                    </tr>
                @endif
            </tfoot>
        </table>
    </div>
    
    
</div>
@endsection
