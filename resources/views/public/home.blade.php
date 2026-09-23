<x-public-layout>
    
    <!-- 1. HERO SECTION (Compact & Mobile-Optimized) -->
    <section class="relative hero-gradient pt-8 pb-14 sm:pt-16 sm:pb-24 lg:pt-20 lg:pb-28 overflow-hidden">
        
        <!-- Abstract Background Orbs -->
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -left-40 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-8 items-center">
                
                <!-- Left Hero Content -->
                <div class="lg:col-span-7 space-y-6 sm:space-y-8 text-center lg:text-left">
                    
                    <!-- Top Chip -->
                    <div class="inline-flex items-center gap-2 px-3 py-1 sm:px-3.5 sm:py-1.5 rounded-full bg-white border border-indigo-100 shadow-sm text-indigo-700 text-[11px] sm:text-xs font-bold">
                        <span class="flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-600"></span>
                        </span>
                        Ekosistem Teaching Factory Terpadu AI
                    </div>

                    <!-- Headline -->
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-[1.15]">
                        Karya Nyata Siswa, <br class="hidden sm:inline">
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 via-indigo-700 to-blue-600">
                            Standar Kualitas Industri.
                        </span>
                    </h1>

                    <p class="text-sm sm:text-lg text-slate-600 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-normal">
                        Temukan ribuan produk inovatif dan layanan jasa profesional langsung dari unit Teaching Factory. Dikerjakan oleh siswa berprestasi dengan supervisi instruktur ahli.
                    </p>

                    <!-- Hero Search Box -->
                    <div class="max-w-xl mx-auto lg:mx-0 bg-white p-1.5 sm:p-2 rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-200/80">
                        <form action="{{ route('produk.list') }}" method="GET" class="flex items-center gap-1.5 sm:gap-2">
                            <div class="pl-2.5 sm:pl-3 text-slate-400">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="q" placeholder="Cari jasa web, desain logo, catering..." class="w-full border-0 focus:ring-0 text-xs sm:text-sm font-medium text-slate-800 placeholder:text-slate-400">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 sm:px-5 py-2.5 sm:py-3 rounded-xl font-bold text-xs sm:text-sm shadow-md shadow-indigo-600/20 transition-all flex items-center gap-1.5 flex-shrink-0">
                                Cari Layanan
                            </button>
                        </form>
                    </div>

                    <!-- Quick Suggestions -->
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-1.5 sm:gap-2 pt-1 text-[11px] sm:text-xs text-slate-500">
                        <span class="font-bold text-slate-700">Populer:</span>
                        <a href="{{ route('produk.list', ['q' => 'Website']) }}" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-indigo-400 hover:text-indigo-600 transition">Website</a>
                        <a href="{{ route('produk.list', ['q' => 'Logo']) }}" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-indigo-400 hover:text-indigo-600 transition">Desain Logo</a>
                        <a href="{{ route('produk.list', ['q' => 'Video']) }}" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-indigo-400 hover:text-indigo-600 transition">Reels/TikTok</a>
                        <a href="{{ route('produk.list', ['q' => 'Bakery']) }}" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-indigo-400 hover:text-indigo-600 transition">Bakery</a>
                    </div>
                </div>

                <!-- Right Hero Feature Box -->
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md bg-white rounded-3xl p-5 sm:p-6 shadow-2xl shadow-indigo-900/10 border border-slate-100 space-y-4">
                        
                        <div class="p-4 rounded-2xl bg-gradient-to-br from-indigo-50/80 to-blue-50/50 border border-indigo-100/80">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-xs font-bold">💻</span>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-900">Project App POS Kasir</h4>
                                        <p class="text-[10px] text-slate-500">TEFA RPL Software House</p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-extrabold">Active</span>
                            </div>
                            <div class="space-y-1 text-[11px] text-slate-600">
                                <div class="flex justify-between">
                                    <span>Progres Kanban Siswa:</span>
                                    <span class="font-bold text-indigo-600">85% Selesai</span>
                                </div>
                                <div class="w-full bg-indigo-200/50 h-2 rounded-full overflow-hidden">
                                    <div class="bg-indigo-600 h-full rounded-full" style="width: 85%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl bg-white border border-slate-100 shadow-sm space-y-1.5">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-slate-800 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Supervisi Pengajar Terverifikasi
                                </span>
                                <span class="text-[10px] text-emerald-600 font-bold">100% QA Passed</span>
                            </div>
                            <p class="text-[11px] text-slate-500 leading-relaxed">
                                Seluruh pekerjaan siswa didampingi oleh instruktur profesional bersertifikasi BNSP & industri partner.
                            </p>
                        </div>

                        <a href="{{ route('jurusan.list') }}" class="w-full py-3 bg-slate-900 hover:bg-indigo-600 text-white rounded-xl font-bold text-xs flex items-center justify-center gap-2 transition-colors">
                            Jelajahi Semua Unit Jurusan &rarr;
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 2. STATS COUNTER BAR -->
    <section class="bg-white border-y border-slate-200/80 py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-slate-100">
                
                <div class="pt-2 md:pt-0">
                    <p class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight">{{ $stats['total_units'] ?? '4' }}</p>
                    <p class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider mt-0.5">Unit TEFA Unggulan</p>
                </div>

                <div class="pt-2 md:pt-0">
                    <p class="text-2xl sm:text-4xl font-black text-indigo-600 tracking-tight">{{ $stats['total_products'] ?? '25' }}</p>
                    <p class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider mt-0.5">Katalog Produk & Jasa</p>
                </div>

                <div class="pt-2 md:pt-0">
                    <p class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight">100%</p>
                    <p class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider mt-0.5">Didampingi Guru Ahli</p>
                </div>

                <div class="pt-2 md:pt-0">
                    <p class="text-2xl sm:text-4xl font-black text-emerald-600 tracking-tight">99.8%</p>
                    <p class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider mt-0.5">Kepuasan Mitra</p>
                </div>

            </div>
        </div>
    </section>

    <!-- 3. KATEGORI LAYANAN POPULER -->
    <section class="py-12 sm:py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex items-center justify-between gap-4 mb-8 sm:mb-12">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Bidang Keahlian</span>
                    <h2 class="text-xl sm:text-3xl font-black text-slate-900 tracking-tight mt-0.5">Kategori Layanan</h2>
                </div>
                <a href="{{ route('produk.list') }}" class="inline-flex items-center gap-1 text-xs sm:text-sm font-bold text-indigo-600 hover:text-indigo-800 transition">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
                @foreach($categories as $category)
                    <a href="{{ route('produk.list', ['category' => $category->id]) }}" 
                       class="group bg-white p-3.5 sm:p-5 rounded-2xl border border-slate-200/80 hover:border-indigo-500 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center">
                        <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl bg-indigo-50 group-hover:bg-indigo-600 text-indigo-600 group-hover:text-white flex items-center justify-center text-xl sm:text-2xl transition-colors duration-300 mb-2 sm:mb-3 shadow-inner">
                            @if(str_contains(strtolower($category->name), 'web') || str_contains(strtolower($category->name), 'software'))
                                💻
                            @elseif(str_contains(strtolower($category->name), 'desain') || str_contains(strtolower($category->name), 'branding'))
                                🎨
                            @elseif(str_contains(strtolower($category->name), 'video') || str_contains(strtolower($category->name), 'animasi'))
                                🎬
                            @elseif(str_contains(strtolower($category->name), 'otomotif'))
                                🚗
                            @elseif(str_contains(strtolower($category->name), 'kuliner') || str_contains(strtolower($category->name), 'bakery'))
                                🥐
                            @else
                                🏷️
                            @endif
                        </div>
                        <h3 class="font-bold text-xs sm:text-sm text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-1">
                            {{ $category->name }}
                        </h3>
                        <span class="text-[10px] sm:text-xs text-slate-400 mt-0.5 font-medium">{{ $category->catalog_items_count ?? '0' }} Layanan</span>
                    </a>
                @endforeach
            </div>

        </div>
    </section>

    <!-- 4. JURUSAN UNGGULAN -->
    <section class="py-12 sm:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-16 space-y-2">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Pusat Keunggulan</span>
                <h2 class="text-2xl sm:text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">Unit Teaching Factory</h2>
                <p class="text-slate-500 text-xs sm:text-sm leading-relaxed">
                    Pilih jurusan untuk melihat profil resmi dan portofolio karya para siswa.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($units as $unit)
                    <div class="group bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
                        
                        <div class="h-36 sm:h-44 bg-slate-900 relative overflow-hidden">
                            @if($unit->banner_url)
                                <img src="{{ $unit->banner_url }}" alt="{{ $unit->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-80">
                            @else
                                <div class="w-full h-full bg-gradient-to-tr from-indigo-900 to-slate-800"></div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                            
                            <div class="absolute bottom-2.5 left-3 w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-white p-1 shadow-lg flex items-center justify-center overflow-hidden">
                                @if($unit->logo_url)
                                    <img src="{{ $unit->logo_url }}" alt="Logo" class="w-full h-full object-cover rounded-lg sm:rounded-xl">
                                @else
                                    <span class="font-black text-indigo-600 text-base sm:text-lg">{{ substr($unit->name, 0, 1) }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="p-4 sm:p-6 flex-1 flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-center gap-1 text-[10px] text-indigo-600 font-bold mb-1">
                                    <span>Official TEFA</span>
                                    <span>•</span>
                                    <span class="text-slate-400 font-medium">{{ $unit->catalog_items_count ?? '0' }} Item</span>
                                </div>
                                
                                <h3 class="font-black text-sm sm:text-base text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-1">
                                    {{ $unit->name }}
                                </h3>
                                
                                <p class="text-xs text-slate-500 leading-relaxed line-clamp-2 mt-1">
                                    {{ $unit->description }}
                                </p>
                            </div>

                            <a href="{{ route('tefa.storefront', $unit->slug) }}" 
                               class="w-full text-center py-2 px-3 rounded-xl bg-slate-100 hover:bg-indigo-600 text-slate-800 hover:text-white font-bold text-xs transition-colors flex items-center justify-center gap-1 mt-auto">
                                Buka Profil &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- 5. TOP PRODUK & JASA PILIHAN (HANYA 4 PRODUK UNGGULAN) -->
    <section class="py-12 sm:py-20 bg-slate-50 border-t border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex items-center justify-between gap-4 mb-8 sm:mb-12">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Top Rekomendasi</span>
                    <h2 class="text-xl sm:text-3xl font-black text-slate-900 tracking-tight mt-0.5">Produk & Layanan Terlaris</h2>
                </div>
                <a href="{{ route('produk.list') }}" class="inline-flex items-center gap-1 text-xs sm:text-sm font-bold text-indigo-600 hover:text-indigo-800 transition">
                    Buka Semua Katalog &rarr;
                </a>
            </div>

            <!-- 4 Top Products Grid (2 cols Mobile / 4 cols Desktop) -->
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
                @foreach($featuredItems as $item)
                    <div class="group bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-xl hover:border-indigo-300 hover:-translate-y-1 transition-all duration-300 flex flex-col">
                        
                        <!-- Thumbnail -->
                        <div class="aspect-square bg-slate-100 relative overflow-hidden">
                            @if($item->thumbnail_url)
                                <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300 text-xs">No Photo</div>
                            @endif
                            
                            <div class="absolute top-2 left-2">
                                <span class="bg-slate-900/90 text-white backdrop-blur-md px-2 py-0.5 rounded text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider shadow-sm">
                                    {{ $item->item_type->label() }}
                                </span>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="p-3 sm:p-5 flex-1 flex flex-col justify-between space-y-2 sm:space-y-3">
                            
                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-[10px] sm:text-xs text-slate-500">
                                    <span class="font-bold text-indigo-600 truncate">{{ $item->category->name ?? 'Kategori' }}</span>
                                </div>

                                <h3 class="font-extrabold text-xs sm:text-sm text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-2 leading-snug" title="{{ $item->title }}">
                                    {{ $item->title }}
                                </h3>
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

                            <!-- Price & Action -->
                            <div class="flex items-center justify-between pt-2 sm:pt-3 border-t border-slate-100 mt-auto">
                                <div>
                                    <span class="block text-[8px] sm:text-[9px] font-bold text-slate-400 uppercase">Mulai</span>
                                    <span class="text-xs sm:text-base font-black text-indigo-600">
                                        Rp{{ number_format((float)$item->price, 0, ',', '.') }}
                                    </span>
                                </div>
                                
                                @if($item->item_type->value === 'jasa')
                                    <a href="{{ route('jasa.nego', $item->slug) }}" class="p-1.5 sm:px-3 sm:py-1.5 rounded-lg sm:rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] sm:text-xs font-bold transition flex items-center gap-1 shadow-sm shadow-emerald-600/20">
                                        <span>Nego</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </a>
                                @elseif($item->item_type->value === 'digital' && (!$item->track_stock || $item->stock > 0))
                                    <a href="{{ route('order.checkout', $item->slug) }}" class="p-1.5 sm:px-3 sm:py-1.5 rounded-lg sm:rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-[10px] sm:text-xs font-bold transition flex items-center gap-1 shadow-sm shadow-purple-600/20">
                                        <span>Beli</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </a>
                                @elseif(!$item->track_stock || $item->stock > 0)
                                    <a href="{{ route('order.checkout', $item->slug) }}" class="p-1.5 sm:px-3 sm:py-1.5 rounded-lg sm:rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] sm:text-xs font-bold transition flex items-center gap-1 shadow-sm shadow-indigo-600/20">
                                        <span>Pesan</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </a>
                                @elseif($item->item_type->value !== 'jasa')
                                    <span class="px-2 py-1.5 rounded-lg bg-red-50 text-red-600 text-[10px] sm:text-xs font-bold">Habis</span>
                                @endif
                            </div>

                        </div>

                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- 6. CARA KERJA ALUR AI -->
    <section class="py-12 sm:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-16 space-y-2">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Teknologi Terkini</span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Alur Pengerjaan Terpadu AI</h2>
                <p class="text-slate-500 text-xs sm:text-sm">
                    Automasi cerdas dari diskusi chat WhatsApp hingga pengerjaan siswa terampil.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                
                <div class="bg-slate-50 p-5 rounded-2xl sm:rounded-3xl border border-slate-200/80 space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white font-black text-base flex items-center justify-center">1</div>
                    <h3 class="font-extrabold text-sm text-slate-900">Chat WhatsApp / AI</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Diskusikan kebutuhan proyek Anda dengan Admin TEFA atau via AI bot.</p>
                </div>

                <div class="bg-slate-50 p-5 rounded-2xl sm:rounded-3xl border border-slate-200/80 space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white font-black text-base flex items-center justify-center">2</div>
                    <h3 class="font-extrabold text-sm text-slate-900">AI Task Breakdown</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Gemini AI mengekstrak kesepakatan chat dan memecahnya ke tugas siswa.</p>
                </div>

                <div class="bg-slate-50 p-5 rounded-2xl sm:rounded-3xl border border-slate-200/80 space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white font-black text-base flex items-center justify-center">3</div>
                    <h3 class="font-extrabold text-sm text-slate-900">Pengerjaan Siswa</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Tugas masuk ke Kanban siswa berbakat dengan supervisi guru ahli.</p>
                </div>

                <div class="bg-slate-50 p-5 rounded-2xl sm:rounded-3xl border border-slate-200/80 space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white font-black text-base flex items-center justify-center">✓</div>
                    <h3 class="font-extrabold text-sm text-slate-900">Validasi & Pengiriman</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Hasil kerja lolos uji mutu standar industri dan diserahkan ke klien.</p>
                </div>

            </div>

        </div>
    </section>

    <!-- 7. PORTFOLIO SHOWCASE -->
    <section class="py-12 sm:py-20 bg-slate-900 text-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="flex items-center justify-between gap-4 mb-8 sm:mb-12">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-400">Bukti Nyata</span>
                    <h2 class="text-xl sm:text-3xl font-black text-white tracking-tight mt-0.5">Portofolio Siswa</h2>
                </div>
                <a href="{{ route('jurusan.list') }}" class="text-xs sm:text-sm font-bold text-indigo-300 hover:text-white transition">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                @foreach($featuredPortfolios as $portfolio)
                    <div class="group bg-slate-800/80 rounded-2xl sm:rounded-3xl border border-slate-700/80 overflow-hidden hover:border-indigo-500 transition-all duration-300 flex flex-col sm:flex-row">
                        
                        <div class="sm:w-1/2 aspect-video sm:aspect-auto bg-slate-950 relative overflow-hidden">
                            @if($portfolio->thumbnail_url)
                                <img src="{{ $portfolio->thumbnail_url }}" alt="{{ $portfolio->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-600">No Image</div>
                            @endif
                        </div>

                        <div class="p-4 sm:p-6 sm:w-1/2 flex flex-col justify-between space-y-3">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-400 block mb-1">
                                    {{ $portfolio->tefaUnit->name ?? 'Unit TEFA' }}
                                </span>
                                <h3 class="font-extrabold text-sm sm:text-base text-white group-hover:text-indigo-300 transition-colors line-clamp-2">
                                    {{ $portfolio->title }}
                                </h3>
                                <p class="text-xs text-slate-400 mt-1 line-clamp-2 leading-relaxed">
                                    {{ $portfolio->description }}
                                </p>
                            </div>

                            <div class="pt-3 border-t border-slate-700/60 flex items-center justify-between text-xs text-slate-300">
                                <span class="flex items-center gap-1 font-medium text-[11px] text-emerald-400">
                                    ✓ Terverifikasi
                                </span>
                                <span class="text-slate-400 text-[10px]">{{ $portfolio->client_name ?? 'Mitra Industri' }}</span>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- 8. CTA SECTION -->
    <section class="py-12 sm:py-20 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-tr from-indigo-700 via-indigo-600 to-blue-600 rounded-3xl p-8 sm:p-14 text-center text-white shadow-2xl shadow-indigo-600/30">
                <div class="space-y-4 max-w-2xl mx-auto">
                    <h2 class="text-2xl sm:text-3xl font-black tracking-tight">Siap Bermitra dengan Siswa Vokasi?</h2>
                    <p class="text-indigo-100 text-xs sm:text-sm leading-relaxed">
                        Pesan layanan atau diskusikan kebutuhan proyek Anda dengan unit TEFA sekarang juga.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('produk.list') }}" class="inline-block px-6 py-3 bg-white text-indigo-700 hover:bg-indigo-50 rounded-xl font-bold text-xs sm:text-sm shadow-lg transition">
                            Lihat Semua Katalog &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-public-layout>
