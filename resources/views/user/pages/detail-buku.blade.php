@extends('user.layouts.app')

@section('title', 'Detail Buku - ' . $buku['judul'])

@section('content')
<div class="py-12 max-w-6xl mx-auto">
    <!-- Back Button -->
    <a href="{{ url()->previous() == url()->current() ? route('katalog') : url()->previous() }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-burgundy-500 font-bold mb-8 transition-colors group">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Gallery
    </a>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-12 items-start">
        <!-- Book Cover Column -->
        <div class="md:col-span-5 lg:col-span-4 animate-fade-up">
            <div class="relative group">
                <!-- Decorative backgrounds -->
                <div class="absolute -inset-4 bg-gradient-to-tr from-red-100 to-rose-50 rounded-[2.5rem] blur-2xl opacity-50 group-hover:opacity-80 transition-opacity duration-700"></div>
                
                <div class="relative glass-panel p-6 rounded-[2rem] border-white/60 shadow-2xl overflow-hidden aspect-[3/4] flex items-center justify-center bg-white/40">
                    <img src="{{ asset('images/' . ($buku['cover'] ?? 'readspace-library.png')) }}" 
                        alt="{{ $buku['judul'] }}"
                        class="h-full object-contain shadow-2xl rounded-lg transform group-hover:scale-105 transition-transform duration-700"
                        onerror=`this.src='{{ asset('images/readspace-library.png') }}'`>
                </div>

                <!-- Status Badge Floating -->
                <div class="absolute -top-4 -right-4 px-6 py-3 rounded-2xl shadow-xl backdrop-blur-xl border-2 {{ $buku['status'] == 'Tersedia' && ($buku['stok'] ?? 0) > 0 ? 'bg-green-500/90 text-white border-green-400' : 'bg-red-500/90 text-white border-red-400' }} font-black text-xs uppercase tracking-widest animate-bounce-slow">
                    {{ $buku['status'] == 'Tersedia' && ($buku['stok'] ?? 0) > 0 ? 'AVAILABLE' : 'BORROWED' }}
                </div>
            </div>
        </div>

        <!-- Book Info Column -->
        <div class="md:col-span-7 lg:col-span-8 space-y-8 animate-fade-up delay-100">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <span class="px-4 py-1.5 rounded-full bg-burgundy-50 text-burgundy-500 text-[10px] font-black uppercase tracking-widest border border-burgundy-100">
                        {{ $buku['genre'] }}
                    </span>
                    <span class="text-gray-300 font-medium text-xs">BOOK ID: {{ $buku['book_id'] ?? '#00'.$buku['id'] }}</span>
                </div>
                <h1 class="text-5xl font-black text-gray-800 leading-tight mb-4">{{ $buku['judul'] }}</h1>
                <p class="text-2xl font-medium text-gray-400 italic">by {{ $buku['penulis'] }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="glass-panel p-6 border-white/40 bg-white/20">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Category</p>
                    <p class="text-lg font-bold text-gray-700">{{ $buku['genre'] }}</p>
                </div>
                <div class="glass-panel p-6 border-white/40 bg-white/20">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Availability</p>
                    <p class="text-lg font-bold {{ $buku['status'] == 'Tersedia' && ($buku['stok'] ?? 0) > 0 ? 'text-green-600' : 'text-red-500' }}">{{ $buku['status'] == 'Tersedia' && ($buku['stok'] ?? 0) > 0 ? 'Available' : 'Borrowed' }}</p>
                </div>
                <div class="glass-panel p-6 border-white/40 bg-white/20">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Publication Year</p>
                    <p class="text-lg font-bold text-gray-700">{{ $buku['tahun_terbit'] ?? 'N/A' }}</p>
                </div>
                <div class="glass-panel p-6 border-white/40 bg-white/20">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Edition (Cetakan)</p>
                    <p class="text-lg font-bold text-gray-700">{{ $buku['cetakan'] ?? 'N/A' }}</p>
                </div>
                <div class="glass-panel p-6 border-white/40 bg-white/20 sm:col-span-2">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Language / Translation (Bahasa)</p>
                    <p class="text-lg font-bold text-gray-700">{{ $buku['bahasa'] ?? 'Indonesia' }}</p>
                </div>
                <div class="glass-panel p-6 border-white/40 bg-white/20 sm:col-span-2">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Rak Buku</p>
                    <p class="text-lg font-bold text-gray-700">{{ $buku['lokasi_rak'] ?? 'Belum ditentukan' }}</p>
                </div>
            </div>

            <div class="prose prose-red max-w-none">
                <h3 class="text-xl font-bold text-gray-800 mb-4">About this book</h3>
                <p class="text-gray-500 leading-relaxed text-lg">
                    Discover the captivating world within the pages of <span class="font-bold text-burgundy-500">{{ $buku['judul'] }}</span>. 
                    This {{ strtolower($buku['genre']) }} masterpiece by {{ $buku['penulis'] }} offers a unique perspective 
                    and deep insights that will leave a lasting impression on every reader. 
                    Perfect for students and enthusiasts alike looking for quality references and engaging narratives.
                </p>
            </div>

            <div class="pt-8 flex flex-col sm:flex-row gap-4">
                @if($buku['status'] == 'Tersedia' && ($buku['stok'] ?? 0) > 0)
                <a href="{{ route('pengajuan', ['judul' => $buku['judul'], 'id' => $buku['id'], 'book_id' => $buku['book_id'] ?? ('B-' . str_pad($buku['id'], 3, '0', STR_PAD_LEFT)), 'cover' => $buku['cover']]) }}" 
                    class="px-10 py-5 bg-burgundy-500 text-white rounded-2xl font-bold text-lg shadow-2xl shadow-red-200 hover:bg-maroon hover:-translate-y-1 transition-all flex items-center justify-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Apply for Loan
                </a>
                @else
                <button disabled class="px-10 py-5 bg-gray-100 text-gray-400 rounded-2xl font-bold text-lg cursor-not-allowed flex items-center justify-center gap-3 border border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Currently Borrowed
                </button>
                @endif

            </div>
        </div>
    </div>
</div>

<style>
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .animate-bounce-slow {
        animation: bounce-slow 3s infinite ease-in-out;
    }
</style>
@endsection
