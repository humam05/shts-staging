@extends('layouts.app')
@section('content')
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="card shadow-xl p-8 rounded-2xl bg-white w-full max-w-xl">
            <h1 class="text-3xl text-center text-blue-600 font-semibold mb-8">Import Data</h1>

            <form action="{{ route('admin.masterdata.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- File Field -->
                <div class="mb-6">
                    <label for="file" class="block text-lg font-semibold text-gray-700 mb-2">Upload File</label>
                    <input 
                        type="file" 
                        class="w-full p-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                        id="file" 
                        name="file" 
                        required
                    >
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-blue-500 text-white font-semibold py-3 rounded-lg shadow-md hover:bg-blue-600 focus:ring-4 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:scale-105"
                >
                    <i class="fas fa-upload mr-2"></i> Import Data
                </button>
            </form>
        </div>
    </div>
@endsection
