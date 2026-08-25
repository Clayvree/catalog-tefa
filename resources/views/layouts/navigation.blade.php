<nav x-data="{ open: false }" class="bg-slate-900 border-b border-slate-800 text-white sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 sm:h-20 items-center">
            
            <!-- Left Brand & Links -->
            <div class="flex items-center gap-3 lg:gap-6">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group flex-shrink-0">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-500 flex items-center justify-center text-white font-black text-base shadow-lg shadow-indigo-600/30">
                        ⚡
                    </div>
                    <span class="text-xl font-black tracking-tight text-white hidden sm:inline">
                        Tefa<span class="text-indigo-400">Hub</span>
                    </span>
                </a>

                @php
                    $role = Auth::user()->role?->value;
                @endphp

                <!-- Navigation Links based on Role -->
                <div class="hidden lg:flex items-center space-x-1">
                    @if($role === 'superadmin')
                        <a href="{{ route('superadmin.dashboard') }}" 
                           class="px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('superadmin.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            📊 Statistik
                        </a>
                        <a href="{{ route('superadmin.projects.index') }}" 
                           class="px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('superadmin.projects.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            ⚡ Riwayat Proyek
                        </a>
                        <a href="{{ route('superadmin.units.index') }}" 
                           class="px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('superadmin.units.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            🏫 Unit TEFA
                        </a>
                        <a href="{{ route('superadmin.admins.index') }}" 
                           class="px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('superadmin.admins.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            👥 Admin Jurusan
                        </a>
                        <a href="{{ route('superadmin.categories.index') }}" 
                           class="px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('superadmin.categories.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            🏷️ Kategori
                        </a>
                    @elseif($role === 'admin_jurusan')
                        <a href="{{ route('admin.dashboard') }}" 
                           class="px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.products.index') }}" 
                           class="px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.products.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            📦 Kelola Produk
                        </a>
                        <a href="{{ route('admin.orders.index') }}" 
                           class="px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            🛒 Pesanan Masuk
                        </a>
                        <a href="{{ route('admin.projects.index') }}" 
                           class="px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.projects.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            ⚡ Proyek
                        </a>
                        <a href="{{ route('admin.projects.import-wa.create') }}" 
                           class="px-3 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1 {{ request()->routeIs('admin.projects.import-wa.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            <span>🤖 Import WA</span>
                        </a>
                        <a href="{{ route('admin.workers.index') }}" 
                           class="px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.workers.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            🎓 Siswa
                        </a>
                    @elseif($role === 'worker')
                        <a href="{{ route('worker.dashboard') }}" 
                           class="px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('worker.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            📊 Statistik & Ringkasan
                        </a>
                        <a href="{{ route('worker.tasks.index') }}" 
                           class="px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('worker.tasks.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            🎯 Daftar Tugas
                        </a>
                        <a href="{{ route('worker.portfolios.index') }}" 
                           class="px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('worker.portfolios.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            🏆 Ajukan Portofolio
                        </a>
                    @endif
                </div>
            </div>

            <!-- Right Actions: Web Publik Link & User Dropdown -->
            <div class="flex items-center gap-3">
                
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

                <button @click="open = !open" class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 lg:hidden focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Drawer Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden lg:hidden border-t border-slate-800 bg-slate-900 px-4 pt-3 pb-6 space-y-1">
        @if($role === 'superadmin')
            <a href="{{ route('superadmin.dashboard') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('superadmin.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">📊 Statistik</a>
            <a href="{{ route('superadmin.projects.index') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('superadmin.projects.*') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">⚡ Riwayat Proyek</a>
            <a href="{{ route('superadmin.units.index') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('superadmin.units.*') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">🏫 Unit TEFA</a>
            <a href="{{ route('superadmin.admins.index') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('superadmin.admins.*') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">👥 Admin Jurusan</a>
            <a href="{{ route('superadmin.categories.index') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('superadmin.categories.*') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">🏷️ Kategori</a>
        @elseif($role === 'admin_jurusan')
            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold text-slate-300">Dashboard</a>
            <a href="{{ route('admin.products.index') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold text-slate-300">📦 Kelola Produk</a>
            <a href="{{ route('admin.orders.index') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold text-slate-300">🛒 Pesanan Masuk</a>
            <a href="{{ route('admin.projects.index') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold text-slate-300">⚡ Proyek</a>
            <a href="{{ route('admin.projects.import-wa.create') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold text-slate-300">🤖 Import WA</a>
            <a href="{{ route('admin.workers.index') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold text-slate-300">🎓 Kelola Siswa</a>
        @elseif($role === 'worker')
            <a href="{{ route('worker.dashboard') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold text-slate-300">📊 Statistik & Ringkasan</a>
            <a href="{{ route('worker.tasks.index') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold text-slate-300">🎯 Daftar Tugas</a>
            <a href="{{ route('worker.portfolios.index') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold text-slate-300">🏆 Ajukan Portofolio</a>
        @endif
        <a href="{{ route('home') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold text-indigo-400">← Kembali ke Web Publik</a>
    </div>
</nav>