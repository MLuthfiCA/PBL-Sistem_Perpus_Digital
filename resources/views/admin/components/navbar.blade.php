<header class="fixed top-0 left-0 right-0 z-50 px-4 py-4 pointer-events-none" x-data="{ mobileMenuOpen: false }">
    <nav class="max-w-7xl mx-auto glass-panel px-8 py-4 flex items-center justify-between shadow-2xl shadow-red-100 pointer-events-auto border-white/60 relative">
        
        <!-- Logo Section -->
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/readspace-library.png') }}" alt="ReadSpace Logo" class="h-10 w-auto">
            <span class="font-bold text-xl text-gray-800 tracking-tight hidden sm:block">ReadSpace <span class="text-burgundy-500">Admin</span></span>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center gap-1">
            <a href="{{ route('admin.katalog') }}" class="px-5 py-2 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.katalog') ? 'bg-burgundy-500 text-white shadow-lg shadow-red-100' : 'text-gray-500 hover:text-burgundy-500 hover:bg-white/80' }} font-medium text-sm">
                Dashboard
            </a>
            <a href="{{ route('admin.search') }}" class="px-5 py-2 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.search') ? 'bg-burgundy-500 text-white shadow-lg shadow-red-100' : 'text-gray-500 hover:text-burgundy-500 hover:bg-white/80' }} font-medium text-sm">
                Search
            </a>
            <a href="{{ route('admin.buku.create') }}" class="px-5 py-2 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.buku.create') ? 'bg-burgundy-500 text-white shadow-lg shadow-red-100' : 'text-gray-500 hover:text-burgundy-500 hover:bg-white/80' }} font-medium text-sm">
                Add Book
            </a>
            <div class="relative group">
                <a href="#" class="px-5 py-2 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.kategori.*', 'admin.penulis.*', 'admin.penerbit.*') ? 'bg-burgundy-500 text-white shadow-lg shadow-red-100' : 'text-gray-500 hover:text-burgundy-500 hover:bg-white/80' }} font-medium text-sm flex items-center gap-1">
                    Categories
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </a>
                <div class="absolute left-0 top-full mt-2 w-48 bg-white/90 backdrop-blur-2xl border border-white/60 rounded-2xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 overflow-hidden">
                    <ul class="py-2 text-sm text-gray-700">
                        <li><a href="{{ route('admin.kategori.index') }}" class="block px-4 py-2 hover:bg-red-50 hover:text-burgundy-500 transition-colors font-medium">Genres / Categories</a></li>
                        <li><a href="{{ route('admin.penulis.index') }}" class="block px-4 py-2 hover:bg-red-50 hover:text-burgundy-500 transition-colors font-medium">Authors (Penulis)</a></li>
                        <li><a href="{{ route('admin.penerbit.index') }}" class="block px-4 py-2 hover:bg-red-50 hover:text-burgundy-500 transition-colors font-medium">Publishers (Penerbit)</a></li>
                    </ul>
                </div>
            </div>
            <a href="{{ route('admin.users.index') }}" class="px-5 py-2 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.users.index') ? 'bg-burgundy-500 text-white shadow-lg shadow-red-100' : 'text-gray-500 hover:text-burgundy-500 hover:bg-white/80' }} font-medium text-sm">
                User Data
            </a>
        </div>

        <!-- Action Section -->
        <div class="flex items-center gap-2 sm:gap-4">
            <div class="flex items-center gap-4 p-1 pl-4 bg-white/60 rounded-2xl border border-white/80">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-gray-800 leading-none">{{ session('user')['name'] ?? 'Admin' }}</p>
                    <p class="text-[9px] text-burgundy-500 mt-1 uppercase font-bold tracking-widest">Administrator</p>
                </div>
                <button id="dropdownAdminButton" data-dropdown-toggle="dropdownAdmin" class="w-9 h-9 rounded-xl bg-burgundy-500 text-white flex items-center justify-center font-bold text-sm shadow-md transition-transform hover:scale-105">
                    {{ substr(session('user')['name'] ?? 'A', 0, 1) }}
                </button>
            </div>

            <!-- Dropdown menu -->
            <div id="dropdownAdmin" class="z-50 hidden bg-white/90 backdrop-blur-2xl border border-white/60 divide-y divide-gray-100 rounded-2xl shadow-2xl w-44">
                <ul class="py-2 text-sm text-gray-700">
                    <li><a href="{{ route('admin.profile') }}" class="block px-4 py-2 hover:bg-red-50 hover:text-burgundy-500 transition-colors font-medium">Admin Profile</a></li>
                    <li><a href="{{ route('admin.manage_data') }}" class="block px-4 py-2 hover:bg-red-50 hover:text-burgundy-500 transition-colors font-medium">Manage Data</a></li>
                </ul>
                <div class="py-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-500 font-bold hover:bg-red-50 transition-colors">Log out</button>
                    </form>
                </div>
            </div>

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
            
            <a href="{{ route('admin.katalog') }}" class="px-4 py-2 rounded-xl {{ request()->routeIs('admin.katalog') ? 'bg-burgundy-50 text-burgundy-500 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">Dashboard</a>
            <a href="{{ route('admin.search') }}" class="px-4 py-2 rounded-xl {{ request()->routeIs('admin.search') ? 'bg-burgundy-50 text-burgundy-500 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">Search</a>
            <a href="{{ route('admin.buku.create') }}" class="px-4 py-2 rounded-xl {{ request()->routeIs('admin.buku.create') ? 'bg-burgundy-50 text-burgundy-500 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">Add Book</a>
            <div class="px-4 py-2 font-bold text-gray-800 border-t border-gray-100 mt-2 pt-3">Categories</div>
            <div class="flex flex-col pl-4 gap-1 border-l-2 border-gray-100 ml-4">
                <a href="{{ route('admin.kategori.index') }}" class="py-2 text-sm text-gray-600 hover:text-burgundy-500">Genres / Categories</a>
                <a href="{{ route('admin.penulis.index') }}" class="py-2 text-sm text-gray-600 hover:text-burgundy-500">Authors (Penulis)</a>
                <a href="{{ route('admin.penerbit.index') }}" class="py-2 text-sm text-gray-600 hover:text-burgundy-500">Publishers (Penerbit)</a>
            </div>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 mt-2 border-t border-gray-100 pt-3 rounded-xl {{ request()->routeIs('admin.users.index') ? 'bg-burgundy-50 text-burgundy-500 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">User Data</a>
        </div>
    </nav>
</header>
