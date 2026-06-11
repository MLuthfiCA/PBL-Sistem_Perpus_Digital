@extends('admin.layouts.app')

@section('title', 'Manage Authors')

@section('content')
<div class="py-10 space-y-8">
    <x-ui.page-header title="Manage Authors" subtitle="List of all book authors in the library">
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="px-6 py-3 rounded-xl bg-burgundy-500 text-white font-bold shadow-lg hover:bg-maroon transition-all">
            + Add Author
        </button>
    </x-ui.page-header>

    @if(session('success'))
        <div class="p-4 bg-green-100 text-green-700 rounded-xl font-bold">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-100 text-red-700 rounded-xl font-bold">{{ session('error') }}</div>
    @endif

    <div class="p-6 bg-white/80 rounded-2xl shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-red-50/50 text-gray-400 text-[10px] font-bold uppercase tracking-widest">
                    <tr>
                        <th class="px-8 py-5">ID</th>
                        <th class="px-8 py-5">Author Name</th>
                        <th class="px-8 py-5">Books</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-red-50">
                    @forelse($penulis as $item)
                    <tr class="group hover:bg-red-50/30 transition-colors duration-300">
                        <td class="px-8 py-6">{{ $item->id_penulis }}</td>
                        <td class="px-8 py-6 font-bold text-gray-700">{{ $item->nama_penulis }}</td>
                        <td class="px-8 py-6">
                            <span class="px-2 py-1 rounded bg-red-50 text-burgundy-500 text-[10px] font-bold uppercase tracking-widest border border-red-100">
                                {{ $item->buku_count }} book(s)
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button onclick="editAuthor({{ $item->id_penulis }}, '{{ addslashes($item->nama_penulis) }}')" class="text-blue-500 hover:text-blue-700 font-bold text-xs px-3 mr-2">Edit</button>
                            <form action="{{ route('admin.penulis.destroy', $item->id_penulis) }}" method="POST" class="inline" onsubmit="return confirm('Delete this author?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-xs px-3">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-12 text-center text-gray-400 font-medium">No authors found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 flex justify-center w-full">
        @if(method_exists($penulis, 'links'))
            {{ $penulis->links() }}
        @endif
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6">Add Author</h2>
        <form action="{{ route('admin.penulis.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Author Name</label>
                <input type="text" name="nama_penulis" required class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-red-200 outline-none">
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-4 py-2 bg-gray-200 rounded-xl font-bold">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-burgundy-500 text-white rounded-xl font-bold">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6">Edit Author</h2>
        <form id="editForm" method="POST">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Author Name</label>
                <input type="text" name="nama_penulis" id="edit_nama_penulis" required class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-red-200 outline-none">
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-4 py-2 bg-gray-200 rounded-xl font-bold">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-xl font-bold">Update</button>
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
