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
                        <!-- Kode User (Autocomplete) -->
                        <div class="mb-4">
                            <label for="kode_user[]" class="text-lg font-medium text-gray-700 mb-2">Kode User</label>
                            <input type="text" name="kode_user[]"
                                class="kode_user w-full px-4 py-2 border border-gray-300 rounded-md text-lg"
                                placeholder="Masukkan kode user atau nama">
                        </div>

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

                        <!-- Pencicilan Rutin -->
                        <div class="mb-4">
                            <label for="pencicilan_rutin[]" class="text-lg font-medium text-gray-700 mb-2">Pencicilan Rutin</label>
                            <input type="number" name="pencicilan_rutin[]"
                                class="pencicilan_rutin w-full px-4 py-2 border border-gray-300 rounded-md text-lg"
                                placeholder="Nominal pencicilan rutin">
                        </div>

                        <!-- Pencicilan Bertahap -->
                        <div class="mb-4">
                            <label for="pencicilan_bertahap[]" class="text-lg font-medium text-gray-700 mb-2">Pencicilan Bertahap</label>
                            <input type="number" name="pencicilan_bertahap[]"
                                class="pencicilan_bertahap w-full px-4 py-2 border border-gray-300 rounded-md text-lg"
                                placeholder="Nominal pencicilan bertahap">
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
            <div class="mb-4">
                <label for="kode_user[]" class="text-lg font-medium text-gray-700 mb-2">Kode User</label>
                <input type="text" name="kode_user[]" class="kode_user w-full px-4 py-2 border border-gray-300 rounded-md text-lg" placeholder="Masukkan kode user atau nama">
            </div>
            <div class="mb-4">
                <label for="nama[]" class="text-lg font-medium text-gray-700 mb-2">Nama</label>
                <input type="text" name="nama[]" class="nama w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-lg cursor-not-allowed">
            </div>
            <div class="mb-4">
                <label for="no_spp[]" class="text-lg font-medium text-gray-700 mb-2">No SPP</label>
                <input type="text" name="no_spp[]" class="no_spp w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-lg cursor-not-allowed">
            </div>
            <div class="mb-4">
                <label for="pencicilan_rutin[]" class="text-lg font-medium text-gray-700 mb-2">Pencicilan Rutin</label>
                <input type="number" name="pencicilan_rutin[]" class="pencicilan_rutin w-full px-4 py-2 border border-gray-300 rounded-md text-lg" placeholder="Nominal pencicilan rutin">
            </div>
            <div class="mb-4">
                <label for="pencicilan_bertahap[]" class="text-lg font-medium text-gray-700 mb-2">Pencicilan Bertahap</label>
                <input type="number" name="pencicilan_bertahap[]" class="pencicilan_bertahap w-full px-4 py-2 border border-gray-300 rounded-md text-lg" placeholder="Nominal pencicilan bertahap">
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
                },
                open: function() {
                    // Style the autocomplete dropdown to make it more accessible and organized
                    $(".ui-autocomplete").css({
                        "max-height": "250px", // Set a max height for the dropdown
                        "overflow-y": "auto", // Enable vertical scrolling if list exceeds max height
                        "font-size": "16px", // Slightly reduce font size for better fit
                        "background-color": "white", // Set background color for contrast
                        "border": "1px solid #ddd", // Add a subtle border for visibility
                        "box-shadow": "0 2px 5px rgba(0,0,0,0.15)", // Optional: Add a shadow for better contrast
                        "width": "auto", // Let the dropdown fit the content width
                        "min-width": "200px", // Set a minimum width to ensure readability for smaller items
                    });

                    // Style each item in the autocomplete dropdown
                    $(".ui-menu-item").css({
                        "padding": "10px 15px", // Add padding to each item for better click area
                        "font-size": "16px", // Ensure the font size matches the input text size
                        "cursor": "pointer", // Change cursor to pointer to indicate it's clickable
                    });

                    // Add a separator between each item
                    $(".ui-menu-item").not(":last-child").css({
                        "border-bottom": "1px solid #ddd" // Add a light separator between items
                    });

                    // Optional: Style for hover state for better interaction feedback
                    $(".ui-menu-item").hover(function() {
                        $(this).css({
                            "background-color": "#f0f0f0", // Change background color on hover
                            "color": "#333" // Ensure text color changes for contrast
                        });
                    }, function() {
                        $(this).css({
                            "background-color": "white", // Reset background color when hover is removed
                            "color": "#000" // Reset text color
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
