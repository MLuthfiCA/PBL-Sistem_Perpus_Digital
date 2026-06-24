@extends('admin.layouts.app')

@section('content')
<div class="py-10 space-y-12">
    @if(isset($db_error))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-xl shadow-sm animate-fade-down" role="alert">
        <p class="font-bold text-sm">Database Error!</p>
        <p class="text-xs">Gagal terhubung ke database. Pastikan MySQL (XAMPP/Laragon) sudah menyala dan Anda sudah menjalankan <code>php artisan migrate</code>.</p>
        <p class="text-[10px] mt-2 opacity-70 italic">{{ $db_error }}</p>
    </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 animate-fade-up">
        <div>
            <h1 class="text-4xl font-bold text-gray-800">Admin Profile</h1>
            <p class="text-gray-500 mt-2">Manage library information and monitor borrowed books.</p>
        </div>
    </div>

    <div class="glass-panel p-8 border-white/60 animate-fade-up delay-100 shadow-2xl shadow-red-50 flex flex-col md:flex-row items-center gap-8 border-l-4 border-l-maroon">
        <div class="w-24 h-24 rounded-full bg-maroon text-white flex items-center justify-center text-4xl font-bold shadow-xl shadow-red-100">
            A
        </div>
        <div class="text-center md:text-left flex-1">
            <h2 class="text-2xl font-bold text-gray-800">Admin ReadSpace</h2>
            <p class="text-gray-500 font-medium">admin@polibatam.ac.id</p>
            <div class="mt-4 inline-block px-4 py-1.5 rounded-lg bg-burgundy-50 text-burgundy-600 border border-burgundy-100 text-xs font-bold uppercase tracking-widest">
                Main Administrator 
            </div>
        </div>
        <div class="w-full md:w-auto flex flex-col sm:flex-row gap-3">
            <a href="{{ route('admin.users.index') }}" class="w-full md:w-auto px-6 py-3 rounded-xl bg-burgundy-500 text-white font-bold hover:bg-maroon transition-colors text-sm text-center shadow-lg shadow-red-100">
                Manage Users
            </a>
            <a href="{{ route('admin.manage-data') }}" class="w-full md:w-auto px-6 py-3 rounded-xl bg-burgundy-500 text-white font-bold hover:bg-maroon transition-colors text-sm text-center shadow-lg shadow-red-100">
                Manage Data
            </a>
            <a href="{{ route('admin.buku.create') }}" class="w-full md:w-auto px-6 py-3 rounded-xl border-2 border-burgundy-500 text-burgundy-600 font-bold hover:bg-red-50 transition-colors text-sm text-center">
                + Add New Book
            </a>
        </div>
    </div>
</div>
@endsection
