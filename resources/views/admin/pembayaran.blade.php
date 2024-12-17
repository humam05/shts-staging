@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Form Pembayaran</h1>
            <form action="{{ route('admin.transactions.store') }}" method="POST" id="paymentForm">
                @csrf
                <div id="dynamicFields">
                    <!-- Initial Row -->
                    <div class="payment-row mb-6 card shadow-md rounded-lg p-6">
                        <div class="flex flex-wrap justify-between space-x-4">
                            <!-- Left Section -->
                            <div class="flex-1 space-y-4">
                                <!-- Kode User (Autocomplete) -->
                                <div class="mb-4">
                                    <label for="kode_user[]" class="text-lg font-medium text-gray-700 mb-2">Kode
                                        User</label>
                                    <input type="text" name="kode_user[]"
                                        class="kode_user w-full px-4 py-2 border border-gray-300 rounded-md text-lg"
                                        placeholder="Masukkan kode user atau nama">
                                </div>

                                <!-- Pencicilan Rutin -->
                                <div class="mb-4">
                                    <label for="pencicilan_rutin[]"
                                        class="text-lg font-medium text-gray-700 mb-2">Pencicilan Rutin</label>
                                    <input type="number" name="pencicilan_rutin[]"
                                        class="pencicilan_rutin w-full px-4 py-2 border border-gray-300 rounded-md text-lg"
                                        placeholder="Nominal pencicilan rutin">
                                </div>

                                <!-- Pencicilan Bertahap -->
                                <div class="mb-4">
                                    <label for="pencicilan_bertahap[]"
                                        class="text-lg font-medium text-gray-700 mb-2">Pencicilan Bertahap</label>
                                    <input type="number" name="pencicilan_bertahap[]"
                                        class="pencicilan_bertahap w-full px-4 py-2 border border-gray-300 rounded-md text-lg"
                                        placeholder="Nominal pencicilan bertahap">
                                </div>
                            </div>

                            <!-- Right Section -->
                            <div class="flex-1 space-y-4">
                                <!-- Nama -->
                                <div class="mb-4">
                                    <label for="nama[]" class="text-lg font-medium text-gray-700 mb-2">Nama</label>
                                    <input type="text" name="nama[]"
                                        class="nama w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-lg cursor-not-allowed"
                                        readonly>
                                </div>

                                <!-- No SPP -->
                                <div class="mb-4">
                                    <label for="no_spp[]" class="text-lg font-medium text-gray-700 mb-2">No SPP</label>
                                    <input type="text" name="no_spp[]"
                                        class="no_spp w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-lg cursor-not-allowed"
                                        readonly>
                                </div>

                                <!-- Tanggal SPP -->
                                <div class="mb-4">
                                    <label for="tanggal_spp[]" class="text-lg font-medium text-gray-700 mb-2">Tanggal
                                        SPP</label>
                                    <input type="text" name="tanggal_spp[]"
                                        class="tanggal_spp w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-lg cursor-not-allowed"
                                        readonly>
                                </div>

                                <!-- Nilai Pokok -->
                                <div class="mb-4">
                                    <label for="nilai_pokok[]" class="text-lg font-medium text-gray-700 mb-2">Nilai
                                        Pokok</label>
                                    <input type="text" name="nilai_pokok[]"
                                        class="nilai_pokok w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-lg cursor-not-allowed"
                                        readonly>
                                </div>

                                <!-- Sisa SHT -->
                                <div class="mb-4">
                                    <label for="sisa_sht[]" class="text-lg font-medium text-gray-700 mb-2">Sisa SHT</label>
                                    <input type="text" name="sisa_sht[]"
                                        class="sisa_sht w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-lg cursor-not-allowed"
                                        readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Remove Row Button -->
                        <div class="flex justify-end">
                            <button type="button" class="text-red-600 hover:text-red-800 text-lg"
                                onclick="removePaymentRow(this)">
                                <i class="fas fa-trash-alt mr-2"></i> Hapus Baris
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Add Row Button -->
                <div class="flex justify-center mb-6">
                    <button type="button" onclick="addPaymentRow()"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-lg px-6 py-3 rounded-md shadow-md transition duration-200">
                        Tambah Baris
                    </button>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center mt-6">
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white text-lg px-6 py-3 rounded-md shadow-md transition duration-200">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js" crossorigin="anonymous"></script>

    <script>
        // Function to add a new row of payment fields
        function addPaymentRow() {
            var dynamicFields = document.getElementById('dynamicFields');
            var newRow = document.createElement('div');
            newRow.classList.add('payment-row', 'mb-6', 'card', 'shadow-md', 'rounded-lg', 'p-6');

            newRow.innerHTML = `
                <div class="flex flex-wrap justify-between space-x-4">
                    <!-- Left Section -->
                    <div class="flex-1 space-y-4">
                        <div class="mb-4">
                            <label for="kode_user[]" class="text-lg font-medium text-gray-700 mb-2">Kode User</label>
                            <input type="text" name="kode_user[]" class="kode_user w-full px-4 py-2 border border-gray-300 rounded-md text-lg" placeholder="Masukkan kode user atau nama">
                        </div>
                        <div class="mb-4">
                            <label for="pencicilan_rutin[]" class="text-lg font-medium text-gray-700 mb-2">Pencicilan Rutin</label>
                            <input type="number" name="pencicilan_rutin[]" class="pencicilan_rutin w-full px-4 py-2 border border-gray-300 rounded-md text-lg" placeholder="Nominal pencicilan rutin">
                        </div>
                        <div class="mb-4">
                            <label for="pencicilan_bertahap[]" class="text-lg font-medium text-gray-700 mb-2">Pencicilan Bertahap</label>
                            <input type="number" name="pencicilan_bertahap[]" class="pencicilan_bertahap w-full px-4 py-2 border border-gray-300 rounded-md text-lg" placeholder="Nominal pencicilan bertahap">
                        </div>
                    </div>

                    <!-- Right Section -->
                    <div class="flex-1 space-y-4">
                        <div class="mb-4">
                            <label for="nama[]" class="text-lg font-medium text-gray-700 mb-2">Nama</label>
                            <input type="text" name="nama[]" class="nama w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-lg cursor-not-allowed">
                        </div>
                        <div class="mb-4">
                            <label for="no_spp[]" class="text-lg font-medium text-gray-700 mb-2">No SPP</label>
                            <input type="text" name="no_spp[]" class="no_spp w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-lg cursor-not-allowed">
                        </div>
                        <div class="mb-4">
                            <label for="tanggal_spp[]" class="text-lg font-medium text-gray-700 mb-2">Tanggal SPP</label>
                            <input type="text" name="tanggal_spp[]" class="tanggal_spp w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-lg cursor-not-allowed">
                        </div>
                        <div class="mb-4">
                            <label for="nilai_pokok[]" class="text-lg font-medium text-gray-700 mb-2">Nilai Pokok</label>
                            <input type="text" name="nilai_pokok[]" class="nilai_pokok w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-lg cursor-not-allowed">
                        </div>
                        <div class="mb-4">
                            <label for="sisa_sht[]" class="text-lg font-medium text-gray-700 mb-2">Sisa SHT</label>
                            <input type="text" name="sisa_sht[]" class="sisa_sht w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-lg cursor-not-allowed">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="button" class="text-red-600 hover:text-red-800 text-lg" onclick="removePaymentRow(this)">
                        <i class="fas fa-trash-alt mr-2"></i> Hapus Baris
                    </button>
                </div>
            `;
            dynamicFields.appendChild(newRow);

            // Apply autocomplete to the new "kode_user" input field
            initializeAutocomplete(newRow.querySelector('.kode_user'));
        }

        // Function to remove a row
        function removePaymentRow(button) {
            var row = button.closest('.payment-row');
            row.remove();
        }

        // Function to initialize autocomplete
        function initializeAutocomplete(element) {
            $(element).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('admin.autocomplete') }}",
                        data: {
                            term: request.term
                        },
                        success: function(data) {
                            response(data.map(function(item) {
                                return {
                                    label: item.label,
                                    value: item.value,
                                    data: item
                                };
                            }));
                        }
                    });
                },
                minLength: 3,
                select: function(event, ui) {
                    $(this).val(ui.item.value); // Set value (kode_user)
                    $(this).closest('.payment-row').find('.nama').val(ui.item.data.nama);
                    $(this).closest('.payment-row').find('.no_spp').val(ui.item.data.no_spp);
                    $(this).closest('.payment-row').find('.tanggal_spp').val(ui.item.data.tanggal_spp);
                    $(this).closest('.payment-row').find('.nilai_pokok').val(ui.item.data.nilai_pokok);
                    $(this).closest('.payment-row').find('.sisa_sht').val(ui.item.data.sisa_sht);
                },
                open: function() {
                    // Style the autocomplete dropdown to make it more accessible and organized
                    var inputWidth = $(this).outerWidth();
                    $(".ui-autocomplete").css({
                        "max-height": "250px", // Set a max height for the dropdown
                        "overflow-y": "auto", // Enable vertical scrolling if list exceeds max height
                        "font-size": "16px", // Slightly reduce font size for better fit
                        "background-color": "white", // Set background color for contrast
                        "border": "1px solid #ddd", // Add a subtle border for visibility
                        "box-shadow": "0 2px 5px rgba(0,0,0,0.15)", // Optional: Add a shadow for better contrast
                        "width": inputWidth + "px", // Set the width to match the input field width
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

        // Initialize autocomplete for all existing rows on page load
        $(document).ready(function() {
            $('.kode_user').each(function() {
                initializeAutocomplete(this);
            });
        });
    </script>
@endpush
