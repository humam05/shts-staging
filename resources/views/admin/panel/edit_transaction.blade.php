@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="w-full max-w-lg p-8 bg-white shadow-lg rounded-xl">
        <h2 class="text-2xl font-semibold text-center text-gray-700 mb-6">Edit Transaksi</h2>

        <form method="POST" action="{{ route('admin.masterdata.update.transactions', $transactions->id) }}">
            @csrf
            @method('PUT')

            <!-- Kode Transaksi (tidak dapat diubah) -->
            <div class="mb-4">
                <label for="code" class="block text-sm font-medium text-gray-600">Kode Transaksi</label>
                <input type="text" id="code" name="code" value="{{ $transactions->id }}" readonly
                    class="mt-1 block w-full px-4 py-2 bg-gray-200 text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Bulan -->
            <div class="mb-4">
                <label for="bulan" class="block text-sm font-medium text-gray-600">Bulan</label>
                <input type="number" id="bulan" name="bulan" value="{{ $transactions->bulan }}" required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Tahun -->
            <div class="mb-4">
                <label for="year" class="block text-sm font-medium text-gray-600">Tahun</label>
                <input type="number" id="year" name="year" value="{{ $transactions->year }}" min="2000" max="2100"
                    required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Pencicilan Rutin -->
            <div class="mb-4">
                <label for="pencicilan_rutin" class="block text-sm font-medium text-gray-600">Pencicilan Rutin</label>
                <input type="number" step="0.01" id="pencicilan_rutin" name="pencicilan_rutin" value="{{ $transactions->pencicilan_rutin }}" min="0"
                    required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Pencicilan Bertahap -->
            <div class="mb-6">
                <label for="pencicilan_bertahap" class="block text-sm font-medium text-gray-600">Pencicilan Bertahap</label>
                <input type="number" step="0.01" id="pencicilan_bertahap" name="pencicilan_bertahap" value="{{ $transactions->pencicilan_bertahap }}" min="0"
                    required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit" class="w-full px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection
