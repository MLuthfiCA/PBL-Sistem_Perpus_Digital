@extends('user.layouts.app')

@section('title', 'Daftar Item PBL')

@section('content')
<div class="p-6 bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Daftar Item Proyek PBL</h1>
        <a href="{{ route('detail-peminjaman.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition duration-150 ease-in-out">
            + Tambah Item
        </a>
    </div>
    
    @if(session('success'))
    <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
        <span class="font-medium">Sukses!</span> {{ session('success') }}
    </div>
    @endif
    
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">No</th>
                    <th scope="col" class="px-6 py-3">Nama Item</th>
                    <th scope="col" class="px-6 py-3">Stok</th>
                    <th scope="col" class="px-6 py-3 text-center">Aksi</th> </tr>
            </thead>
            <tbody>
                @foreach($items as $key => $item)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $key + 1 }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $item['nama'] ?? 'Item Tidak Ditemukan' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $item['stok'] }}
                    </td>
                    <td class="px-6 py-4 text-center space-x-2">
                        <a href="{{ route('detail-peminjaman.edit', $item['id']) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                            Edit
                        </a>
                        <form action="{{ route('detail-peminjaman.destroy', $item['id']) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?')" class="font-medium text-red-600 dark:text-red-500 hover:underline bg-transparent border-none cursor-pointer">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
