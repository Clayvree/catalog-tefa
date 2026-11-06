<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- SEO Primary Meta Tags -->
    <title>{{ $title ?? 'TefaHub — Platform Katalog & Manajemen Proyek Teaching Factory' }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'TefaHub adalah platform katalog digital, portofolio karya siswa, dan sistem manajemen pesanan Teaching Factory SMK secara transparan dan profesional.' }}">
    <meta name="keywords" content="{{ $metaKeywords ?? 'teaching factory, tefa smk, katalog produk smk, jasa smk, portofolio siswa, order tefa' }}">
    <meta name="author" content="Teaching Factory Hub">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="TefaHub">
    <meta property="og:title" content="{{ $title ?? 'TefaHub — Platform Katalog & Manajemen Proyek Teaching Factory' }}">
    <meta property="og:description" content="{{ $metaDescription ?? 'TefaHub adalah platform katalog digital, portofolio karya siswa, dan sistem manajemen pesanan Teaching Factory SMK secara transparan dan profesional.' }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('images/og-preview.png') }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ $title ?? 'TefaHub — Platform Katalog & Manajemen Proyek Teaching Factory' }}">
    <meta name="twitter:description" content="{{ $metaDescription ?? 'TefaHub adalah platform katalog digital, portofolio karya siswa, dan sistem manajemen pesanan Teaching Factory SMK.' }}">
    <meta name="twitter:image" content="{{ $ogImage ?? asset('images/og-preview.png') }}">
    
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
<body class="antialiased text-slate-900 bg-slate-50 flex flex-col min-h-screen overflow-x-hidden selection:bg-indigo-600 selection:text-white" x-data="{ mobileMenuOpen: false }">

    <!-- Top Announcement Bar -->
    <div class="bg-slate-900 text-slate-200 text-xs py-2 px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-2">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300 font-semibold text-[10px] tracking-wide uppercase border border-indigo-500/30">
                    Teaching Factory
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
            <div class="flex justify-between items-center h-16 sm:h-20">
                
                <!-- Left: Brand Logo -->
                <div class="flex items-center flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 sm:gap-3 group">
                        <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-700 to-blue-500 flex items-center justify-center text-white shadow-lg shadow-indigo-600/25 group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div>
                            <span class="text-xl sm:text-2xl font-black tracking-tight text-slate-900 flex items-center gap-0.5">
                                Tefa<span class="text-indigo-600">Hub</span>
                            </span>
                            <span class="hidden sm:block text-[10px] font-bold uppercase tracking-wider text-slate-500 -mt-1">Teaching Factory</span>
                        </div>
                    </a>
                </div>

                <!-- Center: Nav Links Desktop (Balanced & Centered) -->
                <div class="hidden lg:flex items-center justify-center gap-1 xl:gap-2">
                    <a href="{{ route('home') }}" 
                       class="px-3.5 py-2 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('home') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
                        Beranda
                    </a>
                    <a href="{{ route('jurusan.list') }}" 
                       class="px-3.5 py-2 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('jurusan.*', 'tefa.storefront') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
                        Unit Jurusan
                    </a>
                    <a href="{{ route('produk.list') }}" 
                       class="px-3.5 py-2 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('produk.list') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
                        Katalog Produk & Jasa
                    </a>
                    <a href="{{ route('about') }}" 
                       class="px-3.5 py-2 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('about') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
                        Tentang TEFA
                    </a>
                    <a href="{{ route('contact') }}" 
                       class="px-3.5 py-2 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('contact') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
                        Hubungi Kami
                    </a>
                </div>

                <!-- Right Side Actions -->
                <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                    
                    @auth
                        @php
                            $role = Auth::user()->role?->value;
                            $isStaff = in_array($role, ['superadmin', 'admin_jurusan', 'worker']);
                        @endphp

                        <!-- Dashboard Button (Hanya Staff: Admin Jurusan, Worker/Siswa, Superadmin) -->
                        @if($isStaff)
                            <a href="{{ route('dashboard') }}" 
                               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold bg-indigo-600 text-white hover:bg-indigo-700 shadow-md shadow-indigo-600/20 hover:shadow-indigo-600/35 transition-all duration-200 transform hover:-translate-y-0.5 whitespace-nowrap">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                <span>Dashboard</span>
                            </a>
                        @else
                            <!-- Pesanan Saya Icon Button (Khusus Mitra / Customer) -->
                            <a href="{{ route('order.my_orders') }}" 
                               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs sm:text-sm font-bold text-slate-700 hover:text-indigo-600 hover:bg-slate-100 transition whitespace-nowrap" 
                               title="Pesanan Saya">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                <span class="hidden sm:inline">Pesanan Saya</span>
                            </a>
                        @endif

                        <!-- Profile Dropdown (Alpine.js) -->
                        <div x-data="{ profileOpen: false }" class="relative">
                            <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false" 
                                    class="flex items-center gap-2 p-1.5 sm:px-2.5 sm:py-1.5 bg-white border border-slate-200/90 hover:border-indigo-300 rounded-full sm:rounded-2xl transition shadow-xs focus:outline-none">
                                <span class="w-7 h-7 sm:w-8 sm:h-8 rounded-full sm:rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-600 text-white font-bold text-xs flex items-center justify-center shadow-xs uppercase">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </span>
                                <div class="hidden sm:block text-left">
                                    <span class="block text-xs font-bold text-slate-800 max-w-[100px] truncate leading-tight">{{ Auth::user()->name }}</span>
                                    <span class="block text-[10px] font-semibold text-slate-400 capitalize leading-tight">
                                        {{ $role === 'superadmin' ? 'Super Admin' : ($role === 'admin_jurusan' ? 'Admin TEFA' : ($role === 'worker' ? 'Tim Proyek' : 'Mitra')) }}
                                    </span>
                                </div>
                                <svg class="w-3.5 h-3.5 text-slate-400 hidden sm:block transition-transform duration-200" :class="{ 'rotate-180': profileOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="profileOpen" 
                                 x-transition:enter="transition ease-out duration-150" 
                                 x-transition:enter-start="opacity-0 scale-95" 
                                 x-transition:enter-end="opacity-100 scale-100" 
                                 x-transition:leave="transition ease-in duration-100" 
                                 x-transition:leave-start="opacity-100 scale-100" 
                                 x-transition:leave-end="opacity-0 scale-95" 
                                 style="display:none;" 
                                 class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden py-1.5 z-50">
                                
                                <div class="px-4 py-2.5 bg-slate-50/80 border-b border-slate-100">
                                    <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Masuk Sebagai</p>
                                    <p class="text-xs text-slate-800 font-bold truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-[11px] text-slate-500 truncate">{{ Auth::user()->email }}</p>
                                </div>

                                <div class="py-1">
                                    @if($isStaff)
                                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-indigo-600 hover:bg-indigo-50/70 transition">
                                            <svg class="w-4 h-4 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                            Dashboard Panel
                                        </a>
                                    @else
                                        <a href="{{ route('order.my_orders') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition">
                                            <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                            Pesanan Saya
                                        </a>
                                    @endif

                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition">
                                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        Pengaturan Profil
                                    </a>
                                </div>

                                <div class="border-t border-slate-100 pt-1">
                                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2.5 text-left px-4 py-2 text-xs font-bold text-red-500 hover:bg-red-50 transition">
                                            <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                            Keluar / Log Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Guest Mode Buttons -->
                        <a href="{{ route('login') }}" class="text-xs sm:text-sm font-bold text-slate-700 hover:text-indigo-600 px-3 sm:px-4 py-2 rounded-xl hover:bg-slate-100 transition whitespace-nowrap">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 sm:gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-3.5 sm:px-5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm font-bold shadow-md shadow-indigo-600/25 hover:shadow-indigo-600/40 transition-all duration-200 transform hover:-translate-y-0.5 whitespace-nowrap">
                            <span>Daftar Mitra</span>
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    @endauth

                    <!-- Mobile Menu Button (Hamburger) -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" 
                            class="p-2 sm:p-2.5 rounded-xl text-slate-700 hover:bg-slate-100 transition lg:hidden focus:outline-none"
                            aria-label="Toggle navigation menu">
                        <svg class="w-6 h-6" x-show="!mobileMenuOpen" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        <svg class="w-6 h-6" x-show="mobileMenuOpen" style="display:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile / Tablet Nav Menu Dropdown -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             style="display:none;" 
             class="lg:hidden border-t border-slate-200/80 bg-white/95 backdrop-blur-xl px-4 pt-3 pb-6 space-y-1.5 shadow-xl">
            
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold {{ request()->routeIs('home') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-700 hover:bg-slate-50' }}">
                <svg class="w-4 h-4 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span>Beranda</span>
            </a>
            <a href="{{ route('jurusan.list') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold {{ request()->routeIs('jurusan.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-700 hover:bg-slate-50' }}">
                <svg class="w-4 h-4 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <span>Unit Jurusan</span>
            </a>
            <a href="{{ route('produk.list') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold {{ request()->routeIs('produk.list') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-700 hover:bg-slate-50' }}">
                <svg class="w-4 h-4 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span>Katalog Produk & Jasa</span>
            </a>
            <a href="{{ route('about') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold {{ request()->routeIs('about') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-700 hover:bg-slate-50' }}">
                <svg class="w-4 h-4 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Tentang TEFA</span>
            </a>
            <a href="{{ route('contact') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold {{ request()->routeIs('contact') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-700 hover:bg-slate-50' }}">
                <svg class="w-4 h-4 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                <span>Hubungi Kami</span>
            </a>

            <!-- Mobile Auth Section -->
            @auth
                <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
                    <div class="px-3.5 py-2.5 bg-slate-50 rounded-xl flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-800">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-slate-500">{{ Auth::user()->email }}</p>
                        </div>
                        <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full {{ $isStaff ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-200 text-slate-700' }}">
                            {{ $isStaff ? 'Staff Internal' : 'Mitra' }}
                        </span>
                    </div>

                    @if($isStaff)
                        <a href="{{ route('dashboard') }}" class="w-full flex items-center justify-center gap-2 py-2.5 bg-indigo-600 text-white font-bold rounded-xl text-sm shadow-sm hover:bg-indigo-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            <span>Buka Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('order.my_orders') }}" class="w-full text-center py-2.5 bg-indigo-50 text-indigo-600 font-bold rounded-xl text-sm border border-indigo-100 hover:bg-indigo-100 transition">
                            Pesanan Saya
                        </a>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="w-full text-center py-2.5 bg-slate-100 text-slate-700 font-bold rounded-xl text-sm hover:bg-slate-200 transition">
                        Pengaturan Profil
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="w-full text-center py-2.5 bg-red-50 text-red-600 font-bold rounded-xl text-sm border border-red-100 hover:bg-red-100 transition">
                            Keluar / Log Out
                        </button>
                    </form>
                </div>
            @else
                <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
                    <a href="{{ route('login') }}" class="w-full text-center py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-xl text-sm border border-slate-200 transition">
                        Masuk ke Akun
                    </a>
                    <a href="{{ route('register') }}" class="w-full text-center py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm shadow-md shadow-indigo-600/20 transition">
                        Daftar Sebagai Mitra
                    </a>
                </div>
            @endauth
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
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-500 flex items-center justify-center text-white shadow-lg shadow-indigo-500/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <span class="text-2xl font-black text-white tracking-tight">Tefa<span class="text-indigo-400">Hub</span></span>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-md">
                        TefaHub adalah platform Teaching Factory yang menghubungkan pesanan riil dari industri dengan siswa SMK. Riwayat chat dengan klien bisa dibantu AI untuk dirangkum jadi draf tugas, dan setiap pengerjaan tetap diawasi guru pembimbing.
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 text-xs text-slate-300">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            AI Assistant Online
                        </div>
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 text-xs text-slate-300">
                            <svg class="w-3.5 h-3.5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Standar Industri Terverifikasi
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

                <!-- Unit Layanan (dinamis dari data Unit TEFA aktif di database) -->
                @php
                    $footerUnits = \App\Models\TefaUnit::where('is_active', true)
                        ->orWhereNull('is_active')
                        ->orderBy('name')
                        ->get(['name', 'slug']);

                    $unitDisplayNames = [
                        'TEFA PPLG Software House' => 'TEFA PPLG',
                        'TEFA DKV Creative Agency' => 'TEFA DKV',
                        'TEFA TKJ Network Solutions' => 'TEFA TKJ',
                        'TEFA Animation & Creative Studio' => 'TEFA ANIMASI',
                        'TEFA Animation & Creative' => 'TEFA ANIMASI',
                        'TEFA PSPT Broadcasting House' => 'TEFA PSPT',
                        'TEFA PSPT Broadcasting' => 'TEFA PSPT',
                    ];
                @endphp
                <div>
                    <h4 class="text-white font-bold text-sm tracking-wider uppercase mb-5">Unit Produksi</h4>
                    <ul class="space-y-3 text-sm font-medium">
                        @forelse($footerUnits as $unit)
                            @php
                                $unitTitle = $unitDisplayNames[$unit->name] ?? (
                                    str_contains($unit->name, 'PPLG') ? 'TEFA PPLG' : (
                                    str_contains($unit->name, 'DKV') ? 'TEFA DKV' : (
                                    str_contains($unit->name, 'TKJ') ? 'TEFA TKJ' : (
                                    (str_contains(strtolower($unit->name), 'animasi') || str_contains(strtolower($unit->name), 'animation')) ? 'TEFA ANIMASI' : (
                                    str_contains($unit->name, 'PSPT') ? 'TEFA PSPT' : $unit->name
                                )))));
                            @endphp
                            <li>
                                <a href="{{ route('tefa.storefront', $unit->slug) }}" class="hover:text-indigo-400 transition-colors">
                                    {{ $unitTitle }}
                                </a>
                            </li>
                        @empty
                            <li><a href="{{ route('jurusan.list') }}" class="hover:text-indigo-400 transition-colors">Lihat Semua Unit TEFA</a></li>
                        @endforelse
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