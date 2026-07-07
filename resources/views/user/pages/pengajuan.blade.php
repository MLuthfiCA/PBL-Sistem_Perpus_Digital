@extends('user.layouts.app')

@section('content')
<div class="py-6 sm:py-10" x-data="{ showModal: {{ session('success') ? 'true' : 'false' }} }">
    <!-- Header -->
    <div class="mb-8 sm:mb-12 animate-fade-up">
        <h1 class="text-2xl sm:text-4xl font-bold text-gray-800">Book Loan Form</h1>
        <p class="text-gray-500 mt-2 text-sm sm:text-base">Complete the data below to process the physical book loan.</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-6 sm:gap-12">
        <!-- Form Section -->
        <div class="flex-grow glass-panel p-5 sm:p-8 md:p-10 animate-fade-up delay-100 border-white/60 shadow-2xl shadow-red-50">

            <form action="{{ route('pengajuan.store') }}" method="POST" class="space-y-5 sm:space-y-8">
                @csrf
                <input type="hidden" name="buku_id" value="{{ request('id') }}">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-8">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Borrower Name</label>
                        <input type="text" placeholder="Enter Your Full Name" 
                            value="{{ auth()->check() ? auth()->user()->nama : (session()->has('user') ? session('user')['name'] : '') }}" readonly
                            class="w-full p-3 sm:p-4 bg-red-50/30 border border-white rounded-xl sm:rounded-2xl text-gray-400 font-bold cursor-not-allowed text-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">NIM / Member ID</label>
                        <input type="text" placeholder="Enter Your NIM" 
                            value="{{ auth()->check() ? auth()->user()->identity_number : (session()->has('user') ? session('user')['nim'] ?? '' : '') }}" readonly
                            class="w-full p-3 sm:p-4 bg-red-50/30 border border-white rounded-xl sm:rounded-2xl text-gray-400 font-bold cursor-not-allowed text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-8">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Borrow Date</label>
                        <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" required
                            class="w-full p-3 sm:p-4 bg-white/50 border border-white rounded-xl sm:rounded-2xl focus:ring-4 focus:ring-red-100 focus:outline-none transition-all font-medium text-gray-700 shadow-sm text-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Max Return Date</label>
                        <input type="date" id="tanggal_kembali" readonly
                            class="w-full p-3 sm:p-4 bg-red-50/30 border border-white rounded-xl sm:rounded-2xl font-medium text-gray-400 cursor-not-allowed text-sm">
                        <p class="text-xs sm:text-sm">The standard loan period is <strong>5 days(excluding Saturdays and Sundays)</strong></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-8 pt-4 border-t border-red-50">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Book ID</label>
                        <input type="text" value="{{ request('book_id') }}" readonly 
                            class="w-full p-3 sm:p-4 bg-red-50/30 border border-white rounded-xl sm:rounded-2xl text-gray-400 font-bold cursor-not-allowed text-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Book title</label>
                        <input type="text" value="{{ request('judul') }}" readonly 
                            class="w-full p-3 sm:p-4 bg-red-50/30 border border-white rounded-xl sm:rounded-2xl text-gray-400 font-bold cursor-not-allowed text-sm">
                    </div>
                </div>

                <div class="pt-4 sm:pt-6">
                    <button type="submit" 
                        class="w-full sm:w-auto bg-burgundy-500 text-white px-8 sm:px-12 py-3 sm:py-4 rounded-xl sm:rounded-2xl font-bold text-base sm:text-lg hover:bg-maroon transition-all shadow-xl shadow-red-100 transform hover:-translate-y-1 active:scale-95">
                        Loan Confirmation
                    </button>
                </div>
            </form>
        </div>

        <!-- Sidebar / Preview Section -->
        <div class="w-full lg:w-80 xl:w-96 space-y-4 sm:space-y-6 animate-fade-up delay-200 order-first lg:order-last">
            <div class="glass-panel p-5 sm:p-6 border-white/60">
                <div class="aspect-[3/4] bg-gradient-to-br from-red-50 to-rose-100 rounded-xl sm:rounded-2xl flex items-center justify-center border border-white/20 relative overflow-hidden group mb-4 sm:mb-6 shadow-inner max-h-48 sm:max-h-none">
                    @if(request('cover'))
                        <img src="{{ asset('images/' . request('cover')) }}" class="h-4/5 object-contain shadow-2xl transform transition-transform duration-700 group-hover:scale-105" onerror="this.src='{{ asset('images/readspace-library.png') }}'">
                    @else
                        <div class="text-center px-6">
                            <div class="w-12 sm:w-16 h-12 sm:h-16 bg-white/50 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 sm:h-8 w-6 sm:w-8 text-red-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">No Cover Preview</span>
                        </div>
                    @endif
                </div>
                
                <div class="space-y-3 sm:space-y-4">
                    <h3 class="font-bold text-gray-800 text-base sm:text-xl line-clamp-2">{{ request('judul') }}</h3>
                    <div class="p-3 sm:p-4 bg-red-50/50 rounded-xl sm:rounded-2xl border border-red-100 text-sm text-burgundy-900 leading-relaxed shadow-sm">
                    <div class="flex gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 text-burgundy-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-xs sm:text-sm">The standard loan period is <strong>5 days(excluding Saturdays and Sundays)</strong>. Please return the book before the due date to avoid a late fee of <strong>Rp 5,000 per day</strong>.</p>
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
            
            <div class="glass-panel max-w-sm w-full p-6 sm:p-8 text-center border-white shadow-2xl relative overflow-hidden" style="background-color: #FDFBD4;">
                <!-- Decorative Background Icon -->
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-green-50 rounded-full opacity-20"></div>
                
                <div class="w-16 sm:w-20 h-16 sm:h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-5 sm:mb-6 shadow-lg shadow-green-100/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 sm:h-10 w-8 sm:w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-3 sm:mb-4">Submission Successful!!</h2>
                <p class="text-gray-500 text-sm leading-relaxed mb-6 sm:mb-8">
                    {{ session('success') ?? 'Please visit the library to get admin approval and pick up your book.' }}
                </p>
                
                <button @click="showModal = false; window.location.href='{{ route('home') }}'" 
                    class="w-full bg-burgundy-500 text-white py-3 sm:py-4 rounded-xl sm:rounded-2xl font-bold hover:bg-maroon transition-all shadow-lg shadow-red-100 active:scale-95">
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
            ['input', 'change'].forEach(evt => {
                tglPinjam.addEventListener(evt, function() {
                    let val = this.value;
                    if (val) {
                        const dateObj = new Date(val);
                        const day = dateObj.getDay(); // 0 = Sunday, 6 = Saturday
                        if (day === 0 || day === 6) {
                            alert('Sorry, you cannot borrow books on weekends (Saturday/Sunday). Please choose a weekday.');
                            this.value = '';
                            tglKembali.value = '';
                            return;
                        }

                        fetch(`/api/hitung-kembali?tanggal_pinjam=${val}`)
                            .then(res => res.json())
                            .then(data => {
                                tglKembali.value = data.return_date;
                            });
                    }
                });
            });
        }
    });
</script>
@endsection
