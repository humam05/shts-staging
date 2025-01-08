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
                    <input type="number" id="year" name="year" value="{{ $transactions->year }}" min="2000"
                        max="2100" required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Pencicilan Rutin -->
                <div class="mb-4">
                    <label for="pencicilan_rutin" class="block text-sm font-medium text-gray-600">Pencicilan Rutin</label>
                    <input type="text" id="pencicilan_rutin_display" 
                        value="{{ number_format($transactions->pencicilan_rutin, 2, '.', ',') }}" 
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        oninput="formatCurrencyDisplay(this)" onblur="checkZero(this)" autocomplete="off">
                    <input type="hidden" id="pencicilan_rutin" name="pencicilan_rutin" 
                        value="{{ $transactions->pencicilan_rutin }}">
                </div>
                
                <div class="mb-6">
                    <label for="pencicilan_bertahap" class="block text-sm font-medium text-gray-600">Pencicilan Bertahap</label>
                    <input type="text" id="pencicilan_bertahap_display" 
                        value="{{ number_format($transactions->pencicilan_bertahap, 2, '.', ',') }}" 
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        oninput="formatCurrencyDisplay(this)" onblur="checkZero(this)" autocomplete="off">
                    <input type="hidden" id="pencicilan_bertahap" name="pencicilan_bertahap" 
                        value="{{ $transactions->pencicilan_bertahap }}">
                </div>
                
                <button type="submit"
                    class="w-full px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
@endsection
@push('js')
    <script>
       function formatCurrencyDisplay(input) {
        let rawValue = input.value.replace(/[^0-9.]/g, ''); // Hapus karakter non-angka
        if (rawValue === '') {
            rawValue = '0';
        }
        
        let parts = rawValue.split('.');
        let integerPart = parts[0];
        let decimalPart = parts[1] ? parts[1].substring(0, 2) : '';

        // Format bagian integer dengan tanda ribuan
        integerPart = parseInt(integerPart, 10).toLocaleString();

        // Gabungkan kembali angka dengan bagian desimal
        input.value = decimalPart.length > 0 ? `${integerPart}.${decimalPart}` : integerPart;

        // Simpan nilai asli (tanpa pemisah) ke hidden input
        document.getElementById(input.id.replace('_display', '')).value = rawValue;
    }

    function checkZero(input) {
        if (input.value === '0' || input.value === '') {
            input.value = '0.00';
            document.getElementById(input.id.replace('_display', '')).value = '0.00';
        }
    }
    </script>
    
@endpush