<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'TefaHub — Platform Katalog & Manajemen Proyek Teaching Factory' }}</title>
    
    <!-- Modern Font: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #fafafa;
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }
        .hero-gradient {
            background: radial-gradient(circle at 50% 0%, rgba(99, 102, 241, 0.12) 0%, rgba(248, 250, 252, 0) 70%);
        }
        .glow-effect {
            box-shadow: 0 0 50px -10px rgba(99, 102, 241, 0.3);
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="antialiased text-slate-900 bg-slate-50 flex flex-col min-h-screen selection:bg-indigo-600 selection:text-white" x-data="{ mobileMenuOpen: false }">

    <!-- Top Announcement Bar -->
    <div class="bg-slate-900 text-slate-200 text-xs py-2 px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-2">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300 font-semibold text-[10px] tracking-wide uppercase border border-indigo-500/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                    TEFA 2.0 AI Integrated
                </span>
                <span class="text-slate-300 text-xs">Pesan produk & layanan industri langsung dari siswa vokasi tersertifikasi.</span>
            </div>
            <div class="flex items-center gap-4 text-xs font-medium text-slate-400">
                <a href="{{ route('about') }}" class="hover:text-white transition">Panduan Kemitraan</a>
                <span>•</span>
                <span class="text-slate-400 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    Bimbingan Guru Ahli
                </span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <nav class="sticky top-0 z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <!-- Brand Logo -->
                <div class="flex items-center gap-10">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-700 to-blue-500 flex items-center justify-center text-white shadow-lg shadow-indigo-600/25 group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div>
                            <span class="text-2xl font-black tracking-tight text-slate-900 flex items-center gap-1">
                                Tefa<span class="text-indigo-600">Hub</span>
                            </span>
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 -mt-1">Teaching Factory Portal</span>
                        </div>
                    </a>

                    <!-- Nav Links Desktop -->
                    <div class="hidden md:flex items-center space-x-1">
                        <a href="{{ route('home') }}" 
                           class="px-4 py-2 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('home') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/60' }}">
                            Beranda
                        </a>
                        <a href="{{ route('jurusan.list') }}" 
                           class="px-4 py-2 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('jurusan.*', 'tefa.storefront') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/60' }}">
                            Unit Jurusan
                        </a>
                        <a href="{{ route('produk.list') }}" 
                           class="px-4 py-2 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('produk.list') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/60' }}">
                            Katalog Produk & Jasa
                        </a>
                        <a href="{{ route('about') }}" 
                           class="px-4 py-2 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('about') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/60' }}">
                            Tentang TEFA
                        </a>
                    </div>
                </div>

                <!-- Right Side Buttons -->
                <div class="flex items-center gap-3">
                    
                    <!-- Search Icon Shortcut (Mobile) -->
                    <a href="{{ route('produk.list') }}" class="p-2.5 rounded-xl text-slate-500 hover:text-indigo-600 hover:bg-slate-100 transition md:hidden">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </a>

                    @auth
                        <!-- Dashboard Button -->
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-slate-900/10 hover:shadow-indigo-600/20 transition-all duration-200 transform hover:-translate-y-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            Panel Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-block text-sm font-bold text-slate-700 hover:text-indigo-600 px-4 py-2 rounded-xl hover:bg-slate-100 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-indigo-600/25 hover:shadow-indigo-600/40 transition-all duration-200 transform hover:-translate-y-0.5">
                            Daftar Mitra
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2.5 rounded-xl text-slate-700 hover:bg-slate-100 transition md:hidden focus:outline-none">
                        <svg class="w-6 h-6" x-show="!mobileMenuOpen" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        <svg class="w-6 h-6" x-show="mobileMenuOpen" style="display:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Nav Menu Dropdown -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden border-t border-slate-200 bg-white/95 backdrop-blur-xl px-4 pt-3 pb-6 space-y-2">
            <a href="{{ route('home') }}" class="block px-4 py-3 rounded-xl text-sm font-bold {{ request()->routeIs('home') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-700 hover:bg-slate-50' }}">Beranda</a>
            <a href="{{ route('jurusan.list') }}" class="block px-4 py-3 rounded-xl text-sm font-bold {{ request()->routeIs('jurusan.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-700 hover:bg-slate-50' }}">Unit Jurusan</a>
            <a href="{{ route('produk.list') }}" class="block px-4 py-3 rounded-xl text-sm font-bold {{ request()->routeIs('produk.list') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-700 hover:bg-slate-50' }}">Katalog Produk & Jasa</a>
            <a href="{{ route('about') }}" class="block px-4 py-3 rounded-xl text-sm font-bold {{ request()->routeIs('about') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-700 hover:bg-slate-50' }}">Tentang TEFA</a>
            <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="w-full text-center py-3 bg-slate-900 text-white font-bold rounded-xl text-sm">Masuk Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="w-full text-center py-2.5 bg-slate-100 text-slate-800 font-bold rounded-xl text-sm">Log in</a>
                    <a href="{{ route('register') }}" class="w-full text-center py-2.5 bg-indigo-600 text-white font-bold rounded-xl text-sm">Daftar Akun Baru</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Page Body -->
    <main class="flex-grow">
        @yield('content', $slot ?? '')
    </main>

    <!-- Modern Rich Footer -->
    <footer class="bg-slate-950 text-slate-400 pt-20 pb-12 border-t border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 pb-16 border-b border-slate-800">
                
                <!-- Brand Column -->
                <div class="lg:col-span-2 space-y-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-500 flex items-center justify-center text-white font-black text-xl shadow-lg shadow-indigo-500/20">
                            ?
                        </div>
                        <span class="text-2xl font-black text-white tracking-tight">Tefa<span class="text-indigo-400">Hub</span>.</span>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-md">
                        Platform Teaching Factory generasi baru yang mengintegrasikan kecerdasan buatan (AI) untuk automasi pendelegasian proyek dari chat WhatsApp langsung ke siswa berbakat dengan supervisi guru ahli.
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 text-xs text-slate-300">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            AI Assistant Online
                        </div>
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 text-xs text-slate-300">
                            ??? Standar Industri Terverifikasi
                        </div>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div>
                    <h4 class="text-white font-bold text-sm tracking-wider uppercase mb-5">Eksplorasi</h4>
                    <ul class="space-y-3 text-sm font-medium">
                        <li><a href="{{ route('home') }}" class="hover:text-indigo-400 transition-colors">Beranda</a></li>
                        <li><a href="{{ route('jurusan.list') }}" class="hover:text-indigo-400 transition-colors">Daftar Jurusan TEFA</a></li>
                        <li><a href="{{ route('produk.list') }}" class="hover:text-indigo-400 transition-colors">Katalog Semua Layanan</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-indigo-400 transition-colors">Tentang Kami</a></li>
                    </ul>
                </div>

                <!-- Unit Layanan -->
                <div>
                    <h4 class="text-white font-bold text-sm tracking-wider uppercase mb-5">Unit Produksi</h4>
                    <ul class="space-y-3 text-sm font-medium">
                        <li><a href="{{ route('produk.list', ['type' => 'jasa']) }}" class="hover:text-indigo-400 transition-colors">Software & IT Solutions</a></li>
                        <li><a href="{{ route('produk.list', ['type' => 'jasa']) }}" class="hover:text-indigo-400 transition-colors">Desain & Kreatif Agency</a></li>
                        <li><a href="{{ route('produk.list', ['type' => 'jasa']) }}" class="hover:text-indigo-400 transition-colors">Bengkel Servis Otomotif</a></li>
                        <li><a href="{{ route('produk.list', ['type' => 'produk']) }}" class="hover:text-indigo-400 transition-colors">Artisan Bakery & Kuliner</a></li>
                    </ul>
                </div>

                <!-- Kontak -->
                <div>
                    <h4 class="text-white font-bold text-sm tracking-wider uppercase mb-5">Hubungi Kami</h4>
                    <ul class="space-y-3 text-sm font-medium">
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-indigo-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>Gedung Pusat TEFA Vokasi Unggulan, Indonesia</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span>halo@tefahub.id</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span>+62 812-3456-7890</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} TefaHub Ecosystem. Hak cipta dilindungi undang-undang.</p>
                <div class="flex items-center gap-6">
                    <a href="#" class="hover:text-slate-400 transition">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-slate-400 transition">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-slate-400 transition">SOP Pelayanan Industri</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Smart Floating AI Chat Widget -->
    <x-ai-chat-widget />

</body>
</html>
