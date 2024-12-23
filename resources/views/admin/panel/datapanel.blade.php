@extends('layouts.app')

@section('content')
    <!-- Progress Bar Overlay -->
    <div id="progress" class="fixed inset-0 bg-white bg-opacity-70 flex justify-center items-center z-50">
        <div class="text-center">
            <svg aria-hidden="true" class="inline w-12 h-12 text-gray-200 animate-spin fill-blue-600"
                viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                    fill="currentColor" />
                <path
                    d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                    fill="currentFill" />
            </svg>
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 mb-4 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    <div class="container mx-auto mt-10 px-4">
        <h1 class="text-3xl font-semibold text-blue-600 mb-6">Master Data Panel</h1>

        <div class="flex items-center justify-between mb-5">
            <div>
                <a href="{{ route('admin.masterdata.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md shadow-md transition duration-200">
                    Add New Data
                </a>
                <a href="{{ route('admin.masterdata.manage_user') }}" class="ml-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md shadow-md transition duration-200">
                    Manage User Login
                </a>
            </div>

           
        </div>

        <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
            <table id="masterDataTable" class="table-auto w-full text-sm text-left text-gray-800">
                <thead class="bg-blue-800 text-xs text-white uppercase">
                    <tr>
                        <th class="py-3 px-6">Code</th>
                        <th class="py-3 px-6">Name</th>
                        <th class="py-3 px-6">No SPP</th>
                        <th class="py-3 px-6">Tanggal SPP</th>
                        <th class="py-3 px-6">Status Karyawan</th>
                        <th class="py-3 px-6">Unit</th>
                        <th class="py-3 px-6">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                        <tr class="bg-white border-b hover:bg-indigo-100">
                            <td class="py-4 px-6">{{ $row->code }}</td>
                            <td class="py-4 px-6">{{ $row->nama }}</td>
                            <td class="py-4 px-6">{{ $row->no_spp }}</td>
                            <td class="py-4 px-6">{{ $row->tanggal_spp }}</td>
                            <td class="py-4 px-6">{{ $row->status_karyawan }}</td>
                            <td class="py-4 px-6">{{ $row->unit }}</td>
                            <td class="py-4 px-6">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.masterdata.edit', $row->code) }}" class="bg-yellow-400 hover:bg-yellow-500 text-white py-1 px-3 rounded-md text-sm">
                                        Edit
                                    </a>
                                    <a href="{{ route('admin.masterdata.detail', $row->code) }}" class="bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded-md text-sm">
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('css')
    <!-- Include DataTables CSS and Tailwind Integration -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.tailwind.min.css">

    <style>
        /*Overrides for Tailwind CSS*/

        /* Form fields */
        .dataTables_wrapper select,
        .dataTables_wrapper .dataTables_filter input {
            color: #4a5568; /*text-gray-700*/
            padding-left: 1rem; /*pl-4*/
            padding-right: 1rem; /*pl-4*/
            padding-top: .5rem; /*pl-2*/
            padding-bottom: .5rem; /*pl-2*/
            line-height: 1.25; /*leading-tight*/
            border-width: 2px; /*border-2*/
            border-radius: .25rem; /*rounded*/
            border-color: #edf2f7; /*border-gray-200*/
            background-color: #edf2f7; /*bg-gray-200*/
            margin-top: 25px;
            margin-bottom: 25px;
            margin-right: 25px;
        }

        /* Row Hover */
        table.dataTable.hover tbody tr:hover {
            background-color: #ebf4ff; /*bg-indigo-100*/
        }

        /* Pagination Buttons */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            font-weight: 700; /*font-bold*/
            border-radius: .25rem; /*rounded*/
            border: 1px solid transparent; /*border border-transparent*/
        }

        /* Pagination Buttons - Current selected */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            color: #fff !important; /*text-white*/
            background: #1E40AF !important; /*bg-indigo-500*/
            border: 1px solid transparent; /*border border-transparent*/
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
			color: #fff !important;
			/*text-white*/
			box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1), 0 1px 2px 0 rgba(0, 0, 0, .06);
			/*shadow*/
			font-weight: 700;
			/*font-bold*/
			border-radius: .25rem;
			/*rounded*/
			background: #1E40AF !important;
			/*bg-indigo-500*/
			border: 1px solid transparent;
			/*border border-transparent*/
		}


        /* Add padding to bottom border */
        table.dataTable.no-footer {
            border-bottom: 1px solid #e2e8f0; /*border-b-1 border-gray-300*/
            margin-top: 0.75em;
            margin-bottom: 0.75em;
        }
    </style>
@endpush

@push('js')
    <!-- Include DataTables JS and Tailwind Integration -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.tailwind.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#masterDataTable').DataTable({
                processing: true,
                serverSide: false,
                paging: true,
                searching: true,
                ordering: true,
                responsive: true,
                pageLength: 10,  // Set default page length for pagination
                language: {
                    searchPlaceholder: "Search by code, name, etc.",
                    lengthMenu: "_MENU_ items per page",
                    zeroRecords: "No matching records found",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    infoFiltered: "(filtered from _MAX_ total entries)"
                },
                
                initComplete: function() {
                    // Hide progress bar when table is fully loaded
                    $("#progress").fadeOut("slow");
                },
            });
        });
    </script>
@endpush
