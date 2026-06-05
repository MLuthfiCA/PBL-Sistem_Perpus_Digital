@extends('user.layouts.app')

@section('content')
<div class="py-10 space-y-12">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 animate-fade-up">
        <div>
            <h1 class="text-4xl font-bold text-gray-800">My Profile</h1>
            <p class="text-gray-500 mt-2">Manage account information and monitor your literacy activities.</p>
        </div>
    </div>

    <!-- User Info Card -->
    <div class="glass-panel p-8 border-white/60 animate-fade-up delay-100 shadow-2xl shadow-red-50 flex flex-col md:flex-row items-center gap-8">
        <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-burgundy-500 to-maroon text-white flex items-center justify-center text-4xl font-bold shadow-xl shadow-red-100">
            {{ substr(session('user.name', 'User'), 0, 1) }}
        </div>
        <div class="text-center md:text-left flex-1">
            <h2 class="text-2xl font-bold text-gray-800">{{ session('user.name', 'Nama User') }}</h2>
            <p class="text-gray-500 font-medium">{{ session('user.email', 'email@student.polibatam.ac.id') }}</p>
            <div class="mt-4 inline-block px-4 py-1.5 rounded-lg bg-burgundy-50 text-burgundy-600 border border-burgundy-100 text-xs font-bold uppercase tracking-widest">
                {{ session('user.role', 'Mahasiswa') }}
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
    <div class="bg-red-50 border border-red-200 rounded-xl p-5 shadow-sm animate-fade-down flex items-start gap-4 border-l-4 border-l-red-500">
        <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0 mt-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        </div>
        <div>
            <h3 class="font-bold text-red-800 text-lg">Account Warning!</h3>
            <p class="text-red-600 text-sm mt-1">
                @if($hasLateBook && $hasUnpaidFine)
                    You have overdue books and unpaid fines.
                @elseif($hasLateBook)
                    You have books that have exceeded the return deadline. Please return them immediately!
                @elseif($hasUnpaidFine)
                    You have unpaid late fines. Please contact the library administrator to settle your fines.
                @endif
                <br><strong class="mt-2 block">You cannot borrow new books until this issue is resolved.</strong>
            </p>
        </div>
    </div>
    @endif

    <!-- Currently Borrowed -->
    <div class="space-y-6 animate-fade-up delay-200">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-burgundy-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Currently Borrowed
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($peminjaman ?? [] as $p)
            @php /** @var \App\Models\Peminjaman $p */ @endphp
            <div class="glass-panel p-6 border-white/60 shadow-lg shadow-red-50 hover:shadow-xl transition-all group border-l-4 border-l-burgundy-500">
                <h3 class="font-bold text-gray-800 text-lg mb-1">{{ $p->buku?->judul ?? 'Unknown Book' }}</h3>
                <p class="text-xs text-gray-400 font-medium mb-4">Book ID: #{{ $p->buku?->buku_id ?? $p->buku?->id ?? 'N/A' }}</p>
                
                <div class="flex justify-between items-center text-sm border-t border-red-50 pt-4">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Borrow Date</p>
                        <p class="font-bold text-gray-700">{{ optional($p->tanggal_pinjam)->format('Y-m-d') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-red-400 font-bold uppercase tracking-wider">Return Limit</p>
                        <p class="font-bold text-red-600">{{ optional($p->batas_kembali)->format('Y-m-d') }}</p>
                    </div>
                </div>

                <!-- Late Fine Section -->
                @php
                    $calculated_denda = $p->calculateDenda();
                @endphp
                @if($calculated_denda > 0)
                <div class="mt-4 p-3 bg-red-50 rounded-xl border border-red-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-red-500 text-white flex items-center justify-center shadow-lg shadow-red-100 animate-pulse">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[9px] font-bold text-red-600 uppercase tracking-widest leading-tight">Overdue Fine</span>
                            <span class="text-[8px] font-bold {{ $p->status_denda === 'lunas' ? 'text-green-600' : 'text-red-400' }} uppercase">{{ $p->status === 'dipinjam' ? 'ONGOING' : ($p->status_denda === 'lunas' ? 'PAID' : 'UNPAID') }}</span>
                        </div>
                    </div>
                    <span class="font-bold text-red-700 text-sm">Rp {{ number_format($calculated_denda, 0, ',', '.') }}</span>
                </div>
                @endif
            </div>
            @empty
            <div class="col-span-full glass-panel p-10 text-center border-white/60">
                <p class="text-gray-400 font-medium">There are no books currently on loan.</p>
                <a href="{{ route('katalog') }}" class="inline-block mt-4 text-burgundy-500 font-bold hover:underline">Browse the Catalog</a>
            </div>
            @endforelse
        </div>
    </div>

    <!-- History -->
    <div class="space-y-6 animate-fade-up delay-300">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Return History
        </h2>
        
        <div class="glass-panel overflow-hidden border border-white/60 shadow-xl shadow-red-50">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-red-50/50 text-gray-400 text-[10px] font-bold uppercase tracking-widest">
                        <tr>
                            <th class="px-8 py-5">Book title</th>
                            <th class="px-8 py-5">Borrow ID</th>
                            <th class="px-8 py-5 text-center">Fine (Denda)</th>
                            <th class="px-8 py-5 text-right">Return Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-50">
                        @forelse($pengembalian ?? [] as $k)
                        @php /** @var \App\Models\Peminjaman $k */ @endphp
                        <tr class="hover:bg-red-50/30 transition-colors">
                            <td class="px-8 py-6">
                                <p class="font-bold text-gray-800">{{ $k->buku?->judul ?? 'Unknown Book' }}</p>
                                <p class="text-[10px] text-gray-400">{{ $k->buku?->penulis ?? 'Unknown Author' }}</p>
                            </td>
                            <td class="px-8 py-6 text-sm text-gray-500 font-medium">#{{ $k->id }}</td>
                            <td class="px-8 py-6 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="font-bold {{ $k->denda > 0 ? 'text-red-500' : 'text-gray-400' }}">
                                        Rp {{ number_format($k->denda, 0, ',', '.') }}
                                    </span>
                                    @if($k->denda > 0)
                                        <span class="text-[8px] font-bold {{ $k->status_denda === 'lunas' ? 'text-green-600' : 'text-red-400' }}">{{ $k->status_denda === 'lunas' ? 'LUNAS' : 'BELUM LUNAS' }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <span class="px-3 py-1.5 rounded-lg bg-green-50 text-green-600 text-[10px] font-bold uppercase tracking-widest border border-green-100">
                                    {{ optional($k->tanggal_kembali)->format('Y-m-d') }}
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
    </div>

</div>
@endsection
