@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->

        <!-- Error Message -->
        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif



        <!-- Transactions Table -->
        <div class="overflow-x-auto">
            <h1 class="flex items-center text-3xl font-bold mb-5 text-gray-700">Detail Transaksi, {{ $user->nama }}</h1>
            <table id="transactionsTable" class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th
                            class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Bulan
                        </th>
                        <th
                            class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Tahun
                        </th>
                        <th
                            class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Cicilan Rutin
                        </th>
                        <th
                            class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Cicilan Bertahap
                        </th>
                        <th
                            class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $transaction)
                        <tr class="hover:bg-gray-100">
                            <!-- Month -->
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-700">
                                {{ \Carbon\Carbon::createFromFormat('m', $transaction->bulan)->translatedFormat('F') }}
                            </td>
                
                            <!-- Year -->
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-700">
                                {{ e($transaction->year) }}
                            </td>
                
                            <!-- Pencicilan Rutin -->
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-700">
                                Rp {{ number_format($transaction->pencicilan_rutin, 2, ',', '.') }}
                            </td>
                
                            <!-- Pencicilan Bertahap -->
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-700">
                                Rp {{ number_format($transaction->pencicilan_bertahap, 2, ',', '.') }}
                            </td>
                
                            <!-- Actions -->
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-700">
                                <!-- Edit Link -->
                                <a href="{{ route('admin.masterdata.edit.transactions', $transaction->id) }}"
                                   class="text-blue-500 hover:underline">
                                    Edit Transaksi
                                </a>
                
                                <!-- Delete Form -->
                                <form action="{{ route('admin.masterdata.delete.transactions', $transaction->id) }}" 
                                      method="POST" 
                                      class="inline-flex">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded"
                                            aria-label="Hapus transaksi">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <!-- Empty State -->
                        <tr>
                            <td colspan="5" class="text-center py-4 text-sm text-gray-500">
                                Tidak ada transaksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                
            </table>
        </div>
    </div>
@endsection
@push('js')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <!-- jQuery (Required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#transactionsTable').DataTable({
                paging: true, // Enable pagination
                searching: true, // Enable search
                ordering: true, // Enable sorting
                info: true, // Show info like "Showing 1 to 10 of 50 entries"
                autoWidth: false, // Prevent DataTable from changing column widths
                responsive: true // Make the table responsive on small screens
            });
        });
    </script>
@endpush
