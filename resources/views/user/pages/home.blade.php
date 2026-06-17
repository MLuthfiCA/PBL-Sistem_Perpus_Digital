@extends('user.layouts.app')

@section('content')
<div class="space-y-8">

    <!-- Overdue Books Warning -->
    <x-overdue-warning />

    <!-- Welcome Header -->
    <div class="glass-panel p-8 md:p-12 relative overflow-hidden animate-fade-up border-white/60">
        <div class="relative z-10 max-w-2xl">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                Hello, {{ session()->has('user') ? session('user')['name'] : 'Guest' }}! 👋
            </h1>
            <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                Welcome back to ReadSpace. Explore our extensive digital collection and find your favorite book today.
            </p>
            <div class="flex gap-4">
                <a href="{{ route('katalog') }}" class="px-6 py-3 bg-burgundy-500 text-white rounded-2xl font-bold shadow-lg shadow-red-100 hover:bg-burgundy-600 transition-all flex items-center gap-2">
                    Start Exploring
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
        
        <!-- Decorative Background Gradient Blob -->
        <div class="absolute right-[-20px] bottom-[-20px] w-64 h-64 md:w-96 md:h-96 opacity-20 md:opacity-100">
             <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <path fill="#800020" d="M44.7,-76.4C58.2,-69.2,70,-58.5,77.5,-45.3C85,-32.1,88.3,-16,86.2,-0.7C84.1,14.7,76.7,29.3,67.7,42.4C58.7,55.5,48.1,67,35.2,73.5C22.3,80,7.1,81.4,-7.8,79.5C-22.7,77.6,-37.2,72.4,-49.4,63.9C-61.6,55.4,-71.4,43.5,-77.3,30.1C-83.2,16.7,-85.2,1.8,-82.4,-12.3C-79.6,-26.4,-72,-39.7,-61.5,-49.1C-51,-58.5,-37.5,-64.1,-24.8,-71.8C-12.1,-79.6,-0.1,-89.6,12.3,-90.7C24.7,-91.8,31.2,-83.6,44.7,-76.4Z" transform="translate(100 100)" />
            </svg>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass-panel p-6 animate-fade-up delay-100 group hover:bg-burgundy-500 transition-all duration-500 border-white/60">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-2xl bg-red-50 text-burgundy-500 group-hover:bg-burgundy-400 group-hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-red-400 group-hover:text-red-200">+12% this week</span>
            </div>
            <p class="text-sm font-medium text-gray-500 group-hover:text-red-100">Total Collection</p>
            <h3 class="text-3xl font-bold text-gray-800 group-hover:text-white">{{ number_format($totalBuku) }}</h3>
        </div>

        <div class="glass-panel p-6 animate-fade-up delay-200 group hover:bg-maroon transition-all duration-500 border-white/60">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-2xl bg-red-50 text-maroon group-hover:bg-burgundy-400 group-hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-red-400 group-hover:text-red-200">Active Member</span>
            </div>
            <p class="text-sm font-medium text-gray-500 group-hover:text-red-100">Monthly Users</p>
            <h3 class="text-3xl font-bold text-gray-800 group-hover:text-white">{{ number_format($totalMember) }}</h3>
        </div>

        <div class="glass-panel p-6 animate-fade-up delay-300 group hover:bg-burgundy-900 transition-all duration-500 border-white/60">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-2xl bg-red-50 text-burgundy-900 group-hover:bg-burgundy-800 group-hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-red-400 group-hover:text-red-200">Available Now</span>
            </div>
            <p class="text-sm font-medium text-gray-500 group-hover:text-red-100">Open Resources</p>
            <h3 class="text-3xl font-bold text-gray-800 group-hover:text-white">{{ $availablePercent }}%</h3>
        </div>
    </div>

    @php
        $genres = [
            ['name' => 'Self-Dev', 'icon' => '🌱', 'growth' => '25%', 'color' => 'from-red-400 to-burgundy-500'],
            ['name' => 'Technology', 'icon' => '💻', 'growth' => '18%', 'color' => 'from-rose-400 to-maroon'],
            ['name' => 'Finance', 'icon' => '💰', 'growth' => '15%', 'color' => 'from-amber-600 to-orange-700'],
            ['name' => 'Literature', 'icon' => '📚', 'growth' => '10%', 'color' => 'from-purple-600 to-indigo-900'],
            ['name' => 'Psychology', 'icon' => '🧠', 'growth' => '22%', 'color' => 'from-pink-500 to-rose-700'],
        ];
    @endphp

    <!-- Trending Genres & Chart Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate-fade-up delay-300">
        
        <!-- Left: Genre Cards -->
        <div class="lg:col-span-1 space-y-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 px-1">Genre Statistics</h2>
            @foreach($genres as $genre)
            <div class="glass-panel p-4 flex items-center justify-between group hover:border-burgundy-500 transition-all cursor-pointer border-white/60">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $genre['color'] }} flex items-center justify-center text-xl shadow-sm">
                        {{ $genre['icon'] }}
                    </div>
                    <span class="font-bold text-gray-700">{{ $genre['name'] }}</span>
                </div>
                <span class="text-xs font-bold text-burgundy-500 bg-red-50 px-2 py-1 rounded-lg">{{ $genre['growth'] }}</span>
            </div>
            @endforeach
        </div>

        <!-- Right: The Graph Card -->
        <div class="lg:col-span-2 glass-panel p-8 border-white/60">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Weekly Lending Trends</h3>
                    <p class="text-xs text-gray-400 mt-1">Data collected from the last 7 days</p>
                </div>
                <select class="text-xs font-bold text-gray-500 bg-white/50 border-none rounded-xl focus:ring-0">
                    <option>This Week</option>
                    <option>Last Week</option>
                </select>
            </div>
            
            <div class="relative h-64">
                <canvas id="trendingChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Links / Popular Section -->
    <div class="grid grid-cols-1 gap-8">
        <div class="glass-panel p-8 animate-fade-up delay-300 border-white/60">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Trending Category</h3>
            <div class="flex flex-wrap gap-3">
                @forelse($trendingCategories as $cat)
                <span class="px-4 py-2 rounded-xl bg-white/80 text-gray-600 text-sm font-medium border border-white hover:bg-burgundy-500 hover:text-white transition-all cursor-pointer shadow-sm">
                    {{ $cat }}
                </span>
                @empty
                <span class="text-sm text-gray-500">No categories available yet.</span>
                @endforelse
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('trendingChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'pie',
            plugins: [ChartDataLabels],
            data: {
                labels: ['Self-Dev', 'Technology', 'Literature', 'Psychology'],
                datasets: [{
                    data: [30, 20, 15, 35],
                    backgroundColor: [
                        '#800020', // Burgundy
                        '#630330', // Maroon
                        '#B45309', // Amber/Gold tone
                        '#10B981'  // Emerald
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    datalabels: {
                        color: '#ffffff',
                        formatter: (value, ctx) => {
                            let label = ctx.chart.data.labels[ctx.dataIndex];
                            return label + '\n' + value + '%';
                        },
                        font: {
                            weight: 'bold',
                            size: 11,
                            family: 'DM Sans'
                        },
                        textAlign: 'center'
                    },
                    tooltip: { enabled: false }
                }
            }
        });
    });
</script>
@endsection
