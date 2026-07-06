@extends('admin.layouts.app')

@section('title', 'Manage Authors')

@section('content')
<div class="py-6 sm:py-10 space-y-6 sm:space-y-8">
    <x-ui.page-header title="Manage Authors" subtitle="List of all book authors in the library">
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-burgundy-500 text-white font-bold shadow-lg hover:bg-maroon transition-all">
            + Add Author
        </button>
    </x-ui.page-header>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-xl text-green-700 shadow-sm animate-fade-down flex items-start gap-3">
        <div class="w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0 mt-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <div>
            <h4 class="font-bold text-green-800 text-sm">Success!</h4>
            <p class="text-xs text-green-600 mt-0.5 leading-relaxed">{{ session('success') }}</p>
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-xl text-red-700 shadow-sm animate-fade-down flex items-start gap-3">
        <div class="w-6 h-6 rounded-full bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0 mt-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
        <div>
            <h4 class="font-bold text-red-800 text-sm">Action Failed!</h4>
            <p class="text-xs text-red-600 mt-0.5 leading-relaxed">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <form action="{{ route('admin.penulis.index') }}" method="GET" class="p-4 sm:p-6 bg-white/80 rounded-2xl shadow-xl">
        <div class="flex flex-col md:flex-row gap-3 sm:gap-4 items-center">
            <div class="relative w-full md:w-1/2">
                <input type="text" name="search" value="{{ request('search', '') }}" placeholder="Search author name..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-white bg-white/50 focus:ring-2 focus:ring-red-200 outline-none transition-all font-medium text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <div class="flex items-center gap-2 sm:gap-3 w-full md:w-auto">
                <button type="submit" class="flex-1 md:flex-initial px-6 py-2.5 bg-burgundy-500 text-white rounded-xl hover:bg-maroon font-bold text-sm transition-all">Search</button>
                <a href="{{ route('admin.penulis.index') }}" class="flex-1 md:flex-initial px-6 py-2.5 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 font-bold text-sm transition-all text-center">Reset</a>
            </div>
        </div>
    </form>

    <div class="bg-white/80 rounded-2xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[500px]">
                <thead class="bg-red-50/50 text-gray-400 text-[10px] font-bold uppercase tracking-widest">
                    <tr>
                        <th class="px-6 sm:px-8 py-4 sm:py-5">ID</th>
                        <th class="px-6 sm:px-8 py-4 sm:py-5">Author Name</th>
                        <th class="px-6 sm:px-8 py-4 sm:py-5">Books</th>
                        <th class="px-6 sm:px-8 py-4 sm:py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-red-50">
                    @forelse($penulis as $item)
                    <tr class="group hover:bg-red-50/30 transition-colors duration-300">
                        <td class="px-6 sm:px-8 py-4 sm:py-6 text-sm">{{ $item->id_penulis }}</td>
                        <td class="px-6 sm:px-8 py-4 sm:py-6 font-bold text-gray-700 text-sm">{{ $item->nama_penulis }}</td>
                        <td class="px-6 sm:px-8 py-4 sm:py-6">
                            <span class="px-2 py-1 rounded bg-red-50 text-burgundy-500 text-[9px] sm:text-[10px] font-bold uppercase tracking-widest border border-red-100 whitespace-nowrap">
                                {{ $item->buku_count }} book(s)
                            </span>
                        </td>
                        <td class="px-6 sm:px-8 py-4 sm:py-6 text-right opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                            <button onclick="editAuthor({{ $item->id_penulis }}, '{{ addslashes($item->nama_penulis) }}')" class="text-blue-500 hover:text-blue-700 font-bold text-[10px] sm:text-xs px-2 sm:px-3 bg-blue-50 sm:bg-transparent rounded py-1 sm:py-0 mr-1 sm:mr-2">Edit</button>
                            <form action="{{ route('admin.penulis.destroy', $item->id_penulis) }}" method="POST" class="inline" onsubmit="return confirm('Delete this author?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-[10px] sm:text-xs px-2 sm:px-3 bg-red-50 sm:bg-transparent rounded py-1 sm:py-0">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-12 text-center text-gray-400 font-medium text-sm">No authors found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 flex justify-center w-full">
        @if(method_exists($penulis, 'links'))
            {{ $penulis->links('components.ui.pagination') }}
        @endif
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 w-full max-w-md">
        <h2 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6">Add Author</h2>
        <form action="{{ route('admin.penulis.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Author Name</label>
                <input type="text" name="nama_penulis" required class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-red-200 outline-none text-sm">
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-4 py-2 bg-gray-200 rounded-xl font-bold text-sm">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-burgundy-500 text-white rounded-xl font-bold text-sm">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 w-full max-w-md">
        <h2 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6">Edit Author</h2>
        <form id="editForm" method="POST">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Author Name</label>
                <input type="text" name="nama_penulis" id="edit_nama_penulis" required class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-red-200 outline-none text-sm">
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-4 py-2 bg-gray-200 rounded-xl font-bold text-sm">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-xl font-bold text-sm">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editAuthor(id, name) {
        document.getElementById('edit_nama_penulis').value = name;
        document.getElementById('editForm').action = '/admin/penulis/' + id;
        document.getElementById('editModal').classList.remove('hidden');
    }
</script>
@endsection
