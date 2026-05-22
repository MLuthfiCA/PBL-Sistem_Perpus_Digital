@extends('admin.layouts.app')

@section('content')
<div class="py-10 space-y-8 animate-fade-up">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-4xl font-bold text-gray-800">Edit Book Data</h1>
            <p class="text-gray-500 mt-2">Update book information <span class="text-burgundy-500 font-bold">{{ $buku['judul'] }}</span> in the ReadSpace collection.</p>
        </div>
        <div>
            <a href="{{ route('admin.katalog') }}" class="px-6 py-3 rounded-xl bg-white text-gray-600 font-bold shadow-sm border border-gray-100 hover:bg-gray-50 transition-all text-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Cancel
            </a>
        </div>
    </div>

    <x-ui.glass-card class="p-8 border-white/60 shadow-2xl shadow-red-50">
        <form action="{{ route('admin.update', $buku['id']) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <div class="md:col-span-2 space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Book title</label>
                        <input type="text" name="judul" value="{{ $buku['judul'] }}" required
                            class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm transition-all">
                    </div>
                    
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Author</label>
                            <input type="text" name="penulis" value="{{ $buku['penulis'] }}" required
                                class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Publisher</label>
                            <input type="text" name="penerbit" value="{{ $buku['penerbit'] ?? '' }}" placeholder="Publisher Name"
                                class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Genre</label>
                            <select name="genre" class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm transition-all appearance-none cursor-pointer">
                                <option value="Drama" {{ $buku['genre'] == 'Drama' ? 'selected' : '' }}>Drama</option>
                                <option value="Fantasi" {{ $buku['genre'] == 'Fantasi' ? 'selected' : '' }}>Fantasy</option>
                                <option value="Self-Dev" {{ $buku['genre'] == 'Self-Dev' ? 'selected' : '' }}>Self-Dev</option>
                                <option value="Romance" {{ ($buku['genre'] ?? '') == 'Romance' ? 'selected' : '' }}>Romance</option>
                                <option value="Edukasi" {{ ($buku['genre'] ?? '') == 'Edukasi' ? 'selected' : '' }}>Education</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Status</label>
                            <select name="status" class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm transition-all appearance-none cursor-pointer">
                                <option value="Tersedia" {{ $buku['status'] == 'Tersedia' ? 'selected' : '' }}>Available</option>
                                <option value="Dipinjam" {{ $buku['status'] == 'Dipinjam' ? 'selected' : '' }}>Borrowed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Publication Year</label>
                            <input type="text" name="tahun_terbit" value="{{ $buku['tahun_terbit'] ?? '' }}" placeholder="e.g., 2024"
                                class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Edition</label>
                            <input type="text" name="cetakan" value="{{ $buku['cetakan'] ?? '' }}" placeholder="e.g., First edition"
                                class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm transition-all">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Language / Translation (Bahasa)</label>
                            <input type="text" name="bahasa" value="{{ $buku['bahasa'] ?? 'Indonesia' }}" placeholder="e.g., Indonesia atau Terjemahan Inggris"
                                class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm transition-all">
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="w-full md:w-auto px-8 py-4 rounded-2xl bg-burgundy-500 text-white font-bold shadow-lg shadow-red-100 hover:bg-maroon transition-all transform active:scale-95 flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </div>

                <div class="w-full flex flex-col space-y-4">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Book Cover</label>
                    <div class="flex-1 min-h-[320px] bg-red-50/30 border-2 border-dashed border-red-100 rounded-3xl flex flex-col items-center justify-center p-6 relative group overflow-hidden transition-all hover:bg-red-50/50">
                        @if(isset($buku['cover']) && $buku['cover'])
                            <img src="{{ asset('images/' . $buku['cover']) }}" alt="Cover" class="max-h-[320px] md:max-h-[420px] w-full object-contain rounded-xl shadow-xl transition-transform group-hover:scale-105">
                        @else
                            <div class="text-center text-red-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="font-bold text-sm text-red-300">No cover yet</p>
                            </div>
                        @endif
                        
                        <div class="absolute inset-0 bg-burgundy-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <label for="cover_input" class="cursor-pointer px-6 py-3 bg-white rounded-xl text-burgundy-500 font-bold text-sm shadow-xl hover:scale-105 transition-transform">
                                Replace Cover
                                <input type="file" id="cover_input" name="cover" class="hidden" accept="image/*">
                            </label>
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-400 text-center uppercase tracking-widest">Click on the image to change the cover</p>
                </div>

            </div>
        </form>
    </x-ui.glass-card>
</div>
@endsection
