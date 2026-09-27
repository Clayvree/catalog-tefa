<nav x-data="{ open: false }" class="bg-slate-900 border-b border-slate-800 text-white sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 sm:h-20 items-center">

            <!-- Left Brand & Links -->
            <div class="flex items-center gap-3 md:gap-6 min-w-0">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group flex-shrink-0">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-500 flex items-center justify-center text-white shadow-lg shadow-indigo-600/30">
                        <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8z"/></svg>
                    </div>
                    <span class="text-xl font-black tracking-tight text-white hidden sm:inline">
                        Tefa<span class="text-indigo-400">Hub</span>
                    </span>
                </a>

                @php
                    $role = Auth::user()?->role?->value ?? null;
                @endphp

                <!-- Navigation Links based on Role (tablet & up) -->
                <div class="hidden md:flex items-center gap-1 overflow-x-auto no-scrollbar min-w-0 flex-1">
                    @if($role === 'superadmin')
                        <a href="{{ route('superadmin.dashboard') }}"
                           class="whitespace-nowrap px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('superadmin.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Statistik
                        </a>
                        <a href="{{ route('superadmin.projects.index') }}"
                           class="whitespace-nowrap px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('superadmin.projects.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Riwayat Proyek
                        </a>
                        <a href="{{ route('superadmin.units.index') }}"
                           class="whitespace-nowrap px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('superadmin.units.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Unit TEFA
                        </a>
                        <a href="{{ route('superadmin.admins.index') }}"
                           class="whitespace-nowrap px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('superadmin.admins.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Admin Jurusan
                        </a>
                        <a href="{{ route('superadmin.categories.index') }}"
                           class="whitespace-nowrap px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('superadmin.categories.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Kategori
                        </a>
                        <a href="{{ route('superadmin.knowledge.index') }}"
                           class="whitespace-nowrap px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('superadmin.knowledge.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Konteks AI
                        </a>
                    @elseif($role === 'admin_jurusan')
                        <a href="{{ route('admin.dashboard') }}"
                           class="whitespace-nowrap px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.products.index') }}"
                           class="whitespace-nowrap px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.products.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Kelola Produk
                        </a>
                        <a href="{{ route('admin.orders.index') }}"
                           class="whitespace-nowrap px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Pesanan Masuk
                        </a>
                        <a href="{{ route('admin.projects.index') }}"
                           class="whitespace-nowrap px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.projects.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Proyek
                        </a>
                        <a href="{{ route('admin.workers.index') }}"
                           class="whitespace-nowrap px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.workers.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Siswa
                        </a>
                        <a href="{{ route('admin.portfolios.index') }}"
                           class="whitespace-nowrap px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.portfolios.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Review Portofolio
                        </a>
                        <a href="{{ route('admin.knowledge.index') }}"
                           class="whitespace-nowrap px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.knowledge.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Konteks AI
                        </a>
                    @elseif($role === 'worker')
                        <a href="{{ route('worker.dashboard') }}"
                           class="whitespace-nowrap px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('worker.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Statistik & Ringkasan
                        </a>
                        <a href="{{ route('worker.tasks.index') }}"
                           class="whitespace-nowrap px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('worker.tasks.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Daftar Tugas
                        </a>
                        <a href="{{ route('worker.portfolios.index') }}"
                           class="whitespace-nowrap px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('worker.portfolios.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Ajukan Portofolio
                        </a>
                    @endif

                    @if(in_array($role, ['superadmin', 'admin_jurusan', 'worker']))
                        <div class="w-px h-6 bg-slate-700 mx-2 flex-shrink-0"></div>
                    @endif

                    <a href="{{ route('order.my_orders') }}"
                        class="whitespace-nowrap inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('order.my_orders') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                        Pesanan Saya
                    </a>
                </div>
            </div>

            <!-- Right Actions: Web Publik Link & User Dropdown -->
            <div class="flex items-center gap-3 flex-shrink-0">

                <a href="{{ route('home') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-bold border border-slate-700 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    Web Publik
                </a>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-2 border border-slate-800 text-xs font-bold rounded-xl text-slate-200 bg-slate-800/80 hover:bg-slate-800 transition focus:outline-none cursor-pointer">
                            <span class="w-6 h-6 rounded-lg bg-indigo-600 flex items-center justify-center text-white text-[10px]">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </span>
                            <span class="max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                            <svg class="fill-current h-3.5 w-3.5 text-slate-400" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 text-[11px] text-slate-500 border-b border-slate-100">
                            Logged in as <strong class="text-slate-800">{{ Auth::user()->email }}</strong>
                        </div>
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Pengaturan Profil') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-600 font-bold">
                                {{ __('Keluar / Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>

                <button @click="open = !open" class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 md:hidden focus:outline-none" aria-label="Buka menu navigasi">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Drawer Menu (phones only, below md) -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden md:hidden border-t border-slate-800 bg-slate-900 px-4 pt-3 pb-6 space-y-1">
        @php
            $navIcon = function (string $name) {
                return match ($name) {
                    'chart' => '<svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M18 17V9M13 17V5M8 17v-3"/></svg>',
                    'history' => '<svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v5h5"/><path d="M3.05 13A9 9 0 1 0 6 5.3L3 8"/><path d="M12 7v5l4 2"/></svg>',
                    'building' => '<svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/><path d="M9 9v.01M9 12v.01M9 15v.01M9 18v.01"/></svg>',
                    'users' => '<svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
                    'tag' => '<svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41 11 3.83A2 2 0 0 0 9.59 3.24L3 3v6.59a2 2 0 0 0 .59 1.41l9.59 9.59a2 2 0 0 0 2.82 0l4.59-4.59a2 2 0 0 0 0-2.83z"/><circle cx="7.5" cy="7.5" r="1.5"/></svg>',
                    'cpu' => '<svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M1 9h3M1 15h3M20 9h3M20 15h3"/></svg>',
                    'box' => '<svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21 8-9-5-9 5 9 5 9-5Z"/><path d="M3 8v8l9 5 9-5V8"/><path d="M12 13v8"/></svg>',
                    'cart' => '<svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>',
                    'clipboard' => '<svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/></svg>',
                    'award' => '<svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="m9 13.5-1.5 7L12 18l4.5 2.5-1.5-7"/></svg>',
                    'cap' => '<svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10 12 5 2 10l10 5 10-5Z"/><path d="M6 12v5c0 1.1 2.7 2 6 2s6-.9 6-2v-5"/></svg>',
                    'target' => '<svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1"/></svg>',
                    'settings' => '<svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9c.7.36 1.5.36 2.2 0h.06a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>',
                    'logout' => '<svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>',
                    'back' => '<svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>',
                    default => '',
                };
            };
        @endphp

        @if($role === 'superadmin')
            <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('superadmin.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">{!! $navIcon('chart') !!} Statistik</a>
            <a href="{{ route('superadmin.projects.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('superadmin.projects.*') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">{!! $navIcon('history') !!} Riwayat Proyek</a>
            <a href="{{ route('superadmin.units.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('superadmin.units.*') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">{!! $navIcon('building') !!} Unit TEFA</a>
            <a href="{{ route('superadmin.admins.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('superadmin.admins.*') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">{!! $navIcon('users') !!} Admin Jurusan</a>
            <a href="{{ route('superadmin.categories.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('superadmin.categories.*') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">{!! $navIcon('tag') !!} Kategori</a>
            <a href="{{ route('superadmin.knowledge.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('superadmin.knowledge.*') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">{!! $navIcon('cpu') !!} Konteks AI</a>
        @elseif($role === 'admin_jurusan')
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">{!! $navIcon('chart') !!} Dashboard</a>
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('admin.products.*') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">{!! $navIcon('box') !!} Kelola Produk</a>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">{!! $navIcon('cart') !!} Pesanan Masuk</a>
            <a href="{{ route('admin.projects.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('admin.projects.*') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">{!! $navIcon('clipboard') !!} Proyek</a>
            <a href="{{ route('admin.workers.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('admin.workers.*') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">{!! $navIcon('cap') !!} Kelola Siswa</a>
            <a href="{{ route('admin.portfolios.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('admin.portfolios.*') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">{!! $navIcon('award') !!} Review Portofolio</a>
            <a href="{{ route('admin.knowledge.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('admin.knowledge.*') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">{!! $navIcon('cpu') !!} Konteks AI</a>
        @elseif($role === 'worker')
            <a href="{{ route('worker.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('worker.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">{!! $navIcon('chart') !!} Statistik & Ringkasan</a>
            <a href="{{ route('worker.tasks.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('worker.tasks.*') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">{!! $navIcon('target') !!} Daftar Tugas</a>
            <a href="{{ route('worker.portfolios.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('worker.portfolios.*') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">{!! $navIcon('award') !!} Ajukan Portofolio</a>
        @endif
        <div class="h-px w-full bg-slate-800 my-2"></div>
        <a href="{{ route('order.my_orders') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('order.my_orders') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">{!! $navIcon('cart') !!} Pesanan Saya</a>
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('profile.edit') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">{!! $navIcon('settings') !!} Pengaturan Profil</a>
        <form method="POST" action="{{ route('logout') }}" class="block m-0">
            @csrf
            <button type="submit" class="w-full flex items-center gap-2.5 text-left px-3 py-2.5 rounded-xl text-xs font-bold text-red-400 hover:text-red-300">
                {!! $navIcon('logout') !!} Keluar / Log Out
            </button>
        </form>
        <div class="h-px w-full bg-slate-800 my-2"></div>
        <a href="{{ route('home') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold text-indigo-400">{!! $navIcon('back') !!} Kembali ke Web Publik</a>
    </div>
</nav>