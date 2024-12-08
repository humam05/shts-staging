@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-semibold text-blue-600">Login</h2>
            <p class="text-gray-600">Silakan masuk untuk mengakses akun Anda.</p>
        </div>
        <form method="POST" action="/login">
            @csrf
            <div class="mb-4">
                <label for="kode_user" class="block text-gray-700 text-sm font-medium mb-2">Kode User</label>
                <input type="text" id="kode_user" name="kode_user" value="{{ old('kode_user') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Masukkan Kode User">
                @error('kode_user')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Masukkan Password">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="form-checkbox h-4 w-4 text-blue-600">
                    <span class="ml-2 text-gray-700 text-sm">Ingat Saya</span>
                </label>
                <p class="text-sm text-blue-600 hover:underline">Lupa Password?</p>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m16 0h-4m0 0a4 4 0 01-4-4v-7a4 4 0 018 0v7a4 4 0 01-4 4z" />
                </svg>
                Login
            </button>
        </form>
        <div class="mt-6 text-center">
            <p class="text-gray-600 text-sm">Belum memiliki akun? <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Daftar Sekarang</a></p>
        </div>
    </div>
</div>
@endsection
