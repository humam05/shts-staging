@extends('layouts.app')

@section('content')
    <div class="mt-10 w-full px-4 sm:px-6 lg:px-8">
        
        <!-- Filter Section -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-6">Filter Pencarian</h2>

        <form method="GET" action="{{ route('admin.monitor.pembayaran') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-8">
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

                <div>
                    <label for="status_karyawan" class="block text-sm font-medium text-gray-700 mb-2">Karpim/Karpel</label>
                    <select name="status" id="status_karyawan"
                        class="border border-gray-300 rounded-lg w-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua</option>
                        @foreach ($status as $statusItem)
                            <option value="{{ $statusItem->status_karyawan }}"
                                <option value="{{ $statusItem->status_karyawan }}" {{ request('status') == $statusItem->status_karyawan ? 'selected' : '' }}>
                                    {{ $statusItem->status_karyawan }}
                                </option>
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                    <select name="tahun" id="tahun"
                        class="border border-gray-300 rounded-lg w-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Tahun Pembayaran</option>
                        @foreach ($years as $year)
                            <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">Tahun SPP</label>
                    <select name="tahun_spp" id="tahun_spp"
                        class="border border-gray-300 rounded-lg w-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Tahun SPP</option>
                        @foreach ($years_spp as $year_spps)
                            <option value="{{ $year_spps }}" {{ request('tahun_spp') == $year_spps ? 'selected' : '' }}>
                                {{ $year_spps }}
                            </option>
                        @endforeach
                    </select>
                </div>

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
                
            </div>
            <div class="flex justify-end mt-4">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-medium px-6 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Filter
                </button>
            </div>
        </form>
        </div>

        <!-- Data Table Section -->
        <div class="overflow-x-auto mt-5">
            <table class="min-w-full bg-blue-50 border border-blue-200 rounded-lg shadow-md table-auto" id="transactionsData">
                <thead class="bg-blue-800 text-white">
                    <tr>
                        <th class="border px-4 py-2">Status Karyawan</th>
                        <th class="border px-4 py-2">Code</th>
                        <th class="border px-4 py-2">Nama</th>
                        <th class="border px-4 py-2">Unit</th>
                        <th class="border px-4 py-2">No SPP</th>
                        <th class="border px-4 py-2">Tanggal SPP</th>
                        <th class="border px-4 py-2">January</th>
                        <th class="border px-4 py-2">February</th>
                        <th class="border px-4 py-2">March</th>
                        <th class="border px-4 py-2">April</th>
                        <th class="border px-4 py-2">May</th>
                        <th class="border px-4 py-2">June</th>
                        <th class="border px-4 py-2">July</th>
                        <th class="border px-4 py-2">August</th>
                        <th class="border px-4 py-2">September</th>
                        <th class="border px-4 py-2">October</th>
                        <th class="border px-4 py-2">November</th>
                        <th class="border px-4 py-2">December</th>
                        <th class="border px-4 py-2">Total</th>
                        <th class="border px-4 py-2">Total Dibayarkan</th>
                        <th class="border px-4 py-2">Sisa SHT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr class="odd:bg-white even:bg-blue-50 hover:bg-blue-100">
                            <td class="border px-4 py-2">{{ $transaction->status_karyawan }}</td>
                            <td class="border px-4 py-2">{{ $transaction->code }}</td>
                            <td class="border px-4 py-2">{{ $transaction->nama }}</td>
                            <td class="border px-4 py-2">{{ $transaction->unit }}</td>
                            <td class="border px-4 py-2">{{ $transaction->no_spp }}</td>
                            <td class="border px-4 py-2">{{ $transaction->tanggal_spp }}</td>
                            <td class="border px-4 py-2">{{ number_format($transaction->jan_value, 2) }}</td>
                            <td class="border px-4 py-2">{{ number_format($transaction->feb_value, 2) }}</td>
                            <td class="border px-4 py-2">{{ number_format($transaction->mar_value, 2) }}</td>
                            <td class="border px-4 py-2">{{ number_format($transaction->apr_value, 2) }}</td>
                            <td class="border px-4 py-2">{{ number_format($transaction->may_value, 2) }}</td>
                            <td class="border px-4 py-2">{{ number_format($transaction->june_value, 2) }}</td>
                            <td class="border px-4 py-2">{{ number_format($transaction->july_value, 2) }}</td>
                            <td class="border px-4 py-2">{{ number_format($transaction->ags_value, 2) }}</td>
                            <td class="border px-4 py-2">{{ number_format($transaction->sep_value, 2) }}</td>
                            <td class="border px-4 py-2">{{ number_format($transaction->oct_value, 2) }}</td>
                            <td class="border px-4 py-2">{{ number_format($transaction->nov_value, 2) }}</td>
                            <td class="border px-4 py-2">{{ number_format($transaction->dec_value, 2) }}</td>
                            <td class="border px-4 py-2">{{ number_format($transaction->grandTotal, 2) }}</td>
                            <td class="border px-4 py-2">{{ number_format($transaction->total_dibayarkan,2) }}</td>
                            <td class="border px-4 py-2">{{ number_format($transaction->sisa_sht,2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-blue-800 text-white font-semibold">
                    <tr>
                        <td class="border px-4 py-2 font-semibold" colspan="6">Total</td>
                        <td class="border px-4 py-2">{{ number_format($total_jan, 2) }}</td>
                        <td class="border px-4 py-2">{{ number_format($total_feb, 2) }}</td>
                        <td class="border px-4 py-2">{{ number_format($total_mar, 2) }}</td>
                        <td class="border px-4 py-2">{{ number_format($total_apr, 2) }}</td>
                        <td class="border px-4 py-2">{{ number_format($total_may, 2) }}</td>
                        <td class="border px-4 py-2">{{ number_format($total_june, 2) }}</td>
                        <td class="border px-4 py-2">{{ number_format($total_july, 2) }}</td>
                        <td class="border px-4 py-2">{{ number_format($total_ags, 2) }}</td>
                        <td class="border px-4 py-2">{{ number_format($total_sep, 2) }}</td>
                        <td class="border px-4 py-2">{{ number_format($total_oct, 2) }}</td>
                        <td class="border px-4 py-2">{{ number_format($total_nov, 2) }}</td>
                        <td class="border px-4 py-2">{{ number_format($total_dec, 2) }}</td>
                        <td class="border px-4 py-2">{{ number_format($total_grand, 2) }}</td>
                        <td class="border px-4 py-2">{{ number_format($total_dibayarkan, 2) }}</td>
                        <td class="border px-4 py-2">{{ number_format($total_sisa, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection

@push('js')
    <!-- DataTables CSS and JS -->
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
         $('#transactionsData').DataTable({
            "lengthMenu": [10, 25, 50],
            "pageLength": 25
        });
    </script>
@endpush
