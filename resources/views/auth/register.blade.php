<!-- resources/views/register.blade.php -->

@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-center mb-4">Buat Akun Baru</h2>
        <p class="text-center text-gray-600 mb-6">Bergabunglah dengan komunitas kami! Isi form di bawah untuk mendaftar.</p>
        <!-- Tambahkan x-data untuk Alpine.js -->
        <form method="POST" action="/register" class="space-y-4" x-data="{ showPassword: false, showPasswordConfirm: false }">
            @csrf

            <!-- Kode Pengguna -->
            <div>
                <label for="kode_user" class="block text-gray-700">Kode Pengguna <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    id="kode_user"
                    name="kode_user"
                    value="{{ old('kode_user') }}"
                    required
                    placeholder="Masukkan kode unik Anda"
                    class="mt-1 block w-full px-4 py-2 bg-gray-50 border 
                           border-gray-300 rounded-md focus:outline-none 
                           focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                @error('kode_user')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-sm mt-1">Kode unik untuk identifikasi pengguna.</p>
            </div>

            <!-- Nama Lengkap -->
            <div>
                <label for="nama" class="block text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="{{ old('nama') }}"
                    required
                    placeholder="Contoh: John Doe"
                    class="mt-1 block w-full px-4 py-2 bg-gray-50 border 
                           border-gray-300 rounded-md focus:outline-none 
                           focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                @error('nama')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pilih Peran -->
            <div>
                <label for="role" class="block text-gray-700">Pilih Peran Anda <span class="text-red-500">*</span></label>
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
                <p class="text-gray-500 text-sm mt-1">Administrator memiliki akses penuh, sedangkan pengguna memiliki akses terbatas.</p>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-gray-700">Password <span class="text-red-500">*</span></label>
                <div class="mt-1 relative" x-data="{ showPassword: false }">
                    <input
                        :type="showPassword ? 'text' : 'password'"
                        id="password"
                        name="password"
                        required
                        placeholder="Buat password yang kuat"
                        class="block w-full px-4 py-2 bg-gray-50 border 
                               border-gray-300 rounded-md focus:outline-none 
                               focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    <!-- Tombol Toggle -->
                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500">
                        <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <!-- Ikon Mata Terbuka -->
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <!-- Ikon Mata Tertutup -->
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.055 10.055 0 012.009-4.42M4.22 4.22l15.56 15.56" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-sm mt-1">Password harus minimal 8 karakter dan mengandung kombinasi huruf dan angka.</p>
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label for="password_confirmation" class="block text-gray-700">Konfirmasi Password <span class="text-red-500">*</span></label>
                <div class="mt-1 relative" x-data="{ showPasswordConfirm: false }">
                    <input
                        :type="showPasswordConfirm ? 'text' : 'password'"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        placeholder="Ulangi password Anda"
                        class="block w-full px-4 py-2 bg-gray-50 border 
                               border-gray-300 rounded-md focus:outline-none 
                               focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    <!-- Tombol Toggle -->
                    <button type="button" @click="showPasswordConfirm = !showPasswordConfirm" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500">
                        <svg x-show="!showPasswordConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <!-- Ikon Mata Terbuka -->
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="showPasswordConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <!-- Ikon Mata Tertutup -->
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.055 10.055 0 012.009-4.42M4.22 4.22l15.56 15.56" />
                        </svg>
                    </button>
                </div>
                @error('password_confirmation')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-sm mt-1">Pastikan password sama dengan yang Anda buat sebelumnya.</p>
            </div>

            <!-- Tombol Submit -->
            <div>
                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-md 
                           hover:bg-blue-700 transition duration-200"
                >
                    Daftar Sekarang
                </button>
            </div>
        </form>

        <!-- Sudah Punya Akun -->
        <p class="text-center text-gray-600 mt-6">
            Sudah memiliki akun? 
            <a href="/login" class="text-blue-600 hover:underline">Masuk di sini</a>.
        </p>
    </div>
</div>
@endsection
