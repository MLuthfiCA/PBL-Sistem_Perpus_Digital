@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="py-10 space-y-8" x-data="{ role: '{{ old('role', $user->role == 'mahasiswa' ? 'student' : 'admin') }}' }">
    <x-ui.page-header title="Edit User" subtitle="Update user information"></x-ui.page-header>
    <div class="p-6 bg-white/80 rounded-2xl shadow-xl">
        <form action="{{ route('admin.users.update', $user->user_id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Full Name</label>
                <input type="text" name="full_name" class="w-full px-4 py-2.5 border rounded" value="{{ $user->full_name }}" required>
            </div>
            
            <div x-show="role === 'student'">
                <label class="block text-sm font-bold text-gray-700 mb-1">Student ID (NIM)</label>
                <input type="text" name="identity_number" inputmode="numeric" pattern="[0-9]*" 
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');" 
                    x-bind:disabled="role !== 'student'"
                    class="w-full px-4 py-2.5 border rounded" 
                    value="{{ $user->identity_number }}" 
                    placeholder="Enter Student ID" required>
            </div>

            <div x-show="role === 'admin'" x-cloak>
                <label class="block text-sm font-bold text-gray-700 mb-1">Admin ID (NIK)</label>
                <input type="text" name="identity_number" inputmode="numeric" pattern="[0-9]*" 
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');" 
                    x-bind:disabled="role !== 'admin'"
                    class="w-full px-4 py-2.5 border rounded" 
                    value="{{ $user->identity_number }}" 
                    placeholder="Enter Admin ID" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" class="w-full px-4 py-2.5 border rounded" value="{{ $user->email }}" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Password <span class="text-gray-400 font-normal">(leave blank to keep current)</span></label>
                <div class="relative">
                    <input type="password" id="edit_password" name="password" 
                        minlength="8" maxlength="12" oninput="this.value = this.value.replace(/ /g, '');"
                        class="w-full px-4 py-2.5 pr-10 border rounded">
                    <button type="button" onclick="toggleAdminPassword('edit_password', 'editEye1')"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                        <svg id="editEye1" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <p class="mt-1 text-xs text-gray-500">8–12 characters, no spaces allowed.</p>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Role</label>
                <select name="role" x-model="role" class="w-full px-4 py-2.5 border rounded" required>
                    <option value="student" {{ $user->role == 'mahasiswa' ? 'selected' : '' }}>Student (Mahasiswa)</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
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

<script>
function toggleAdminPassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
        `;
    } else {
        input.type = 'password';
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
    }
}
</script>
@endsection
