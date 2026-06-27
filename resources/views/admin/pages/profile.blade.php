@extends('admin.layouts.app')

@section('content')
<div class="py-6 sm:py-10 space-y-8 sm:space-y-12">
    @if(isset($db_error))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-xl shadow-sm animate-fade-down" role="alert">
        <p class="font-bold text-sm">Database Error!</p>
        <p class="text-xs">Gagal terhubung ke database. Pastikan MySQL (XAMPP/Laragon) sudah menyala dan Anda sudah menjalankan <code>php artisan migrate</code>.</p>
        <p class="text-[10px] mt-2 opacity-70 italic">{{ $db_error }}</p>
    </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 animate-fade-up">
        <div class="text-center sm:text-left">
            <h1 class="text-2xl sm:text-4xl font-bold text-gray-800">Admin Profile</h1>
            <p class="text-gray-500 mt-2 text-sm sm:text-base">Manage library information and monitor borrowed books.</p>
        </div>
    </div>

    <div class="glass-panel p-6 sm:p-8 border-white/60 animate-fade-up delay-100 shadow-2xl shadow-red-50 flex flex-col sm:flex-row items-center gap-6 sm:gap-8 sm:border-l-4 sm:border-l-maroon border-t-4 border-t-maroon sm:border-t-white">
        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-maroon text-white flex items-center justify-center text-3xl sm:text-4xl font-bold shadow-xl shadow-red-100">
            A
        </div>
        <div class="text-center sm:text-left flex-1">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800">{{ $admin['name'] ?? 'Admin ReadSpace' }}</h2>
            <p class="text-sm sm:text-base text-gray-500 font-medium">{{ $admin['email'] ?? 'admin@polibatam.ac.id' }}</p>
            <div class="mt-3 sm:mt-4 inline-block px-3 sm:px-4 py-1.5 rounded-lg bg-burgundy-50 text-burgundy-600 border border-burgundy-100 text-[10px] sm:text-xs font-bold uppercase tracking-widest">
                Main Administrator 
            </div>
        </div>
        <div class="w-full sm:w-auto flex flex-col lg:flex-row gap-3">
            <a href="{{ route('admin.users.index') }}" class="w-full lg:w-auto px-4 sm:px-6 py-2.5 sm:py-3 rounded-xl bg-burgundy-500 text-white font-bold hover:bg-maroon transition-colors text-xs sm:text-sm text-center shadow-lg shadow-red-100">
                Manage Users
            </a>
            <a href="{{ route('admin.manage_data') }}" class="w-full lg:w-auto px-4 sm:px-6 py-2.5 sm:py-3 rounded-xl bg-burgundy-500 text-white font-bold hover:bg-maroon transition-colors text-xs sm:text-sm text-center shadow-lg shadow-red-100">
                Manage Data
            </a>
            <a href="{{ route('admin.buku.create') }}" class="w-full lg:w-auto px-4 sm:px-6 py-2.5 sm:py-3 rounded-xl border-2 border-burgundy-500 text-burgundy-600 font-bold hover:bg-red-50 transition-colors text-xs sm:text-sm text-center">
                + Add Book
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 animate-fade-up delay-200">
        <div class="bg-white/80 rounded-2xl p-4 sm:p-6 border border-gray-100 shadow-xl flex flex-col sm:flex-row items-center sm:items-start gap-3 sm:gap-4 text-center sm:text-left">
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-burgundy-50 flex items-center justify-center text-burgundy-600 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            </div>
            <div>
                <p class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-widest">Total Books</p>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-800">{{ $totalBuku ?? 0 }}</h3>
            </div>
        </div>
        
        <div class="bg-white/80 rounded-2xl p-4 sm:p-6 border border-gray-100 shadow-xl flex flex-col sm:flex-row items-center sm:items-start gap-3 sm:gap-4 text-center sm:text-left">
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            </div>
            <div>
                <p class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-widest">Total Users</p>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-800">{{ $totalUser ?? 0 }}</h3>
            </div>
        </div>
        
        <div class="bg-white/80 rounded-2xl p-4 sm:p-6 border border-gray-100 shadow-xl flex flex-col sm:flex-row items-center sm:items-start gap-3 sm:gap-4 text-center sm:text-left col-span-2 lg:col-span-1">
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-green-50 flex items-center justify-center text-green-600 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
            </div>
            <div>
                <p class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-widest">Total Borrows</p>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-800">{{ $totalPeminjaman ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <!-- Activity Log -->
    <div class="bg-white/80 rounded-2xl border border-gray-100 shadow-xl overflow-hidden animate-fade-up delay-300">
        <div class="p-4 sm:p-6 border-b border-gray-100 bg-gray-50/50 text-center sm:text-left">
            <h3 class="font-bold text-gray-800 text-base sm:text-lg">Recent Library Activity</h3>
            <p class="text-xs sm:text-sm text-gray-500">Latest actions and events recorded in the system.</p>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentActivities ?? [] as $log)
                <div class="p-4 sm:p-6 hover:bg-gray-50/50 transition-colors flex flex-col sm:flex-row items-start gap-3 sm:gap-4">
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex-shrink-0 bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-xs sm:text-sm uppercase">
                            {{ substr($log->user?->name ?? $log->user?->full_name ?? 'U', 0, 1) }}
                        </div>
                        <div class="flex-1 sm:hidden">
                            <p class="text-xs font-bold text-gray-800">{{ $log->user?->name ?? $log->user?->full_name ?? 'System' }}</p>
                            <p class="text-[10px] text-gray-400 font-bold">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="flex-1 pl-11 sm:pl-0">
                        <p class="text-sm font-bold text-gray-800 hidden sm:block">{{ $log->user?->name ?? $log->user?->full_name ?? 'System' }}</p>
                        <p class="text-xs text-gray-500 sm:mt-0.5"><span class="font-semibold text-burgundy-600">{{ $log->aktivitas }}</span> &bull; {{ $log->deskripsi }}</p>
                    </div>
                    <div class="text-right flex-shrink-0 text-xs font-bold text-gray-400 hidden sm:block">
                        {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <p class="text-gray-500 text-xs sm:text-sm font-medium">No recent activities found.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
