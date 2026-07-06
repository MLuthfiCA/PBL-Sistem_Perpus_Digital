@extends('user.layouts.app')

@section('content')
{{-- 
    FIX: view state disimpan di URL (?view=table) supaya
    pagination tidak mereset pilihan tampilan ke grid.
--}}
<div class="py-6 sm:py-10 space-y-6 sm:space-y-10"
     x-data="{
        view: localStorage.getItem('catalog_view') || new URLSearchParams(window.location.search).get('view') || 'grid',
        showFilters: false,
        selectedCategories: {{ json_encode($categories) }},
        init() {
            window.addEventListener('pageshow', () => {
                this.view = localStorage.getItem('catalog_view') || this.view;
            });
        },
        setView(v) {
            this.view = v;
            localStorage.setItem('catalog_view', v);
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
        },
        toggleCategory(cat) {
            if (this.selectedCategories.includes(cat)) {
                this.selectedCategories = this.selectedCategories.filter(c => c !== cat);
            } else {
                this.selectedCategories.push(cat);
            }
        }
     }">

    <!-- Overdue Books Warning -->
    <x-overdue-warning />

    <!-- Page Header -->
    <x-ui.page-header 
        title="Library Catalog"
        subtitle="Discover and borrow our best digital collections."
    >
        <!-- View Toggle -->
        <div class="flex p-1 bg-white/60 backdrop-blur-md rounded-2xl border border-white/80 shadow-xl shadow-red-50 w-fit">
            <button @click="setView('grid')"
                    :class="view === 'grid' ? 'bg-burgundy-500 shadow-lg text-white' : 'text-gray-400'"
                    class="px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl transition-all duration-300 flex items-center gap-1.5 sm:gap-2 font-bold text-xs sm:text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 14a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 14a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Grid
            </button>
            <button @click="setView('table')"
                    :class="view === 'table' ? 'bg-burgundy-500 shadow-lg text-white' : 'text-gray-400'"
                    class="px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl transition-all duration-300 flex items-center gap-1.5 sm:gap-2 font-bold text-xs sm:text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                Table
            </button>
        </div>

    </x-ui.page-header>

    <!-- Search Bar -->
    <div x-data="{
            showFilters: false,
            selectedCategories: {{ json_encode($categories ?? []) }}
         }"
         class="max-w-3xl mx-auto mb-8 sm:mb-10 animate-fade-up delay-100 relative z-40">

        <form id="search-form" action="{{ route('katalog') }}" method="GET"
              class="flex flex-col sm:flex-row gap-3 sm:gap-4 relative items-stretch">
            
            <input type="hidden" name="view" :value="view">

            {{-- Hidden inputs untuk multi-kategori --}}
            <template x-for="cat in selectedCategories" :key="cat">
                <input type="hidden" name="categories[]" :value="cat">
            </template>

            <div class="relative w-full group">
                <input type="text" name="query" value="{{ request('query') }}"
                    placeholder="Search by title or author..."
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
                        @if(isset($allCategories))
                            @foreach($allCategories as $cat)
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
            @foreach($daftarBuku as $index => $buku)
            <x-ui.glass-card class="p-3 sm:p-4 flex flex-col group animate-fade-up border-white/60" style="animation-delay: {{ $index * 100 }}ms">
                <a href="{{ route('katalog.detail', $buku['id']) }}" class="relative h-44 sm:h-56 md:h-64 rounded-xl sm:rounded-2xl mb-3 sm:mb-5 overflow-hidden bg-gradient-to-br from-red-50 to-rose-100 flex items-center justify-center border border-white/20 group-hover:shadow-2xl transition-all duration-500">
                    <img src="{{ asset('images/' . ($buku['cover'] ?? 'readspace-library.png')) }}" 
                        class="h-4/5 object-contain shadow-2xl transform group-hover:scale-110 group-hover:rotate-2 transition-transform duration-700"
                        onerror="this.src='{{ asset('images/readspace-library.png') }}'">
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
                    <div class="absolute top-2 sm:top-4 right-2 sm:right-4 px-2 sm:px-3 py-1 sm:py-1.5 rounded-full text-[9px] sm:text-[10px] font-bold uppercase tracking-widest backdrop-blur-xl {{ $badgeClass }}">
                        {{ $statusText }}
                    </div>
                </a>
                
                <a href="{{ route('katalog.detail', $buku['id']) }}" class="group/title relative">
                    <h3 class="font-bold text-gray-800 line-clamp-2 mb-1 text-sm sm:text-base sm:text-lg group-hover/title:text-burgundy-500 transition-colors leading-snug">{{ $buku['judul'] }}</h3>
                    <span class="pointer-events-none absolute -top-10 left-1/2 -translate-x-1/2 whitespace-normal w-max break-words rounded-lg bg-gray-800 px-2.5 py-1.5 text-[11px] font-medium text-white opacity-0 group-hover/title:opacity-100 transition-opacity duration-200 shadow-lg z-50 max-w-[200px] text-center leading-tight after:content-[''] after:absolute after:left-1/2 after:-translate-x-1/2 after:top-full after:border-4 after:border-transparent after:border-t-gray-800">{{ $buku['judul'] }}</span>
                </a>
                <div class="relative group/author inline-block">
                    <p class="text-[11px] sm:text-xs text-gray-400 mb-3 sm:mb-6 font-medium line-clamp-1">{{ $buku['penulis'] }}</p>
                    <span class="pointer-events-none absolute -top-10 left-1/2 -translate-x-1/2 whitespace-normal w-max break-words rounded-lg bg-gray-700 px-2.5 py-1.5 text-[11px] font-medium text-white opacity-0 group-hover/author:opacity-100 transition-opacity duration-200 shadow-lg z-50 max-w-[200px] text-center leading-tight after:content-[''] after:absolute after:left-1/2 after:-translate-x-1/2 after:top-full after:border-4 after:border-transparent after:border-t-gray-700">{{ $buku['penulis'] }}</span>
                </div>
                
                <div class="mt-auto pt-3 sm:pt-5 border-t border-red-50 flex items-center justify-between gap-2">
                    <span class="px-1.5 sm:px-2 py-1 rounded bg-white/80 text-[9px] sm:text-[10px] font-bold text-burgundy-500 uppercase tracking-tighter border border-red-100 truncate max-w-[60%]">{{ $buku['genre'] }}</span>
                    @if($buku['status'] == 'Tersedia' && ($buku['stok'] ?? 0) > 0)
                    <a href="{{ route('pengajuan', ['judul' => $buku['judul'], 'id' => $buku['id'], 'book_id' => $buku['book_id'] ?? ('B-' . str_pad($buku['id'], 3, '0', STR_PAD_LEFT)), 'cover' => $buku['cover']]) }}" class="text-burgundy-500 hover:text-maroon font-bold text-[11px] sm:text-xs flex items-center gap-1 transition-all group-hover:translate-x-1 whitespace-nowrap flex-shrink-0">
                        Borrow
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-3.5 sm:w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    @else
                    <span class="text-gray-300 font-bold text-[11px] sm:text-xs italic tracking-wide">
                        {{ $buku['status'] == 'Perawatan' ? 'Maintenance' : ($buku['status'] == 'Hilang' ? 'Lost' : 'Borrowed') }}
                    </span>
                    @endif
                </div>
            </x-ui.glass-card>
            @endforeach
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
                            <th class="px-4 sm:px-8 py-4 sm:py-5 hidden sm:table-cell">Genre</th>
                            <th class="px-4 sm:px-8 py-4 sm:py-5">Status</th>
                            <th class="px-4 sm:px-8 py-4 sm:py-5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-50">
                        @foreach($daftarBuku as $index => $buku)
                        <tr class="group hover:bg-red-50/30 transition-colors duration-300">
                            <td class="px-4 sm:px-8 py-4 sm:py-6">
                                <div class="flex items-center gap-3 sm:gap-5">
                                    <a href="{{ route('katalog.detail', $buku['id']) }}" class="w-10 h-14 sm:w-12 sm:h-16 bg-white rounded-lg sm:rounded-xl shadow-md flex items-center justify-center overflow-hidden border border-white group-hover:scale-110 transition-transform duration-500 flex-shrink-0">
                                        <img src="{{ asset('images/' . ($buku['cover'] ?? 'readspace-library.png')) }}" 
                                             class="w-full h-full object-cover"
                                             onerror="this.src='{{ asset('images/readspace-library.png') }}'">
                                    </a>
                                    <div class="min-w-0">
                                        <div class="relative group/title inline-block max-w-full">
                                            <a href="{{ route('katalog.detail', $buku['id']) }}" class="font-bold text-gray-800 hover:text-burgundy-500 transition-colors text-sm line-clamp-1 block">{{ $buku['judul'] }}</a>
                                            <span class="pointer-events-none absolute -top-10 left-0 whitespace-normal w-max break-words rounded-lg bg-gray-800 px-2.5 py-1.5 text-[11px] font-medium text-white opacity-0 group-hover/title:opacity-100 transition-opacity duration-200 shadow-lg z-50 max-w-[250px] after:content-[''] after:absolute after:left-4 after:top-full after:border-4 after:border-transparent after:border-t-gray-800">{{ $buku['judul'] }}</span>
                                        </div>
                                        <div class="relative group/author inline-block max-w-full">
                                            <p class="text-xs text-gray-400 font-medium line-clamp-1">{{ $buku['penulis'] }}</p>
                                            <span class="pointer-events-none absolute -top-10 left-0 whitespace-normal w-max break-words rounded-lg bg-gray-700 px-2.5 py-1.5 text-[11px] font-medium text-white opacity-0 group-hover/author:opacity-100 transition-opacity duration-200 shadow-lg z-50 max-w-[250px] after:content-[''] after:absolute after:left-4 after:top-full after:border-4 after:border-transparent after:border-t-gray-700">{{ $buku['penulis'] }}</span>
                                        </div>
                                        <span class="sm:hidden mt-1 inline-block px-2 py-0.5 rounded bg-white/80 text-[9px] font-bold text-burgundy-500 uppercase border border-red-100">{{ $buku['genre'] }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 sm:px-8 py-4 sm:py-6 hidden sm:table-cell">
                                <span class="px-3 py-1.5 rounded-lg bg-white/80 text-gray-500 text-[10px] font-bold uppercase tracking-widest border border-red-50">
                                    {{ $buku['genre'] }}
                                </span>
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
                                @if($buku['status'] == 'Tersedia' && ($buku['stok'] ?? 0) > 0)
                                <a href="{{ route('pengajuan', ['judul' => $buku['judul'], 'id' => $buku['id'], 'book_id' => $buku['book_id'] ?? ('B-' . str_pad($buku['id'], 3, '0', STR_PAD_LEFT)), 'cover' => $buku['cover']]) }}" class="px-3 sm:px-5 py-2 sm:py-2.5 bg-burgundy-500 text-white rounded-lg sm:rounded-xl text-xs font-bold shadow-lg shadow-red-100 hover:bg-maroon transition-all inline-block">
                                    Borrow
                                </a>
                                @else
                                <span class="text-gray-300 text-xs font-bold italic">N/A</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-ui.glass-card>
    </template>
    </div>

    {{-- Pagination: sertakan ?view= agar state tidak hilang saat ganti halaman --}}
    <div class="mt-6 sm:mt-8 flex justify-center text-gray-700 w-full" x-cloak>
        @if(isset($daftarBuku) && method_exists($daftarBuku, 'links'))
            {{-- append view param ke semua link pagination --}}
            {{ $daftarBuku->appends(['view' => request('view', 'grid')])->links('components.ui.pagination') }}
        @endif
    </div>

</div>
@endsection
