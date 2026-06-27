@extends('user.layouts.app')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 animate-fade-up">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center">
                <img src="{{ asset('images/readspace-library.png') }}" alt="ReadSpace Logo" class="h-20 w-auto drop-shadow-2xl">
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Create a New Account</h2>
            <p class="mt-2 text-sm text-gray-500 font-medium">Join our community of readers.</p>
        </div>

        <div class="glass-panel p-6 sm:p-8 shadow-2xl shadow-red-50 border-white/60">
            <form class="space-y-5" action="{{ route('register.post') }}" method="POST">
                @csrf

                @if($errors->any())
                <div class="p-4 mb-4 text-sm text-red-800 rounded-2xl bg-red-50 border border-red-100" role="alert">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li class="font-medium">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Full name</label>
                    <input name="nama" type="text" value="{{ old('nama') }}" required class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl placeholder-gray-400 text-gray-800 focus:outline-none focus:ring-4 focus:ring-red-100 focus:border-burgundy-500 transition-all text-sm font-medium" placeholder="Your full name">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Username</label>
                    <input name="username" type="text" value="{{ old('username') }}" required class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl placeholder-gray-400 text-gray-800 focus:outline-none focus:ring-4 focus:ring-red-100 focus:border-burgundy-500 transition-all text-sm font-medium" placeholder="Choose a username">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Email</label>
                    <input name="email" type="email" value="{{ old('email') }}" required class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl placeholder-gray-400 text-gray-800 focus:outline-none focus:ring-4 focus:ring-red-100 focus:border-burgundy-500 transition-all text-sm font-medium" placeholder="email@example.com">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Student ID (NIM)</label>
                    <input name="nim" type="text" value="{{ old('nim') }}" required class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl placeholder-gray-400 text-gray-800 focus:outline-none focus:ring-4 focus:ring-red-100 focus:border-burgundy-500 transition-all text-sm font-medium" placeholder="Enter your student ID number">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Password</label>
                    <input name="password" type="password" required class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl placeholder-gray-400 text-gray-800 focus:outline-none focus:ring-4 focus:ring-red-100 focus:border-burgundy-500 transition-all text-sm font-medium" placeholder="••••••••">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent text-sm font-bold rounded-2xl text-white bg-burgundy-500 hover:bg-maroon focus:outline-none focus:ring-4 focus:ring-red-100 transition-all shadow-lg shadow-red-100 transform active:scale-95">
                        Register Account
                    </button>
                </div>
            </form>
            
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500 font-medium">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="font-bold text-burgundy-600 hover:text-maroon transition-colors">Sign in here</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
