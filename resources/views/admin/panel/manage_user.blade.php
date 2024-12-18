@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-10 px-4">

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <h1 class="text-3xl font-bold text-blue-600 mb-6">Master Data Panel</h1>
        <div class="mb-6">
            <a href="{{ route('register') }}"
                class="btn bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                Add New User
            </a>
        </div>

        <div class="overflow-x-auto relative shadow-lg rounded-lg">
            <table class="w-full text-sm text-left text-gray-800 dark:text-gray-400">
                <thead class="text-xs uppercase bg-blue-800 text-white">
                    <tr>
                        <th scope="col" class="py-3 px-6">Code</th>
                        <th scope="col" class="py-3 px-6">Name</th>
                        <th scope="col" class="py-3 px-6">Role</th>
                        <th scope="col" class="py-3 px-6">Password</th>
                        <th scope="col" class="py-3 px-6">Created At</th>
                        <th scope="col" class="py-3 px-6">Updated At</th>
                        <th scope="col" class="py-3 px-6">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user as $row)
                        <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 border-b">
                            <td class="py-4 px-6">{{ $row->kode_user }}</td>
                            <td class="py-4 px-6">{{ $row->nama }}</td>
                            <td class="py-4 px-6">{{ $row->role }}</td>
                            <td class="py-4 px-6">{{ $row->password }}</td>
                            <td class="py-4 px-6">{{ $row->created_at }}</td>
                            <td class="py-4 px-6">{{ $row->updated_at }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-2">
                                    <a href="#"
                                        class="btn bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-1 px-3 rounded">Edit</a>
                                    <a href="#"
                                        class="btn bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded">Hapus</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
