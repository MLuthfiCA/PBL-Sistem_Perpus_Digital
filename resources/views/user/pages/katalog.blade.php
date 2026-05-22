@extends('user.layouts.app')

@section('content')
<div class="py-10 space-y-10" x-data="{ view: 'grid' }">
    
    <!-- Page Header -->
    <x-ui.page-header 
        title="Library Catalog"
        subtitle="Discover and borrow our best digital collections."
    >
        <!-- View Toggle -->
        <div class="flex p-1 bg-white/60 backdrop-blur-md rounded-2xl border border-white/80 shadow-xl shadow-red-50 w-fit">
            <button @click="view = 'grid'" :class="view === 'grid' ? 'bg-burgundy-500 shadow-lg text-white' : 'text-gray-400'" class="px-5 py-2.5 rounded-xl transition-all duration-300 flex items-center gap-2 font-bold text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 14a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 14a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Grid
            </button>
            <button @click="view = 'table'" :class="view === 'table' ? 'bg-burgundy-500 shadow-lg text-white' : 'text-gray-400'" class="px-5 py-2.5 rounded-xl transition-all duration-300 flex items-center gap-2 font-bold text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                Table
            </button>
        </div>
    </x-ui.page-header>

    <!-- Grid View -->
    <template x-if="view === 'grid'">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($daftarBuku as $index => $buku)
            <x-ui.glass-card class="p-4 flex flex-col group animate-fade-up border-white/60" style="animation-delay: {{ $index * 100 }}ms">
                <a href="{{ route('katalog.detail', $buku['id']) }}" class="relative h-64 rounded-2xl mb-5 overflow-hidden bg-gradient-to-br from-red-50 to-rose-100 flex items-center justify-center border border-white/20 group-hover:shadow-2xl transition-all duration-500">
                    <!-- Real Image from images folder -->
                    <img src="{{ asset('images/' . ($buku['cover'] ?? 'readspace-library.png')) }}" 
                        class="h-4/5 object-contain shadow-2xl transform group-hover:scale-110 group-hover:rotate-2 transition-transform duration-700"
                        onerror="this.src='{{ asset('images/readspace-library.png') }}'">
                    
                    <!-- Availability Badge -->
                    <div class="absolute top-4 right-4 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest backdrop-blur-xl {{ $buku['status'] == 'Tersedia' ? 'bg-green-500/10 text-green-600 border border-green-200' : 'bg-red-500/10 text-red-600 border border-red-200' }}">
                        {{ $buku['status'] == 'Tersedia' ? 'AVAILABLE' : 'BORROWED' }}
                    </div>
                </a>
                
                <a href="{{ route('katalog.detail', $buku['id']) }}" class="group/title">
                    <h3 class="font-bold text-gray-800 line-clamp-1 mb-1 text-lg group-hover/title:text-burgundy-500 transition-colors">{{ $buku['judul'] }}</h3>
                </a>
                <p class="text-xs text-gray-400 mb-6 font-medium">{{ $buku['penulis'] }}</p>
                
                <div class="mt-auto pt-5 border-t border-red-50 flex items-center justify-between">
                    <span class="px-2 py-1 rounded bg-white/80 text-[10px] font-bold text-burgundy-500 uppercase tracking-tighter border border-red-100">{{ $buku['genre'] }}</span>
                    @if($buku['status'] == 'Tersedia')
                    <a href="{{ route('pengajuan', ['judul' => $buku['judul'], 'id' => $buku['book_id'] ?? '#00'.$buku['id'], 'cover' => $buku['cover']]) }}" class="text-burgundy-500 hover:text-maroon font-bold text-xs flex items-center gap-1 transition-all group-hover:translate-x-1">
                        Borrow Now
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    @else
                    <span class="text-gray-300 font-bold text-xs italic tracking-wide">Borrowed</span>
                    @endif
                </div>
            </x-ui.glass-card>
            @endforeach
        </div>
    </template>

    <!-- Table View -->
    <template x-if="view === 'table'">
        <x-ui.glass-card class="overflow-hidden border border-white/60 animate-fade-up shadow-2xl shadow-red-50">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-red-50/50 text-gray-400 text-[10px] font-bold uppercase tracking-widest">
                        <tr>
                            <th class="px-8 py-5">Book Info</th>
                            <th class="px-8 py-5">Genre</th>
                            <th class="px-8 py-5">Status</th>
                            <th class="px-8 py-5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-50">
                        @foreach($daftarBuku as $index => $buku)
                        <tr class="group hover:bg-red-50/30 transition-colors duration-300">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-5">
                                    <a href="{{ route('katalog.detail', $buku['id']) }}" class="w-12 h-16 bg-white rounded-xl shadow-md flex items-center justify-center overflow-hidden border border-white group-hover:scale-110 transition-transform duration-500">
                                        <img src="{{ asset('images/' . ($buku['cover'] ?? 'readspace-library.png')) }}" class="w-full h-full object-cover">
                                    </a>
                                    <div>
                                        <a href="{{ route('katalog.detail', $buku['id']) }}" class="font-bold text-gray-800 hover:text-burgundy-500 transition-colors">{{ $buku['judul'] }}</a>
                                        <p class="text-xs text-gray-400 font-medium">{{ $buku['penulis'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1.5 rounded-lg bg-white/80 text-gray-500 text-[10px] font-bold uppercase tracking-widest border border-red-50">
                                    {{ $buku['genre'] }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-2.5 h-2.5 rounded-full {{ $buku['status'] == 'Tersedia' ? 'bg-green-500 shadow-lg shadow-green-200' : 'bg-red-400 shadow-lg shadow-red-100' }}"></div>
                                    <span class="text-sm font-bold {{ $buku['status'] == 'Tersedia' ? 'text-green-600' : 'text-red-400' }}">
                                        {{ $buku['status'] == 'Tersedia' ? 'Available' : 'Borrowed' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                @if($buku['status'] == 'Tersedia')
                                <a href="{{ route('pengajuan', ['judul' => $buku['judul'], 'id' => $buku['book_id'] ?? '#00'.$buku['id'], 'cover' => $buku['cover']]) }}" class="px-5 py-2.5 bg-burgundy-500 text-white rounded-xl text-xs font-bold shadow-lg shadow-red-100 hover:bg-maroon transition-all opacity-0 group-hover:opacity-100 inline-block">
                                    Apply for a loan
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
@endsection
