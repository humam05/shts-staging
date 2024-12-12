<div class="bg-indigo-800 text-white shadow-md">
    <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
        <!-- Logo/Brand -->
        <a href="{{ url('/') }}" class="text-3xl font-semibold text-white hover:text-gray-200 transition duration-300 ease-in-out">
            Santunan Hari Muda
        </a>
        
        <!-- Navigation Items -->
        <div class="flex items-center space-x-6">
            <!-- Navbar for authenticated users -->
            @auth
                <div class="flex items-center space-x-6">       
                    <!-- Logout Button -->
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-white hover:text-gray-300 transition duration-300 ease-in-out">Logout</button>
                    </form>
                </div>
            @else
                <!-- Navbar for guests -->
                <a href="{{ route('login') }}" class="text-white hover:text-gray-300 transition duration-300 ease-in-out">Login</a>
                <a href="{{ route('register') }}" class="text-white hover:text-gray-300 transition duration-300 ease-in-out">Register</a>
            @endauth
        </div>
    </nav>
</div>
