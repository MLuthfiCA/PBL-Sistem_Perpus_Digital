@extends('admin.layouts.app')

@section('title', 'Katalog Buku Admin')

@section('content')
<div class="py-4 md:py-10 space-y-6 md:space-y-10" x-data="{ showModal: {{ session('success') ? 'true' : 'false' }}, view: 'grid' }">
    
    <!-- Page Header -->
    <x-ui.page-header 
        title="Catalog Management" 
        subtitle="Manage all books available in the Readspace Library."
    >
        <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
            <!-- View Toggle -->
            <div class="flex p-1 bg-white/60 backdrop-blur-md rounded-2xl border border-white/80 shadow-xl shadow-red-50 w-full sm:w-fit justify-center">
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

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.buku.create') }}" class="w-full sm:w-auto justify-center px-6 py-3.5 bg-burgundy-500 text-white rounded-2xl text-sm font-bold shadow-lg shadow-red-100 hover:bg-maroon transition-all transform active:scale-95 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New Book
            </a>
                <a href="{{ route('admin.katalog.trash') }}" class="px-4 py-2 bg-white rounded-2xl border border-gray-100 shadow-sm text-sm font-bold hover:bg-red-50">View Trash</a>
            </div>
        </div>

    </x-ui.page-header>

    <!-- Grid View -->
    <template x-if="view === 'grid'">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8">
            @forelse($Buku as $index => $buku)
            <x-ui.glass-card class="p-4 flex flex-col animate-fade-up border-white/60" style="animation-delay: {{ $index * 100 }}ms">
                <div class="group relative h-64 rounded-2xl mb-5 overflow-hidden bg-gradient-to-br from-red-50 to-rose-100 flex items-center justify-center border border-white/20">
                    <a href="{{ route('admin.katalog.detail', $buku['id']) }}" class="absolute inset-0 flex items-center justify-center">
                        <!-- Real Image from images folder -->
                        <img src="{{ asset('images/' . ($buku['cover'] ?? 'readspace-library.png')) }}" 
                            class="h-4/5 object-contain shadow-2xl transform group-hover:scale-110 group-hover:rotate-2 transition-transform duration-700"
                            data-fallback="{{ asset('images/readspace-library.png') }}"
                            onerror="this.src=this.dataset.fallback" alt="Book Cover">
                    </a>
                    
                    <!-- Availability Badge -->
                    <div class="absolute top-4 right-4 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest backdrop-blur-xl {{ ($buku['status'] == 'Tersedia' && ($buku['stok'] ?? 0) > 0) ? 'bg-green-500/10 text-green-600 border border-green-200' : 'bg-red-500/10 text-red-600 border border-red-200' }}">
                        {{ ($buku['status'] == 'Tersedia' && ($buku['stok'] ?? 0) > 0) ? 'AVAILABLE' : 'BORROWED' }}
                    </div>

                    <!-- Admin Action Overlay (Desktop only) -->
                    <div class="absolute inset-0 bg-burgundy-900/40 opacity-0 group-hover:opacity-100 transition-opacity hidden md:flex items-center justify-center gap-3 backdrop-blur-[2px]">
                        <a href="{{ route('admin.katalog.detail', $buku['id']) }}" class="p-3 bg-white rounded-xl text-burgundy-500 shadow-xl hover:scale-110 active:scale-95 transition-transform" title="Lihat Detail">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                        <a href="{{ route('admin.edit_buku', $buku['id']) }}" class="p-3 bg-white rounded-xl text-burgundy-500 shadow-xl hover:scale-110 active:scale-95 transition-transform" title="Edit Buku" onclick="event.stopPropagation()">
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
                
                <a href="{{ route('admin.katalog.detail', $buku['id']) }}" class="font-bold text-gray-800 line-clamp-1 mb-1 text-lg hover:text-burgundy-500 transition-colors block">{{ $buku['judul'] }}</a>
                <p class="text-xs text-gray-400 mb-6 font-medium">{{ $buku['penulis'] }}</p>
                
                <div class="mt-auto pt-5 border-t border-red-50 flex items-center justify-between">
                    <span class="px-2 py-1 rounded bg-white/80 text-[10px] font-bold text-burgundy-500 uppercase tracking-tighter border border-red-100">{{ $buku['genre'] }}</span>
                    <span class="hidden md:inline text-[10px] font-bold text-gray-300 uppercase tracking-widest">ID: {{ $buku['book_id'] ?? '#00'.$buku['id'] }}</span>
                    
                    <!-- Mobile Actions -->
                    <div class="flex md:hidden items-center gap-1.5">
                        <a href="{{ route('admin.edit_buku', $buku['id']) }}" class="text-burgundy-500 font-bold text-[10px] bg-red-50 px-2 py-1.5 rounded-lg border border-red-100 uppercase tracking-widest">Edit</a>
                        <form action="{{ route('admin.delete', $buku['id']) }}" method="POST" onsubmit="return confirm('Remove this book from the catalog?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 font-bold text-[10px] bg-red-50 px-2 py-1.5 rounded-lg border border-red-100 uppercase tracking-widest">Delete</button>
                        </form>
                    </div>
                </div>
            </x-ui.glass-card>
            @empty
            <div class="col-span-full py-20 text-center">
                <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 text-burgundy-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <p class="text-gray-400 font-medium">There are no books in the catalog yet.</p>
            </div>
            @endforelse
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
                        @forelse($Buku as $index => $buku)
                        <tr>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-5">
                                    <a href="{{ route('admin.katalog.detail', $buku['id']) }}" class="w-12 h-16 bg-white rounded-xl shadow-md flex items-center justify-center overflow-hidden border border-white hover:scale-110 transition-transform duration-500">
                                        <img src="{{ asset('images/' . ($buku['cover'] ?? 'readspace-library.png')) }}" class="w-full h-full object-cover" onerror="this.src='{{ asset('images/readspace-library.png') }}'">
                                    </a>
                                    <div>
                                        <a href="{{ route('admin.katalog.detail', $buku['id']) }}" class="font-bold text-gray-800 hover:text-burgundy-500 transition-colors">{{ $buku['judul'] }}</a>
                                        <p class="text-xs text-gray-400 font-medium">{{ $buku['penulis'] }}</p>
                                        <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest mt-1 block">ID: {{ 'B-' . str_pad($buku['id'], 3, '0', STR_PAD_LEFT) }}</span>
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
                                                    <div class="w-2.5 h-2.5 rounded-full {{ ($buku['status'] == 'Tersedia' && ($buku['stok'] ?? 0) > 0) ? 'bg-green-500 shadow-lg shadow-green-200' : 'bg-red-400 shadow-lg shadow-red-100' }}"></div>
                                                    <span class="text-sm font-bold {{ ($buku['status'] == 'Tersedia' && ($buku['stok'] ?? 0) > 0) ? 'text-green-600' : 'text-red-400' }}">
                                                        {{ ($buku['status'] == 'Tersedia' && ($buku['stok'] ?? 0) > 0) ? 'Available' : 'Borrowed' }}
                                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.edit_buku', $buku['id']) }}" class="px-4 py-2 bg-white text-burgundy-500 rounded-xl text-xs font-bold shadow-md hover:scale-105 hover:bg-red-50 transition-all flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.delete', $buku['id']) }}" method="POST" onsubmit="return confirm('Remove this book from the catalog?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-white text-red-500 rounded-xl text-xs font-bold shadow-md hover:scale-105 hover:bg-red-50 transition-all flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-1 12a2 2 0 01-2 2H8a2 2 0 01-2-2L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-12 text-center text-gray-400 font-medium">There are no books in the catalog yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-ui.glass-card>
    </template>

    <div class="mt-8 flex justify-center text-gray-700 w-full">
        @if(isset($Buku) && method_exists($Buku, 'links'))
            {{ $Buku->links() }}
        @endif
    </div>

    <!-- Success Modal Pop-up -->
    <div x-show="showModal" style="display: none;"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-maroon/20 backdrop-blur-md">
        
        <x-ui.glass-card class="max-w-sm w-full p-8 text-center border-white shadow-2xl relative overflow-hidden" style="background-color: #FDFBD4;">
            <!-- Decorative Background Icon -->
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-green-50 rounded-full opacity-20"></div>
            
            <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-green-100/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-800 mb-4">A new book was succesfully added to the archive</h2>
            <p class="text-gray-500 text-sm leading-relaxed mb-8">
                {{ session('success') ?? 'A new book has been successfully added to the catalog.' }}
            </p>
            
            <button @click="showModal = false" 
                class="w-full bg-burgundy-500 text-white py-4 rounded-2xl font-bold hover:bg-maroon transition-all shadow-lg shadow-red-100 active:scale-95">
                Close
            </button>
        </x-ui.glass-card>
    </div>
</div>
@endsection
