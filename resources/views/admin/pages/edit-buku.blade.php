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

            @if(session('success'))
            <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-100 text-green-700">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-100 text-red-700">
                {{ session('error') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-yellow-50 border border-yellow-100 text-yellow-700">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <!-- COVER LEFT -->
                <div class="md:col-span-1 w-full flex flex-col">

                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">
                        Book Cover
                    </label>

                    <label for="cover_input" class="cursor-pointer flex-1 min-h-[300px] border-2 border-dashed border-gray-300 bg-white/50 rounded-3xl flex flex-col items-center justify-center hover:bg-gray-50 hover:border-red-200 transition-all group relative overflow-hidden">

                        <input type="file" name="cover" id="cover_input" class="hidden" accept="image/*">

                        @if(isset($buku['cover']) && $buku['cover'])
                            <img id="cover_preview" src="{{ asset('images/' . $buku['cover']) }}" alt="Cover" class="w-full h-full object-contain">
                        @else
                            <div class="text-center group-hover:scale-105 transition-transform">
                                <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 text-burgundy-500 group-hover:bg-red-100 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-gray-500 group-hover:text-burgundy-500 transition-colors">Upload / Replace Cover</p>
                            </div>
                        @endif

                    </label>

                </div>

                <div class="md:col-span-2 space-y-6">
                    <!-- JUDUL -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Book Title</label>
                        <input type="text" name="judul" value="{{ $buku['judul'] }}" required
                            class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm transition-all">
                    </div>

                    <!-- OPTIONAL BOOK ID AND BOOK SHELF -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Book ID (Optional)</label>
                            <input type="number" name="buku_id" value="{{ $buku['buku_id'] ?? '' }}" min="1"
                                class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm transition-all"
                                placeholder="Optional — leave blank to keep current">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Book Shelf</label>
                            <input type="text" name="lokasi_rak" value="{{ $buku['lokasi_rak'] ?? '' }}" placeholder="e.g., Shelf A1"
                                class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm transition-all">
                        </div>
                    </div>

                    <!-- AUTHOR & PUBLISHER -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Author</label>
                            <input type="text" name="penulis" value="{{ $buku['penulis'] }}" required
                                class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Publisher</label>
                            <input type="text" name="penerbit" value="{{ $buku['penerbit'] ?? '' }}" placeholder="Publisher Name"
                                class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm transition-all">
                        </div>
                    </div>

                    <!-- ISBN -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">ISBN</label>
                        <input type="text" name="isbn" value="{{ $buku['isbn'] ?? '' }}" required
                            class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm transition-all">
                    </div>

                    <!-- GENRE, STATUS, TAHUN TERBIT, CETAKAN, BAHASA, STOK -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Category (Genre)</label>
                            <select name="id_kategori" required class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm transition-all appearance-none cursor-pointer">
                                <option value="">Select Category</option>
                                @foreach($kategoris as $kat)
                                    <option value="{{ $kat->id_kategori }}" {{ ($buku['id_kategori'] ?? '') == $kat->id_kategori ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Status</label>
                            <select name="status" required class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm transition-all appearance-none cursor-pointer">
                                <option value="">Select Status</option>
                                <option value="Tersedia" {{ ($buku['status'] ?? '') == 'Tersedia' || ($buku['status'] ?? '') == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="Dipinjam" {{ ($buku['status'] ?? '') == 'Dipinjam' || ($buku['status'] ?? '') == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                                <option value="Hilang" {{ ($buku['status'] ?? '') == 'Hilang' ? 'selected' : '' }}>Lost</option>
                                <option value="Perawatan" {{ ($buku['status'] ?? '') == 'Perawatan' ? 'selected' : '' }}>Maintenance</option>
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
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Language</label>
                            <input type="text" name="bahasa" value="{{ $buku['bahasa'] ?? 'Indonesia' }}" placeholder="e.g., Indonesia atau English"
                                class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Stock</label>
                            <input type="number" name="stok" value="{{ $buku['stok'] ?? 1 }}" min="0"
                                class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm transition-all">
                        </div>
                    </div>

                    <!-- DESCRIPTION -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Description</label>
                        <textarea name="deskripsi" rows="5" class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm transition-all" placeholder="Write a short description for this book">{{ $buku['deskripsi'] ?? '' }}</textarea>
                    </div>

                    <!-- SUBMIT BUTTON -->
                    <div class="pt-6">
                        <button type="submit" class="w-full md:w-auto px-8 py-4 rounded-2xl bg-burgundy-500 text-white font-bold shadow-lg shadow-red-100 hover:bg-maroon transition-all transform active:scale-95 flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </div>

                
            </div>
        </form>
    </x-ui.glass-card>
    <script>
        (function(){
            const input = document.getElementById('cover_input');
            const preview = document.getElementById('cover_preview');
            if (!input) return;
            input.addEventListener('change', function(e){
                const file = this.files && this.files[0];
                if (!file) return;
                const url = URL.createObjectURL(file);
                if (preview) preview.src = url;
            });
        })();
    </script>
</div>
@endsection
