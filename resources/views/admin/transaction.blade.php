@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <h2 class="text-2xl font-semibold text-gray-700 mb-6">
            Transaksi {{ $user->nama }}
        </h2>

        <!-- Error Message -->
        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Transactions Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Bulan
                        </th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Tahun
                        </th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Cicilan Rutin
                        </th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Cicilan Bertahap
                        </th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Total Cicilan
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($user->transactions as $transaction)
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-700">
                                {{ \Carbon\Carbon::createFromFormat('m', $transaction->bulan)->translatedFormat('F') }}
                            </td>
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-700">
                                {{ $transaction->year }}
                            </td>
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-700">
                                Rp {{ number_format($transaction->pencicilan_rutin, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-700">
                                Rp {{ number_format($transaction->pencicilan_bertahap, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-700">
                                Rp {{ number_format($transaction->total_cicilan, 2, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-sm text-gray-500">Tidak ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
