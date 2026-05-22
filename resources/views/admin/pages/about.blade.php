@extends('admin.layouts.app')

@section('content')
    <div class="py-10">
        <div class="text-center mb-16 animate-fade-up">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Our Team</h2>
            <p class="text-gray-500 max-w-2xl mx-auto">The creative minds behind ReadSpace Library are dedicated to building the best digital reading experience for Polibatam students.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
                // To add photos:
                // 1. Create a 'team' folder inside 'public/images/' (public/images/team/)
                // 2. Put the photo files into that folder
                // 3. Ensure the filename matches the 'photo' key below
                $team = [
                    ['name' => 'M. Luthfi Causart Azavi', 'nim' => '3312501052', 'role' => 'Team Leader', 'color' => 'bg-red-50 text-burgundy-500', 'photo' => 'luthfi.jpg'],
                    ['name' => 'Muhammad Risky Kurnia', 'nim' => '3312501056', 'role' => 'Team Member', 'color' => 'bg-rose-50 text-maroon', 'photo' => 'risky.jpg'],
                    ['name' => 'Siti Halimah Chania', 'nim' => '3312501057', 'role' => 'Team Member', 'color' => 'bg-orange-50 text-orange-700', 'photo' => 'siti.jpg'],
                    ['name' => 'Zahrah Athirah Badiah', 'nim' => '3312501060', 'role' => 'Team Member', 'color' => 'bg-purple-50 text-indigo-900', 'photo' => 'zahrah.jpg'],
                ];
            @endphp

            @foreach($team as $index => $member)
            <x-ui.glass-card class="p-8 text-center hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group animate-fade-up" style="animation-delay: {{ $index * 100 }}ms">
                
                <!-- TEAM MEMBER PHOTO -->
                <div class="w-24 h-24 mx-auto mb-6 rounded-full {{ $member['color'] }} flex items-center justify-center text-3xl font-bold shadow-inner overflow-hidden relative border-4 border-white">
                    <!-- Default initial if photo fails to load (or is missing) -->
                    <span class="relative z-0">{{ substr($member['name'], 0, 1) }}</span>
                    
                    <!-- Photo will overlay the initial above if the file exists in public/images/team/ -->
                    <img src="{{ asset('images/team/' . $member['photo']) }}" alt="{{ $member['name'] }}" class="w-full h-full object-cover absolute inset-0 z-10" onerror="this.style.display='none'">
                </div>

                <p class="font-bold text-xl text-gray-800 mb-2 group-hover:text-burgundy-500 transition-colors">{{ $member['name'] }}</p>
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-400">Student ID: {{ $member['nim'] }}</p>
                    <p class="text-sm font-medium text-gray-400">Program: Informatics Engineering</p>
                    <div class="mt-6 pt-6 border-t border-red-50">
                        <span class="px-4 py-1.5 rounded-full bg-white text-[10px] font-bold text-gray-400 uppercase tracking-widest border border-red-50">{{ $member['role'] }}</span>
                    </div>
                </div>
            </x-ui.glass-card>
            @endforeach
        </div>
    </div>
@endsection
