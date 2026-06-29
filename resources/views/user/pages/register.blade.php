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
            <form class="space-y-5" action="{{ route('register.post') }}" method="POST" id="registerForm">
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

                {{-- Full Name --}}
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Full Name</label>
                    <input name="nama" type="text" value="{{ old('nama') }}" required 
                        class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl placeholder-gray-400 text-gray-800 focus:outline-none focus:ring-4 focus:ring-red-100 focus:border-burgundy-500 transition-all text-sm font-medium" 
                        placeholder="Your full name">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Email</label>
                    <input name="email" type="email" value="{{ old('email') }}" required 
                        class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl placeholder-gray-400 text-gray-800 focus:outline-none focus:ring-4 focus:ring-red-100 focus:border-burgundy-500 transition-all text-sm font-medium" 
                        placeholder="email@example.com">
                </div>

                {{-- NIM --}}
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Student ID (NIM)</label>
                    <input name="nim" type="text" inputmode="numeric" pattern="[0-9]*" 
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');" 
                        value="{{ old('nim') }}" required 
                        class="w-full px-4 py-3.5 border border-white bg-white/50 rounded-2xl placeholder-gray-400 text-gray-800 focus:outline-none focus:ring-4 focus:ring-red-100 focus:border-burgundy-500 transition-all text-sm font-medium" 
                        placeholder="Enter your Student ID">
                    <p class="mt-1 text-xs text-gray-400 ml-1">This Student ID will be used for login.</p>
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Password</label>
                    <div class="relative">
                        <input id="password" name="password" type="password" required
                            minlength="8" maxlength="12"
                            oninput="this.value = this.value.replace(/ /g, '');"
                            class="w-full px-4 py-3.5 pr-12 border border-white bg-white/50 rounded-2xl placeholder-gray-400 text-gray-800 focus:outline-none focus:ring-4 focus:ring-red-100 focus:border-burgundy-500 transition-all text-sm font-medium" 
                            placeholder="••••••••">
                        <button type="button" id="togglePassword"
                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition-colors"
                            onclick="togglePasswordVisibility('password', 'eyeIcon1')">
                            <svg id="eyeIcon1" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <p class="mt-1.5 text-[11px] text-gray-400 ml-1 leading-relaxed">
                        8–12 characters, no spaces allowed.
                    </p>
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Confirm Password</label>
                    <div class="relative">
                        <input id="password_confirm" name="password_confirmation" type="password" required
                            minlength="8" maxlength="12"
                            oninput="this.value = this.value.replace(/ /g, '');"
                            class="w-full px-4 py-3.5 pr-12 border border-white bg-white/50 rounded-2xl placeholder-gray-400 text-gray-800 focus:outline-none focus:ring-4 focus:ring-red-100 focus:border-burgundy-500 transition-all text-sm font-medium" 
                            placeholder="••••••••">
                        <button type="button" id="togglePasswordConfirm"
                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition-colors"
                            onclick="togglePasswordVisibility('password_confirm', 'eyeIcon2')">
                            <svg id="eyeIcon2" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <p id="passwordMatchMsg" class="mt-1 text-xs ml-1 hidden"></p>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent text-sm font-bold rounded-2xl text-white bg-burgundy-500 hover:bg-maroon focus:outline-none focus:ring-4 focus:ring-red-100 transition-all shadow-lg shadow-red-100 transform active:scale-95">
                        Register Now
                    </button>
                </div>
            </form>
            
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500 font-medium">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="font-bold text-burgundy-600 hover:text-maroon transition-colors">Login here</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    
    if (input.type === 'password') {
        input.type = 'text';
        // Eye-slash icon (hide)
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
        `;
    } else {
        input.type = 'password';
        // Eye icon (show)
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
    }
}

// Real-time password match check
document.getElementById('password_confirm').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirm = this.value;
    const msg = document.getElementById('passwordMatchMsg');

    if (confirm.length === 0) {
        msg.classList.add('hidden');
        return;
    }

    if (password === confirm) {
        msg.textContent = '✓ Passwords match';
        msg.className = 'mt-1 text-xs ml-1 text-green-600 font-medium';
    } else {
        msg.textContent = '✗ Passwords do not match';
        msg.className = 'mt-1 text-xs ml-1 text-red-500 font-medium';
    }
});

document.getElementById('password').addEventListener('input', function() {
    const confirmInput = document.getElementById('password_confirm');
    if (confirmInput.value.length > 0) {
        confirmInput.dispatchEvent(new Event('input'));
    }
});

// Prevent form submit if passwords don't match
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('password_confirm').value;
    if (password !== confirm) {
        e.preventDefault();
        const msg = document.getElementById('passwordMatchMsg');
        msg.textContent = '✗ Passwords do not match, please check again';
        msg.className = 'mt-1 text-xs ml-1 text-red-500 font-medium';
        document.getElementById('password_confirm').focus();
    }
});
</script>
@endsection
