<!-- resources/views/admin/create_user.blade.php -->

@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100 px-4">
    <div class="w-full max-w-lg bg-white p-8 rounded-lg shadow-md">
        <div class="mb-6 text-center">
            <h2 class="text-2xl font-semibold text-blue-600">Tambah User Baru</h2>
            <p class="text-gray-600">Isi form di bawah ini untuk menambahkan user baru.</p>
        </div>
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="mb-4">
                <label for="kode_user" class="block text-gray-700 text-sm font-medium mb-2">Kode User <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="text" id="kode_user" name="kode_user" value="{{ old('kode_user') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                        placeholder="Masukkan Kode User">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-3 top-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m0 0l4-4m-4 4l4 4" />
                    </svg>
                </div>
                @error('kode_user')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="nama" class="block text-gray-700 text-sm font-medium mb-2">Nama <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                        placeholder="Masukkan Nama Lengkap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-3 top-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                @error('nama')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="role" class="block text-gray-700 text-sm font-medium mb-2">Pilih Peran Anda <span class="text-red-500">*</span></label>
                <select
                    id="role"
                    name="role"
                    required
                    class="mt-1 block w-full px-4 py-2 bg-gray-50 border 
                           border-gray-300 rounded-md focus:outline-none 
                           focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">-- Pilih Peran --</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Pengguna</option>
                </select>
                @error('role')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                        placeholder="Masukkan Password">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-3 top-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3zm0 2c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z" />
                    </svg>
                </div>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-700 text-sm font-medium mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                        placeholder="Konfirmasi Password">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-3 top-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12h.01M9 12h.01M12 15h.01M12 9h.01" />
                    </svg>
                </div>
                @error('password_confirmation')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah User
            </button>
        </form>
    </div>
</div>
@endsection
