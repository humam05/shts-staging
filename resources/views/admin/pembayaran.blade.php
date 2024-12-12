@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 flex justify-center">
        <div class="bg-white rounded-lg shadow-md p-6 w-full max-w-4xl">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Form Pembayaran</h1>
            <form action="{{ route('admin.transactions.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom Kiri: Input Pembayaran -->
                    <div class="space-y-4">
                        <!-- Field Kode User (Autocomplete) -->
                        <div>
                            <label for="kode_user" class="block text-sm font-medium text-gray-700 mb-1">Kode User</label>
                            <input type="text" name="kode_user" id="kode_user"
                                class="border border-gray-300 rounded w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan kode user...">
                        </div>
                        <!-- Field Pembayaran Rutin -->
                        <div>
                            <label for="pencicilan_rutin" class="block text-sm font-medium text-gray-700 mb-1">Pencicilan
                                Rutin</label>
                            <input type="number" name="pencicilan_rutin" id="pencicilan_rutin"
                                class="border border-gray-300 rounded w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan nominal pencicilan rutin...">
                        </div>

                        <!-- Field Pembayaran Bertahap -->
                        <div>
                            <label for="pencicilan_bertahap" class="block text-sm font-medium text-gray-700 mb-1">Pencicilan
                                Bertahap</label>
                            <input type="number" name="pencicilan_bertahap" id="pencicilan_bertahap"
                                class="border border-gray-300 rounded w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan nominal pencicilan bertahap...">
                        </div>

                    </div>

                    <!-- Kolom Kanan: Field Hasil Autocomplete -->
                    <div class="space-y-4">
                        <!-- Field Nama -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                            <input type="text" name="nama" id="nama"
                                class="border border-gray-300 rounded w-full px-3 py-2 bg-gray-100 focus:outline-none"
                                readonly>
                        </div>

                        <!-- Field No SPP -->
                        <div>
                            <label for="no_spp" class="block text-sm font-medium text-gray-700 mb-1">No SPP</label>
                            <input type="text" name="no_spp" id="no_spp"
                                class="border border-gray-300 rounded w-full px-3 py-2 bg-gray-100 focus:outline-none"
                                readonly>
                        </div>

                        <!-- Field Status Karyawan (Jabatan) -->
                        <div>
                            <label for="status_karyawan"
                                class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                            <input type="text" name="status_karyawan" id="status_karyawan"
                                class="border border-gray-300 rounded w-full px-3 py-2 bg-gray-100 focus:outline-none"
                                readonly>
                        </div>

                        <!-- Field Tanggal Pensiun -->
                        <div>
                            <label for="tanggal_pensiun" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                Pensiun</label>
                            <input type="text" name="tanggal_pensiun" id="tanggal_pensiun"
                                class="border border-gray-300 rounded w-full px-3 py-2 bg-gray-100 focus:outline-none"
                                readonly>
                        </div>

                        <!-- Field Nilai Pokok -->
                        <div>
                            <label for="nilai_pokok" class="block text-sm font-medium text-gray-700 mb-1">Nilai
                                Pokok</label>
                            <input type="text" name="nilai_pokok" id="nilai_pokok"
                                class="border border-gray-300 rounded w-full px-3 py-2 bg-gray-100 focus:outline-none"
                                readonly>
                        </div>

                        <!-- Field Sisa SHT -->
                        <div>
                            <label for="sisa_sht" class="block text-sm font-medium text-gray-700 mb-1">Sisa SHT</label>
                            <input type="text" name="sisa_sht" id="sisa_sht"
                                class="border border-gray-300 rounded w-full px-3 py-2 bg-gray-100 focus:outline-none"
                                readonly>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran</label>
                            <!-- Gunakan tag <div> atau <p> untuk menampilkan status tanpa input -->
                            <div id="status_lunas" class="text-green-600 font-semibold bg-white px-3 py-2 rounded">
                                <!-- Akan diisi dari JavaScript setelah autocomplete memilih user -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="flex justify-end mt-4">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <!-- Pastikan jQuery ter-load -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script> --}}
    <!-- jQuery UI (untuk autocomplete) -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" />
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js" crossorigin="anonymous"></script>

    <script>
        $(function() {
            var availableTags = @json($data);
            $("#kode_user").autocomplete({
                source: availableTags,
                minLength: 3
            });
        });
    </script>
@endpush
