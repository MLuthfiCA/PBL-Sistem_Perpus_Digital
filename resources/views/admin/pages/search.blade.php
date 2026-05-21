@extends('admin.layouts.app')

@section('content')
<div class="py-10">
    <!-- Header -->
    <div class="mb-12 text-center animate-fade-up">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Book Data Search</h1>
        <p class="text-gray-500">Library collection search management</p>
    </div>

    <!-- Search Bar -->
    <div x-data="{ showFilters: false, selectedCategory: '{{ request('category') }}' }" class="max-w-3xl mx-auto mb-16 animate-fade-up delay-100 relative z-40">
        <form action="{{ route('admin.search') }}" method="GET" class="flex flex-col md:flex-row gap-4 relative items-stretch">
            <input type="hidden" name="category" :value="selectedCategory">
            
            <div class="relative w-full group">
                <input type="text" name="query" value="{{ request('query') }}" 
                    placeholder="Search for ID, title or author..." 
                    class="w-full pl-8 pr-20 py-5 sm:py-6 bg-white/70 backdrop-blur-xl border border-white shadow-2xl shadow-red-50 rounded-3xl focus:ring-4 focus:ring-red-100 focus:outline-none transition-all text-lg text-gray-700 placeholder-gray-400">
                <button type="submit" class="absolute right-3 top-2 sm:top-3 bg-burgundy-500 text-white p-3 sm:p-4 rounded-2xl hover:bg-maroon transition-all shadow-lg shadow-red-200 group-hover:scale-105 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
            
            <div class="relative flex-shrink-0 flex">
                <button type="button" @click="showFilters = !showFilters" class="h-full aspect-square bg-white/70 backdrop-blur-xl border border-white shadow-2xl shadow-red-50 rounded-3xl text-gray-700 hover:text-burgundy-500 hover:bg-white transition-all focus:outline-none focus:ring-4 focus:ring-red-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Dropdown Filter Menu like the image -->
                <div x-show="showFilters" x-transition @click.away="showFilters = false" style="display: none;" class="absolute top-full right-0 mt-3 w-56 bg-white rounded-xl shadow-2xl overflow-hidden z-50 border border-gray-100">
                    <div class="bg-burgundy-500 text-white px-5 py-3 font-bold text-sm">
                        All Categories
                    </div>
                    <ul class="py-2 text-gray-700 text-sm">
                        <li>
                            <button type="button" @click="selectedCategory = ''; $nextTick(() => { $el.closest('form').submit() })" class="w-full text-left px-5 py-3 hover:bg-red-50 hover:text-burgundy-500 transition-colors {{ !request('category') ? 'font-bold text-burgundy-500 bg-red-50/50' : '' }}">
                                All Categories
                            </button>
                        </li>
                        @if(isset($categories))
                            @foreach($categories as $cat)
                                <li>
                                    <button type="button" @click="selectedCategory = '{{ $cat }}'; $nextTick(() => { $el.closest('form').submit() })" class="w-full text-left px-5 py-3 hover:bg-red-50 hover:text-burgundy-500 transition-colors {{ request('category') == $cat ? 'font-bold text-burgundy-500 bg-red-50/50' : '' }}">
                                        {{ $cat }}
                                    </button>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </form>
    </div>

    <!-- Results Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8">
        @forelse($books as $index => $buku)
        <x-ui.glass-card class="p-4 flex flex-col group animate-fade-up border-white/60" style="animation-delay: {{ $index * 100 }}ms">
            <div class="relative h-64 rounded-2xl mb-5 overflow-hidden bg-gradient-to-br from-red-50 to-rose-100 flex items-center justify-center border border-white/20">
                <!-- Real Image from images folder -->
                <img src="{{ asset('images/' . ($buku->cover ?? 'readspace-library.png')) }}" 
                    class="h-4/5 object-contain shadow-2xl transform group-hover:scale-110 group-hover:rotate-2 transition-transform duration-700"
                    onerror="this.src='{{ asset('images/readspace-library.png') }}'">
                
                <!-- Availability Badge -->
                <div class="absolute top-4 right-4 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest backdrop-blur-xl {{ $buku->status == 'Tersedia' ? 'bg-green-500/10 text-green-600 border border-green-200' : 'bg-red-500/10 text-red-600 border border-red-200' }}">
                    {{ $buku->status == 'Tersedia' ? 'AVAILABLE' : 'BORROWED' }}
                </div>

                <!-- Admin Action Overlay (Desktop only) -->
                <div class="absolute inset-0 bg-burgundy-900/40 opacity-0 md:group-hover:opacity-100 transition-opacity hidden md:flex items-center justify-center gap-3 backdrop-blur-[2px]">
                    <a href="{{ route('admin.edit_buku', $buku->buku_id) }}"class="p-3 bg-white rounded-xl text-burgundy-500 shadow-xl hover:scale-110 transition-transform" title="Edit Buku">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </a>
                    <form action="{{ route('admin.delete', $buku->buku_id) }}" method="POST" onsubmit="return confirm('Hapus buku ini dari katalog?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-3 bg-white rounded-xl text-red-500 shadow-xl hover:scale-110 transition-transform" title="Hapus Buku">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-1 12a2 2 0 01-2 2H8a2 2 0 01-2-2L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            
            <h3 class="font-bold text-gray-800 line-clamp-1 mb-1 text-lg group-hover:text-burgundy-500 transition-colors">{{ $buku->judul }}</h3>
            <p class="text-xs text-gray-400 mb-6 font-medium">{{ $buku->penulis }}</p>
            
            <div class="mt-auto pt-5 border-t border-red-50 flex items-center justify-between">
                <span class="px-2 py-1 rounded bg-white/80 text-[10px] font-bold text-burgundy-500 uppercase tracking-tighter border border-red-100">{{ $buku->genre }}</span>
                <span class="hidden md:inline text-[10px] font-bold text-gray-300 uppercase tracking-widest">ID: {{ $buku->buku_id ?? '#00'.$buku->bid }}</span>

                <!-- Mobile Actions -->
                <div class="flex md:hidden items-center gap-1.5">
                    <a href="{{ route('admin.edit_buku', $buku->buku_id) }}" class="text-burgundy-500 font-bold text-[10px] bg-red-50 px-2 py-1.5 rounded-lg border border-red-100 uppercase tracking-widest">Edit</a>
                    <form action="{{ route('admin.delete', $buku->buku_id) }}" method="POST" onsubmit="return confirm('Hapus buku ini dari katalog?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 font-bold text-[10px] bg-red-50 px-2 py-1.5 rounded-lg border border-red-100 uppercase tracking-widest">Del</button>
                    </form>
                </div>
            </div>
        </x-ui.glass-card>
        @empty
            <div class="col-span-full py-20 text-center animate-fade-up">
                <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 text-burgundy-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <p class="text-gray-400 font-medium text-xl">The book you are looking for was not found 📚</p>
                <p class="text-gray-400 text-sm mt-2">Try using other keywords like title or author.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
