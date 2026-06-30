<header class="fixed top-0 left-0 right-0 z-50 px-4 py-4 pointer-events-none" x-data="{ mobileMenuOpen: false }">
    <nav class="max-w-7xl mx-auto glass-panel px-8 py-4 flex items-center justify-between shadow-2xl shadow-red-100 pointer-events-auto border-white/60 relative">
        
        <!-- Logo Section -->
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/readspace-library.png') }}" alt="ReadSpace Logo" class="h-10 w-auto">
            <span class="font-bold text-xl text-gray-800 tracking-tight hidden sm:block">ReadSpace</span>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center gap-1">
            <a href="{{ route('home') }}" class="px-5 py-2 rounded-xl transition-all duration-300 {{ request()->routeIs('home') ? 'bg-burgundy-500 text-white shadow-lg shadow-red-100' : 'text-gray-500 hover:text-burgundy-500 hover:bg-white/80' }} font-medium text-sm">
                Home
            </a>
            @if(session()->has('user') && session('user')['role'] === 'admin')
                <a href="{{ route('admin.katalog') }}" class="px-5 py-2 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.katalog') ? 'bg-burgundy-500 text-white shadow-lg shadow-red-100' : 'text-gray-500 hover:text-burgundy-500 hover:bg-white/80' }} font-medium text-sm">
                    Book Catalog
                </a>
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.users.index') ? 'bg-burgundy-500 text-white shadow-lg shadow-red-100' : 'text-gray-500 hover:text-burgundy-500 hover:bg-white/80' }} font-medium text-sm">
                    User Data
                </a>
            @else
                <a href="{{ route('katalog') }}" class="px-5 py-2 rounded-xl transition-all duration-300 {{ request()->routeIs('katalog') ? 'bg-burgundy-500 text-white shadow-lg shadow-red-100' : 'text-gray-500 hover:text-burgundy-500 hover:bg-white/80' }} font-medium text-sm">
                    Catalog
                </a>
                <a href="{{ route('search') }}" class="px-5 py-2 rounded-xl transition-all duration-300 {{ request()->routeIs('search') ? 'bg-burgundy-500 text-white shadow-lg shadow-red-100' : 'text-gray-500 hover:text-burgundy-500 hover:bg-white/80' }} font-medium text-sm">
                    Search
                </a>
            @endif
            <a href="{{ route('about') }}" class="px-5 py-2 rounded-xl transition-all duration-300 {{ request()->routeIs('about') ? 'bg-burgundy-500 text-white shadow-lg shadow-red-100' : 'text-gray-500 hover:text-burgundy-500 hover:bg-white/80' }} font-medium text-sm">
                About
            </a>
        </div>

        <!-- Auth & Action Section -->
        <div class="flex items-center gap-2 sm:gap-4">
            @if(session()->has('user'))
            <div class="flex items-center gap-4 p-1 pl-4 bg-white/60 rounded-2xl border border-white/80">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-gray-800 leading-none">{{ session('user')['name'] }}</p>
                    <p class="text-[9px] text-gray-400 mt-1 uppercase font-bold tracking-widest">{{ strtolower(session('user')['role']) === 'mahasiswa' ? 'Student' : session('user')['role'] }}</p>
                </div>
                <button id="dropdownUserButton" data-dropdown-toggle="dropdownUser" class="w-9 h-9 rounded-xl bg-burgundy-500 text-white flex items-center justify-center font-bold text-sm shadow-md transition-transform hover:scale-105">
                    {{ substr(session('user')['name'], 0, 1) }}
                </button>
            </div>

            <!-- Dropdown menu -->
            <div id="dropdownUser" class="z-50 hidden bg-white/90 backdrop-blur-2xl border border-white/60 divide-y divide-gray-100 rounded-2xl shadow-2xl w-44">
                <ul class="py-2 text-sm text-gray-700">
                    @if(session('user')['role'] === 'admin')
                        <li><a href="{{ route('admin.profile') }}" class="block px-4 py-2 hover:bg-red-50 hover:text-burgundy-500 transition-colors font-medium">Admin Profile</a></li>
                    @else
                        <li><a href="{{ route('profile') }}" class="block px-4 py-2 hover:bg-red-50 hover:text-burgundy-500 transition-colors font-medium">My Profile</a></li>
                    @endif
                </ul>
                <div class="py-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-500 font-bold hover:bg-red-50 transition-colors">Log Out</button>
                    </form>
                </div>
            </div>
            @else
            <div class="flex items-center gap-2">
                <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl bg-burgundy-500 text-white text-sm font-bold shadow-lg shadow-red-100 hover:bg-burgundy-600 transition-all hover:-translate-y-0.5">Log In</a>
            </div>
            @endif

            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-gray-500 p-2 hover:text-burgundy-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>

        <!-- Mobile Navigation Menu -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             @click.away="mobileMenuOpen = false"
             class="absolute top-[110%] left-0 right-0 bg-white/95 backdrop-blur-2xl border border-white/60 rounded-2xl shadow-2xl overflow-hidden md:hidden z-50 p-4 flex flex-col gap-2"
             style="display: none;">
            
            <a href="{{ route('home') }}" class="px-4 py-2 rounded-xl {{ request()->routeIs('home') ? 'bg-burgundy-50 text-burgundy-500 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">Home</a>
            @if(session()->has('user') && session('user')['role'] === 'admin')
                <a href="{{ route('admin.katalog') }}" class="px-4 py-2 rounded-xl {{ request()->routeIs('admin.katalog') ? 'bg-burgundy-50 text-burgundy-500 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">Book Catalog</a>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-xl {{ request()->routeIs('admin.users.index') ? 'bg-burgundy-50 text-burgundy-500 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">User Data</a>
            @else
                <a href="{{ route('katalog') }}" class="px-4 py-2 rounded-xl {{ request()->routeIs('katalog') ? 'bg-burgundy-50 text-burgundy-500 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">Catalog</a>
                <a href="{{ route('search') }}" class="px-4 py-2 rounded-xl {{ request()->routeIs('search') ? 'bg-burgundy-50 text-burgundy-500 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">Search</a>
            @endif
            <a href="{{ route('about') }}" class="px-4 py-2 rounded-xl {{ request()->routeIs('about') ? 'bg-burgundy-50 text-burgundy-500 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">About</a>
        </div>
    </nav>
</header>