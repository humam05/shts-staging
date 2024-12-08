<!-- resources/views/admin/edit_user.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-gray-100 px-4">
        <div class="w-full max-w-lg bg-white p-8 rounded-lg shadow-md">
            <div class="mb-6 text-center">
                <h2 class="text-2xl font-semibold text-blue-600">Edit User</h2>
                <p class="text-gray-600">Perbarui informasi user di bawah ini.</p>
            </div>
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="kode_user" class="block text-gray-700 text-sm font-medium mb-2">Kode User</label>
                    <div class="relative">
                        <input type="text" id="kode_user" name="kode_user"
                            value="{{ old('kode_user', $user->kode_user) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="Masukkan Kode User">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-3 top-3 h-5 w-5 text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 12H8m0 0l4-4m-4 4l4 4" />
                        </svg>
                    </div>
                    @error('kode_user')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="nama" class="block text-gray-700 text-sm font-medium mb-2">Nama</label>
                    <div class="relative">
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="Masukkan Nama Lengkap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-3 top-3 h-5 w-5 text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    @error('nama')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="status_karyawan" class="block text-gray-700 text-sm font-medium mb-2">Status Karyawan</label>
                    <div class="relative">
                        <input type="text" id="status_karyawan" name="status_karyawan"
                            value="{{ old('status_karyawan', $user->lampiran->status_karyawan ?? '') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="Masukkan Status Saryawan">
                    </div>
                    @error('status_karyawan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="no_spp" class="block text-gray-700 text-sm font-medium mb-2">No SPP</label>
                    <div class="relative">
                        <input type="text" id="no_spp" name="no_spp"
                            value="{{ old('no_spp', $user->lampiran->no_spp ?? '') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="Masukkan No SPP">
                    </div>
                    @error('no_spp')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="tanggal_spp" class="block text-gray-700 text-sm font-medium mb-2">Tanggal SPP</label>
                    <div class="relative">
                        <input type="date" id="tanggal_spp" name="tanggal_spp"
                            value="{{ old('tanggal_spp', isset($tanggal_spp) ? $tanggal_spp->format('Y-m-d') : now()->format('Y-m-d')) }}" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-3 top-3 h-5 w-5 text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    @error('tanggal_spp')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>                
                <div class="mb-4">
                    <label for="unit" class="block text-gray-700 text-sm font-medium mb-2">Unit</label>
                    <div class="relative">
                        <input type="text" id="unit" name="unit"
                            value="{{ old('unit', $user->lampiran->unit ?? '') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="Masukkan Unit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-3 top-3 h-5 w-5 text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7a2 2 0 012-2h3.586a2 2 0 011.414.586l3.828 3.828a2 2 0 010 2.828l-3.828 3.828a2 2 0 01-1.414.586H5a2 2 0 01-2-2V7z" />
                        </svg>
                    </div>
                    @error('unit')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="hutang" class="block text-gray-700 text-sm font-medium mb-2">Hutang</label>
                    <div class="relative">
                        <input type="number" step="0.01" id="hutang" name="hutang"
                            value="{{ old('hutang', $user->lampiran->hutang ?? 0) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="Masukkan Jumlah Hutang">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-3 top-3 h-5 w-5 text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a5 5 0 10-10 0v2m10 0H7m10 0l-4 4m4-4l-4-4" />
                        </svg>
                    </div>
                    @error('hutang')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <hr class="my-6 border-gray-300">
                <h4 class="text-xl font-semibold text-gray-700 mb-4">Ubah Password</h4>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password Baru (Biarkan
                        kosong jika tidak ingin mengubah)</label>
                    <div class="relative">
                        <input type="password" id="password" name="password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="Masukkan Password Baru">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-3 top-3 h-5 w-5 text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3zm0 2c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z" />
                        </svg>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex space-x-4">
                    <button type="submit"
                        class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-5 w-5 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Perbarui User
                    </button>
                    <a href="{{ route('admin.dashboard') }}"
                        class="w-full bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
