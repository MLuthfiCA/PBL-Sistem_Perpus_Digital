@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="py-10 space-y-8" x-data="{}">
    <x-ui.page-header title="Edit User" subtitle="Update user information"></x-ui.page-header>
    <div class="p-6 bg-white/80 rounded-2xl shadow-xl">
        <form action="{{ route('admin.users.update', $user->user_id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Full Name</label>
                <input type="text" name="full_name" class="w-full px-4 py-2.5 border rounded" value="{{ $user->full_name }}" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Student ID</label>
                <input type="text" name="identity_number" class="w-full px-4 py-2.5 border rounded" value="{{ $user->identity_number }}" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Username</label>
                <input type="text" name="username" class="w-full px-4 py-2.5 border rounded" value="{{ $user->username }}" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                <input type="email" name="email" class="w-full px-4 py-2.5 border rounded" value="{{ $user->email }}" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Password (leave blank to keep current)</label>
                <input type="password" name="password" class="w-full px-4 py-2.5 border rounded">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Role</label>
                <select name="role" class="w-full px-4 py-2.5 border rounded" required>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="student" {{ $user->role == 'student' ? 'selected' : '' }}>Student</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-4 py-2.5 border rounded" required>
                    <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ $user->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            <div class="col-span-2 flex justify-end space-x-4">
                <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 bg-gray-300 text-gray-800 rounded-xl hover:bg-gray-400">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-burgundy-500 text-white rounded-xl hover:bg-maroon">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
