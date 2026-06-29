@extends('user.layouts.app')

@section('content')
{{-- 
    FIX: view state disimpan di URL (?view=table) supaya
    pagination tidak mereset pilihan tampilan ke grid.
--}}
<div class="py-6 sm:py-10 space-y-6 sm:space-y-10"
     x-data="{
        view: new URLSearchParams(window.location.search).get('view') || 'grid',
        setView(v) {
            this.view = v;
            const url = new URL(window.location.href);
            url.searchParams.set('view', v);
            history.replaceState(null, '', url.toString());
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

    <!-- Grid View -->
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
                            $statusText = 'AVAIL';
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
                
                <a href="{{ route('katalog.detail', $buku['id']) }}" class="group/title">
                    <h3 class="font-bold text-gray-800 line-clamp-2 mb-1 text-sm sm:text-base sm:text-lg group-hover/title:text-burgundy-500 transition-colors leading-snug">{{ $buku['judul'] }}</h3>
                </a>
                <p class="text-[11px] sm:text-xs text-gray-400 mb-3 sm:mb-6 font-medium line-clamp-1">{{ $buku['penulis'] }}</p>
                
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

    <!-- Table View -->
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
                                        <img src="{{ asset('images/' . ($buku['cover'] ?? 'readspace-library.png')) }}" class="w-full h-full object-cover">
                                    </a>
                                    <div class="min-w-0">
                                        <a href="{{ route('katalog.detail', $buku['id']) }}" class="font-bold text-gray-800 hover:text-burgundy-500 transition-colors text-sm line-clamp-1">{{ $buku['judul'] }}</a>
                                        <p class="text-xs text-gray-400 font-medium line-clamp-1">{{ $buku['penulis'] }}</p>
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

    {{-- Pagination: sertakan ?view= agar state tidak hilang saat ganti halaman --}}
    <div class="mt-6 sm:mt-8 flex justify-center text-gray-700 w-full" x-cloak>
        @if(isset($daftarBuku) && method_exists($daftarBuku, 'links'))
            {{-- append view param ke semua link pagination --}}
            {{ $daftarBuku->appends(['view' => request('view', 'grid')])->links() }}
        @endif
    </div>

</div>
@endsection
