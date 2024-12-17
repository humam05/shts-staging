@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="card shadow-xl p-8 rounded-2xl bg-white w-full max-w-xl">
            <h1 class="text-3xl text-center text-blue-600 font-semibold mb-6">Add Data</h1>

            <form action="{{ route('admin.masterdata.store') }}" method="POST">
                @csrf

                <!-- Code Field -->
                <div class="form-group mb-6">
                    <label for="code" class="block font-semibold text-lg mb-2">Code</label>
                    <input type="text"
                        class="form-control form-control-lg w-full p-3 border-2 {{ $errors->has('code') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500"
                        id="code" name="code" value="{{ old('code') }}" required>
                    @if ($errors->has('code'))
                        <div class="text-sm text-red-500">{{ $errors->first('code') }}</div>
                    @endif
                </div>

                <!-- Name Field -->
                <div class="form-group mb-6">
                    <label for="nama" class="block font-semibold text-lg mb-2">Name</label>
                    <input type="text"
                        class="form-control form-control-lg w-full p-3 border-2 {{ $errors->has('nama') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500"
                        id="nama" name="nama" value="{{ old('nama') }}" required>
                    @if ($errors->has('nama'))
                        <div class="text-sm text-red-500">{{ $errors->first('nama') }}</div>
                    @endif
                </div>

                <!-- No SPP Field -->
                <div class="form-group mb-6">
                    <label for="no_spp" class="block font-semibold text-lg mb-2">No SPP</label>
                    <input type="text"
                        class="form-control form-control-lg w-full p-3 border-2 {{ $errors->has('no_spp') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500"
                        id="no_spp" name="no_spp" value="{{ old('no_spp') }}" required>
                    @if ($errors->has('no_spp'))
                        <div class="text-sm text-red-500">{{ $errors->first('no_spp') }}</div>
                    @endif
                </div>

                <!-- Tanggal SPP Field -->
                <div class="form-group mb-6">
                    <label for="tanggal_spp" class="block font-semibold text-lg mb-2">Tanggal SPP</label>
                    <input type="date"
                        class="form-control form-control-lg w-full p-3 border-2 {{ $errors->has('tanggal_spp') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500"
                        id="tanggal_spp" name="tanggal_spp" value="{{ old('tanggal_spp') }}" required>
                    @if ($errors->has('tanggal_spp'))
                        <div class="text-sm text-red-500">{{ $errors->first('tanggal_spp') }}</div>
                    @endif
                </div>

                <!-- Unit Field -->
                <div class="form-group mb-6">
                    <label for="unit" class="block font-semibold text-lg mb-2">Unit</label>
                    <input type="text"
                        class="form-control form-control-lg w-full p-3 border-2 {{ $errors->has('unit') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500"
                        id="unit" name="unit" value="{{ old('unit') }}" required>
                    @if ($errors->has('unit'))
                        <div class="text-sm text-red-500">{{ $errors->first('unit') }}</div>
                    @endif
                </div>

                <!-- Nilai SHT Field -->
                <div class="form-group mb-6">
                    <label for="hutang" class="block font-semibold text-lg mb-2">Nilai SHT</label>
                    <input type="number"
                        class="form-control form-control-lg w-full p-3 border-2 {{ $errors->has('hutang') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500"
                        id="hutang" name="hutang" value="{{ old('hutang') }}" required>
                    @if ($errors->has('hutang'))
                        <div class="text-sm text-red-500">{{ $errors->first('hutang') }}</div>
                    @endif
                </div>

                <!-- Status Karyawan Field -->
                <div class="form-group mb-6">
                    <label for="status_karyawan" class="block font-semibold text-lg mb-2">Status Karyawan</label>
                    <input type="text"
                        class="form-control form-control-lg w-full p-3 border-2 {{ $errors->has('status_karyawan') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500"
                        id="status_karyawan" name="status_karyawan" value="{{ old('status_karyawan') }}" required>
                    @if ($errors->has('status_karyawan'))
                        <div class="text-sm text-red-500">{{ $errors->first('status_karyawan') }}</div>
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="text-center mt-6">
                    <button type="submit"
                        class="bg-blue-500 text-white font-semibold py-3 px-8 rounded-lg shadow-md hover:shadow-lg focus:ring-4 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:scale-105">
                        <i class="fas fa-plus-circle"></i> Add Data
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('css')
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush

@push('js')
    <!-- jQuery (required for autocomplete) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <script>
        $(document).ready(function() {
            // Autocomplete function for both fields
            $('form').submit(function() {
                $(this).find('input').each(function() {
                    if (!$(this).val()) {
                        $(this).addClass('border-red-500');
                    } else {
                        $(this).removeClass('border-red-500');
                    }
                });
            });

            function initializeAutocomplete(selector, url) {
                $(selector).autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: url, // Correct route for autocomplete
                            data: {
                                term: request.term
                            },
                            success: function(data) {
                                // Map the response to match label and value format
                                response(data.map(function(item) {
                                    return {
                                        label: item.unit || item
                                            .status_karyawan, // Display the appropriate label
                                        value: item.unit || item
                                            .status_karyawan // Set the value to the selected item
                                    };
                                }));
                            }
                        });
                    },
                    minLength: 2, // Minimum characters before starting autocomplete

                    // Customize the appearance of the autocomplete dropdown
                    open: function() {
                        var inputWidth = $(this).outerWidth();
                        $(".ui-autocomplete").css({
                            "max-height": "250px", // Max height of dropdown
                            "overflow-y": "auto", // Vertical scrolling
                            "font-size": "16px", // Font size
                            "background-color": "white", // Background color
                            "border": "1px solid #ddd", // Border for visibility
                            "box-shadow": "0 2px 5px rgba(0,0,0,0.15)", // Optional shadow
                            "width": inputWidth + "px", // Match input field width
                            "max-width": inputWidth +
                                "px" // Ensure dropdown width doesn't exceed input field
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

            // Initialize autocomplete for the status_karyawan field
            initializeAutocomplete('#status_karyawan', "{{ route('admin.masterdata.autocomplete.status') }}");
        });
    </script>
@endpush
