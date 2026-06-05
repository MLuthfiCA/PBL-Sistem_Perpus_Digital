@extends('user.layouts.app')

@section('content')
<div class="py-10" x-data="{ showModal: {{ session('success') ? 'true' : 'false' }} }">
    <!-- Header -->
    <div class="mb-12 animate-fade-up">
        <h1 class="text-4xl font-bold text-gray-800">Book Loan Form</h1>
        <p class="text-gray-500 mt-2">Complete the data below to process the physical book loan.</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-12">
        <!-- Form Section -->
        <div class="flex-grow glass-panel p-8 md:p-10 animate-fade-up delay-100 border-white/60 shadow-2xl shadow-red-50">
            @if(session('error'))
            <div class="mb-8 p-5 bg-red-50 border-l-4 border-red-500 rounded-xl text-red-700 shadow-lg animate-fade-down flex items-start gap-4">
                <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-red-800 text-sm">Action Failed</h4>
                    <p class="text-xs text-red-600 mt-1 leading-relaxed">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            <form action="{{ route('pengajuan.store') }}" method="POST" class="space-y-8">
                @csrf
                <input type="hidden" name="buku_id" value="{{ request('id') }}">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Borrower Name</label>
                        <input type="text" placeholder="Enter Your Full Name" 
                            value="{{ session()->has('user') ? session('user')['name'] : '' }}"
                            class="w-full p-4 bg-white/50 border border-white rounded-2xl focus:ring-4 focus:ring-red-100 focus:outline-none transition-all font-medium text-gray-700 shadow-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">NIM / Member ID</label>
                        <input type="text" placeholder="Enter Your NIM" 
                            class="w-full p-4 bg-white/50 border border-white rounded-2xl focus:ring-4 focus:ring-red-100 focus:outline-none transition-all font-medium text-gray-700 shadow-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Borrow Date</label>
                        <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" required
                            class="w-full p-4 bg-white/50 border border-white rounded-2xl focus:ring-4 focus:ring-red-100 focus:outline-none transition-all font-medium text-gray-700 shadow-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Return Date</label>
                        <input type="date" id="tanggal_kembali" readonly
                            class="w-full p-4 bg-red-50/30 border border-white rounded-2xl font-medium text-gray-400 cursor-not-allowed">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4 border-t border-red-50">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Book ID</label>
                        <input type="text" value="{{ request('book_id') }}" readonly 
                            class="w-full p-4 bg-red-50/30 border border-white rounded-2xl text-gray-400 font-bold cursor-not-allowed">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Book title</label>
                        <input type="text" value="{{ request('judul') }}" readonly 
                            class="w-full p-4 bg-red-50/30 border border-white rounded-2xl text-gray-400 font-bold cursor-not-allowed">
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" 
                        class="w-full md:w-auto bg-burgundy-500 text-white px-12 py-4 rounded-2xl font-bold text-lg hover:bg-maroon transition-all shadow-xl shadow-red-100 transform hover:-translate-y-1 active:scale-95">
                        Loan Confirmation
                    </button>
                </div>
            </form>
        </div>

        <!-- Sidebar / Preview Section -->
        <div class="w-full lg:w-96 space-y-6 animate-fade-up delay-200">
            <div class="glass-panel p-6 border-white/60">
                <div class="aspect-[3/4] bg-gradient-to-br from-red-50 to-rose-100 rounded-2xl flex items-center justify-center border border-white/20 relative overflow-hidden group mb-6 shadow-inner">
                    @if(request('cover'))
                        <img src="{{ asset('images/' . request('cover')) }}" class="h-4/5 object-contain shadow-2xl transform transition-transform duration-700 group-hover:scale-105" onerror="this.src='{{ asset('images/readspace-library.png') }}'">
                    @else
                        <div class="text-center px-6">
                            <div class="w-16 h-16 bg-white/50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">No Cover Preview</span>
                        </div>
                    @endif
                </div>
                
                <div class="space-y-4">
                    <h3 class="font-bold text-gray-800 text-xl line-clamp-2">{{ request('judul') }}</h3>
                    <div class="p-4 bg-red-50/50 rounded-2xl border border-red-100 text-sm text-burgundy-900 leading-relaxed shadow-sm">
                        <div class="flex gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 text-burgundy-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p>The standard loan period is <strong>7 working days</strong>. Please return the book before the due date to avoid a late fee of <strong>Rp 5.000 per day</strong>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal Pop-up -->
    <template x-teleport="body">
        <div x-show="showModal" style="display: none;"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-maroon/20 backdrop-blur-md">
            
            <div class="glass-panel max-w-sm w-full p-8 text-center border-white shadow-2xl relative overflow-hidden" style="background-color: #FDFBD4;">
                <!-- Decorative Background Icon -->
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-green-50 rounded-full opacity-20"></div>
                
                <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-green-100/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Submission Successful!!</h2>
                <p class="text-gray-500 text-sm leading-relaxed mb-8">
                    {{ session('success') ?? 'Please visit the library to get admin approval and pick up your book.' }}
                </p>
                
                <button @click="showModal = false; window.location.href='{{ route('home') }}'" 
                    class="w-full bg-burgundy-500 text-white py-4 rounded-2xl font-bold hover:bg-maroon transition-all shadow-lg shadow-red-100 active:scale-95">
                    Return to Home
                </button>
            </div>
        </div>
    </template>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tglPinjam = document.getElementById('tanggal_pinjam');
        const tglKembali = document.getElementById('tanggal_kembali');

        if (tglPinjam && tglKembali) {
            tglPinjam.addEventListener('change', function() {
                let startDate = new window.Date(this.value);
                if(!isNaN(startDate.getTime())) {
                    startDate.setDate(startDate.getDate() + 7);
                    let year = startDate.getFullYear();
                    let month = String(startDate.getMonth() + 1).padStart(2, '0');
                    let day = String(startDate.getDate()).padStart(2, '0');
                    tglKembali.value = `${year}-${month}-${day}`;
                }
            });
        }
    });
</script>
@endsection
