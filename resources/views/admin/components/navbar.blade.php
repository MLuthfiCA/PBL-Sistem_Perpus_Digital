<header class="fixed top-0 left-0 right-0 z-50 px-4 py-4 pointer-events-none">
    <nav class="max-w-7xl mx-auto glass-panel px-8 py-4 flex items-center justify-between shadow-2xl shadow-red-100 pointer-events-auto border-white/60">
        
        <!-- Logo Section -->
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/readspace-library.png') }}" alt="ReadSpace Logo" class="h-10 w-auto">
            <span class="font-bold text-xl text-gray-800 tracking-tight">ReadSpace <span class="text-burgundy-500">Admin</span></span>
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
            <a href="{{ route('admin.kategori.index') }}" class="px-5 py-2 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.kategori.*') ? 'bg-burgundy-500 text-white shadow-lg shadow-red-100' : 'text-gray-500 hover:text-burgundy-500 hover:bg-white/80' }} font-medium text-sm">
                Categories
            </a>
            <a href="{{ route('admin.users.index') }}" class="px-5 py-2 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.users.index') ? 'bg-burgundy-500 text-white shadow-lg shadow-red-100' : 'text-gray-500 hover:text-burgundy-500 hover:bg-white/80' }} font-medium text-sm">
                User Data
            </a>
        </div>

        <!-- Action Section -->
        <div class="flex items-center gap-4">
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
                </ul>
                <div class="py-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-500 font-bold hover:bg-red-50 transition-colors">Log out</button>
                    </form>
                </div>
            </div>

            <button class="md:hidden text-gray-500 p-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>
    </nav>
</header>
