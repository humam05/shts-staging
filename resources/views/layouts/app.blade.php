<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Santunan Hari Tua PTPN</title>
    <!-- Tailwind CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Flowbite CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    

</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Branding -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="#" class="text-blue-600 text-xl font-bold">Santunan Hari Tua PTPN</a>
                </div>
                <!-- Navigation Links -->
                <div class="flex items-center">
                    @auth
                        @if (Auth::user()->role === 'admin')
                            <a href="{{ route('admin.rekap') }}"
                                class="text-gray-700 px-3 py-2 rounded-md text-sm font-medium hover:text-blue-600">Rekap</a>
                            <a href="{{ route('admin.dashboard') }}"
                                class="text-gray-700 px-3 py-2 rounded-md text-sm font-medium hover:text-blue-600">Monitoring</a>
                            {{-- <a href="{{ route('admin.pembayaran') }}" class="text-gray-700 px-3 py-2 rounded-md text-sm font-medium hover:text-blue-600">Pembayaran</a> --}}
                            <div class="relative ml-3">
                                <button id="user-menu-button" data-dropdown-toggle="pembayaran-dropdown"
                                    class="flex items-center text-gray-700 focus:outline-none">
                                    <span
                                        class="text-gray-700 px-3 py-2 rounded-md text-sm font-medium hover:text-blue-600">
                                        Pembayaran
                                    </span>
                                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div id="pembayaran-dropdown"
                                    class="hidden z-10 absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                                    <a href="{{ route('admin.form.pembayaran') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Form Pembayaran
                                    </a>
                                    <a href="{{ route('admin.monitor.pembayaran') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Monitor Pembayaran
                                    </a>
                                </div>
                            </div>

                            <a href="{{ route('admin.masterdata') }}"
                                class="text-gray-700 px-3 py-2 rounded-md text-sm font-medium hover:text-blue-600">Master Data Panel</a>
                        @elseif (Auth::user()->role === 'user')
                        <a href="{{ route('user.home') }}"
                        class="text-gray-700 px-3 py-2 rounded-md text-sm font-medium hover:text-blue-600">Home</a>
                        <a href="{{ route('user.monitor') }}"
                        class="text-gray-700 px-3 py-2 rounded-md text-sm font-medium hover:text-blue-600">Monitoring</a>
                        @else
                           
                        @endif

                        <!-- Dropdown -->
                        <div class="relative ml-3">
                            <button id="user-menu-button" data-dropdown-toggle="user-dropdown"
                                class="flex items-center text-gray-700 focus:outline-none">
                                <img class="w-8 h-8 rounded-full" src="https://via.placeholder.com/30" alt="User avatar">
                                <span
                                    class="text-gray-700 px-3 py-2 rounded-md text-sm font-medium hover:text-blue-600">Hello,
                                    {{ Auth::user()->nama }}</span>
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="user-dropdown"
                                class="hidden z-10 absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="mr-2">
                            <button
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-300">Login</button>
                        </a>
                        <a href="{{ route('register') }}">
                            <button
                                class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition duration-300">Register</button>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="mx-auto mt-10 px-2">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>
    

    <!-- Flowbite JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- DataTables CSS -->
     <!-- jQuery harus dimuat terlebih dahulu -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <!-- DataTables JS -->
     <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    @stack('js')

    @stack('css')
</body>

</html>
