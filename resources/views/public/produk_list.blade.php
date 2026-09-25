<x-public-layout>
    <div class="bg-slate-50 min-h-screen py-4 sm:py-8" x-data="{ mobileFilterOpen: false }">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="mb-4 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-bold">
                    {{ session('error') }}
                </div>
            @endif
            
            <!-- Breadcrumb (Desktop) -->
            <nav class="hidden sm:flex items-center gap-2 text-xs font-semibold text-slate-500 mb-4">
                <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Beranda</a>
                <span>/</span>
                <span class="text-slate-900 font-bold">Katalog Produk & Layanan</span>
            </nav>

            <!-- Search & Mobile Bar -->
            <div class="bg-white rounded-2xl sm:rounded-3xl p-3 sm:p-6 border border-slate-200/80 shadow-sm mb-4 sm:mb-8">
                
                <form action="{{ route('produk.list') }}" method="GET" class="flex items-center gap-2">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if(request('type'))
                        <input type="hidden" name="type" value="{{ request('type') }}">
                    @endif
                    
                    <!-- Search Input -->
                    <div class="relative flex-1">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk, website, desain, servis..." 
                               class="w-full pl-9 sm:pl-10 pr-3 py-2.5 sm:py-3 bg-slate-50 border border-slate-200 focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/10 rounded-xl sm:rounded-2xl text-xs sm:text-sm font-medium transition">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3 sm:top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>

                    <!-- Search Button -->
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl font-bold text-xs sm:text-sm shadow-sm transition flex-shrink-0">
                        Cari
                    </button>

                    <!-- Mobile Filter Drawer Trigger Button -->
                    <button type="button" @click="mobileFilterOpen = true" class="lg:hidden p-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100 flex items-center gap-1.5 text-xs font-bold flex-shrink-0">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        <span>Filter</span>
                        @if(request('category') || request('type'))
                            <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                        @endif
                    </button>
                </form>

                <!-- Quick Horizontal Categories (Shopee / Gojek Style Pills) -->
                <div class="mt-3 pt-3 border-t border-slate-100 flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
                    <a href="{{ route('produk.list', array_merge(request()->except('category'))) }}" 
                       class="px-3.5 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition-all flex-shrink-0 {{ !request('category') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/30' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Semua Kategori
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('produk.list', array_merge(request()->all(), ['category' => $category->id])) }}" 
                           class="px-3.5 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition-all flex-shrink-0 {{ request('category') == $category->id ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/30' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>

                <!-- Active Filter Tags Display (if any) -->
                @if(request('q') || request('category') || request('type'))
                    <div class="flex flex-wrap items-center gap-1.5 pt-3 mt-3 border-t border-slate-100 text-xs">
                        <span class="font-bold text-slate-400 text-[11px]">Aktif:</span>
                        
                        @if(request('q'))
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg bg-indigo-50 text-indigo-700 font-medium text-[11px]">
                                "{{ request('q') }}"
                                <a href="{{ route('produk.list', array_merge(request()->except('q'))) }}" class="text-indigo-400 hover:text-indigo-700 font-bold ml-1">×</a>
                            </span>
                        @endif

                        @if(request('type'))
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg bg-indigo-50 text-indigo-700 font-medium text-[11px]">
                                Tipe: {{ ucfirst(request('type')) }}
                                <a href="{{ route('produk.list', array_merge(request()->except('type'))) }}" class="text-indigo-400 hover:text-indigo-700 font-bold ml-1">×</a>
                            </span>
                        @endif

                        <a href="{{ route('produk.list') }}" class="text-[11px] text-red-500 font-bold hover:underline ml-1">
                            Reset
                        </a>
                    </div>
                @endif

            </div>

            <!-- Content Area: Desktop Sidebar + Product Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
                
                <!-- Left Sidebar Filters (Desktop Only) -->
                <div class="hidden lg:block lg:col-span-3">
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm sticky top-28 space-y-6">
                        
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <h3 class="font-black text-xs text-slate-900 uppercase tracking-wider">Filter Lanjutan</h3>
                            @if(request()->hasAny(['category', 'type', 'q']))
                                <a href="{{ route('produk.list') }}" class="text-xs font-bold text-indigo-600 hover:underline">Reset</a>
                            @endif
                        </div>

                        <!-- Tipe Penawaran -->
                        <div class="space-y-2">
    <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Tipe Layanan</h4>
    <div class="space-y-1">
        <a href="{{ route('produk.list', array_merge(request()->except('type'))) }}"
           class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold transition {{ !request('type') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-600 hover:bg-slate-50' }}">
            <span>Semua Tipe</span>
        </a>
        <a href="{{ route('produk.list', array_merge(request()->all(), ['type' => 'jasa'])) }}"
           class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold transition {{ request('type') === 'jasa' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-600 hover:bg-slate-50' }}">
            <span>🛠️ Layanan Jasa</span>
        </a>
        <a href="{{ route('produk.list', array_merge(request()->all(), ['type' => 'produk'])) }}"
           class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold transition {{ request('type') === 'produk' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-600 hover:bg-slate-50' }}">
            <span>📦 Produk Fisik</span>
        </a>
        <a href="{{ route('produk.list', array_merge(request()->all(), ['type' => 'digital'])) }}"
           class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold transition {{ request('type') === 'digital' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-600 hover:bg-slate-50' }}">
            <span>💻 Produk Digital</span>
        </a>
    </div>
</div>

                        <!-- Kategori List -->
                        <div class="space-y-2 pt-4 border-t border-slate-100">
                            <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Kategori Lengkap</h4>
                            <div class="space-y-1">
                                @foreach($categories as $category)
                                    <a href="{{ route('produk.list', array_merge(request()->all(), ['category' => $category->id])) }}" 
                                       class="flex items-center justify-between px-3 py-1.5 rounded-xl text-xs font-bold transition {{ request('category') == $category->id ? 'text-indigo-600 bg-indigo-50' : 'text-slate-600 hover:bg-slate-50' }}">
                                        <span class="truncate">{{ $category->name }}</span>
                                        <span class="text-[10px] px-1.5 py-0.5 rounded-full {{ request('category') == $category->id ? 'bg-indigo-200/60 text-indigo-800' : 'bg-slate-100 text-slate-400' }}">
                                            {{ $category->catalog_items_count }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Consultation Badge -->
                        <div class="p-3.5 rounded-2xl bg-indigo-50/70 border border-indigo-100 text-xs text-indigo-900 space-y-1">
                            <p class="font-bold text-indigo-800 text-[11px]">✦  Konsultasi via AI</p>
                            <p class="text-[10px] text-indigo-700/80 leading-relaxed">
                                Klik tombol bot di pojok kanan bawah untuk tanya spek & estimasi pengerjaan.
                            </p>
                        </div>

                    </div>
                </div>

                <!-- Products Grid Column -->
                <div class="lg:col-span-9 space-y-4">
                    
                    <!-- Result Count & Sort Bar -->
                    <div class="flex items-center justify-between text-xs text-slate-500 px-1">
                        <div>
                            Ditemukan <strong class="text-slate-900">{{ $items->total() }}</strong> hasil
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="hidden sm:inline">Urutkan:</span>
                            <a href="{{ route('produk.list', array_merge(request()->all(), ['sort' => 'latest'])) }}" 
                               class="px-2.5 py-1 rounded-lg font-bold text-[11px] {{ request('sort', 'latest') === 'latest' ? 'bg-slate-900 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">Terbaru</a>
                            <a href="{{ route('produk.list', array_merge(request()->all(), ['sort' => 'price_asc'])) }}" 
                               class="px-2.5 py-1 rounded-lg font-bold text-[11px] {{ request('sort') === 'price_asc' ? 'bg-slate-900 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">Termurah</a>
                        </div>
                    </div>

                    <!-- Products Grid (2 cols on Mobile / Android, 3 cols on Desktop) -->
                    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-3 gap-2.5 sm:gap-4 lg:gap-6">
                        @forelse($items as $item)
                            <div class="group bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-xl hover:border-indigo-300 hover:-translate-y-1 transition-all duration-300 flex flex-col">
                                
                                <!-- Product Photo (Square 1:1 Aspect Ratio) -->
                                <div class="aspect-square bg-slate-100 relative overflow-hidden">
                                    @if($item->thumbnail_url)
                                        <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300 text-xs">No Image</div>
                                    @endif

                                    <!-- Type Pill Tag -->
                                    <div class="absolute top-2 left-2">
                                        <span class="bg-slate-900/90 text-white backdrop-blur-md px-2 py-0.5 rounded-md text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider">
                                            {{ $item->item_type->label() }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Card Details -->
                                <div class="p-3 sm:p-5 flex-1 flex flex-col justify-between space-y-2 sm:space-y-3">
                                    
                                    <div class="space-y-1">
                                        <!-- Unit / Store Name -->
                                        <div class="flex items-center gap-1 text-[10px] sm:text-xs text-slate-400 truncate">
                                            <svg class="w-3 h-3 text-indigo-500 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 010-5 2.5 2.5 0 010 5z"></path></svg>
                                            <span class="truncate font-medium">{{ $item->tefaUnit->name }}</span>
                                        </div>

                                        <!-- Title -->
                                        <h3 class="font-extrabold text-xs sm:text-sm text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-2 leading-snug" title="{{ $item->title }}">
                                            {{ $item->title }}
                                        </h3>
                                    </div>

                                    <!-- Price & CTA -->
                                    <div class="pt-2 sm:pt-3 border-t border-slate-100 flex items-center justify-between mt-auto">
                                        <div>
                                            <span class="block text-[8px] sm:text-[9px] font-bold text-slate-400 uppercase">Mulai</span>
                                            <span class="text-xs sm:text-base font-black text-indigo-600">
                                                Rp{{ number_format((float)$item->price, 0, ',', '.') }}
                                            </span>
                                            @if($item->item_type->value === 'jasa')
                                                <span class="block text-[10px] font-bold text-emerald-600">Tersedia untuk konsultasi</span>
                                            @elseif(!$item->track_stock)
                                                <span class="block text-[10px] font-bold text-emerald-600">Stok unlimited</span>
                                            @else
                                                <span class="block text-[10px] font-bold {{ $item->stock > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                                    {{ $item->stock > 0 ? 'Stok: ' . $item->stock : 'Stok habis' }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        @if($item->item_type->value === 'jasa')
                                            <a href="{{ route('jasa.nego', $item->slug) }}" 
                                               class="p-1.5 sm:px-3 sm:py-2 rounded-lg sm:rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] sm:text-xs font-bold transition flex items-center gap-1 shadow-sm shadow-emerald-600/20">
                                                <span class="hidden sm:inline">💬 Nego & Konsultasi</span>
                                                <span class="sm:hidden">Nego</span>
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                            </a>
                                        @elseif($item->item_type->value === 'digital' && (!$item->track_stock || $item->stock > 0))
                                            <a href="{{ route('order.checkout', $item->slug) }}" 
                                               class="p-1.5 sm:px-3 sm:py-2 rounded-lg sm:rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-[10px] sm:text-xs font-bold transition flex items-center gap-1 shadow-sm shadow-purple-600/20">
                                                <span class="hidden sm:inline">⚡ Beli Digital</span>
                                                <span class="sm:hidden">Beli</span>
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                            </a>
                                        @elseif(!$item->track_stock || $item->stock > 0)
                                            <a href="{{ route('order.checkout', $item->slug) }}" 
                                               class="p-1.5 sm:px-3 sm:py-2 rounded-lg sm:rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] sm:text-xs font-bold transition flex items-center gap-1 shadow-sm shadow-indigo-600/20">
                                                <span class="hidden sm:inline">🛒 Beli Sekarang</span>
                                                <span class="sm:hidden">Beli</span>
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                            </a>
                                        @elseif($item->item_type->value !== 'jasa')
                                            <span class="px-2 py-1.5 rounded-lg bg-red-50 text-red-600 text-[10px] sm:text-xs font-bold">Habis</span>
                                        @endif
                                    </div>

                                </div>

                            </div>
                        @empty
                            <div class="col-span-full py-16 px-4 bg-white rounded-2xl sm:rounded-3xl border border-dashed border-slate-300 text-center space-y-3">
                                <div class="text-3xl">🔍</div>
                                <h3 class="text-sm font-bold text-slate-800">Tidak ada produk yang cocok</h3>
                                <p class="text-xs text-slate-500">Coba gunakan kata kunci atau kategori lain.</p>
                                <a href="{{ route('produk.list') }}" class="inline-block px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl">Reset Filter</a>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="pt-4 sm:pt-6">
                        {{ $items->links() }}
                    </div>

                </div>

            </div>

        </div>

        <!-- Mobile Filter Drawer (Modal) -->
        <div x-show="mobileFilterOpen" style="display:none;" class="fixed inset-0 z-50 overflow-hidden lg:hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-slate-950/50 backdrop-blur-sm transition-opacity" @click="mobileFilterOpen = false"></div>
            
            <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
                <div class="w-screen max-w-xs bg-white shadow-2xl p-6 space-y-6 flex flex-col justify-between">
                    <div class="space-y-6">
                        
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <h3 class="font-black text-sm text-slate-900 uppercase">Filter Katalog</h3>
                            <button @click="mobileFilterOpen = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">×</button>
                        </div>

                        <!-- Tipe -->
                        <div class="space-y-2">
                            <h4 class="text-xs font-bold text-slate-400 uppercase">Tipe Penawaran</h4>
                            <div class="space-y-1">
                                <a href="{{ route('produk.list', array_merge(request()->except('type'))) }}" class="block px-3 py-2 rounded-xl text-xs font-bold {{ !request('type') ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700' }}">Semua Tipe</a>
                                <a href="{{ route('produk.list', array_merge(request()->all(), ['type' => 'jasa'])) }}" class="block px-3 py-2 rounded-xl text-xs font-bold {{ request('type') === 'jasa' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700' }}">🛠️ Layanan Jasa</a>
                                <a href="{{ route('produk.list', array_merge(request()->all(), ['type' => 'produk'])) }}" class="block px-3 py-2 rounded-xl text-xs font-bold {{ request('type') === 'produk' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700' }}">📦 Produk Fisik</a>
                                <a href="{{ route('produk.list', array_merge(request()->all(), ['type' => 'digital'])) }}" class="block px-3 py-2 rounded-xl text-xs font-bold {{ request('type') === 'digital' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700' }}">💻 Produk Digital</a>
                            </div>
                        </div>

                        <!-- Kategori -->
                        <div class="space-y-2 pt-2 border-t border-slate-100">
                            <h4 class="text-xs font-bold text-slate-400 uppercase">Kategori</h4>
                            <div class="space-y-1 max-h-60 overflow-y-auto">
                                @foreach($categories as $category)
                                    <a href="{{ route('produk.list', array_merge(request()->all(), ['category' => $category->id])) }}" class="block px-3 py-1.5 rounded-xl text-xs font-bold {{ request('category') == $category->id ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600' }}">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                    </div>

                    <div class="pt-4 border-t border-slate-100 space-y-2">
                        <a href="{{ route('produk.list') }}" class="block text-center py-2.5 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl">Reset Filter</a>
                        <button @click="mobileFilterOpen = false" class="w-full py-2.5 bg-indigo-600 text-white text-xs font-bold rounded-xl">Tutup & Tampilkan</button>
                    </div>

                </div>
            </div>
        </div>

    </div>
</x-public-layout>
