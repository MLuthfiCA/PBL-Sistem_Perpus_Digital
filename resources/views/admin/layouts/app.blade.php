<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>ReadSpace Library Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        burgundy: {
                            50: '#fff1f2',
                            100: '#ffe4e6',
                            500: '#800020',
                            600: '#630330',
                            900: '#4c0519',
                        },
                        maroon: '#630330',
                        rose: {
                            gold: '#E7C0B7',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --glass: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.5);
            --burgundy: #800020;
            --maroon: #630330;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(135deg, #FFFDF5 0%, #F3E5D8 100%);
            background-attachment: fixed;
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
            color: #2D1B1E;
        }

        .glass-panel {
            background: var(--glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-up {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }

        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(128, 0, 32, 0.1); border-radius: 10px; }
    </style>
</head>
<body class="flex flex-col min-h-screen">

    <!-- Background Elements -->
    <div class="fixed inset-0 pointer-events-none z-[-1] overflow-hidden">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-orange-50 opacity-50 blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-[#EBD8C1] opacity-30 blur-[120px]"></div>
        <div class="absolute top-[20%] right-[10%] w-[30%] h-[30%] rounded-full bg-amber-50 opacity-40 blur-[100px]"></div>
    </div>

    @include('admin.components.navbar')

    <div class="flex-grow pt-24 transition-all duration-300">
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @yield('content')
        </main>

        <footer class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 animate-fade-up delay-300">
            <div class="glass-panel p-8 md:p-12 border-white/60 shadow-2xl shadow-red-50">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                    <!-- Brand Section -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('images/readspace-library.png') }}" alt="ReadSpace Logo" class="h-10 w-auto">
                            <span class="font-bold text-2xl text-gray-800 tracking-tight">ReadSpace</span>
                        </div>
                        <p class="text-sm text-gray-500 leading-relaxed">
                            ReadSpace Library is a modern digital literacy platform specifically designed to facilitate Polibatam students in accessing unlimited knowledge.
                        </p>
                        <div class="flex gap-4">
                            <div class="w-8 h-8 rounded-lg bg-white border border-red-50 flex items-center justify-center text-burgundy-500 hover:bg-burgundy-500 hover:text-white transition-all cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                            </div>
                            <div class="w-8 h-8 rounded-lg bg-white border border-red-50 flex items-center justify-center text-burgundy-500 hover:bg-burgundy-500 hover:text-white transition-all cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.162 6.162 6.162 6.162-2.759 6.162-6.162-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <div>
                        <h4 class="font-bold text-gray-800 mb-6 uppercase text-xs tracking-widest">Admin Menu</h4>
                        <ul class="space-y-4">
                            <li><a href="{{ route('admin.katalog') }}" class="text-gray-500 hover:text-burgundy-500 text-sm transition-colors">Dashboard</a></li>
                            <li><a href="{{ route('admin.buku.create') }}" class="text-gray-500 hover:text-burgundy-500 text-sm transition-colors">Add Book</a></li>
                            <li><a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-burgundy-500 text-sm transition-colors">User Data</a></li>
                            <li><a href="{{ route('admin.search') }}" class="text-gray-500 hover:text-burgundy-500 text-sm transition-colors">Search</a></li>
                        </ul>
                    </div>

                    <!-- Contact & Location -->
                    <div>
                        <h4 class="font-bold text-gray-800 mb-6 uppercase text-xs tracking-widest">Contacts & Locations</h4>
                        <ul class="space-y-4">
                            <li class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-burgundy-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="text-sm text-gray-500">Ahmad Yani Street, Batam City, Batam,  Kepulauan Riau 29461</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-burgundy-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm text-gray-500">library@polibatam.ac.id</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Opening Hours -->
                    <div>
                        <h4 class="font-bold text-gray-800 mb-6 uppercase text-xs tracking-widest">Operating Hours</h4>
                        <ul class="space-y-3">
                            <li class="flex justify-between text-sm">
                                <span class="text-gray-400">Monday - Friday</span>
                                <span class="font-bold text-gray-700">08:00 - 20:00</span>
                            </li>
                            <li class="flex justify-between text-sm">
                                <span class="text-gray-400">Saturday</span>
                                <span class="font-bold text-gray-700">09:00 - 15:00</span>
                            </li>
                            <li class="flex justify-between text-sm">
                                <span class="text-gray-400">Sunday</span>
                                <span class="font-bold text-red-500 uppercase tracking-widest text-[10px]">Closed</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Copyright Section -->
                <div class="mt-12 pt-8 border-t border-red-50 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">© {{ date('Y') }} Readspace Library. Built for Polibatam.</p>
                    <div class="flex gap-8">
                        <a href="#" class="text-[11px] font-bold text-gray-400 hover:text-burgundy-500 transition-colors uppercase tracking-widest">Terms</a>
                        <a href="#" class="text-[11px] font-bold text-gray-400 hover:text-burgundy-500 transition-colors uppercase tracking-widest">Privacy</a>
                        <a href="#" class="text-[11px] font-bold text-gray-400 hover:text-burgundy-500 transition-colors uppercase tracking-widest">Help Center</a>
                    </div>
                </div>
            </div>
        </footer>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    </div>
</body>
</html>
