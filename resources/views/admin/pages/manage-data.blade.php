@extends('admin.layouts.app')

@section('content')
<div class="py-6 sm:py-10 space-y-8 sm:space-y-12">
    @if(isset($db_error))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-xl shadow-sm animate-fade-down" role="alert">
        <p class="font-bold text-sm">Database Error!</p>
        <p class="text-xs">Gagal terhubung ke database. Pastikan MySQL (XAMPP/Laragon) sudah menyala dan Anda sudah menjalankan <code>php artisan migrate</code>.</p>
        <p class="text-[10px] mt-2 opacity-70 italic">{{ $db_error }}</p>
    </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 animate-fade-up">
        <div>
            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('admin.profile') }}" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors shrink-0" title="Back to Profile">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-2xl sm:text-4xl font-bold text-gray-800">Manage Data</h1>
            </div>
            <p class="text-gray-500 mt-2 ml-10 sm:ml-11 text-sm sm:text-base">Monitor active loans, confirm book returns, and view borrowing reports.</p>
        </div>
    </div>

    <!-- LAPORAN PEMINJAMAN SECTION -->
    <div id="borrowing-report" class="space-y-4 sm:space-y-6 animate-fade-up delay-150">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 sm:gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Borrowing Report</h2>
                <p class="text-gray-600 mt-1 text-xs sm:text-sm font-medium">Summary of digital library book borrowing data</p>
            </div>
            
            <form action="{{ route('admin.manage_data') }}" method="GET" class="flex flex-wrap sm:flex-nowrap gap-2 sm:gap-3 w-full sm:w-auto">
                <select name="bulan" class="flex-grow sm:flex-grow-0 px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-xs sm:text-sm font-bold text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-gray-200 cursor-pointer" onchange="this.form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }))">
                    @forelse($availableMonths as $m)
                        @php
                            $namaBulan = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'][$m->bulan - 1];
                            $value = $m->bulan;
                        @endphp
                        <option value="{{ $value }}" {{ $bulan == $value ? 'selected' : '' }}>
                            {{ $namaBulan }} {{ $m->tahun }}
                        </option>
                    @empty
                        <option value="{{ now()->month }}">{{ ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'][now()->month - 1] }} {{ now()->year }}</option>
                    @endforelse
                </select>
                <!-- Export button -->
                <a target="_blank" href="{{ route('admin.laporan.export', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="px-3 sm:px-4 py-2 bg-white border border-gray-300 text-gray-700 font-bold rounded-lg text-xs sm:text-sm flex items-center justify-center gap-1.5 sm:gap-2 hover:bg-gray-50 transition-colors cursor-pointer shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    Export
                </a>
            </form>
        </div>

        <!-- 4 STAT CARDS -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <!-- Total dipinjam -->
            <div class="bg-[#F9F9F7] rounded-xl p-4 sm:p-5 border border-gray-100">
                <div class="flex items-center gap-2 text-gray-500 mb-2 sm:mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    <span class="text-[11px] sm:text-[13px] font-medium leading-tight">Total Borrowed</span>
                </div>
                <h3 class="text-2xl sm:text-[32px] leading-none font-bold text-gray-900">{{ $totalDipinjam ?? 0 }}</h3>
                <p class="text-[10px] sm:text-[13px] text-gray-500 mt-1 sm:mt-2 font-medium">this month</p>
            </div>
            <!-- Sudah kembali -->
            <div class="bg-[#F9F9F7] rounded-xl p-4 sm:p-5 border border-gray-100">
                <div class="flex items-center gap-2 text-gray-500 mb-2 sm:mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    <span class="text-[11px] sm:text-[13px] font-medium leading-tight">Returned</span>
                </div>
                <h3 class="text-2xl sm:text-[32px] leading-none font-bold text-gray-900">{{ $sudahKembali ?? 0 }}</h3>
                <p class="text-[10px] sm:text-[13px] text-gray-500 mt-1 sm:mt-2 font-medium">confirmed</p>
            </div>
            <!-- Sedang dipinjam -->
            <div class="bg-[#F9F9F7] rounded-xl p-4 sm:p-5 border border-gray-100">
                <div class="flex items-center gap-2 text-gray-500 mb-2 sm:mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="text-[11px] sm:text-[13px] font-medium leading-tight">Borrowed</span>
                </div>
                <h3 class="text-2xl sm:text-[32px] leading-none font-bold text-gray-900">{{ $sedangDipinjam ?? 0 }}</h3>
                <p class="text-[10px] sm:text-[13px] text-gray-500 mt-1 sm:mt-2 font-medium">not returned</p>
            </div>
            <!-- Total denda -->
            <div class="bg-[#F9F9F7] rounded-xl p-4 sm:p-5 border border-gray-100">
                <div class="flex items-center gap-2 text-gray-500 mb-2 sm:mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <span class="text-[11px] sm:text-[13px] font-medium leading-tight">Total Fines</span>
                </div>
                <h3 class="text-xl sm:text-[26px] leading-none font-bold text-gray-900 mt-1 mb-[3px] sm:mb-[5px] whitespace-nowrap">Rp {{ number_format($totalDenda ?? 0, 0, ',', '.') }}</h3>
                <p class="text-[10px] sm:text-[13px] text-gray-500 mt-1 sm:mt-2 font-medium">this month</p>
            </div>
        </div>

        <!-- 3 SECTIONS: BUKU TERPOPULER, ANGGOTA AKTIF, BUKU SEDANG DIPINJAM -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-4">
            
            <!-- Buku Paling Sering Dipinjam -->
            <div class="border border-gray-200 rounded-2xl p-5 sm:p-6 bg-white shadow-sm flex flex-col">
                <h3 class="font-bold text-gray-900 mb-4 sm:mb-6 text-[15px] sm:text-[16px]">Most frequently borrowed books</h3>
                
                <div class="space-y-4">
                    @php
                        $maxBuku = ($bukuTerpopuler ?? collect())->max('total') ?: 1;
                    @endphp
                    @forelse($bukuTerpopuler ?? [] as $bt)
                    <div class="flex items-center justify-between gap-3 sm:gap-4">
                        <span class="text-[12px] sm:text-[13.5px] font-medium text-gray-700 truncate flex-1 min-w-[80px]">{{ $bt->buku?->judul ?? 'Unknown' }}</span>
                        <div class="w-16 sm:w-20 md:flex-1 h-[7px] sm:h-[9px] bg-[#F3F4F6] rounded-full overflow-hidden flex shrink-0">
                            <div class="h-full bg-[#7B6FE3] rounded-full" style="width: {{ ($bt->total / $maxBuku) * 100 }}%"></div>
                        </div>
                        <span class="text-[12px] sm:text-[13.5px] font-medium text-gray-600 w-5 sm:w-6 text-right shrink-0">{{ $bt->total }}x</span>
                    </div>
                    @empty
                    <p class="text-xs sm:text-sm text-gray-500">No book borrowing data available.</p>
                    @endforelse
                </div>
            </div>

            <!-- Anggota Paling Aktif -->
            <div class="border border-gray-200 rounded-2xl p-5 sm:p-6 bg-white shadow-sm flex flex-col">
                <h3 class="font-bold text-gray-900 mb-4 sm:mb-6 text-[15px] sm:text-[16px]">Most active members</h3>
                
                <div class="space-y-4 sm:space-y-5">
                    @forelse($anggotaAktif ?? [] as $aa)
                    <div class="flex items-center gap-3 sm:gap-4">
                        @php
                            $nama = $aa->user?->name ?? $aa->user?->full_name ?? 'U';
                            $initial = strtoupper(substr($nama, 0, 1));
                            $colors = ['bg-[#D3E3F8] text-[#336699]', 'bg-[#E3F2D4] text-[#4F7F2D]', 'bg-[#FAE8CC] text-[#996633]'];
                            $color = $colors[$loop->index % count($colors)];
                        @endphp
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full {{ $color }} flex items-center justify-center font-bold text-xs sm:text-sm shrink-0">
                            {{ $initial }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-[13px] sm:text-[14px] font-bold text-gray-900 truncate">{{ $nama }}</p>
                            <p class="text-[11px] sm:text-[12px] font-medium text-gray-500">{{ $aa->total }} borrows</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs sm:text-sm text-gray-500">No active member data available.</p>
                    @endforelse
                </div>
            </div>

            <!-- Buku Sedang Dipinjam -->
            <div class="border border-gray-200 rounded-2xl p-5 sm:p-6 bg-white shadow-sm flex flex-col">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-gray-900 text-[15px] sm:text-[16px]">Currently Borrowed</h3>
                    <a href="{{ route('admin.manage_data', ['status' => 'dipinjam']) }}" class="text-[10px] sm:text-[11px] font-bold text-burgundy-600 hover:text-maroon bg-red-50 px-2 py-1 rounded-md transition-colors shrink-0">View All &rarr;</a>
                </div>
                
                <div class="space-y-3 sm:space-y-4 overflow-y-auto pr-1 sm:pr-2 custom-scrollbar" style="max-height: 240px;">
                    @forelse($bukuSedangDipinjam ?? [] as $bsd)
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="w-7 sm:w-8 h-9 sm:h-10 bg-gray-100 rounded border border-gray-200 flex items-center justify-center overflow-hidden shrink-0">
                            @if($bsd->buku?->cover)
                                <img src="{{ asset('images/' . $bsd->buku->cover) }}" class="w-full h-full object-cover">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="text-[12px] sm:text-[13.5px] font-bold text-gray-800 line-clamp-1">{{ $bsd->buku?->judul ?? $bsd->snapshot_judul_buku ?? 'Unknown' }}</p>
                            <p class="text-[10px] sm:text-[11px] font-medium text-red-500 mt-0.5">Due: {{ \Carbon\Carbon::parse($bsd->batas_kembali)->format('d M') }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs sm:text-sm text-gray-500">No books currently borrowed.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <!-- ACTIVE LOANS SECTION -->
    <div id="active-loans" class="space-y-4 sm:space-y-6 animate-fade-up delay-200">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 flex items-center gap-2 sm:gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-burgundy-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Active Loans
            </h2>
            <span class="px-3 sm:px-4 py-1.5 rounded-full bg-red-50 text-burgundy-600 font-bold text-xs sm:text-sm self-start sm:self-auto">
                Total: {{ $books->total() }} Books
            </span>
        </div>

        <!-- Search & Filter Bar -->
        <x-ui.glass-card class="p-3 sm:p-4 border-white/60 shadow-md">
            <form action="{{ route('admin.manage_data') }}" method="GET" class="flex flex-col md:flex-row items-center gap-3 sm:gap-4 w-full">
                <!-- Search Input -->
                <div class="relative w-full md:flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by borrower name or book title..." 
                        class="w-full pl-10 pr-4 py-2.5 sm:py-3 bg-white/80 border border-gray-100 rounded-xl sm:rounded-2xl text-xs sm:text-sm font-medium text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-burgundy-500/20 focus:border-burgundy-500 transition-all">
                </div>

                <div class="flex gap-2 sm:gap-3 w-full md:w-auto">
                    <!-- Status Filter -->
                    <div class="relative w-full sm:w-48 md:w-56">
                        <select name="status" onchange="this.form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }))"
                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 bg-white/80 border border-gray-100 rounded-xl sm:rounded-2xl text-xs sm:text-sm font-bold text-gray-600 focus:outline-none focus:ring-2 focus:ring-burgundy-500/20 focus:border-burgundy-500 transition-all appearance-none cursor-pointer">
                            <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Borrowed</option>
                            <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>Returned</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 sm:px-4 text-gray-400">
                            <svg class="fill-current h-3 w-3 sm:h-4 sm:w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                            </svg>
                        </div>
                    </div>
    
                    <!-- Filter Button (Funnel Icon) -->
                    <button type="submit" title="Filter" class="px-3 sm:px-4 py-2.5 sm:py-3 bg-burgundy-500 text-white rounded-xl sm:rounded-2xl text-xs sm:text-sm font-bold shadow-md hover:bg-maroon transition-all transform active:scale-95 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                        </svg>
                        <span class="hidden sm:inline">Filter</span>
                    </button>
                    @if(request()->filled('search') || request('status') !== 'all')
                        <a href="{{ route('admin.manage_data') }}" class="px-4 sm:px-5 py-2.5 sm:py-3 bg-white border border-gray-100 text-gray-500 rounded-xl sm:rounded-2xl text-xs sm:text-sm font-bold hover:bg-gray-50 transition-all transform active:scale-95 flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </x-ui.glass-card>
        
        <!-- Data List / Table -->
        <div class="glass-panel border-white/60 shadow-lg shadow-red-50 rounded-xl sm:rounded-2xl overflow-hidden">
            <!-- Desktop Header -->
            <div class="hidden md:grid grid-cols-12 gap-4 px-6 sm:px-8 py-4 sm:py-5 border-b border-gray-100 bg-gray-50/30 text-[10px] sm:text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                <div class="col-span-4 lg:col-span-3">Book information</div>
                <div class="col-span-3">Borrower</div>
                <div class="col-span-3">Borrow Date &amp; Due Date</div>
                <div class="col-span-1">Fine &amp; Status</div>
                <div class="col-span-1 lg:col-span-2 text-right">Actions</div>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($books ?? [] as $b)
                @php /** @var \App\Models\Peminjaman $b */ @endphp
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 sm:gap-4 px-4 sm:px-6 md:px-8 py-4 sm:py-5 hover:bg-gray-50/50 transition-colors group">
                    
                    <!-- Book Info -->
                    <div class="col-span-1 md:col-span-4 lg:col-span-3 flex items-start sm:items-center gap-3 sm:gap-4">
                        <div class="w-12 h-16 sm:w-12 sm:h-16 bg-gray-100 rounded-md border border-gray-200 flex-shrink-0 flex items-center justify-center text-gray-300 overflow-hidden">
                            @if($b->buku?->cover)
                                <img src="{{ asset('images/' . $b->buku->cover) }}" class="w-full h-full object-cover {{ $b->buku->trashed() ? 'grayscale opacity-60' : '' }}" onerror="this.src='{{ asset('images/readspace-library.png') }}'">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-gray-800 text-sm line-clamp-1 leading-snug">
                                {{ $b->buku?->judul ?? $b->snapshot_judul_buku ?? 'Unknown Book' }}
                                @if(!$b->buku) <span class="text-red-500 text-[10px] ml-1">(Deleted)</span> @endif
                            </h3>
                            <p class="text-[11px] text-gray-400 font-medium mt-0.5 line-clamp-1">
                                {{ $b->buku && $b->buku->penulis->isNotEmpty() ? $b->buku->penulis->pluck('nama_penulis')->implode(', ') : ($b->buku ? 'Unknown Author' : 'Data Removed') }}
                            </p>
                            <!-- Show borrower on mobile ONLY directly under book info -->
                            <div class="md:hidden mt-2 flex items-center gap-1.5">
                                <div class="w-4 h-4 rounded-full bg-burgundy-500 text-white flex items-center justify-center text-[8px] font-bold">
                                    {{ substr($b->user?->name ?? $b->user?->full_name ?? 'U', 0, 1) }}
                                </div>
                                <p class="font-semibold text-gray-600 text-[11px] truncate">{{ $b->user?->name ?? $b->user?->full_name ?? 'Unknown User' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Borrower (Desktop Only) -->
                    <div class="hidden md:flex col-span-3 items-center">
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-full border border-gray-100 bg-white shadow-sm w-fit max-w-full">
                            <div class="w-5 h-5 rounded-full bg-burgundy-500 text-white flex items-center justify-center text-[10px] font-bold shrink-0">
                                {{ substr($b->user?->name ?? $b->user?->full_name ?? 'U', 0, 1) }}
                            </div>
                            <p class="font-semibold text-gray-700 text-[11px] lg:text-xs truncate">{{ $b->user?->name ?? $b->user?->full_name ?? 'Unknown User' }}</p>
                        </div>
                    </div>

                    <!-- Borrow Date & Due Date Mobile Grid -->
                    <div class="col-span-1 md:hidden grid grid-cols-2 gap-2 mt-1 py-2 border-y border-dashed border-gray-100">
                        <div>
                            <span class="text-[9px] font-bold text-gray-400 uppercase block mb-1">Borrow Date</span>
                            <p class="font-bold text-gray-700 text-xs">{{ \Carbon\Carbon::parse($b->tanggal_pinjam)->format('d M Y') }}</p>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-gray-400 uppercase block mb-1">Due Date</span>
                            @php
                                $isPast = \Carbon\Carbon::parse($b->batas_kembali)->isPast();
                                $isReturned = $b->status === 'dikembalikan';
                                $textColor = $isReturned ? 'text-gray-400' : ($isPast ? 'text-red-600' : 'text-gray-700');
                            @endphp
                            <p class="font-bold {{ $textColor }} text-xs">{{ \Carbon\Carbon::parse($b->batas_kembali)->format('d M Y') }}</p>
                        </div>
                        <div class="col-span-2 mt-1">
                            <span class="text-[9px] font-bold text-gray-400 uppercase block mb-1">Fine</span>
                            @php $calculated_denda = $b->calculateDenda(); @endphp
                            @if($calculated_denda > 0)
                                <p class="font-bold text-red-600 text-xs">Rp {{ number_format($calculated_denda, 0, ',', '.') }}</p>
                                @if($b->status === 'dikembalikan' && $b->status_denda === 'lunas')
                                    <span class="text-[9px] font-bold text-green-600">PAID</span>
                                @elseif($b->status === 'dipinjam')
                                    <span class="text-[9px] font-bold text-red-600">UNPAID</span>
                                @endif
                            @else
                                <p class="text-xs text-gray-400 font-medium">No Fines</p>
                            @endif
                        </div>
                    </div>

                    <!-- Borrow Date & Due Date (Desktop Only) -->
                    <div class="hidden md:flex col-span-3 items-center">
                        @php
                            $bIsLate = $b->status === 'dipinjam' && \Carbon\Carbon::parse($b->batas_kembali)->isPast();
                            $bIsReturned = $b->status === 'dikembalikan';
                            $borrowDot  = $bIsReturned ? 'bg-gray-300' : ($bIsLate ? 'bg-red-500' : 'bg-emerald-500');
                            $dueDot     = $bIsReturned ? 'bg-gray-300' : ($bIsLate ? 'bg-red-500 animate-pulse' : 'bg-amber-400');
                            $dueText    = $bIsReturned ? 'text-gray-400' : ($bIsLate ? 'text-red-600 font-extrabold' : 'text-gray-600');
                        @endphp
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-1.5">
                                <span class="text-[9px] font-bold text-gray-400 uppercase w-16">Borrow</span>
                                <span class="w-2 h-2 rounded-full {{ $borrowDot }} shrink-0"></span>
                                <p class="font-bold text-gray-600 text-xs">{{ \Carbon\Carbon::parse($b->tanggal_pinjam)->format('d M Y') }}</p>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[9px] font-bold text-gray-400 uppercase w-16">Due</span>
                                <span class="w-2 h-2 rounded-full {{ $dueDot }} shrink-0"></span>
                                <p class="font-bold {{ $dueText }} text-xs">{{ \Carbon\Carbon::parse($b->batas_kembali)->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Fine & Status (Desktop Only) -->
                    <div class="hidden md:flex col-span-1 flex-col justify-center gap-1">
                        @php
                            $calculated_denda = $b->calculateDenda();
                        @endphp
                        @if($calculated_denda > 0)
                            <p class="font-bold text-red-600 text-[11px] lg:text-xs">Rp {{ number_format($calculated_denda, 0, ',', '.') }}</p>
                            @if($b->status === 'dikembalikan' && $b->status_denda === 'belum_lunas')
                                <form action="{{ route('admin.peminjaman.bayar', $b->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[9px] font-bold text-white bg-red-500 px-2 py-0.5 rounded hover:bg-red-600 transition-colors">
                                        MARK PAID
                                    </button>
                                </form>
                            @elseif($b->status === 'dikembalikan' && $b->status_denda === 'lunas')
                                <span class="text-[9px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded border border-green-100 w-fit">PAID</span>
                            @elseif($b->status === 'dipinjam')
                                <span class="text-[9px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded border border-red-100 w-fit">UNPAID</span>
                            @endif
                        @else
                            <p class="text-[11px] lg:text-xs text-gray-400 font-medium">No Fines</p>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="col-span-1 md:col-span-1 lg:col-span-2 flex items-center justify-end md:justify-end w-full">
                        @if($b->status === 'dipinjam')
                            <div class="w-full md:w-auto flex flex-col gap-1.5">
                            @if(!$b->is_diambil)
                                {{-- STEP 1: Acc Pick Up --}}
                                <button type="button"
                                    onclick="showConfirmModal('confirm-pickup-{{ $b->id }}', false)"
                                    class="text-xs sm:text-[10px] font-bold text-burgundy-600 bg-red-50 hover:bg-red-100 border border-burgundy-200 px-4 py-2 rounded-xl sm:rounded-lg transition-all shadow-sm w-full md:w-auto whitespace-nowrap text-center">
                                    Acc Pick Up
                                </button>
                                <form id="confirm-pickup-{{ $b->id }}" action="{{ route('admin.peminjaman.acc_ambil', $b->id) }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                                {{-- Cancel loan (no fine, book never picked up) --}}
                                @php $bCancelLate = \Carbon\Carbon::parse($b->batas_kembali)->isPast(); @endphp
                                @if($bCancelLate)
                                <button type="button"
                                    onclick="showConfirmModal('cancel-loan-{{ $b->id }}', false, true)"
                                    class="text-[9px] font-bold text-gray-400 hover:text-red-500 underline text-center transition-colors">
                                    Cancel Loan
                                </button>
                                <form id="cancel-loan-{{ $b->id }}" action="{{ route('admin.peminjaman.cancel', $b->id) }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                                @endif
                            @else
                                {{-- STEP 2: Confirm Pick Up (book received by student) → then Return --}}
                                <button type="button"
                                    onclick="showConfirmModal('confirm-return-{{ $b->id }}', false)"
                                    class="text-xs sm:text-[10px] font-bold text-white bg-burgundy-500 hover:bg-maroon px-4 py-2 rounded-xl sm:rounded-lg transition-all shadow-md shadow-red-50 w-full md:w-auto whitespace-nowrap text-center">
                                    Confirm Return
                                </button>
                                <form id="confirm-return-{{ $b->id }}" action="{{ route('admin.peminjaman.acc', $b->id) }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            @endif
                            </div>
                        @else
                            <div class="w-full md:w-auto flex flex-col items-center md:items-end">
                                <span class="text-xs sm:text-[10px] font-bold text-gray-400 bg-gray-50 px-4 py-2.5 sm:py-2 rounded-xl sm:rounded-lg border border-gray-100 uppercase text-center w-full md:w-auto">Returned</span>
                                <!-- Show mark paid button on mobile if needed -->
                                @if($calculated_denda > 0 && $b->status === 'dikembalikan' && $b->status_denda === 'belum_lunas')
                                    <form action="{{ route('admin.peminjaman.bayar', $b->id) }}" method="POST" class="md:hidden mt-2 w-full">
                                        @csrf
                                        <button type="submit" class="text-xs font-bold text-white bg-red-500 hover:bg-red-600 px-4 py-2.5 rounded-xl w-full text-center shadow-sm">
                                            MARK PAID
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>

                </div>
                @empty
                <div class="p-8 sm:p-10 text-center">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4 text-burgundy-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-8 sm:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <p class="text-gray-400 font-medium text-sm">No borrowing records found.</p>
                </div>
                @endforelse
            </div>
        </div>

        @if(isset($books) && method_exists($books, 'links'))
            <div class="mt-6 sm:mt-8 flex justify-center text-gray-700">
                {{ $books->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Custom Confirm Modal -->
<div id="custom-confirm-modal" class="fixed inset-0 z-[999] flex items-center justify-center bg-black/40 backdrop-blur-sm hidden">
    <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 w-full max-w-sm mx-4 animate-fade-up">
        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-50 mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-burgundy-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h3 id="modal-title" class="text-base font-bold text-gray-800 text-center mb-2">Confirm Action</h3>
        <p id="modal-message" class="text-sm text-gray-500 text-center mb-6">Are you sure you want to proceed?</p>
        <div class="flex gap-3">
            <button onclick="cancelConfirmModal()" class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-bold text-sm hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <button id="modal-confirm-btn" onclick="submitConfirmModal()" class="flex-1 px-4 py-2.5 rounded-xl bg-burgundy-500 text-white font-bold text-sm hover:bg-maroon transition-colors shadow-md">
                Confirm
            </button>
        </div>
    </div>
</div>

<script>
    let pendingFormId = null;

    function showConfirmModal(formId, isPickup, isCancel) {
        pendingFormId = formId;
        isCancel = isCancel || false;
        isPickup = formId.startsWith('confirm-pickup-');

        const title   = isCancel ? 'Cancel Loan?' : (isPickup ? 'Confirm Book Pick Up' : 'Confirm Book Return');
        const message = isCancel
            ? 'This loan will be cancelled. Since the book was never picked up, no fine will be charged.'
            : (isPickup
                ? 'Confirm that the book has been picked up by the student?'
                : 'Confirm that this book has been returned?');

        document.getElementById('modal-title').textContent   = title;
        document.getElementById('modal-message').textContent = message;

        const confirmBtn = document.getElementById('modal-confirm-btn');
        if (isCancel) {
            confirmBtn.className = 'flex-1 px-4 py-2.5 rounded-xl bg-red-500 text-white font-bold text-sm hover:bg-red-600 transition-colors shadow-md';
            confirmBtn.textContent = 'Yes, Cancel';
        } else {
            confirmBtn.className = 'flex-1 px-4 py-2.5 rounded-xl bg-burgundy-500 text-white font-bold text-sm hover:bg-maroon transition-colors shadow-md';
            confirmBtn.textContent = 'Confirm';
        }

        document.getElementById('custom-confirm-modal').classList.remove('hidden');
    }

    function cancelConfirmModal() {
        pendingFormId = null;
        document.getElementById('custom-confirm-modal').classList.add('hidden');
    }

    function submitConfirmModal() {
        if (pendingFormId) {
            document.getElementById(pendingFormId).submit();
        }
        cancelConfirmModal();
    }

    // Close modal when clicking backdrop
    document.getElementById('custom-confirm-modal').addEventListener('click', function(e) {
        if (e.target === this) cancelConfirmModal();
    });

    // Auto-scroll to hash section on page load
    document.addEventListener('DOMContentLoaded', function() {
        const hash = window.location.hash;
        if (hash) {
            const target = document.querySelector(hash);
            if (target) {
                setTimeout(() => {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 400);
            }
        }
    });
</script>
@endsection
