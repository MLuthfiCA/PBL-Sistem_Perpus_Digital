@extends('user.layouts.app')

@section('content')
<div class="py-6 sm:py-10 space-y-8 sm:space-y-12">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 animate-fade-up">
        <div>
            <h1 class="text-2xl sm:text-4xl font-bold text-gray-800">My Profile</h1>
            <p class="text-gray-500 mt-2 text-sm sm:text-base">Manage account information and monitor your literacy activities.</p>
        </div>
    </div>

    <!-- User Info Card -->
    <div class="glass-panel p-5 sm:p-8 border-white/60 animate-fade-up delay-100 shadow-2xl shadow-red-50 flex flex-col sm:flex-row items-center gap-5 sm:gap-8">
        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gradient-to-tr from-burgundy-500 to-maroon text-white flex items-center justify-center text-3xl sm:text-4xl font-bold shadow-xl shadow-red-100 flex-shrink-0">
            {{ substr(session('user.name', 'User'), 0, 1) }}
        </div>
        <div class="text-center sm:text-left flex-1">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800">{{ session('user.name', 'Nama User') }}</h2>
            <p class="text-gray-500 font-medium text-sm sm:text-base">{{ session('user.email', 'email@student.polibatam.ac.id') }}</p>
            <div class="mt-3 inline-block px-3 sm:px-4 py-1.5 rounded-lg bg-burgundy-50 text-burgundy-600 border border-burgundy-100 text-xs font-bold uppercase tracking-widest">
                @php
                    $roleRaw = session('user.role', 'Student');
                    $roleDisplay = strtolower($roleRaw) === 'mahasiswa' ? 'Student' : $roleRaw;
                @endphp
                {{ $roleDisplay }}
            </div>
        </div>
    </div>

    @php
        $hasLateBook = false;
        $hasUnpaidFine = false;
        $totalPeminjamanAktif = $peminjaman ?? collect([]);
        foreach($totalPeminjamanAktif as $px) {
            if ($px->isOverdue()) {
                $hasLateBook = true;
            }
        }
        $totalPengembalian = $pengembalian ?? collect([]);
        foreach($totalPengembalian as $py) {
            if ($py->status_denda === 'belum_lunas') {
                $hasUnpaidFine = true;
            }
        }
    @endphp

    @if($hasLateBook || $hasUnpaidFine)
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 sm:p-5 shadow-sm animate-fade-down flex items-start gap-3 sm:gap-4 border-l-4 border-l-red-500">
        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0 mt-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        </div>
        <div>
            <h3 class="font-bold text-red-800 text-base sm:text-lg">Account Warning!</h3>
            <p class="text-red-600 text-xs sm:text-sm mt-1">
                @if($hasLateBook && $hasUnpaidFine)
                    You have overdue books and unpaid fines.
                @elseif($hasLateBook)
                    You have books that have exceeded the return deadline. Please return them immediately!
                @elseif($hasUnpaidFine)
                    You have unpaid late fines. Please contact the library administrator to settle your fines.
                @endif
                <br><strong class="mt-1 sm:mt-2 block">You cannot borrow new books until this issue is resolved.</strong>
            </p>
        </div>
    </div>
    @endif

    <!-- Currently Borrowed -->
    <div class="space-y-4 sm:space-y-6 animate-fade-up delay-200">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 sm:h-6 w-5 sm:w-6 text-burgundy-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Currently Borrowed
        </h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @forelse($peminjaman ?? [] as $p)
            @php
                /** @var \App\Models\Peminjaman $p */
                $calc_denda = $p->calculateDenda();
                $overdueDays = $p->calculateOverdueDays();
                $isLate = $overdueDays > 0;
                $batasKembali = $p->batas_kembali;
                $isPast = $batasKembali && \Carbon\Carbon::parse($batasKembali)->isPast();
                // Circle colors: both red if overdue, else green for borrow, yellow for due
                $circleGreen  = $isLate ? 'bg-red-500 shadow-red-200' : 'bg-emerald-500 shadow-emerald-200';
                $circleYellow = $isLate ? 'bg-red-500 shadow-red-200' : 'bg-amber-400 shadow-amber-200';
                $modalId = 'loan-modal-' . $p->id_peminjaman;
            @endphp
            <!-- Card: click to open detail modal -->
            <div onclick="document.getElementById('{{ $modalId }}').classList.remove('hidden'); document.getElementById('{{ $modalId }}').classList.add('flex')"
                 class="glass-panel p-4 sm:p-5 border-white/60 shadow-lg shadow-red-50 hover:shadow-xl hover:-translate-y-0.5 transition-all group border-l-4 {{ $isLate ? 'border-l-red-500' : 'border-l-burgundy-500' }} cursor-pointer select-none">
                <!-- Book title -->
                <div class="flex items-start justify-between gap-2 mb-3">
                    <div class="min-w-0">
                        <h3 class="font-bold text-gray-800 text-sm sm:text-base leading-snug line-clamp-2">
                            {{ $p->buku?->judul ?? $p->snapshot_judul_buku ?? 'Unknown Book' }}
                            @if(!$p->buku) <span class="text-red-500 text-[10px] ml-1">(Deleted)</span> @endif
                        </h3>
                        <p class="text-[10px] text-gray-400 font-medium mt-0.5">Book ID: #{{ $p->buku?->id_buku ?? 'N/A' }}</p>
                    </div>
                    @if($isLate)
                    <span class="flex-shrink-0 px-2 py-0.5 rounded-full bg-red-100 text-red-600 text-[9px] font-bold uppercase tracking-widest border border-red-200">OVERDUE</span>
                    @elseif($p->is_diambil)
                    <span class="flex-shrink-0 px-2 py-0.5 rounded-full bg-blue-50 text-blue-600 text-[9px] font-bold uppercase tracking-widest border border-blue-100">PICKED UP</span>
                    @else
                    <span class="flex-shrink-0 px-2 py-0.5 rounded-full bg-amber-50 text-amber-600 text-[9px] font-bold uppercase tracking-widest border border-amber-100">PENDING</span>
                    @endif
                </div>
                
                <!-- Dates with circles -->
                <div class="flex justify-between items-center text-sm border-t border-red-50 pt-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full {{ $circleGreen }} shadow-sm flex-shrink-0"></span>
                        <div>
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider leading-none">Borrow</p>
                            <p class="font-bold text-gray-700 text-xs">{{ optional($p->tanggal_pinjam)->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-right">
                        <div>
                            <p class="text-[9px] {{ $isLate ? 'text-red-400' : 'text-amber-500' }} font-bold uppercase tracking-wider leading-none text-right">Due</p>
                            <p class="font-bold {{ $isLate ? 'text-red-600' : 'text-amber-600' }} text-xs">{{ optional($batasKembali)->format('d M Y') }}</p>
                        </div>
                        <span class="w-2.5 h-2.5 rounded-full {{ $circleYellow }} shadow-sm flex-shrink-0"></span>
                    </div>
                </div>

                <!-- Fine bar if overdue -->
                @if($calc_denda > 0)
                <div class="mt-3 p-2.5 bg-red-50 rounded-xl border border-red-100 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse flex-shrink-0"></span>
                        <span class="text-[9px] font-bold text-red-600 uppercase tracking-widest">Fine · {{ $overdueDays }}d late</span>
                    </div>
                    <span class="font-bold text-red-700 text-xs">Rp {{ number_format($calc_denda, 0, ',', '.') }}</span>
                </div>
                @endif
                
                <!-- Tap hint -->
                <p class="text-center text-[9px] text-gray-300 mt-2 font-medium tracking-wide group-hover:text-gray-400 transition-colors">TAP FOR DETAILS</p>
            </div>

            <!-- Detail Modal -->
            <div id="{{ $modalId }}" class="fixed inset-0 z-[999] items-center justify-center bg-black/50 backdrop-blur-sm hidden p-4"
                 onclick="if(event.target===this){this.classList.add('hidden');this.classList.remove('flex')}">
                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden animate-fade-up">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r {{ $isLate ? 'from-red-500 to-rose-600' : 'from-burgundy-500 to-maroon' }} px-6 py-5 text-white">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-[10px] font-bold uppercase tracking-widest opacity-70 mb-1">Loan Details</p>
                                <h3 class="font-bold text-base leading-snug line-clamp-2">{{ $p->buku?->judul ?? $p->snapshot_judul_buku ?? 'Unknown Book' }}</h3>
                            </div>
                            <button onclick="document.getElementById('{{ $modalId }}').classList.add('hidden');document.getElementById('{{ $modalId }}').classList.remove('flex');"
                                    class="w-8 h-8 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-5 space-y-3">
                        <!-- Borrow Date -->
                        <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50 border border-emerald-100">
                            <div class="flex items-center gap-2.5">
                                <span class="w-3 h-3 rounded-full {{ $isLate ? 'bg-red-500' : 'bg-emerald-500' }} shadow-sm"></span>
                                <span class="text-xs font-bold text-gray-600 uppercase tracking-wide">Borrow Date</span>
                            </div>
                            <span class="text-sm font-bold text-gray-800">{{ optional($p->tanggal_pinjam)->format('d M Y') }}</span>
                        </div>

                        <!-- Due Date -->
                        <div class="flex items-center justify-between p-3 rounded-xl {{ $isLate ? 'bg-red-50 border border-red-200' : 'bg-amber-50 border border-amber-100' }}">
                            <div class="flex items-center gap-2.5">
                                <span class="w-3 h-3 rounded-full {{ $isLate ? 'bg-red-500' : 'bg-amber-400' }} shadow-sm"></span>
                                <span class="text-xs font-bold {{ $isLate ? 'text-red-700' : 'text-gray-600' }} uppercase tracking-wide">Due Date</span>
                            </div>
                            <span class="text-sm font-bold {{ $isLate ? 'text-red-700' : 'text-gray-800' }}">{{ optional($batasKembali)->format('d M Y') }}</span>
                        </div>

                        <!-- Pick Up Date -->
                        <div class="flex items-center justify-between p-3 rounded-xl bg-blue-50 border border-blue-100">
                            <div class="flex items-center gap-2.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                                <span class="text-xs font-bold text-gray-600 uppercase tracking-wide">Picked Up</span>
                            </div>
                            @if($p->is_diambil)
                                <span class="text-sm font-bold text-blue-700">Confirmed ✓</span>
                            @else
                                <span class="text-sm font-semibold text-gray-400 italic">Not yet</span>
                            @endif
                        </div>

                        <!-- Overdue & Fine -->
                        @if($isLate)
                        <div class="p-3 rounded-xl bg-red-50 border border-red-200 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-red-700 uppercase tracking-wide flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Days Overdue
                                </span>
                                <span class="font-bold text-red-700 text-sm">{{ $overdueDays }} day{{ $overdueDays > 1 ? 's' : '' }}</span>
                            </div>
                            <div class="flex items-center justify-between border-t border-red-200 pt-2">
                                <span class="text-xs font-bold text-red-700 uppercase tracking-wide">Accumulated Fine</span>
                                <span class="font-bold text-red-700 text-base">Rp {{ number_format($calc_denda, 0, ',', '.') }}</span>
                            </div>
                            <p class="text-[10px] text-red-400 text-center">Rp 5,000 per overdue working day</p>
                        </div>
                        @else
                        <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Fine</span>
                            <span class="text-sm font-bold text-green-600">None ✓</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full glass-panel p-8 sm:p-10 text-center border-white/60">
                <p class="text-gray-400 font-medium">There are no books currently on loan.</p>
                <a href="{{ route('katalog') }}" class="inline-block mt-4 text-burgundy-500 font-bold hover:underline text-sm">Browse the Catalog</a>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Return History / Borrowing History Dropdown Tabs -->
    <div class="space-y-4 sm:space-y-6 animate-fade-up delay-300" x-data="{ activeTab: 'return' }">
        <!-- Tab Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 sm:h-6 w-5 sm:w-6 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span x-text="activeTab === 'return' ? 'Return History' : 'Borrowing History'"></span>
            </h2>
            <!-- Dropdown Tab Switcher -->
            <div class="flex bg-gray-100 rounded-xl p-1 gap-1 w-full sm:w-auto">
                <button @click="activeTab = 'return'"
                    :class="activeTab === 'return' ? 'bg-white text-burgundy-600 shadow-sm font-bold' : 'text-gray-500 font-semibold hover:text-gray-700'"
                    class="flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs transition-all duration-200 flex items-center justify-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Return History
                </button>
                <button @click="activeTab = 'borrow'"
                    :class="activeTab === 'borrow' ? 'bg-white text-burgundy-600 shadow-sm font-bold' : 'text-gray-500 font-semibold hover:text-gray-700'"
                    class="flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs transition-all duration-200 flex items-center justify-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Borrowing History
                </button>
            </div>
        </div>
        
        <!-- Return History Tab -->
        <div x-show="activeTab === 'return'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="glass-panel overflow-hidden border border-white/60 shadow-xl shadow-red-50">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[500px]">
                        <thead class="bg-red-50/50 text-gray-400 text-[10px] font-bold uppercase tracking-widest">
                            <tr>
                                <th class="px-4 sm:px-8 py-4 sm:py-5">Book title</th>
                                <th class="px-4 sm:px-8 py-4 sm:py-5 hidden sm:table-cell">Borrow ID</th>
                                <th class="px-4 sm:px-8 py-4 sm:py-5 text-center">Fine</th>
                                <th class="px-4 sm:px-8 py-4 sm:py-5 text-right">Return Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-red-50">
                            @forelse($pengembalian ?? [] as $k)
                            @php /** @var \App\Models\Peminjaman $k */ @endphp
                            <tr class="hover:bg-red-50/30 transition-colors">
                                <td class="px-4 sm:px-8 py-4 sm:py-6">
                                    <p class="font-bold text-gray-800 text-sm line-clamp-1">
                                        {{ $k->buku?->judul ?? $k->snapshot_judul_buku ?? 'Unknown Book' }}
                                        @if(!$k->buku) <span class="text-red-500 text-[10px] ml-1">(Deleted)</span> @endif
                                    </p>
                                    <p class="text-[10px] text-gray-400 line-clamp-1">{{ $k->buku && $k->buku->penulis->isNotEmpty() ? $k->buku->penulis->pluck('nama_penulis')->implode(', ') : ($k->buku ? 'Unknown Author' : 'Data Removed') }}</p>
                                    <span class="sm:hidden text-[9px] font-bold text-gray-400">#{{ $k->id }}</span>
                                </td>
                                <td class="px-4 sm:px-8 py-4 sm:py-6 text-sm text-gray-500 font-medium hidden sm:table-cell">#{{ $k->id }}</td>
                                <td class="px-4 sm:px-8 py-4 sm:py-6 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="font-bold text-xs sm:text-sm {{ $k->denda > 0 ? 'text-red-500' : 'text-gray-400' }}">
                                            Rp {{ number_format($k->denda, 0, ',', '.') }}
                                        </span>
                                        @if($k->denda > 0)
                                            <span class="text-[8px] font-bold {{ $k->status_denda === 'lunas' ? 'text-green-600' : 'text-red-400' }}">{{ $k->status_denda === 'lunas' ? 'PAID' : 'UNPAID' }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 sm:px-8 py-4 sm:py-6 text-right">
                                    <span class="px-2 sm:px-3 py-1 sm:py-1.5 rounded-lg bg-green-50 text-green-600 text-[10px] font-bold uppercase tracking-widest border border-green-100 whitespace-nowrap">
                                        {{ optional($k->tanggal_kembali)->format('d M Y') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-10 text-center text-gray-400 font-medium">There is no return history yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            {{-- Pagination --}}
            <div class="mt-6 sm:mt-8 flex justify-center text-gray-700 w-full" x-cloak>
                @if(isset($pengembalian) && method_exists($pengembalian, 'links'))
                    {{ $pengembalian->links() }}
                @endif
            </div>
        </div>

        <!-- Borrowing History Tab -->
        <div x-show="activeTab === 'borrow'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
            <div class="glass-panel overflow-hidden border border-white/60 shadow-xl shadow-red-50">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[500px]">
                        <thead class="bg-red-50/50 text-gray-400 text-[10px] font-bold uppercase tracking-widest">
                            <tr>
                                <th class="px-4 sm:px-8 py-4 sm:py-5">Book title</th>
                                <th class="px-4 sm:px-8 py-4 sm:py-5 hidden sm:table-cell">Borrow ID</th>
                                <th class="px-4 sm:px-8 py-4 sm:py-5 text-center">Fine</th>
                                <th class="px-4 sm:px-8 py-4 sm:py-5 text-right">Loan Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-red-50">
                            @forelse($pengembalian ?? [] as $k)
                            @php /** @var \App\Models\Peminjaman $k */ @endphp
                            <tr class="hover:bg-red-50/30 transition-colors">
                                <td class="px-4 sm:px-8 py-4 sm:py-6">
                                    <p class="font-bold text-gray-800 text-sm line-clamp-1">
                                        {{ $k->buku?->judul ?? $k->snapshot_judul_buku ?? 'Unknown Book' }}
                                        @if(!$k->buku) <span class="text-red-500 text-[10px] ml-1">(Deleted)</span> @endif
                                    </p>
                                    <p class="text-[10px] text-gray-400 line-clamp-1">{{ $k->buku && $k->buku->penulis->isNotEmpty() ? $k->buku->penulis->pluck('nama_penulis')->implode(', ') : ($k->buku ? 'Unknown Author' : 'Data Removed') }}</p>
                                    <span class="sm:hidden text-[9px] font-bold text-gray-400">#{{ $k->id }}</span>
                                </td>
                                <td class="px-4 sm:px-8 py-4 sm:py-6 text-sm text-gray-500 font-medium hidden sm:table-cell">#{{ $k->id }}</td>
                                <td class="px-4 sm:px-8 py-4 sm:py-6 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="font-bold text-xs sm:text-sm {{ $k->denda > 0 ? 'text-red-500' : 'text-gray-400' }}">
                                            Rp {{ number_format($k->denda, 0, ',', '.') }}
                                        </span>
                                        @if($k->denda > 0)
                                            <span class="text-[8px] font-bold {{ $k->status_denda === 'lunas' ? 'text-green-600' : 'text-red-400' }}">{{ $k->status_denda === 'lunas' ? 'PAID' : 'UNPAID' }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 sm:px-8 py-4 sm:py-6 text-right">
                                    <span class="px-2 sm:px-3 py-1 sm:py-1.5 rounded-lg bg-burgundy-50 text-burgundy-600 text-[10px] font-bold uppercase tracking-widest border border-burgundy-100 whitespace-nowrap">
                                        {{ optional($k->tanggal_pinjam)->format('d M Y') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-10 text-center text-gray-400 font-medium">There is no borrowing history yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
