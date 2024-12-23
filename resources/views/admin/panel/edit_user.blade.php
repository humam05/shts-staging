<!-- resources/views/admin/edit_user.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-gray-100 px-4">
        <div class="w-full max-w-lg bg-white p-8 rounded-lg shadow-md">
            <div class="mb-6 text-center">
                <h2 class="text-2xl font-semibold text-blue-600">Edit User</h2>
                <p class="text-gray-600">Perbarui informasi user di bawah ini.</p>
            </div>
            <form method="POST" action="#">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="kode_user" class="block text-gray-700 text-sm font-medium mb-2">Kode User</label>
                    <div class="relative">
                        <input type="text" id="kode_user" name="kode_user"
                            value="{{ old('kode_user', $data->kode_user) }}" required
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
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $data->nama) }}" required
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
                <div>
                    <label for="role" class="block text-gray-700">Pilih Peran Anda <span
                            class="text-red-500">*</span></label>
                    <select id="role" name="role" required
                        class="mt-1 block w-full px-4 py-2 bg-gray-50 border 
                               border-gray-300 rounded-md focus:outline-none 
                               focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Pilih Peran --</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Pengguna</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">Administrator memiliki akses penuh, sedangkan pengguna memiliki
                        akses terbatas.</p>
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
