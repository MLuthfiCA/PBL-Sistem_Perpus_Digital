@extends('user.layouts.app')

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- Welcome Header -->
    <div class="glass-panel p-6 sm:p-8 md:p-12 relative overflow-hidden animate-fade-up border-white/60">
        <div class="relative z-10 max-w-2xl">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-800 mb-3 sm:mb-4 leading-tight">
                Hello, {{ auth()->check() ? auth()->user()->name : 'Guest' }}! 👋
            </h1>
            <p class="text-base sm:text-lg text-gray-600 mb-6 sm:mb-8 leading-relaxed">
                Welcome back to ReadSpace. Explore our extensive digital collection and find your favorite book today.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('katalog') }}" class="px-5 sm:px-6 py-2.5 sm:py-3 bg-burgundy-500 text-white rounded-2xl font-bold shadow-lg shadow-red-100 hover:bg-burgundy-600 transition-all flex items-center gap-2 text-sm sm:text-base">
                    Start Exploring
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
        
        <div class="absolute right-[-20px] bottom-[-20px] w-40 h-40 sm:w-64 sm:h-64 md:w-96 md:h-96 opacity-10 sm:opacity-20 md:opacity-100 pointer-events-none">
             <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <path fill="#800020" d="M44.7,-76.4C58.2,-69.2,70,-58.5,77.5,-45.3C85,-32.1,88.3,-16,86.2,-0.7C84.1,14.7,76.7,29.3,67.7,42.4C58.7,55.5,48.1,67,35.2,73.5C22.3,80,7.1,81.4,-7.8,79.5C-22.7,77.6,-37.2,72.4,-49.4,63.9C-61.6,55.4,-71.4,43.5,-77.3,30.1C-83.2,16.7,-85.2,1.8,-82.4,-12.3C-79.6,-26.4,-72,-39.7,-61.5,-49.1C-51,-58.5,-37.5,-64.1,-24.8,-71.8C-12.1,-79.6,-0.1,-89.6,12.3,-90.7C24.7,-91.8,31.2,-83.6,44.7,-76.4Z" transform="translate(100 100)" />
            </svg>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
        <div class="glass-panel p-5 sm:p-6 animate-fade-up delay-100 group hover:bg-burgundy-500 transition-all duration-500 border-white/60">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div class="p-2.5 sm:p-3 rounded-2xl bg-red-50 text-burgundy-500 group-hover:bg-white group-hover:text-burgundy-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-red-400 group-hover:text-red-200">+12% this week</span>
            </div>
            <p class="text-sm font-medium text-gray-500 group-hover:text-red-100">Total Collection</p>
            <h3 class="text-2xl sm:text-3xl font-bold text-gray-800 group-hover:text-white">{{ number_format($totalBuku ?? \App\Models\Buku::count()) }}</h3>
        </div>

        <div class="glass-panel p-5 sm:p-6 animate-fade-up delay-200 group hover:bg-maroon transition-all duration-500 border-white/60">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div class="p-2.5 sm:p-3 rounded-2xl bg-red-50 text-maroon group-hover:bg-white group-hover:text-maroon transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-red-400 group-hover:text-red-200">Registered Member</span>
            </div>
            <p class="text-sm font-medium text-gray-500 group-hover:text-red-100">Total Users</p>
            <h3 class="text-2xl sm:text-3xl font-bold text-gray-800 group-hover:text-white">{{ number_format($totalUsers ?? \App\Models\User::where('role','!=','admin')->count()) }}</h3>
        </div>

        <div class="glass-panel p-5 sm:p-6 animate-fade-up delay-300 group hover:bg-burgundy-900 transition-all duration-500 border-white/60">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div class="p-2.5 sm:p-3 rounded-2xl bg-red-50 text-burgundy-900 group-hover:bg-white group-hover:text-burgundy-900 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-red-400 group-hover:text-red-200">Available Now</span>
            </div>
            <p class="text-sm font-medium text-gray-500 group-hover:text-red-100">Open Resources</p>
            @php
                $tb = \App\Models\Buku::count();
                $pct = isset($pctTersedia) ? $pctTersedia : ($tb > 0 ? round((\App\Models\Buku::where('status','Tersedia')->count() / $tb) * 100) : 0);
            @endphp
            <h3 class="text-2xl sm:text-3xl font-bold text-gray-800 group-hover:text-white">{{ $pct }}%</h3>
        </div>
    </div>

    @php
        $genreColors = [
            'from-red-400 to-burgundy-500',
            'from-rose-400 to-maroon',
            'from-amber-600 to-orange-700',
            'from-purple-600 to-indigo-900',
            'from-pink-500 to-rose-700',
            'from-teal-500 to-emerald-700',
            'from-blue-500 to-indigo-700',
            'from-green-500 to-teal-700',
        ];
        $genreIcons = ['📚', '💻', '🌱', '🧠', '💰', '🔬', '🎨', '📖'];
        $gs = isset($genreStats) ? $genreStats : \App\Models\Kategori::withCount('buku')->orderByDesc('buku_count')->get()->filter(fn($k) => $k->buku_count > 0)->map(fn($k) => ['name'=>$k->nama_kategori,'count'=>$k->buku_count]);
    @endphp

    <!-- Genre Statistics & Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8 animate-fade-up delay-300">
        
        <!-- Genre Cards -->
        <div class="lg:col-span-1 space-y-3">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6 px-1">Genre Statistics</h2>
            @forelse($gs as $i => $genre)
            <div class="glass-panel p-3 sm:p-4 flex items-center justify-between group hover:border-burgundy-500 transition-all cursor-pointer border-white/60">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-br {{ $genreColors[$i % count($genreColors)] }} flex items-center justify-center text-lg sm:text-xl shadow-sm flex-shrink-0">
                        {{ $genreIcons[$i % count($genreIcons)] }}
                    </div>
                    <span class="font-bold text-gray-700 text-sm sm:text-base">{{ $genre['name'] }}</span>
                </div>
                <span class="text-xs font-bold text-burgundy-500 bg-red-50 px-2 py-1 rounded-lg whitespace-nowrap ml-2">{{ $genre['count'] }} buku</span>
            </div>
            @empty
            <div class="glass-panel p-4 text-gray-400 text-sm border-white/60">Belum ada kategori buku.</div>
            @endforelse
        </div>

        <!-- Chart -->
        <div class="lg:col-span-2 glass-panel p-6 sm:p-8 border-white/60">
            <div class="flex items-center justify-between mb-6 sm:mb-8 gap-4">
                <div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800">Weekly Trends</h3>
                    <p class="text-xs text-gray-400 mt-1 hidden sm:block">Data collected from the last 7 days</p>
                </div>
                
            </div>
            
            <div class="relative h-52 sm:h-64">
                <canvas id="trendingChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Trending Categories -->
    <div class="glass-panel p-6 sm:p-8 animate-fade-up delay-300 border-white/60">
        <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 sm:mb-6">Trending Categories</h3>
        <div class="flex flex-wrap gap-2 sm:gap-3">
            @foreach(['Technology', 'Literature', 'Science', 'History', 'Art', 'Design'] as $cat)
            <span class="px-3 sm:px-4 py-1.5 sm:py-2 rounded-xl bg-white/80 text-gray-600 text-sm font-medium border border-white hover:bg-burgundy-500 hover:text-white transition-all cursor-pointer shadow-sm">
                {{ $cat }}
            </span>
            @endforeach
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('trendingChart').getContext('2d');

        const chartLabels = {!! json_encode(collect($gs)->pluck('name')) !!};
        const chartData   = {!! json_encode(collect($gs)->pluck('count')) !!};

        const palette = [
            '#800020', '#630330', '#B45309', '#10B981',
            '#6366F1', '#EC4899', '#0EA5E9', '#F59E0B'
        ];
        const colors = chartLabels.map((_, i) => palette[i % palette.length]);

        if (chartLabels.length === 0) {
            document.getElementById('trendingChart').parentElement.innerHTML =
                '<p class="text-center text-gray-400 text-sm mt-20">Belum ada data genre.</p>';
            return;
        }

        new window.Chart(ctx, {
            type: 'pie',
            plugins: [window.ChartDataLabels],
            data: {
                labels: chartLabels,
                datasets: [{
                    data: chartData,
                    backgroundColor: colors,
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    datalabels: {
                        color: '#fff',
                        formatter: (value, ctx) => {
                            const total = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const pct   = total > 0 ? Math.round((value / total) * 100) : 0;
                            return ctx.chart.data.labels[ctx.dataIndex] + '\n' + pct + '%';
                        },
                        font: { weight: 'bold', size: 11 }
                    }
                }
            }
        });
    });
</script>

@endsection