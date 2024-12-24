@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Laporan Bulanan</h1>

        {{-- Form Filter --}}
        <form method="GET" action="{{ route('admin.masterdata.monthly_report') }}"
            class="bg-white p-6 shadow rounded-lg mb-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                {{-- Tahun --}}
                <div>
                    <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
                    <select id="tahun" name="tahun"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @for ($i = date('Y'); $i >= 2000; $i--)
                            <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>{{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- Bulan --}}
                <div>
                    <label for="bulan" class="block text-sm font-medium text-gray-700">Bulan</label>
                    <select id="bulan" name="bulan"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Transaksi Terakhir --}}
                <div>
                    <label for="transaksi_akhir" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Transaksi
                        Terakhir</label>
                    <input type="month" name="transaksi_akhir" id="transaksi_akhir"
                        class="border border-gray-300 rounded-lg w-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ request('transaksi_akhir') }}">
                </div>

                {{-- Status Lunas --}}
                <div>
                    <label for="status_lunas" class="block text-sm font-medium text-gray-700">Status Lunas</label>
                    <select id="status_lunas" name="status_lunas"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Semua</option>
                        <option value="Lunas" {{ request('status_lunas') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="Belum Lunas" {{ request('status_lunas') == 'Belum Lunas' ? 'selected' : '' }}>Belum
                            Lunas</option>
                    </select>
                </div>

                {{-- Filter Unit (Textfield Autocomplete) --}}
                <div>
                    <label for="unit" class="block text-sm font-medium text-gray-700">Unit</label>
                    <input type="text" id="unit" name="unit"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        value="{{ request('unit') }}" autocomplete="off">
                </div>

                
                <div class="flex space-x-2 items-end">
                    <button type="submit" name="filter" value="filter"
                        class="bg-indigo-600 text-white py-2 px-4 rounded-md shadow hover:bg-indigo-700 focus:outline-none focus:ring focus:ring-indigo-300">
                        Terapkan Filter
                    </button>

                    {{-- Export to Excel Button --}}
                    <button type="submit" name="export" value="true"
                        class="bg-green-600 text-white py-2 px-4 rounded-md shadow hover:bg-green-700 focus:outline-none focus:ring focus:ring-green-300">
                        Export to Excel
                    </button>

                    {{-- Reset Button --}}
                    <a href="{{ route('admin.masterdata.monthly_report') }}"
                        class="bg-gray-200 text-gray-700 py-2 px-4 rounded-md shadow hover:bg-gray-300 focus:outline-none focus:ring focus:ring-gray-300">
                        Reset
                    </a>
            </div>

        </form>


        {{-- Tabel Data --}}
        <div class="bg-white shadow rounded-lg overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 border-b">Code</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 border-b">Nama</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-600 border-b">Pembayaran</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 border-b">No SPP</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 border-b">Tanggal SPP</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 border-b">Status Karyawan</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 border-b">Unit</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-600 border-b">Sisa</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 border-b">Transaksi Terakhir</th>
                        @if (request('status_lunas'))
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-600 border-b">Status Lunas</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }} hover:bg-indigo-50">
                            <td class="px-6 py-4 text-sm text-gray-900 border-b">{{ $transaction->code }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 border-b">{{ $transaction->nama }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-right border-b">
                                {{ number_format($transaction->pembayaran, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 border-b">{{ $transaction->no_spp }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 border-b">
                                {{ \Carbon\Carbon::parse($transaction->tanggal_spp)->format('d-m-Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 border-b">{{ $transaction->status_karyawan }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 border-b">{{ $transaction->unit }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-right border-b">
                                {{ number_format($transaction->sisa_hutang, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 border-b">
                                @if ($transaction->last_transaction_id)
                                    {{ \Carbon\Carbon::create($transaction->last_transaction_year, $transaction->last_transaction_bulan, 1)->format('F Y') }}
                                @else
                                    Tidak Ada
                                @endif
                            </td>
                            @if (request('status_lunas'))
                                <td class="px-6 py-4 text-sm text-gray-900 text-center border-b">
                                    {{ $transaction->status_lunas }}</td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ request('status_lunas') ? 10 : 9 }}"
                                class="px-6 py-4 text-center text-sm text-gray-500 border-b">
                                Tidak ada data ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('js')
    {{-- Autocomplete script --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            function initializeAutocomplete(selector, url) {
                $(selector).autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: url,
                            data: {
                                term: request.term
                            },
                            success: function(data) {
                                response(data.map(function(item) {
                                    return {
                                        label: item.unit || item
                                            .status_karyawan,
                                        value: item.unit || item.status_karyawan
                                    };
                                }));
                            }
                        });
                    },
                    minLength: 2,
                    open: function() {
                        var inputWidth = $(this).outerWidth();

                        $(".ui-autocomplete").css({
                            "max-height": "250px", // Set a max height for the dropdown
                            "overflow-y": "auto", // Enable vertical scrolling if list exceeds max height
                            "font-size": "16px", // Slightly reduce font size for better fit
                            "background-color": "white", // Set background color for contrast
                            "border": "1px solid #ddd", // Add a subtle border for visibility
                            "box-shadow": "0 2px 5px rgba(0,0,0,0.15)", // Optional: Add a shadow for better contrast
                            "width": inputWidth +
                                "px", // Set the width to match the input field width
                            "max-width": inputWidth +
                                "px", // Ensure the dropdown doesn't exceed the input width
                        });

                        $(".ui-menu-item").css({
                            "padding": "10px 15px",
                            "font-size": "16px",
                            "cursor": "pointer",
                        });

                        $(".ui-menu-item").not(":last-child").css({
                            "border-bottom": "1px solid #ddd"
                        });

                        $(".ui-menu-item").hover(function() {
                            $(this).css({
                                "background-color": "#f0f0f0",
                                "color": "#333"
                            });
                        }, function() {
                            $(this).css({
                                "background-color": "white",
                                "color": "#000"
                            });
                        });
                    }
                });
            }

            // Initialize autocomplete for the unit field
            initializeAutocomplete('#unit', "{{ route('admin.masterdata.autocomplete') }}");
        });
    </script>
@endpush
