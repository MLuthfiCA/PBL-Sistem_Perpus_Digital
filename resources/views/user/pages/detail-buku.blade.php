@extends('user.layouts.app')

@section('title', 'Detail Buku - ' . $buku['judul'])

@section('content')
<div class="py-10 space-y-8 animate-fade-up">

    {{-- Back Button --}}
    <div>
        <a href="{{ url()->previous() == url()->current() ? route('katalog') : url()->previous() }}"
           class="inline-flex items-center gap-2 text-gray-400 hover:text-burgundy-500 font-bold mb-3 transition-colors group text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Catalog
        </a>
        <h1 class="text-3xl font-black text-gray-800">Book Detail</h1>
        <p class="text-gray-400 text-sm mt-1">ID: <span class="font-bold text-burgundy-500">{{ $buku['book_id'] ?? 'B-' . str_pad($buku['id'], 3, '0', STR_PAD_LEFT) }}</span></p>
    </div>

    {{-- Main Detail Card --}}
    <div class="grid grid-cols-1 md:grid-cols-12 gap-10 items-start">

        {{-- Book Cover --}}
        <div class="md:col-span-4">
            <div class="glass-panel p-6 border-white/60 rounded-3xl shadow-2xl shadow-red-50">
                <div class="relative group">
                    <div class="absolute -inset-3 bg-gradient-to-tr from-red-100 to-rose-50 rounded-[2rem] blur-2xl opacity-40 group-hover:opacity-70 transition-opacity duration-700"></div>
                    <div class="relative rounded-[1.5rem] overflow-hidden aspect-[3/4] flex items-center justify-center bg-gradient-to-br from-red-50 to-rose-100 border border-white/40">
                        <img src="{{ asset('images/' . ($buku['cover'] ?? 'readspace-library.png')) }}"
                             alt="{{ $buku['judul'] }}"
                             class="h-full object-contain shadow-2xl transform group-hover:scale-105 transition-transform duration-700"
                             onerror="this.src='{{ asset('images/readspace-library.png') }}'">
                    </div>
                    {{-- Status Badge --}}
                    <div class="absolute -top-3 -right-3 px-4 py-2 rounded-2xl shadow-xl backdrop-blur-xl font-black text-xs uppercase tracking-widest
                        {{ ($buku['status'] == 'Tersedia' && ($buku['stok'] ?? 0) > 0) ? 'bg-green-500 text-white shadow-green-200' : 'bg-red-500 text-white shadow-red-200' }}">
                        {{ ($buku['status'] == 'Tersedia' && ($buku['stok'] ?? 0) > 0) ? 'AVAILABLE' : 'BORROWED' }}
                    </div>
                </div>

                {{-- Loan Button --}}
                <div class="mt-6">
                    @if($buku['status'] == 'Tersedia' && ($buku['stok'] ?? 0) > 0)
                        <a href="{{ route('pengajuan', ['judul' => $buku['judul'], 'id' => $buku['id'], 'book_id' => $buku['book_id'] ?? ('B-' . str_pad($buku['id'], 3, '0', STR_PAD_LEFT)), 'cover' => $buku['cover']]) }}"
                           class="w-full flex items-center justify-center gap-2 px-6 py-4 bg-burgundy-500 text-white rounded-2xl font-bold shadow-xl shadow-red-100 hover:bg-maroon hover:-translate-y-0.5 transition-all active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Apply for Loan
                        </a>
                    @else
                        <button disabled
                            class="w-full flex items-center justify-center gap-2 px-6 py-4 bg-gray-100 text-gray-400 rounded-2xl font-bold border border-gray-200 cursor-not-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Currently Borrowed
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Book Info --}}
        <div class="md:col-span-8 space-y-6">
            <div class="glass-panel p-8 border-white/60 rounded-3xl shadow-xl shadow-red-50">
                <div class="mb-6">
                    <span class="px-4 py-1.5 rounded-full bg-burgundy-50 text-burgundy-500 text-[10px] font-black uppercase tracking-widest border border-burgundy-100">
                        {{ $buku['genre'] ?? 'N/A' }}
                    </span>
                </div>
                <h2 class="text-4xl font-black text-gray-800 leading-tight mb-2">{{ $buku['judul'] }}</h2>
                <p class="text-xl font-medium text-gray-400 italic mb-8">by {{ $buku['penulis'] }}</p>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <div class="bg-white/60 rounded-2xl p-4 border border-white/80">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">ISBN</p>
                        <p class="text-sm font-bold text-gray-700">{{ $buku['isbn'] ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-white/60 rounded-2xl p-4 border border-white/80">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Publisher</p>
                        <p class="text-sm font-bold text-gray-700">{{ $buku['penerbit'] ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-white/60 rounded-2xl p-4 border border-white/80">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Publication Year</p>
                        <p class="text-sm font-bold text-gray-700">{{ $buku['tahun_terbit'] ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-white/60 rounded-2xl p-4 border border-white/80">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Edition</p>
                        <p class="text-sm font-bold text-gray-700">{{ $buku['cetakan'] ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-white/60 rounded-2xl p-4 border border-white/80">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Language</p>
                        <p class="text-sm font-bold text-gray-700">{{ $buku['bahasa'] ?? 'Indonesia' }}</p>
                    </div>
                    <div class="bg-white/60 rounded-2xl p-4 border border-white/80">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Status</p>
                        <p class="text-sm font-bold {{ ($buku['status'] == 'Tersedia' && ($buku['stok'] ?? 0) > 0) ? 'text-green-600' : 'text-red-500' }}">
                            {{ ($buku['status'] == 'Tersedia' && ($buku['stok'] ?? 0) > 0) ? 'Available' : 'Borrowed' }}
                        </p>
                    </div>
                    <div class="bg-white/60 rounded-2xl p-4 border border-white/80">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Rak Buku</p>
                        <p class="text-sm font-bold text-gray-700">{{ $buku['lokasi_rak'] ?? '-' }}</p>
                    </div>
                    <div class="bg-white/60 rounded-2xl p-4 border border-white/80">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Book Code</p>
                        <p class="text-sm font-bold text-gray-700">{{ $buku['book_id'] ?? '#00'.$buku['id'] }}</p>
                    </div>
                    <div class="bg-white/60 rounded-2xl p-4 border border-white/80">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Category</p>
                        <p class="text-sm font-bold text-burgundy-500">{{ $buku['genre'] ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div class="glass-panel p-8 border-white/60 rounded-3xl shadow-xl shadow-red-50">
                <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-burgundy-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Deskripsi Buku
                </h3>
                <p class="text-gray-500 leading-relaxed text-sm">
                    @if(!empty($buku['deskripsi']))
                        {{ $buku['deskripsi'] }}
                    @else
                        Deskripsi untuk buku <span class="font-bold text-burgundy-500">{{ $buku['judul'] }}</span> belum tersedia.
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
