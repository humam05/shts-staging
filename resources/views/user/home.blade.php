<!-- resources/views/dashboard/user.blade.php -->

@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Dashboard User</h2>
            <p class="mt-2 text-gray-600">Selamat datang di dashboard, <span class="font-medium">{{ $user->nama }}</span>.</p>
        </div>
{{-- 
        <!-- Hutang Section -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h3 class="text-xl font-semibold text-gray-800">Hutang Anda</h3>
            <p class="mt-2 text-2xl text-red-600 font-bold">Rp {{ number_format($user->hutang, 2, ',', '.') }}</p>
        </div> --}}

        <!-- Transaksi Section -->
        {{-- <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Transaksi Anda</h3> --}}

            {{-- @if($transactions->count()) --}}
                {{-- <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Bulan</th>
                                <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Tahun</th>
                                <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Pencicilan Rutin</th>
                                <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Pencicilan Bertahap</th>
                                <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Tanggal Dibuat</th>
                            </tr>
                        </thead> --}}
                        {{-- <tbody>
                            @foreach($transactions as $transaction)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $transaction->bulan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $transaction->year }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Rp {{ number_format($transaction->pencicilan_rutin, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Rp {{ number_format($transaction->pencicilan_bertahap, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $transaction->created_at->format('d-m-Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody> --}}
                    {{-- </table>
                </div> --}}

                {{-- <!-- Pagination (Jika Diperlukan) -->
                <div class="mt-4">
                    {{ $transactions->links('pagination::tailwind') }}
                </div> --}}
            {{-- @else
                <p class="text-gray-600">Anda belum memiliki transaksi.</p>
            @endif --}}
        {{-- </div> --}}
    </div>
</div>
@endsection
