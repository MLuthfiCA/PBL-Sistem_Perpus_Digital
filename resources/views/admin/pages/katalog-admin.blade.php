@extends('admin.layouts.app')

@section('title', 'Katalog Buku Admin')

@section('content')
<div class="py-6 sm:py-10 space-y-6 sm:space-y-10" 
     x-data="{ 
         showModal: {{ session('success') ? 'true' : 'false' }}, 
         view: localStorage.getItem('catalog_admin_view') || new URLSearchParams(window.location.search).get('view') || 'grid',
         init() {
            window.addEventListener('pageshow', () => {
                this.view = localStorage.getItem('catalog_admin_view') || this.view;
            });
         },
         setView(v) {
            this.view = v;
            localStorage.setItem('catalog_admin_view', v);
            const url = new URL(window.location.href);
            url.searchParams.set('view', v);
            history.replaceState(null, '', url.toString());

            document.querySelectorAll('.pagination a').forEach(link => {
                try {
                    let linkUrl = new URL(link.href);
                    linkUrl.searchParams.set('view', v);
                    link.href = linkUrl.toString();
                } catch(e) {}
            });
         }
     }">
    
    <!-- Page Header -->
    <x-ui.page-header 
        title="Catalog Management" 
        subtitle="Manage all books available in the Readspace Library."
    >
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
            <!-- View Toggle -->
            <div class="flex p-1 bg-white/60 backdrop-blur-md rounded-2xl border border-white/80 shadow-xl shadow-red-50 w-full sm:w-fit justify-center">
                <button @click="setView('grid')" :class="view === 'grid' ? 'bg-burgundy-500 shadow-lg text-white' : 'text-gray-400'" class="px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl transition-all duration-300 flex items-center gap-1.5 sm:gap-2 font-bold text-xs sm:text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 14a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 14a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Grid
                </button>
                <button @click="setView('table')" :class="view === 'table' ? 'bg-burgundy-500 shadow-lg text-white' : 'text-gray-400'" class="px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl transition-all duration-300 flex items-center gap-1.5 sm:gap-2 font-bold text-xs sm:text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    Table
                </button>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <a href="{{ route('admin.buku.create') }}" class="flex-1 sm:flex-initial justify-center px-4 sm:px-6 py-2 sm:py-3.5 bg-burgundy-500 text-white rounded-xl sm:rounded-2xl text-xs sm:text-sm font-bold shadow-lg shadow-red-100 hover:bg-maroon transition-all transform active:scale-95 flex items-center gap-1.5 sm:gap-2 whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="sm:inline">Add Book</span>
                </a>
                <a href="{{ route('admin.katalog.trash') }}" class="px-3 sm:px-4 py-2 sm:py-3.5 bg-white rounded-xl sm:rounded-2xl border border-gray-100 shadow-sm text-xs sm:text-sm font-bold hover:bg-red-50 text-gray-600 flex items-center justify-center shrink-0">
                    Trash
                </a>
            </div>
        </div>
    </x-ui.page-header>

    <!-- Search Bar -->
    <div x-data="{
            showFilters: false,
            selectedCategories: {{ json_encode($selectedCategories ?? []) }}
         }"
         class="max-w-3xl mx-auto mb-8 sm:mb-10 animate-fade-up delay-100 relative z-40">

        <form id="search-form" action="{{ route('admin.katalog') }}" method="GET"
              class="flex flex-col sm:flex-row gap-3 sm:gap-4 relative items-stretch">
            
            <input type="hidden" name="view" :value="view">

            {{-- Hidden inputs untuk multi-kategori --}}
            <template x-for="cat in selectedCategories" :key="cat">
                <input type="hidden" name="categories[]" :value="cat">
            </template>

            <div class="relative w-full group">
                <input type="text" name="query" value="{{ request('query') }}"
                    placeholder="Search by title, author or isbn..."
                    class="w-full pl-6 sm:pl-8 pr-16 sm:pr-20 py-4 sm:py-5 sm:py-6 bg-white/70 backdrop-blur-xl border border-white shadow-2xl shadow-red-50 rounded-2xl sm:rounded-3xl focus:ring-4 focus:ring-red-100 focus:outline-none transition-all text-base sm:text-lg text-gray-700 placeholder-gray-400">
                <button type="submit"
                    class="absolute right-2.5 top-2 sm:top-2.5 sm:top-3 bg-burgundy-500 text-white p-2.5 sm:p-3 sm:p-4 rounded-xl sm:rounded-2xl hover:bg-maroon transition-all shadow-lg shadow-red-200 group-hover:scale-105 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 sm:h-6 w-5 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>

            <div class="relative flex-shrink-0 flex">
                {{-- Tombol filter (Funnel Icon) --}}
                <button type="button" @click="showFilters = !showFilters"
                    class="h-full min-h-[50px] sm:min-h-0 sm:aspect-square w-full sm:w-auto px-4 sm:px-0 bg-white/70 backdrop-blur-xl border border-white shadow-2xl shadow-red-50 rounded-2xl sm:rounded-3xl text-gray-700 hover:text-burgundy-500 hover:bg-white transition-all focus:outline-none focus:ring-4 focus:ring-red-100 flex items-center justify-center gap-2 sm:gap-0 relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 sm:h-6 w-5 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                    </svg>
                    <span class="sm:hidden text-sm font-bold">Filter</span>
                    {{-- Badge jumlah kategori terpilih --}}
                    <span x-show="selectedCategories.length > 0"
                          x-text="selectedCategories.length"
                          class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-burgundy-500 text-white text-[10px] font-bold flex items-center justify-center" style="display:none;">
                    </span>
                </button>

                <!-- Dropdown Multi-Kategori -->
                <div x-show="showFilters" x-transition @click.away="showFilters = false"
                     style="display: none;"
                     class="absolute top-full right-0 mt-3 w-64 bg-white rounded-xl shadow-2xl overflow-hidden z-50 border border-gray-100">

                    <div class="bg-burgundy-500 text-white px-5 py-3 font-bold text-sm flex items-center justify-between">
                        <span>Filter</span>
                        <button type="button"
                            @click="selectedCategories = []; $nextTick(() => document.getElementById('search-form').dispatchEvent(new Event('submit', { cancelable: true, bubbles: true })))"
                            x-show="selectedCategories.length > 0"
                            class="text-[10px] bg-white/20 hover:bg-white/30 px-2 py-1 rounded-lg transition-colors font-medium">
                            Reset
                        </button>
                    </div>

                    <ul class="py-2 text-gray-700 text-sm max-h-64 overflow-y-auto">
                        @if(isset($categories))
                            @foreach($categories as $cat)
                            <li>
                                <label class="flex items-center gap-3 w-full px-5 py-3 hover:bg-red-50 hover:text-burgundy-500 transition-colors cursor-pointer"
                                       :class="selectedCategories.includes('{{ $cat }}') ? 'font-semibold text-burgundy-500 bg-red-50/50' : ''">
                                    <input type="checkbox"
                                           class="w-4 h-4 rounded accent-burgundy-500 cursor-pointer"
                                           value="{{ $cat }}"
                                           :checked="selectedCategories.includes('{{ $cat }}')"
                                           @change="
                                               if ($event.target.checked) {
                                                   selectedCategories.push('{{ $cat }}');
                                               } else {
                                                   selectedCategories = selectedCategories.filter(c => c !== '{{ $cat }}');
                                               }
                                           ">
                                    {{ $cat }}
                                </label>
                            </li>
                            @endforeach
                        @endif
                    </ul>

                    {{-- Tombol Apply --}}
                    <div class="px-5 py-3 border-t border-gray-100">
                        <button type="button"
                            @click="showFilters = false; $nextTick(() => document.getElementById('search-form').dispatchEvent(new Event('submit', { cancelable: true, bubbles: true })))"
                            class="w-full bg-burgundy-500 text-white py-2.5 rounded-xl font-bold text-sm hover:bg-maroon transition-all">
                            Apply Filter
                        </button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Chips kategori terpilih --}}
        <div x-show="selectedCategories.length > 0" class="mt-3 sm:mt-4 flex flex-wrap gap-2" style="display:none;">
            <template x-for="cat in selectedCategories" :key="cat">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-burgundy-500/10 text-burgundy-500 border border-burgundy-200 rounded-full text-xs font-semibold">
                    <span x-text="cat"></span>
                    <button type="button"
                        @click="selectedCategories = selectedCategories.filter(c => c !== cat); $nextTick(() => document.getElementById('search-form').dispatchEvent(new Event('submit', { cancelable: true, bubbles: true })))"
                        class="hover:text-maroon transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </span>
            </template>
        </div>
    </div>

    <!-- Grid View -->
    <div x-cloak>
    <template x-if="view === 'grid'">
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
            @forelse($Buku as $index => $buku)
            <x-ui.glass-card class="p-3 sm:p-4 flex flex-col animate-fade-up border-white/60" style="animation-delay: {{ $index * 100 }}ms">
                <div class="group relative h-44 sm:h-56 md:h-64 rounded-xl sm:rounded-2xl mb-3 sm:mb-5 overflow-hidden bg-gradient-to-br from-red-50 to-rose-100 flex items-center justify-center border border-white/20">
                    <a href="{{ route('admin.katalog.detail', $buku['id']) }}" class="absolute inset-0 flex items-center justify-center">
                        <img src="{{ asset('images/' . ($buku['cover'] ?? 'readspace-library.png')) }}" 
                            class="h-4/5 object-contain shadow-2xl transform group-hover:scale-110 group-hover:rotate-2 transition-transform duration-700"
                            data-fallback="{{ asset('images/readspace-library.png') }}"
                            onerror="this.src=this.dataset.fallback" alt="Book Cover">
                    </a>
                    
                    @php
                        $statusText = '';
                        $badgeClass = '';
                        if ($buku['status'] == 'Tersedia' && ($buku['stok'] ?? 0) > 0) {
                            $statusText = 'AVAILABLE';
                            $badgeClass = 'bg-green-500/10 text-green-600 border border-green-200';
                        } elseif ($buku['status'] == 'Perawatan') {
                            $statusText = 'MAINTENANCE';
                            $badgeClass = 'bg-yellow-500/10 text-yellow-600 border border-yellow-200';
                        } elseif ($buku['status'] == 'Hilang') {
                            $statusText = 'LOST';
                            $badgeClass = 'bg-gray-500/10 text-gray-600 border border-gray-200';
                        } else {
                            $statusText = 'BORROWED';
                            $badgeClass = 'bg-red-500/10 text-red-600 border border-red-200';
                        }
                    @endphp
                    <!-- Availability Badge -->
                    <div class="absolute top-2 sm:top-4 right-2 sm:right-4 px-2 sm:px-3 py-1 sm:py-1.5 rounded-full text-[9px] sm:text-[10px] font-bold uppercase tracking-widest backdrop-blur-xl {{ $badgeClass }}">
                        {{ $statusText }}
                    </div>

                    <!-- Admin Action Overlay (Desktop only) -->
                    <div class="absolute inset-0 bg-burgundy-900/40 opacity-0 group-hover:opacity-100 transition-opacity hidden md:flex items-center justify-center gap-3 backdrop-blur-[2px]">
                        <a href="{{ route('admin.katalog.detail', $buku['id']) }}" class="p-3 bg-white rounded-xl text-burgundy-500 shadow-xl hover:scale-110 active:scale-95 transition-transform" title="View Details">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                        <a href="{{ route('admin.edit_buku', $buku['id']) }}" class="p-3 bg-white rounded-xl text-burgundy-500 shadow-xl hover:scale-110 active:scale-95 transition-transform" title="Edit Book" onclick="event.stopPropagation()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </a>
                        <form action="{{ route('admin.delete', $buku['id']) }}" method="POST" onsubmit="event.stopPropagation(); return confirm('Remove this book from the catalog?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-3 bg-white rounded-xl text-red-500 shadow-xl hover:scale-110 active:scale-95 transition-transform" title="Delete Book">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-1 12a2 2 0 01-2 2H8a2 2 0 01-2-2L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                
                <a href="{{ route('admin.katalog.detail', $buku['id']) }}" class="group/title relative block">
                    <span class="font-bold text-gray-800 line-clamp-2 mb-1 text-sm sm:text-base sm:text-lg group-hover/title:text-burgundy-500 transition-colors leading-snug">{{ $buku['judul'] }}</span>
                    <span class="pointer-events-none absolute -top-10 left-1/2 -translate-x-1/2 whitespace-normal w-max break-words rounded-lg bg-gray-800 px-2.5 py-1.5 text-[11px] font-medium text-white opacity-0 group-hover/title:opacity-100 transition-opacity duration-200 shadow-lg z-50 max-w-[200px] text-center leading-tight after:content-[''] after:absolute after:left-1/2 after:-translate-x-1/2 after:top-full after:border-4 after:border-transparent after:border-t-gray-800">{{ $buku['judul'] }}</span>
                </a>
                <div class="relative group/author inline-block w-full">
                    <p class="text-[11px] sm:text-xs text-gray-400 mb-3 sm:mb-6 font-medium line-clamp-1">{{ $buku['penulis'] }}</p>
                    <span class="pointer-events-none absolute -top-10 left-1/2 -translate-x-1/2 whitespace-normal w-max break-words rounded-lg bg-gray-700 px-2.5 py-1.5 text-[11px] font-medium text-white opacity-0 group-hover/author:opacity-100 transition-opacity duration-200 shadow-lg z-50 max-w-[200px] text-center leading-tight after:content-[''] after:absolute after:left-1/2 after:-translate-x-1/2 after:top-full after:border-4 after:border-transparent after:border-t-gray-700">{{ $buku['penulis'] }}</span>
                </div>
                
                <div class="mt-auto pt-3 sm:pt-5 border-t border-red-50 flex items-center justify-between gap-1">
                    <span class="px-1.5 sm:px-2 py-1 rounded bg-white/80 text-[9px] sm:text-[10px] font-bold text-burgundy-500 uppercase tracking-tighter border border-red-100 truncate max-w-[50%] sm:max-w-[60%]">{{ $buku['genre'] }}</span>
                    <span class="hidden md:inline text-[10px] font-bold text-gray-300 uppercase tracking-widest shrink-0">ID: {{ $buku['book_id'] ?? '#00'.$buku['id'] }}</span>
                    
                    <!-- Mobile Actions -->
                    <div class="flex md:hidden items-center gap-1">
                        <a href="{{ route('admin.edit_buku', $buku['id']) }}" class="text-burgundy-500 font-bold text-[9px] bg-red-50 px-2 py-1.5 rounded-md border border-red-100 uppercase tracking-widest shrink-0">Edit</a>
                        <form action="{{ route('admin.delete', $buku['id']) }}" method="POST" onsubmit="return confirm('Remove this book from the catalog?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 font-bold text-[9px] bg-red-50 px-2 py-1.5 rounded-md border border-red-100 uppercase tracking-widest shrink-0">Del</button>
                        </form>
                    </div>
                </div>
            </x-ui.glass-card>
            @empty
            <div class="col-span-full py-12 sm:py-20 text-center animate-fade-up">
                <div class="w-20 h-20 sm:w-24 sm:h-24 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 sm:h-12 sm:w-12 text-red-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <p class="text-gray-400 font-medium text-lg sm:text-xl">The catalog is still empty 📚</p>
                <p class="text-gray-400 text-xs sm:text-sm mt-2">Start adding new books to manage them here.</p>
                <a href="{{ route('admin.buku.create') }}" class="inline-block mt-4 sm:mt-6 px-6 sm:px-8 py-2.5 sm:py-3 bg-burgundy-500 text-white rounded-xl sm:rounded-2xl font-bold shadow-lg shadow-red-100 hover:bg-maroon transition-all text-sm">Add First Book</a>
            </div>
            @endforelse
        </div>
    </template>
    </div>

    <!-- Table View -->
    <div x-cloak>
    <template x-if="view === 'table'">
        <x-ui.glass-card class="overflow-hidden border border-white/60 animate-fade-up shadow-2xl shadow-red-50">
            <div class="overflow-x-auto -mx-px">
                <table class="w-full text-left border-collapse min-w-[500px]">
                    <thead class="bg-red-50/50 text-gray-400 text-[10px] font-bold uppercase tracking-widest">
                        <tr>
                            <th class="px-4 sm:px-8 py-4 sm:py-5">Book Info</th>
                            <th class="px-4 sm:px-8 py-4 sm:py-5 hidden sm:table-cell">Genre & Stock</th>
                            <th class="px-4 sm:px-8 py-4 sm:py-5">Status</th>
                            <th class="px-4 sm:px-8 py-4 sm:py-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-50">
                        @forelse($Buku as $index => $buku)
                        <tr class="group hover:bg-red-50/30 transition-colors duration-300">
                            <td class="px-4 sm:px-8 py-4 sm:py-6">
                                <div class="flex items-center gap-3 sm:gap-5">
                                    <a href="{{ route('admin.katalog.detail', $buku['id']) }}" class="w-10 h-14 sm:w-12 sm:h-16 bg-white rounded-lg sm:rounded-xl shadow-md flex items-center justify-center overflow-hidden border border-white group-hover:scale-110 transition-transform duration-500 flex-shrink-0">
                                        <img src="{{ asset('images/' . ($buku['cover'] ?? 'readspace-library.png')) }}" 
                                             class="w-full h-full object-cover"
                                             data-fallback="{{ asset('images/readspace-library.png') }}"
                                             onerror="this.src=this.dataset.fallback">
                                    </a>
                                    <div class="min-w-0">
                                        <div class="relative group/title inline-block max-w-full">
                                            <a href="{{ route('admin.katalog.detail', $buku['id']) }}" class="font-bold text-gray-800 hover:text-burgundy-500 transition-colors text-sm line-clamp-1 block">{{ $buku['judul'] }}</a>
                                            <span class="pointer-events-none absolute -top-10 left-0 whitespace-normal w-max break-words rounded-lg bg-gray-800 px-2.5 py-1.5 text-[11px] font-medium text-white opacity-0 group-hover/title:opacity-100 transition-opacity duration-200 shadow-lg z-50 max-w-[250px] after:content-[''] after:absolute after:left-4 after:top-full after:border-4 after:border-transparent after:border-t-gray-800">{{ $buku['judul'] }}</span>
                                        </div>
                                        <div class="relative group/author inline-block max-w-full">
                                            <p class="text-xs text-gray-400 font-medium line-clamp-1">{{ $buku['penulis'] }}</p>
                                            <span class="pointer-events-none absolute -top-10 left-0 whitespace-normal w-max break-words rounded-lg bg-gray-700 px-2.5 py-1.5 text-[11px] font-medium text-white opacity-0 group-hover/author:opacity-100 transition-opacity duration-200 shadow-lg z-50 max-w-[250px] after:content-[''] after:absolute after:left-4 after:top-full after:border-4 after:border-transparent after:border-t-gray-700">{{ $buku['penulis'] }}</span>
                                        </div>
                                        <div class="sm:hidden mt-1 flex gap-1 items-center">
                                            <span class="inline-block px-2 py-0.5 rounded bg-white/80 text-[9px] font-bold text-burgundy-500 uppercase border border-red-100 shrink-0">{{ $buku['genre'] }}</span>
                                            <span class="text-[9px] font-bold text-gray-400 shrink-0">Stok: {{ $buku['stok'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 sm:px-8 py-4 sm:py-6 hidden sm:table-cell">
                                <div class="flex flex-col gap-1 items-start">
                                    <span class="px-3 py-1.5 rounded-lg bg-white/80 text-gray-500 text-[10px] font-bold uppercase tracking-widest border border-red-50">
                                        {{ $buku['genre'] }}
                                    </span>
                                    <span class="text-[10px] font-bold text-gray-400 ml-1">Stock: {{ $buku['stok'] }}</span>
                                </div>
                            </td>
                            <td class="px-4 sm:px-8 py-4 sm:py-6">
                                @php
                                    $dotClass = '';
                                    $textClass = '';
                                    $text = '';
                                    if ($buku['status'] == 'Tersedia' && ($buku['stok'] ?? 0) > 0) {
                                        $dotClass = 'bg-green-500 shadow-lg shadow-green-200';
                                        $textClass = 'text-green-600';
                                        $text = 'Available';
                                    } elseif ($buku['status'] == 'Perawatan') {
                                        $dotClass = 'bg-yellow-500 shadow-lg shadow-yellow-200';
                                        $textClass = 'text-yellow-600';
                                        $text = 'Maintenance';
                                    } elseif ($buku['status'] == 'Hilang') {
                                        $dotClass = 'bg-gray-500 shadow-lg shadow-gray-200';
                                        $textClass = 'text-gray-600';
                                        $text = 'Lost';
                                    } else {
                                        $dotClass = 'bg-red-400 shadow-lg shadow-red-100';
                                        $textClass = 'text-red-400';
                                        $text = 'Borrowed';
                                    }
                                @endphp
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full {{ $dotClass }}"></div>
                                    <span class="text-xs sm:text-sm font-bold {{ $textClass }}">
                                        {{ $text }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 sm:px-8 py-4 sm:py-6 text-right">
                                <div class="flex items-center justify-end gap-1 sm:gap-2">
                                    <a href="{{ route('admin.edit_buku', $buku['id']) }}" class="p-2 sm:p-2.5 bg-white border border-gray-100 rounded-lg sm:rounded-xl text-burgundy-500 hover:bg-red-50 transition-colors shadow-sm" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.delete', $buku['id']) }}" method="POST" class="inline" onsubmit="return confirm('Remove this book from the catalog?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 sm:p-2.5 bg-white border border-gray-100 rounded-lg sm:rounded-xl text-red-500 hover:bg-red-50 transition-colors shadow-sm" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-1 12a2 2 0 01-2 2H8a2 2 0 01-2-2L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-12 text-center text-gray-400 font-medium">The catalog is empty.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-ui.glass-card>
    </template>
    </div>

    <div class="mt-6 sm:mt-8 flex justify-center text-gray-700 w-full" x-cloak>
        @if(isset($Buku) && method_exists($Buku, 'links'))
            {{ $Buku->appends(['view' => request('view', 'grid')])->links('components.ui.pagination') }}
        @endif
    </div>

</div>

<!-- Modal Success -->
@if(session('success'))
<!-- Will use the global toast notification system in app.blade.php instead -->
@endif

@endsection
