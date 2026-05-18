@extends('admin.layouts.app')

@section('content')
<div class="py-10 space-y-8" x-data="{
    searchQuery: '',
    roleFilter: 'All',
    modalMode: 'add',
    editIndex: null,
    form: { name: '', username: '', id_number: '', email: '', role: 'Student' },
    users: [
        { name: 'Aksel Sarira', username: 'aksel123', email: 'aksel@student.polibatam.ac.id', id_number: '3312001001', role: 'Student' },
        { name: 'Library Admin', username: 'admin123', email: 'admin@polibatam.ac.id', id_number: 'ADM-001', role: 'Admin' }
    ],
    filteredUsers() {
        return this.users.filter(user => {
            const q = this.searchQuery.toLowerCase();
            const matchesSearch = !q || 
                user.name.toLowerCase().includes(q) ||
                (user.username && user.username.toLowerCase().includes(q)) ||
                user.email.toLowerCase().includes(q) ||
                user.id_number.toLowerCase().includes(q);
            const matchesRole = this.roleFilter === 'All' || user.role === this.roleFilter;
            return matchesSearch && matchesRole;
        });
    },
    openModal(mode, user = null) {
        this.modalMode = mode;
        if (mode === 'edit' && user) {
            this.editIndex = this.users.indexOf(user);
            this.form = { ...user };
        } else {
            this.editIndex = null;
            this.form = { name: '', username: '', id_number: '', email: '', role: 'Student' };
        }
        
        const modal = document.getElementById('userModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        
        setTimeout(() => {
            modal.setAttribute('data-show', 'true');
            modal.querySelector('.transform').setAttribute('data-show', 'true');
        }, 10);
        
        document.getElementById('modalTitle').innerText = mode === 'add' ? 'Add New User' : 'Edit User';
    },
    closeModal() {
        const modal = document.getElementById('userModal');
        modal.setAttribute('data-show', 'false');
        modal.querySelector('.transform').setAttribute('data-show', 'false');
        document.body.style.overflow = 'auto';
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    },
    saveUser() {
        if (!this.form.name || !this.form.username || !this.form.id_number || !this.form.email) {
            alert('Please fill out all fields.');
            return;
        }
        if (this.modalMode === 'add') {
            this.users.push({ ...this.form });
            this.showToast('New user successfully added!');
        } else {
            this.users[this.editIndex] = { ...this.form };
            this.showToast('User successfully updated!');
        }
        this.closeModal();
    },
    deleteUser(user) {
        if (confirm('Are you sure you want to delete this user?')) {
            const index = this.users.indexOf(user);
            if (index > -1) {
                this.users.splice(index, 1);
                this.showToast('User successfully deleted!');
            }
        }
    },
    showToast(message = 'Operation successful!') {
        const toast = document.getElementById('successToast');
        toast.querySelector('.toast-msg').innerText = message;
        toast.classList.remove('translate-x-full', 'opacity-0');
        
        setTimeout(() => {
            this.hideToast();
        }, 4000);
    },
    hideToast() {
        const toast = document.getElementById('successToast');
        toast.classList.add('translate-x-full', 'opacity-0');
    }
}">

    <!-- Page Header -->
    <x-ui.page-header 
        title="Users Data" 
        subtitle="Manage library user and student data."
    >
        <button @click="openModal('add')" class="px-6 py-3 rounded-xl bg-burgundy-500 text-white font-bold shadow-lg shadow-red-100 hover:bg-maroon transition-all">
            + Add User
        </button>
    </x-ui.page-header>

    <!-- Table Section -->
    <x-ui.glass-card class="overflow-hidden border border-white/60 animate-fade-up delay-100 shadow-2xl shadow-red-50">
        <div class="p-6 border-b border-red-50/50 flex flex-col md:flex-row gap-4 justify-between items-center bg-white/40">
            <div class="relative w-full md:w-1/3">
                <input type="text" x-model="searchQuery" placeholder="Search Users (Name, Username, Email, ID)..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-white bg-white/50 focus:ring-2 focus:ring-red-200 outline-none transition-all font-medium text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            
            <div class="flex items-center gap-3 w-full md:w-auto">
                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Filter Role:</label>
                <select x-model="roleFilter" class="w-full md:w-44 px-4 py-2.5 border border-white bg-white/50 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-200 font-medium text-sm text-gray-700">
                    <option value="All">All Roles</option>
                    <option value="Student">Student</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-red-50/50 text-gray-400 text-[10px] font-bold uppercase tracking-widest">
                    <tr>
                        <th class="px-8 py-5">User Info</th>
                        <th class="px-8 py-5">ID Number</th>
                        <th class="px-8 py-5">Role</th>
                        <th class="px-8 py-5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-red-50">
                    <template x-for="user in filteredUsers()" :key="user.id_number">
                        <tr class="group hover:bg-red-50/30 transition-colors duration-300">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div :class="user.role === 'Admin' ? 'bg-maroon' : 'bg-burgundy-500'" class="w-10 h-10 rounded-full text-white flex items-center justify-center font-bold shadow-md" x-text="user.name.charAt(0).toUpperCase()">
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800" x-text="user.name"></p>
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2">
                                            <span class="text-xs text-gray-400 font-medium" x-text="user.email"></span>
                                            <span class="hidden sm:inline text-gray-300">•</span>
                                            <span class="text-[10px] font-bold text-burgundy-500" x-text="'@' + user.username"></span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 font-medium text-gray-600" x-text="user.id_number"></td>
                            <td class="px-8 py-6">
                                <span :class="user.role === 'Admin' ? 'bg-burgundy-50/80 text-burgundy-600 border border-burgundy-100' : 'bg-white/80 text-gray-500 border border-red-50'" class="px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest border">
                                    <span x-text="user.role"></span>
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <button @click="openModal('edit', user)" class="text-blue-500 hover:text-blue-700 font-bold text-xs px-3 transition-colors">Edit</button>
                                <button @click="deleteUser(user)" class="text-red-500 hover:text-red-700 font-bold text-xs px-3 transition-colors">Delete</button>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredUsers().length === 0">
                        <td colspan="4" class="px-8 py-12 text-center text-gray-400 font-medium">
                            <div class="flex flex-col items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span>No users found matching your criteria.</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </x-ui.glass-card>

    <!-- Modal (Moved inside the x-data block so Alpine.js binds successfully!) -->
    <div id="userModal" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm hidden items-center justify-center p-4 z-[100] transition-all duration-300 opacity-0 data-[show=true]:opacity-100 !m-0">
        <div class="bg-white/90 backdrop-blur-xl border border-white/60 w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden relative transform scale-95 data-[show=true]:scale-100 transition-transform duration-300">
            <div class="p-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-6" id="modalTitle">Add User</h3>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Full Name</label>
                            <input type="text" x-model="form.name" placeholder="Full Name" class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Username</label>
                            <input type="text" x-model="form.username" placeholder="Username (for login)" class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm transition-all">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Email</label>
                            <input type="email" x-model="form.email" placeholder="Email Address" class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">ID Number</label>
                            <input type="text" x-model="form.id_number" placeholder="ID Number" class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm transition-all">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Role</label>
                            <select x-model="form.role" class="w-full px-4 py-3 border border-white bg-white/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-100 font-medium text-sm transition-all text-gray-700">
                                <option value="Student">Student</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button @click="closeModal()" class="px-6 py-2.5 rounded-xl font-bold text-gray-500 hover:bg-gray-100 transition-colors">Cancel</button>
                    <button @click="saveUser()" class="px-6 py-2.5 rounded-xl font-bold text-white bg-burgundy-500 shadow-lg shadow-red-100 hover:bg-maroon transition-all">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Toast Notification (Moved inside the x-data block so Alpine.js binds successfully!) -->
    <div id="successToast" class="fixed top-5 right-5 z-[200] transform transition-all duration-500 translate-x-full opacity-0">
        <div class="bg-white/90 backdrop-blur-xl border border-white/60 p-4 rounded-2xl shadow-2xl flex items-center gap-4 w-80 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-green-500"></div>
            <div class="bg-green-100 p-2.5 rounded-full ml-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <h4 class="text-sm font-bold text-gray-800">Success</h4>
                <p class="text-xs text-gray-500 font-medium toast-msg">New user successfully added!</p>
            </div>
            <button @click="hideToast()" class="ml-auto text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-1 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

</div>
@endsection
