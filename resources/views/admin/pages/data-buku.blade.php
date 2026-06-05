@extends('admin.layouts.app')

@section('content')
<div class="py-10 space-y-8">

    <x-ui.page-header 
        title="Add Book" 
        subtitle="Add new book collections to the library."
    >
        <a href="{{ route('admin.katalog') }}" 
           class="px-6 py-3 rounded-xl bg-white text-gray-600 font-bold shadow-sm border border-gray-100 hover:bg-gray-50 transition-all text-sm">
            Return to Catalog
        </a>
    </x-ui.page-header>

    <x-ui.glass-card class="p-8 border-white/60 animate-fade-up delay-100 shadow-2xl shadow-red-50">

        <form action="{{ route('admin.buku.store') }}" 
              method="POST" 
              enctype="multipart/form-data">

            @csrf

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

                    <label for="cover_input"
                        class="cursor-pointer flex-1 min-h-[300px] border-2 border-dashed border-gray-300 bg-white/50 rounded-3xl flex flex-col items-center justify-center hover:bg-gray-50 hover:border-red-200 transition-all group relative overflow-hidden">

                        <!-- FIXED -->
                        <input type="file" name="cover" id="cover_input" class="hidden" accept="image/*">
                        <img id="cover_preview" src="{{ asset('images/readspace-library.png') }}" alt="Cover Preview" class="w-full h-full object-contain">

                        <div class="text-center group-hover:scale-105 transition-transform">

                            <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 text-burgundy-500 group-hover:bg-red-100 transition-colors">

                                <svg xmlns="http://www.w3.org/2000/svg" 
                                     class="h-8 w-8" 
                                     fill="none" 
                                     viewBox="0 0 24 24" 
                                     stroke="currentColor">

                                    <path stroke-linecap="round" 
                                          stroke-linejoin="round" 
                                          stroke-width="2" 
                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />

                                </svg>

                            </div>

                            <p class="text-sm font-bold text-gray-500 group-hover:text-burgundy-500 transition-colors">
                                Upload Cover
                            </p>

                            <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wider">
                                JPG, PNG max 2MB
                            </p>

                        </div>

                    </label>

                </div>

                <!-- RIGHT SIDE (FORM) -->
                <div class="md:col-span-2 space-y-6">

                    <!-- JUDUL -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">
                            Book Title
                        </label>

                        <input type="text" 
                               name="judul" 
                               required
                               placeholder="Enter Book Title"
                               class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm">
                    </div>

                    <!-- AUTHOR & PUBLISHER -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">
                                Author
                            </label>

                            <input type="text" 
                                   name="penulis" 
                                   required
                                   placeholder="Author Name"
                                   class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">
                                Publisher
                            </label>

                            <input type="text" 
                                   name="penerbit"
                                   placeholder="Publisher Name"
                                   class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm">
                        </div>

                    </div>

                    <!-- ISBN -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">
                            ISBN
                        </label>

                        <input type="text" 
                               name="isbn"
                               required
                               placeholder="Enter ISBN"
                               class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm">
                    </div>

                    <!-- GRID -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- OPTIONAL BOOK ID -->
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">
                                Book ID (Optional)
                            </label>
                            <input type="number" name="buku_id" min="1" placeholder="Optional — leave blank to auto-generate"
                                class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm">
                        </div>

                        <!-- RAK BUKU -->
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">
                                Book Shelf
                            </label>
                            <input type="text" name="lokasi_rak" placeholder="e.g., Shelf A1"
                                class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm">
                        </div>

                        <!-- CATEGORY -->
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">
                                Category (Genre)
                            </label>
                            <select name="id_kategori" required
                                class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm appearance-none cursor-pointer">
                                <option value="">Select Category</option>
                                @foreach($kategoris as $kat)
                                    <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- STATUS -->
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">
                                Status
                            </label>

                            <select name="status" required
                                class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm">

                                <option value="">Select Status</option>
                                <option value="Tersedia">Available</option>
                                <option value="Dipinjam">Borrowed</option>
                                <option value="Hilang">Lost</option>
                                <option value="Perawatan">Maintenance</option>

                            </select>
                        </div>

                        <!-- TAHUN -->
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">
                                Publication Year
                            </label>
                            <input type="text" name="tahun_terbit" placeholder="e.g., 2024"
                                class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm">
                        </div>

                        <!-- CETAKAN -->
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">
                                Edition
                            </label>

                            <input type="text" 
                                   name="cetakan"
                                   placeholder="e.g. First Edition"
                                   class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm">
                        </div>

                        <!-- BAHASA -->
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">
                                Language
                            </label>

                            <input type="text" 
                                   name="bahasa"
                                   placeholder="Indonesia / English"
                                   class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm">
                        </div>

                        <!-- STOK -->
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">
                                Stock
                            </label>

                            <input type="number" 
                                name="stok"
                                min="0"
                                value="1"
                                class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm">
                        </div>

                    </div>

                        <!-- DESCRIPTION -->
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">
                                Description
                            </label>
                            <textarea name="deskripsi" rows="5" placeholder="Write a short description about the book" class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm"></textarea>
                        </div>

                    <!-- BUTTON -->
                    <div class="pt-6">
                        <button type="submit"
                            class="w-full md:w-auto px-8 py-3.5 rounded-xl bg-burgundy-500 text-white font-bold shadow-lg shadow-red-100 hover:bg-maroon transition-all text-sm">

                            Save Book Data

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
                preview.src = url;
            });
        })();
    </script>
</div>
@endsection