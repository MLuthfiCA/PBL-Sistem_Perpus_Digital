<header x-data="{ mobileMenuOpen: false }" class="fixed top-0 left-0 right-0 z-50 px-4 py-4 pointer-events-none">
    <nav class="max-w-7xl mx-auto glass-panel px-8 py-4 flex items-center justify-between shadow-2xl shadow-red-100 pointer-events-auto border-white/60 relative">
        
        <!-- Logo Section -->
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/readspace-library.png') }}" alt="ReadSpace Logo" class="h-10 w-auto">
            <span class="font-bold text-xl text-gray-800 tracking-tight">ReadSpace</span>
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
                <a href="{{ route('admin.users') }}" class="px-5 py-2 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.users') ? 'bg-burgundy-500 text-white shadow-lg shadow-red-100' : 'text-gray-500 hover:text-burgundy-500 hover:bg-white/80' }} font-medium text-sm">
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
        <div class="flex items-center gap-4">
            @if(session()->has('user'))
            <div class="flex items-center gap-4 p-1 pl-4 bg-white/60 rounded-2xl border border-white/80 hidden sm:flex">
                <div class="text-right">
                    <p class="text-xs font-bold text-gray-800 leading-none">{{ session('user')['name'] }}</p>
                    <p class="text-[9px] text-gray-400 mt-1 uppercase font-bold tracking-widest">{{ session('user')['role'] }}</p>
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
            <div class="hidden sm:flex items-center gap-2">
                <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl bg-burgundy-500 text-white text-sm font-bold shadow-lg shadow-red-100 hover:bg-burgundy-600 transition-all hover:-translate-y-0.5">Log In</a>
            </div>
            @endif

            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-gray-500 p-2 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>

        <!-- Mobile Navigation Menu -->
        <div x-show="mobileMenuOpen" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             @click.away="mobileMenuOpen = false"
             class="absolute top-full left-0 right-0 mt-4 p-4 bg-white/95 backdrop-blur-2xl border border-white/60 rounded-2xl shadow-2xl flex flex-col gap-2 md:hidden pointer-events-auto">
             
            <a href="{{ route('home') }}" class="px-5 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('home') ? 'bg-burgundy-50 text-burgundy-600 font-bold' : 'text-gray-600 hover:bg-gray-50' }} font-medium text-sm">
                Home
            </a>
            @if(session()->has('user') && session('user')['role'] === 'admin')
                <a href="{{ route('admin.katalog') }}" class="px-5 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.katalog') ? 'bg-burgundy-50 text-burgundy-600 font-bold' : 'text-gray-600 hover:bg-gray-50' }} font-medium text-sm">
                    Book Catalog
                </a>
                <a href="{{ route('admin.users') }}" class="px-5 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.users') ? 'bg-burgundy-50 text-burgundy-600 font-bold' : 'text-gray-600 hover:bg-gray-50' }} font-medium text-sm">
                    User Data
                </a>
            @else
                <a href="{{ route('katalog') }}" class="px-5 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('katalog') ? 'bg-burgundy-50 text-burgundy-600 font-bold' : 'text-gray-600 hover:bg-gray-50' }} font-medium text-sm">
                    Catalog
                </a>
                <a href="{{ route('search') }}" class="px-5 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('search') ? 'bg-burgundy-50 text-burgundy-600 font-bold' : 'text-gray-600 hover:bg-gray-50' }} font-medium text-sm">
                    Search
                </a>
            @endif
            <a href="{{ route('about') }}" class="px-5 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('about') ? 'bg-burgundy-50 text-burgundy-600 font-bold' : 'text-gray-600 hover:bg-gray-50' }} font-medium text-sm">
                About
            </a>

            @if(!session()->has('user'))
            <div class="mt-4 pt-4 border-t border-gray-100 flex flex-col gap-3">
                <a href="{{ route('login') }}" class="text-center w-full px-5 py-3 rounded-xl bg-burgundy-500 text-white font-bold shadow-lg shadow-red-100 hover:bg-burgundy-600 transition-all">Log In</a>
            </div>
            @else
            <div class="mt-4 pt-4 border-t border-gray-100 flex flex-col gap-3">
                 <div class="flex items-center gap-3 px-5 py-2">
                    <div class="w-10 h-10 rounded-xl bg-burgundy-500 text-white flex items-center justify-center font-bold text-lg shadow-md">
                        {{ substr(session('user')['name'], 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800">{{ session('user')['name'] }}</p>
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">{{ session('user')['role'] }}</p>
                    </div>
                 </div>
                 @if(session('user')['role'] === 'admin')
                     <a href="{{ route('admin.profile') }}" class="px-5 py-3 rounded-xl text-gray-600 font-bold hover:bg-gray-50 transition-colors">Admin Profile</a>
                 @else
                     <a href="{{ route('profile') }}" class="px-5 py-3 rounded-xl text-gray-600 font-bold hover:bg-gray-50 transition-colors">My Profile</a>
                 @endif
                 <form method="POST" action="{{ route('logout') }}" class="w-full">
                     @csrf
                     <button type="submit" class="w-full text-left px-5 py-3 rounded-xl text-red-500 font-bold hover:bg-red-50 transition-colors">Log Out</button>
                 </form>
            </div>
            @endif
        </div>
    </nav>
</header>
