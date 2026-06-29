@extends('admin.layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="py-6 sm:py-10 space-y-6 sm:space-y-8">
    <x-ui.page-header title="Manage Users" subtitle="List of all library users">
        <a href="{{ route('admin.users.create') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-burgundy-500 text-white font-bold shadow-lg hover:bg-maroon transition-all text-center block sm:inline-block">
            + Add User
        </a>
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

    <form action="{{ route('admin.users.index') }}" method="GET" class="p-4 sm:p-6 bg-white/80 rounded-2xl shadow-xl">
        <div class="flex flex-col md:flex-row gap-3 sm:gap-4 items-center">
            <div class="relative w-full md:flex-1">
                <input type="text" name="search" value="{{ request('search', '') }}" placeholder="Search Users..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-white bg-white/50 focus:ring-2 focus:ring-red-200 outline-none transition-all font-medium text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <div class="flex flex-col sm:flex-row w-full md:w-auto items-start sm:items-center gap-3">
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <select name="role" class="w-full sm:w-36 px-4 py-2.5 border border-white bg-white/50 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-200 font-medium text-sm text-gray-700">
                        <option value="" {{ request('role') === '' ? 'selected' : '' }}>All Roles</option>
                        <option value="mahasiswa" {{ request('role') === 'mahasiswa' ? 'selected' : '' }}>Student</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <button type="submit" class="flex-1 sm:flex-initial px-6 py-2.5 bg-burgundy-500 text-white rounded-xl hover:bg-maroon font-bold text-sm transition-all text-center">Search</button>
                    <a href="{{ route('admin.users.index') }}" class="flex-1 sm:flex-initial px-6 py-2.5 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 font-bold text-sm transition-all text-center">Reset</a>
                </div>
            </div>
        </div>
    </form>

    <div class="bg-white/80 rounded-2xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[600px]">
                <thead class="bg-red-50/50 text-gray-400 text-[10px] font-bold uppercase tracking-widest">
                    <tr>
                        <th class="px-4 sm:px-8 py-4 sm:py-5">Name</th>
                        <th class="px-4 sm:px-8 py-4 sm:py-5">Contact</th>
                        <th class="px-4 sm:px-8 py-4 sm:py-5">Role</th>
                        <th class="px-4 sm:px-8 py-4 sm:py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-red-50">
                    @forelse($users as $user)
                    <tr class="group hover:bg-red-50/30 transition-colors duration-300">
                        <td class="px-4 sm:px-8 py-4 sm:py-6">
                            <p class="font-bold text-gray-700 text-sm">{{ $user->full_name }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">NIM/NIK: {{ $user->identity_number }}</p>
                        </td>
                        <td class="px-4 sm:px-8 py-4 sm:py-6">
                            <p class="text-xs sm:text-sm font-medium text-gray-600">{{ $user->email }}</p>
                        </td>
                        <td class="px-4 sm:px-8 py-4 sm:py-6">
                            <span class="px-2 sm:px-3 py-1 sm:py-1.5 rounded-lg text-[9px] sm:text-[10px] font-bold uppercase border whitespace-nowrap {{ $user->role === 'admin' ? 'bg-burgundy-50/80 text-burgundy-600 border-burgundy-100' : 'bg-white/80 text-gray-500 border-red-50' }}">
                                {{ $user->role === 'mahasiswa' ? 'student' : $user->role }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-8 py-4 sm:py-6 text-right opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                            <a href="{{ route('admin.users.edit', $user->user_id) }}" class="text-blue-500 hover:text-blue-700 font-bold text-[10px] sm:text-xs px-2 sm:px-3 bg-blue-50 sm:bg-transparent rounded py-1 sm:py-0 mr-1 sm:mr-2">Edit</a>
                            <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this user?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-[10px] sm:text-xs px-2 sm:px-3 bg-red-50 sm:bg-transparent rounded py-1 sm:py-0">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-12 text-center text-gray-400 font-medium text-sm">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 flex justify-center w-full">
        @if(method_exists($users, 'links'))
            {{ $users->links() }}
        @endif
    </div>
</div>
@endsection
