@extends('admin.layouts.app')

@section('content')
<div class="py-10 space-y-8">
    <x-ui.page-header 
        title="Add Book" 
        subtitle="Add new book collections to the library."
    >
        <a href="{{ route('admin.katalog') }}" class="px-6 py-3 rounded-xl bg-white text-gray-600 font-bold shadow-sm border border-gray-100 hover:bg-gray-50 transition-all text-sm">
            Return to Catalog
        </a>
    </x-ui.page-header>

    <x-ui.glass-card class="p-8 border-white/60 animate-fade-up delay-100 shadow-2xl shadow-red-50">
        <form action="{{ route('admin.buku.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <div class="md:col-span-2 space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Book title</label>
                        <input type="text" name="judul" required placeholder="Enter the Book Title" class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Author</label>
                            <input type="text" name="penulis" required placeholder="Author Name" class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Publisher</label>
                            <input type="text" name="penerbit" placeholder="Publisher Name" class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Genre</label>
                            <select name="genre" required class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm">
                                <option value="">Select Genre</option>
                                <option value="Drama">Drama</option>
                                <option value="Fantasi">Fantasy</option>
                                <option value="Self-Dev">Self-Dev</option>
                                <option value="Romance">Romance</option>
                                <option value="Edukasi">Education</option>
                                <option value="Technology">Technology</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Status</label>
                            <select name="status" required class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm">
                                <option value="">Select Status</option>
                                <option value="Tersedia">Available</option>
                                <option value="Dipinjam">Borrowed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Publication Year</label>
                            <input type="text" name="tahun_terbit" placeholder="e.g., 2024" class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Edition (Cetakan)</label>
                            <input type="text" name="cetakan" placeholder="e.g., Cetakan Pertama" class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Language / Translation (Bahasa)</label>
                            <input type="text" name="bahasa" placeholder="e.g., Indonesia atau Terjemahan Inggris" class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Stock</label>
                            <input type="number" name="stok" min="1" value="1" placeholder="Number of books" class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm">
                        </div>
                    </div>
                    <div class="pt-6">
                        <button type="submit" class="w-full md:w-auto px-8 py-3.5 rounded-xl bg-burgundy-500 text-white font-bold shadow-lg shadow-red-100 hover:bg-maroon transition-all text-sm">
                            Save Book Data
                        </button>
                    </div>
                </div>

                <div class="w-full flex flex-col">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Book Cover</label>
                    <label for="cover_input" class="cursor-pointer flex-1 min-h-[300px] border-2 border-dashed border-gray-300 bg-white/50 rounded-3xl flex flex-col items-center justify-center hover:bg-gray-50 hover:border-red-200 transition-all group relative overflow-hidden">
                        <input type="file" id="cover_input" class="hidden" accept="image/*">
                        <div class="text-center group-hover:scale-105 transition-transform">
                            <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 text-burgundy-500 group-hover:bg-red-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                            </div>
                            <p class="text-sm font-bold text-gray-500 group-hover:text-burgundy-500 transition-colors">Upload Cover</p>
                            <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wider">JPG, PNG max 2MB</p>
                        </div>
                    </label>
                </div>

            </div>
        </form>
    </x-ui.glass-card>
</div>
@endsection
