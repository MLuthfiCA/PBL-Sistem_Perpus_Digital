@extends('admin.layouts.app')

@section('title', 'Add User')

@section('content')
<div class="py-10 space-y-8" x-data="{}">
    <x-ui.page-header title="Add User" subtitle="Form to add a new user"></x-ui.page-header>

    @if($errors->any())
    <div class="p-6 bg-red-50 border border-red-200 rounded-2xl text-red-700 font-medium text-sm">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="p-6 bg-white/80 rounded-2xl shadow-xl">
        <form action="{{ route('admin.users.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Full Name</label>
                <input type="text" name="full_name" value="{{ old('full_name') }}" class="w-full px-4 py-2.5 border rounded" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Student ID (NIM)</label>
                <input type="text" name="identity_number" value="{{ old('identity_number') }}" placeholder="Enter Student ID (NIM)" class="w-full px-4 py-2.5 border rounded" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" class="w-full px-4 py-2.5 border rounded" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2.5 border rounded" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                <input type="password" name="password" class="w-full px-4 py-2.5 border rounded" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Role</label>
                <select name="role" class="w-full px-4 py-2.5 border rounded" required>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-4 py-2.5 border rounded" required>
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            <div class="col-span-2 flex justify-end space-x-4">
                <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 bg-gray-300 text-gray-800 rounded-xl hover:bg-gray-400">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-burgundy-500 text-white rounded-xl hover:bg-maroon">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection
