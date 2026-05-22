@extends('admin.layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="py-10 space-y-8" x-data="{ search: '' }"><!-- Simple search (client side) -->
    <x-ui.page-header title="Manage Users" subtitle="List of all library users">
        <a href="{{ route('admin.users.create') }}" class="px-6 py-3 rounded-xl bg-burgundy-500 text-white font-bold shadow-lg hover:bg-maroon transition-all">
            + Add User
        </a>
    </x-ui.page-header>

    @if(session('success'))
    <div class="flex items-center gap-3 px-6 py-4 bg-green-50 border border-green-200 rounded-2xl text-green-700 font-medium text-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="flex items-start gap-3 px-6 py-4 bg-red-50 border border-red-200 rounded-2xl text-red-700 font-medium text-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/></svg>
        <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="p-6 bg-white/80 rounded-2xl shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-red-50/50 text-gray-400 text-[10px] font-bold uppercase tracking-widest">
                    <tr>
                        <th class="px-8 py-5">Name</th>
                        <th class="px-8 py-5">Username</th>
                        <th class="px-8 py-5">Email</th>
                        <th class="px-8 py-5">Role</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-red-50">
                    @forelse($users as $user)
                    <tr class="group hover:bg-red-50/30 transition-colors duration-300">
                        <td class="px-8 py-6">{{ $user->full_name }}</td>
                        <td class="px-8 py-6">{{ $user->username }}</td>
                        <td class="px-8 py-6">{{ $user->email }}</td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1.5 rounded-lg bg-white/80 text-gray-500 text-[10px] font-bold uppercase border">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <a href="{{ route('admin.users.edit', $user->user_id) }}" class="text-blue-500 hover:text-blue-700 font-bold text-xs px-3 mr-2">Edit</a>
                            <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this user?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-xs px-3">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center text-gray-400 font-medium">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
