@extends('user.layouts.app')

@section('content')

<h2 class="text-lg font-semibold mb-2">
    Hello, {{ session('user')['name'] ?? 'User' }}
</h2>

<p class="mb-6 text-gray-600">
    Here is your book borrowing history:
</p>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">

@forelse($daftarBuku as $buku)

<div class="bg-white border border-stone-100 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 {{ ($buku->status ?? '') == 'Dipinjam' ? 'opacity-75 bg-stone-50' : '' }}">

    <div class="h-56 {{ ($buku->status ?? '') == 'Dipinjam' ? 'bg-stone-200' : 'bg-amber-100' }} rounded-t-3xl flex items-center justify-center p-6">

        @if(!empty($buku->cover))
            <img src="{{ asset('images/' . $buku->cover) }}"
                 class="w-full h-full object-cover">
        @else
            <span class="text-sm">Book Cover</span>
        @endif

    </div>

    <div class="p-6">

        <div class="flex justify-between mb-2">
            <h5 class="text-xl font-bold">
                {{ $buku->judul ?? 'Hujan' }}
            </h5>

            <span class="text-xs px-3 py-1 rounded-full
                {{ ($buku->status ?? '') == 'Tersedia'
                    ? 'bg-green-100 text-green-900'
                    : 'bg-red-100 text-red-900' }}">
                {{ ($buku->status ?? '') == 'Tersedia' ? 'Available' : (($buku->status ?? '') == 'Dipinjam' ? 'Borrowed' : 'Not available') }}
            </span>
        </div>

        <p class="text-sm mb-4">
            {{ $buku->penulis ?? 'Tere Liye' }}
        </p>

        <p class="text-sm mb-4">
            {{ $buku->genre ?? 'Science Fiction' }}
        </p>

        @if(($buku->status ?? '') == 'Tersedia')
            <button class="w-full bg-red-950 text-white py-2 rounded-xl">
                Borrow Books
            </button>
        @else
            <button disabled class="w-full bg-gray-400 text-white py-2 rounded-xl">
                Not available
            </button>
        @endif

    </div>
</div>

@empty
<div class="col-span-full text-center">
    Book not found.
</div>
@endforelse

</div>

@endsection
