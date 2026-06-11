@extends('admin.layouts.app')

@section('content')
<div class="py-10 space-y-12">
    @if(isset($db_error))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-xl shadow-sm animate-fade-down" role="alert">
        <p class="font-bold text-sm">Database Error!</p>
        <p class="text-xs">Gagal terhubung ke database. Pastikan MySQL (XAMPP/Laragon) sudah menyala dan Anda sudah menjalankan <code>php artisan migrate</code>.</p>
        <p class="text-[10px] mt-2 opacity-70 italic">{{ $db_error }}</p>
    </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 animate-fade-up">
        <div>
            <h1 class="text-4xl font-bold text-gray-800">Admin Profile</h1>
            <p class="text-gray-500 mt-2">Manage library information and monitor borrowed books.</p>
        </div>
    </div>

    <div class="glass-panel p-8 border-white/60 animate-fade-up delay-100 shadow-2xl shadow-red-50 flex flex-col md:flex-row items-center gap-8 border-l-4 border-l-maroon">
        <div class="w-24 h-24 rounded-full bg-maroon text-white flex items-center justify-center text-4xl font-bold shadow-xl shadow-red-100">
            A
        </div>
        <div class="text-center md:text-left flex-1">
            <h2 class="text-2xl font-bold text-gray-800">Admin ReadSpace</h2>
            <p class="text-gray-500 font-medium">admin@polibatam.ac.id</p>
            <div class="mt-4 inline-block px-4 py-1.5 rounded-lg bg-burgundy-50 text-burgundy-600 border border-burgundy-100 text-xs font-bold uppercase tracking-widest">
                Main Administrator 
            </div>
        </div>
            <div class="w-full md:w-auto flex flex-col sm:flex-row gap-3">
            <a href="{{ route('admin.users.index') }}" class="w-full md:w-auto px-6 py-3 rounded-xl bg-burgundy-500 text-white font-bold hover:bg-maroon transition-colors text-sm text-center shadow-lg shadow-red-100">
                Manage Users
            </a>
            <a href="{{ route('admin.buku.create') }}" class="w-full md:w-auto px-6 py-3 rounded-xl border-2 border-burgundy-500 text-burgundy-600 font-bold hover:bg-red-50 transition-colors text-sm text-center">
                + Add New Book
            </a>
        </div>
    </div>

    <!-- LAPORAN PEMINJAMAN SECTION -->
    <div class="space-y-6 animate-fade-up delay-150">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Borrowing Report</h2>
                <p class="text-gray-600 mt-1 text-sm font-medium">Summary of digital library book borrowing data</p>
            </div>
            
            <form action="{{ route('admin.profile') }}" method="GET" class="flex gap-3">
                <select name="bulan" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-bold text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-gray-200 cursor-pointer" onchange="this.form.submit()">
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
                <a target="_blank" href="{{ route('admin.laporan.export', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-bold rounded-lg text-sm flex items-center gap-2 hover:bg-gray-50 transition-colors cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    Export
                </a>
            </form>
        </div>

        <!-- 4 STAT CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Total dipinjam -->
            <div class="bg-[#F9F9F7] rounded-xl p-5 border border-gray-100">
                <div class="flex items-center gap-2 text-gray-500 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    <span class="text-[13px] font-medium">Total Borrowed</span>
                </div>
                <h3 class="text-[32px] leading-none font-bold text-gray-900">{{ $totalDipinjam ?? 0 }}</h3>
                <p class="text-[13px] text-gray-500 mt-2 font-medium">this month</p>
            </div>
            <!-- Sudah kembali -->
            <div class="bg-[#F9F9F7] rounded-xl p-5 border border-gray-100">
                <div class="flex items-center gap-2 text-gray-500 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    <span class="text-[13px] font-medium">Returned</span>
                </div>
                <h3 class="text-[32px] leading-none font-bold text-gray-900">{{ $sudahKembali ?? 0 }}</h3>
                <p class="text-[13px] text-gray-500 mt-2 font-medium">confirmed</p>
            </div>
            <!-- Sedang dipinjam -->
            <div class="bg-[#F9F9F7] rounded-xl p-5 border border-gray-100">
                <div class="flex items-center gap-2 text-gray-500 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="text-[13px] font-medium">Currently Borrowed</span>
                </div>
                <h3 class="text-[32px] leading-none font-bold text-gray-900">{{ $sedangDipinjam ?? 0 }}</h3>
                <p class="text-[13px] text-gray-500 mt-2 font-medium">not returned</p>
            </div>
            <!-- Total denda -->
            <div class="bg-[#F9F9F7] rounded-xl p-5 border border-gray-100">
                <div class="flex items-center gap-2 text-gray-500 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <span class="text-[13px] font-medium">Total Fines</span>
                </div>
                <h3 class="text-[26px] leading-none font-bold text-gray-900 mt-1 mb-[5px]">Rp {{ number_format($totalDenda ?? 0, 0, ',', '.') }}</h3>
                <p class="text-[13px] text-gray-500 mt-2 font-medium">this month</p>
            </div>
        </div>

        <!-- 2 SECTIONS: BUKU TERPOPULER & ANGGOTA AKTIF -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            <!-- Buku Paling Sering Dipinjam -->
            <div class="border border-gray-200 rounded-2xl p-6 bg-white shadow-sm">
                <h3 class="font-bold text-gray-900 mb-6 text-[16px]">Most frequently borrowed books</h3>
                
                <div class="space-y-4">
                    @php
                        $maxBuku = ($bukuTerpopuler ?? collect())->max('total') ?: 1;
                    @endphp
                    @forelse($bukuTerpopuler ?? [] as $bt)
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-[13.5px] font-medium text-gray-700 truncate w-32">{{ $bt->buku?->judul ?? 'Unknown' }}</span>
                        <div class="flex-1 h-[9px] bg-[#F3F4F6] rounded-full overflow-hidden flex">
                            <div class="h-full bg-[#7B6FE3] rounded-full" style="width: {{ ($bt->total / $maxBuku) * 100 }}%"></div>
                        </div>
                        <span class="text-[13.5px] font-medium text-gray-600 w-6 text-right">{{ $bt->total }}x</span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500">No book borrowing data available.</p>
                    @endforelse
                </div>
            </div>

            <!-- Anggota Paling Aktif -->
            <div class="border border-gray-200 rounded-2xl p-6 bg-white shadow-sm">
                <h3 class="font-bold text-gray-900 mb-6 text-[16px]">Most active members</h3>
                
                <div class="space-y-5">
                    @forelse($anggotaAktif ?? [] as $aa)
                    <div class="flex items-center gap-4">
                        @php
                            $nama = $aa->user?->name ?? $aa->user?->full_name ?? 'U';
                            $initial = strtoupper(substr($nama, 0, 1));
                            $colors = ['bg-[#D3E3F8] text-[#336699]', 'bg-[#E3F2D4] text-[#4F7F2D]', 'bg-[#FAE8CC] text-[#996633]'];
                            $color = $colors[$loop->index % count($colors)];
                        @endphp
                        <div class="w-10 h-10 rounded-full {{ $color }} flex items-center justify-center font-bold text-sm">
                            {{ $initial }}
                        </div>
                        <div>
                            <p class="text-[14px] font-bold text-gray-900">{{ $nama }}</p>
                            <p class="text-[12px] font-medium text-gray-500">{{ $aa->total }} borrows</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500">No active member data available.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <div class="space-y-6 animate-fade-up delay-200">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-burgundy-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Active Loans
            </h2>
            <span class="px-4 py-1.5 rounded-full bg-red-50 text-burgundy-600 font-bold text-sm self-start sm:self-auto">
                Total: {{ $books->total() }} Books
            </span>
        </div>

        <!-- Search & Filter Bar -->
        <x-ui.glass-card class="p-4 border-white/60 shadow-md">
            <form action="{{ route('admin.profile') }}" method="GET" class="flex flex-col md:flex-row items-center gap-4">
                <!-- Search Input -->
                <div class="relative w-full md:flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by borrower name or book title..." 
                        class="w-full pl-11 pr-4 py-3 bg-white/80 border border-gray-100 rounded-2xl text-sm font-medium text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-burgundy-500/20 focus:border-burgundy-500 transition-all">
                </div>

                <!-- Status Filter -->
                <div class="relative w-full md:w-56">
                    <select name="status" onchange="this.form.submit()" 
                        class="w-full px-4 py-3 bg-white/80 border border-gray-100 rounded-2xl text-sm font-bold text-gray-600 focus:outline-none focus:ring-2 focus:ring-burgundy-500/20 focus:border-burgundy-500 transition-all appearance-none cursor-pointer">
                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Borrowed (Dipinjam)</option>
                        <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>Returned (Dikembalikan)</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                        </svg>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <button type="submit" class="flex-1 md:flex-initial px-6 py-3 bg-burgundy-500 text-white rounded-2xl text-sm font-bold shadow-md hover:bg-maroon transition-all transform active:scale-95 whitespace-nowrap">
                        Search
                    </button>
                    @if(request()->filled('search') || request('status') !== 'all')
                        <a href="{{ route('admin.profile') }}" class="px-5 py-3 bg-white border border-gray-100 text-gray-500 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-all transform active:scale-95 flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </x-ui.glass-card>
        
        <div class="glass-panel border-white/60 shadow-lg shadow-red-50 rounded-2xl overflow-hidden">
                <div class="hidden md:grid grid-cols-12 gap-4 px-8 py-5 border-b border-gray-100 bg-gray-50/30 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                <div class="col-span-3">Book information</div>
                <div class="col-span-3">Borrower</div>
                <div class="col-span-2">Due Date</div>
                <div class="col-span-2">Fine & Status</div>
                <div class="col-span-2 text-right">Actions</div>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($books ?? [] as $b)
                @php /** @var \App\Models\Peminjaman $b */ @endphp
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center px-8 py-5 hover:bg-gray-50/50 transition-colors group">
                    
                    <div class="col-span-3 flex items-center gap-4">
                        <div class="w-12 h-16 bg-gray-100 rounded-md border border-gray-200 flex-shrink-0 flex items-center justify-center text-gray-300 overflow-hidden">
                            @if($b->buku?->cover)
                                <img src="{{ asset('images/' . $b->buku->cover) }}" class="w-full h-full object-cover {{ $b->buku->trashed() ? 'grayscale opacity-60' : '' }}" onerror="this.src='{{ asset('images/readspace-library.png') }}'">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 text-sm line-clamp-1">{{ $b->buku?->judul ?? 'Unknown Book' }}</h3>
                            <p class="text-[10px] text-gray-400 font-medium mt-0.5">{{ $b->buku?->penulis?->nama_penulis ?? 'Unknown Author' }}</p>
                        </div>
                    </div>

                        <div class="col-span-3 flex flex-col md:flex-row md:items-center gap-2 mt-2 md:mt-0">
                        <span class="md:hidden text-[10px] font-bold text-gray-400 uppercase">Borrower:</span>
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-full border border-gray-100 w-fit bg-white shadow-sm">
                            <div class="w-5 h-5 rounded-full bg-burgundy-500 text-white flex items-center justify-center text-[10px] font-bold">
                                {{ substr($b->user?->name ?? $b->user?->full_name ?? 'U', 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-700 text-xs">{{ $b->user?->name ?? $b->user?->full_name ?? 'Unknown User' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-2 flex flex-col md:flex-row md:items-center gap-2 mt-2 md:mt-0">
                        <span class="md:hidden text-[10px] font-bold text-gray-400 uppercase">Due Date:</span>
                        @php
                            $isPast = \Carbon\Carbon::parse($b->batas_kembali)->isPast();
                            $isReturned = $b->status === 'dikembalikan';
                            $dotColor = $isReturned ? 'bg-gray-400' : ($isPast ? 'bg-red-500 animate-pulse' : 'bg-green-500');
                            $textColor = $isReturned ? 'text-gray-400' : ($isPast ? 'text-red-600' : 'text-gray-600');
                        @endphp
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full {{ $dotColor }}"></span>
                            <p class="font-bold {{ $textColor }} text-xs">{{ \Carbon\Carbon::parse($b->batas_kembali)->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="col-span-2 flex flex-col justify-center gap-1 mt-2 md:mt-0">
                        <span class="md:hidden text-[10px] font-bold text-gray-400 uppercase">Fine & Status:</span>
                        @php
                            $calculated_denda = $b->calculateDenda();
                        @endphp
                        @if($calculated_denda > 0)
                            <p class="font-bold text-red-600 text-xs">Rp {{ number_format($calculated_denda, 0, ',', '.') }}</p>
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
                            <p class="text-xs text-gray-400 font-medium">No Fines</p>
                        @endif
                    </div>

                    <div class="col-span-2 flex flex-col md:flex-row md:justify-end gap-2 mt-4 md:mt-0 border-t md:border-t-0 border-gray-100 pt-3 md:pt-0">
                        @if($b->status === 'dipinjam')
                            @if(!$b->is_diambil)
                                <form action="{{ route('admin.peminjaman.acc_ambil', $b->id) }}" method="POST" onsubmit="return confirm('Confirm book has been picked up by student?')">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-bold text-burgundy-600 bg-red-50 hover:bg-red-100 border border-burgundy-200 px-3 py-2 rounded-lg transition-all shadow-sm w-full md:w-auto whitespace-nowrap">
                                        Acc Pick Up
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.peminjaman.acc', $b->id) }}" method="POST" onsubmit="return confirm('Confirm this book has been returned?')">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-bold text-white bg-burgundy-500 hover:bg-maroon px-3 py-2 rounded-lg transition-all shadow-md shadow-red-50 w-full md:w-auto whitespace-nowrap">
                                        Confirm Return
                                    </button>
                                </form>
                            @endif
                        @else
                            <span class="text-[10px] font-bold text-gray-400 bg-gray-50 px-3 py-2 rounded-lg border border-gray-100 uppercase text-center md:text-left">Returned</span>
                        @endif
                    </div>

                </div>
                @empty
                <div class="p-10 text-center">
                    <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 text-burgundy-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <p class="text-gray-400 font-medium">No borrowing records found.</p>
                </div>
                @endforelse
            </div>
        </div>

        @if(isset($books) && method_exists($books, 'links'))
            <div class="mt-6 flex justify-center text-gray-700">
                {{ $books->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
