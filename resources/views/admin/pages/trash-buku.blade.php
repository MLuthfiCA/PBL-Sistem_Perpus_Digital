@extends('admin.layouts.app')

@section('title', 'Trash - Deleted Books')

@section('content')
<div class="py-4 md:py-10 space-y-6 md:space-y-10">
    
    <!-- Page Header -->
    <x-ui.page-header 
        title="Deleted Books (Trash)" 
        subtitle="Manage and restore previously deleted books back to the catalog."
    >
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.katalog') }}" class="px-5 py-3 bg-white text-gray-700 rounded-2xl text-sm font-bold shadow-md hover:bg-gray-50 border border-gray-100 transition-all transform active:scale-95 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Catalog
            </a>
        </div>
    </x-ui.page-header>

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-green-500/10 border border-green-200 text-green-700 text-sm font-semibold flex items-center gap-2 animate-fade-up">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-2xl bg-red-500/10 border border-red-200 text-red-700 text-sm font-semibold flex items-center gap-2 animate-fade-up">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Trashed Books Table Card -->
    <x-ui.glass-card class="overflow-hidden border border-white/60 animate-fade-up shadow-2xl shadow-red-50">
        @if($trashed->isEmpty())
            <div class="py-20 text-center">
                <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 text-burgundy-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <p class="text-gray-400 font-medium">There are no deleted books in the trash.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-red-50/50 text-gray-400 text-[10px] font-bold uppercase tracking-widest">
                        <tr>
                            <th class="px-8 py-5">Book Info</th>
                            <th class="px-8 py-5">ISBN</th>
                            <th class="px-8 py-5">Stock</th>
                            <th class="px-8 py-5">Deleted At</th>
                            <th class="px-8 py-5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-50">
                        @foreach($trashed as $b)
                        <tr>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-5">
                                    <div class="w-12 h-16 bg-white rounded-xl shadow-md flex items-center justify-center overflow-hidden border border-white">
                                        <img src="{{ asset('images/' . ($b['cover'] ?? 'readspace-library.png')) }}" 
                                             class="w-full h-full object-cover" 
                                             onerror="this.src='{{ asset('images/readspace-library.png') }}'">
                                    </div>
                                    <div>
                                        <span class="font-bold text-gray-800 block">{{ $b['judul'] }}</span>
                                        <p class="text-xs text-gray-400 font-medium">{{ $b['penulis'] }}</p>
                                        <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest mt-1 block">ID: {{ 'B-' . str_pad($b['id'], 3, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1.5 rounded-lg bg-white/80 text-gray-500 text-[10px] font-bold uppercase tracking-widest border border-red-50">
                                    {{ $b['isbn'] ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-sm font-bold text-gray-600">
                                    {{ $b['stok'] ?? 0 }} copies
                                </span>
                            </td>
                            <td class="px-8 py-6 text-sm text-gray-500 font-medium">
                                {{ $b['deleted_at'] }}
                            </td>
                            <td class="px-8 py-6 text-right">
                                <form action="{{ route('admin.katalog.restore', $b['id']) }}" method="POST" class="inline-block" onsubmit="return confirm('Restore this book back to the catalog?')">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-burgundy-500 text-white rounded-xl text-xs font-bold shadow-md hover:bg-maroon hover:scale-105 transition-all flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89H18" />
                                        </svg>
                                        Restore
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-ui.glass-card>
</div>
@endsection
