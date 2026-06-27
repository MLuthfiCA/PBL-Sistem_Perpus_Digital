@extends('user.layouts.app')

@section('content')
<div class="py-6 sm:py-10">
    <!-- Header -->
    <div class="mb-8 sm:mb-12 text-center animate-fade-up">
        <h1 class="text-2xl sm:text-4xl font-bold text-gray-800 mb-3 sm:mb-4">Search Book Collections</h1>
        <p class="text-gray-500 text-sm sm:text-base">Find the best references for your studies in the ReadSpace Library.</p>
    </div>

    <!-- Search Bar -->
    <div x-data="{
            showFilters: false,
            selectedCategories: {{ json_encode($categories) }}
         }"
         class="max-w-3xl mx-auto mb-8 sm:mb-16 animate-fade-up delay-100 relative z-40">

        <form id="search-form" action="{{ route('search') }}" method="GET"
              class="flex flex-col sm:flex-row gap-3 sm:gap-4 relative items-stretch">

            {{-- Hidden inputs untuk multi-kategori --}}
            <template x-for="cat in selectedCategories" :key="cat">
                <input type="hidden" name="categories[]" :value="cat">
            </template>

            <div class="relative w-full group">
                <input type="text" name="query" value="{{ request('query') }}"
                    placeholder="Search by title, author or category..."
                    class="w-full pl-6 sm:pl-8 pr-16 sm:pr-20 py-4 sm:py-5 sm:py-6 bg-white/70 backdrop-blur-xl border border-white shadow-2xl shadow-red-50 rounded-2xl sm:rounded-3xl focus:ring-4 focus:ring-red-100 focus:outline-none transition-all text-base sm:text-lg text-gray-700 placeholder-gray-400">
                <button type="submit"
                    class="absolute right-2.5 top-2 sm:top-2.5 sm:top-3 bg-burgundy-500 text-white p-2.5 sm:p-3 sm:p-4 rounded-xl sm:rounded-2xl hover:bg-maroon transition-all shadow-lg shadow-red-200 group-hover:scale-105 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 sm:h-6 w-5 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>

            <div class="relative flex-shrink-0 flex">
                {{-- Tombol filter --}}
                <button type="button" @click="showFilters = !showFilters"
                    class="h-full min-h-[50px] sm:min-h-0 sm:aspect-square w-full sm:w-auto px-4 sm:px-0 bg-white/70 backdrop-blur-xl border border-white shadow-2xl shadow-red-50 rounded-2xl sm:rounded-3xl text-gray-700 hover:text-burgundy-500 hover:bg-white transition-all focus:outline-none focus:ring-4 focus:ring-red-100 flex items-center justify-center gap-2 sm:gap-0 relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 sm:h-6 w-5 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <span class="sm:hidden text-sm font-bold">Filter</span>
                    {{-- Badge jumlah kategori terpilih --}}
                    <span x-show="selectedCategories.length > 0"
                          x-text="selectedCategories.length"
                          class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-burgundy-500 text-white text-[10px] font-bold flex items-center justify-center">
                    </span>
                </button>

                <!-- Dropdown Multi-Kategori -->
                <div x-show="showFilters" x-transition @click.away="showFilters = false"
                     style="display: none;"
                     class="absolute top-full right-0 mt-3 w-64 bg-white rounded-xl shadow-2xl overflow-hidden z-50 border border-gray-100">

                    <div class="bg-burgundy-500 text-white px-5 py-3 font-bold text-sm flex items-center justify-between">
                        <span>Filter's Category</span>
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
                            Terapkan Filter
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

    <!-- Results -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
        @forelse($books as $index => $book)
            <div class="glass-panel p-3 sm:p-4 flex flex-col group animate-fade-up border-white/60" style="--delay: {{ $index * 100 }}ms; animation-delay: var(--delay);">
                <a href="{{ route('katalog.detail', $book->buku_id) }}" class="relative h-40 sm:h-56 md:h-64 rounded-xl sm:rounded-2xl mb-3 sm:mb-4 overflow-hidden bg-gradient-to-br from-red-50 to-rose-100 flex items-center justify-center border border-white/20 group-hover:shadow-2xl transition-all duration-500">
                    @if(isset($book->cover))
                        <img src="{{ asset('images/'.$book->cover) }}" class="h-4/5 object-contain shadow-2xl transform group-hover:scale-110 group-hover:rotate-2 transition-transform duration-700" onerror="this.src='{{ asset('images/readspace-library.png') }}'">
                    @else
                        <div class="w-16 h-24 bg-white shadow-2xl rounded-sm flex items-center justify-center text-3xl font-bold text-red-100">
                            {{ substr($book->judul, 0, 1) }}
                        </div>
                    @endif
                </a>

                <a href="{{ route('katalog.detail', $book->buku_id) }}" class="group/title">
                    <h3 class="font-bold text-gray-800 line-clamp-2 mb-1 text-sm sm:text-base group-hover/title:text-burgundy-500 transition-colors leading-snug">{{ $book->judul }}</h3>
                </a>
                <p class="text-[11px] sm:text-xs text-gray-400 mb-3 sm:mb-6 font-medium line-clamp-1">{{ $book->penulis_nama }}</p>

                <div class="mt-auto">
                    <a href="{{ route('katalog.detail', $book->buku_id) }}" class="block text-center bg-burgundy-500 text-white py-2.5 sm:py-3 rounded-xl sm:rounded-2xl font-bold shadow-lg shadow-red-100 hover:bg-maroon transition-all text-xs sm:text-sm">View Details</a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 sm:py-20 animate-fade-up">
                <div class="w-16 sm:w-24 h-16 sm:h-24 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 sm:h-12 w-8 sm:w-12 text-red-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <p class="text-gray-400 font-medium text-base sm:text-xl">The book you are looking for was not found 📚</p>
                <p class="text-gray-400 text-xs sm:text-sm mt-2">Try using other keywords like title or author.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
