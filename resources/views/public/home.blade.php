<x-public-layout>
    
    <!-- 1. HERO SECTION (Compact & Mobile-Optimized) -->
    <section class="relative hero-gradient pt-8 pb-14 sm:pt-16 sm:pb-24 lg:pt-20 lg:pb-28 overflow-hidden">
        
        <!-- Abstract Background Orbs -->
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -left-40 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-4xl mx-auto space-y-6 sm:space-y-8 text-center flex flex-col items-center">
                
                <!-- Top Chip -->
                <div class="inline-flex items-center gap-2 px-3 py-1 sm:px-3.5 sm:py-1.5 rounded-full bg-white border border-indigo-100 shadow-sm text-indigo-700 text-[11px] sm:text-xs font-bold">
                    <svg class="w-3 h-3 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8z"/></svg>
                    Teaching Factory
                </div>

                <!-- Headline -->
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-[1.15]">
                    Karya Nyata Siswa, <br class="hidden sm:inline">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 via-indigo-700 to-blue-600">
                        Standar Kualitas Industri.
                    </span>
                </h1>

                <p class="text-sm sm:text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed font-normal">
                    Temukan beragam produk inovatif dan layanan jasa profesional langsung dari unit Teaching Factory. Dikerjakan oleh siswa berprestasi dengan supervisi instruktur ahli.
                </p>

                <!-- Hero Search Box -->
                <div class="max-w-xl w-full mx-auto bg-white p-1.5 sm:p-2 rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-200/80">
                    <form action="{{ route('produk.list') }}" method="GET" class="flex items-center gap-1.5 sm:gap-2">
                        <div class="pl-2.5 sm:pl-3 text-slate-400">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="q" placeholder="Cari jasa web, desain logo, produksi video..." class="w-full border-0 focus:ring-0 text-xs sm:text-sm font-medium text-slate-800 placeholder:text-slate-400">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 sm:px-5 py-2.5 sm:py-3 rounded-xl font-bold text-xs sm:text-sm shadow-md shadow-indigo-600/20 transition-all flex items-center gap-1.5 flex-shrink-0">
                            Cari Layanan
                        </button>
                    </form>
                </div>

                <!-- Quick Suggestions -->
                <div class="flex flex-wrap items-center justify-center gap-1.5 sm:gap-2 pt-1 text-[11px] sm:text-xs text-slate-500">
                    <span class="font-bold text-slate-700">Populer:</span>
                    <a href="{{ route('produk.list', ['q' => 'Website']) }}" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-indigo-400 hover:text-indigo-600 transition">Website</a>
                    <a href="{{ route('produk.list', ['q' => 'Logo']) }}" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-indigo-400 hover:text-indigo-600 transition">Desain Logo</a>
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
                    <p class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight">{{ $stats['total_portfolios'] ?? 0 }}</p>
                    <p class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider mt-0.5">Karya Siswa Tervalidasi</p>
                </div>

                <div class="pt-2 md:pt-0">
                    <p class="text-2xl sm:text-4xl font-black text-emerald-600 tracking-tight">{{ $stats['total_workers'] ?? 0 }}</p>
                    <p class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider mt-0.5">Siswa Talenta Aktif</p>
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
            </div>

            @php
                // Peta ikon per kategori sesuai dengan data di database
                $categoryIcons = [
                    'default'     => '<path d="M20.59 13.41 11 3.83A2 2 0 0 0 9.59 3.24L3 3v6.59a2 2 0 0 0 .59 1.41l9.59 9.59a2 2 0 0 0 2.82 0l4.59-4.59a2 2 0 0 0 0-2.83z"/><circle cx="7.5" cy="7.5" r="1.5"/>',
                    'aplikasi'    => '<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>',
                    'website'     => '<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>',
                    'software'    => '<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>',
                    'game'        => '<rect x="2" y="6" width="20" height="12" rx="6"/><path d="M6 12h4M8 10v4M15.5 11.5h.01M17.5 13.5h.01"/>',
                    'vr'          => '<rect x="2" y="6" width="20" height="12" rx="6"/><path d="M6 12h4M8 10v4M15.5 11.5h.01M17.5 13.5h.01"/>',
                    'otomasi'     => '<rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M1 9h3M1 15h3M20 9h3M20 15h3"/>',
                    'iot'         => '<rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M1 9h3M1 15h3M20 9h3M20 15h3"/>',
                    'logistik'    => '<rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>',
                    'desain'      => '<circle cx="13.5" cy="6.5" r=".5"/><circle cx="17.5" cy="10.5" r=".5"/><circle cx="8.5" cy="7.5" r=".5"/><circle cx="6.5" cy="12.5" r=".5"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.9 0 1.5-.7 1.5-1.5 0-.4-.2-.8-.4-1.1-.2-.3-.4-.6-.4-1 0-.8.7-1.5 1.5-1.5H16c3.3 0 6-2.7 6-6 0-4.6-4.5-8.4-10-8.4z"/>',
                    'grafis'      => '<circle cx="13.5" cy="6.5" r=".5"/><circle cx="17.5" cy="10.5" r=".5"/><circle cx="8.5" cy="7.5" r=".5"/><circle cx="6.5" cy="12.5" r=".5"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.9 0 1.5-.7 1.5-1.5 0-.4-.2-.8-.4-1.1-.2-.3-.4-.6-.4-1 0-.8.7-1.5 1.5-1.5H16c3.3 0 6-2.7 6-6 0-4.6-4.5-8.4-10-8.4z"/>',
                    'cetak'       => '<polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>',
                    'percetakan'  => '<polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>',
                    'merchandise' => '<path d="m21 8-9-5-9 5 9 5 9-5Z"/><path d="M3 8v8l9 5 9-5V8"/><path d="M12 13v8"/>',
                    'instalasi'   => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
                    'jaringan'    => '<circle cx="12" cy="5" r="2"/><circle cx="5" cy="19" r="2"/><circle cx="19" cy="19" r="2"/><path d="M12 7v4m0 0-5.5 6M12 11l5.5 6"/>',
                    'servis'      => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
                    'maintenance' => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
                    'aset'        => '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="M3.27 6.96 12 12l8.73-5.04M12 22.08V12"/>',
                    'pemodelan'   => '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="M3.27 6.96 12 12l8.73-5.04M12 22.08V12"/>',
                    '3d'          => '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="M3.27 6.96 12 12l8.73-5.04M12 22.08V12"/>',
                    'video'       => '<rect x="2" y="5" width="15" height="14" rx="2"/><path d="m17 10 5-3v10l-5-3"/>',
                    'animasi'     => '<rect x="2" y="5" width="15" height="14" rx="2"/><path d="m17 10 5-3v10l-5-3"/>',
                    'produksi'    => '<rect x="2" y="5" width="15" height="14" rx="2"/><path d="m17 10 5-3v10l-5-3"/>',
                    'penyiaran'   => '<path d="M4.93 4.93A10 10 0 0 0 2 12a10 10 0 0 0 2.93 7.07M19.07 4.93A10 10 0 0 1 22 12a10 10 0 0 1-2.93 7.07M7.76 7.76A6 6 0 0 0 6 12a6 6 0 0 0 1.76 4.24M16.24 7.76A6 6 0 0 1 18 12a6 6 0 0 1-1.76 4.24M12 10a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>',
                ];

                $pickIcon = function (string $name) use ($categoryIcons) {
                    $name = strtolower($name);
                    foreach ($categoryIcons as $keyword => $svgPaths) {
                        if ($keyword !== 'default' && str_contains($name, $keyword)) {
                            return $svgPaths;
                        }
                    }
                    return $categoryIcons['default'];
                };
            @endphp

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
                @forelse($categories as $category)
                    <a href="{{ route('produk.list', ['category' => $category->id]) }}" 
                       class="group bg-white p-3.5 sm:p-5 rounded-2xl border border-slate-200/80 hover:border-indigo-500 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center">
                        <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl bg-indigo-50 group-hover:bg-indigo-600 text-indigo-600 group-hover:text-white flex items-center justify-center transition-colors duration-300 mb-2 sm:mb-3 overflow-hidden">
                            @if(!empty($category->image))
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover rounded-xl sm:rounded-2xl">
                            @else
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    {!! $pickIcon($category->name) !!}
                                </svg>
                            @endif
                        </div>
                        <h3 class="font-bold text-xs sm:text-sm text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-1">
                            {{ $category->name }}
                        </h3>
                        <span class="text-[10px] sm:text-xs text-slate-400 mt-0.5 font-medium">{{ $category->catalog_items_count ?? '0' }} Layanan</span>
                    </a>
                @empty
                    <div class="col-span-full py-8 text-center text-slate-400 text-xs font-semibold bg-white rounded-2xl border border-dashed border-slate-200">
                        Belum ada kategori layanan yang tersedia.
                    </div>
                @endforelse

                <!-- Card Lihat Semua Kategori (di samping card terakhir) -->
                <a href="{{ route('produk.list') }}" 
                   class="group bg-gradient-to-br from-indigo-50/70 via-white to-slate-50 p-3.5 sm:p-5 rounded-2xl border border-dashed border-indigo-200 hover:border-indigo-500 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col items-center justify-center text-center">
                    <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl bg-indigo-600 text-white flex items-center justify-center shadow-md shadow-indigo-600/20 group-hover:scale-105 transition-transform duration-300 mb-2 sm:mb-3">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-xs sm:text-sm text-slate-900 group-hover:text-indigo-600 transition-colors">
                        Lihat Semua
                    </h3>
                    <span class="text-[10px] sm:text-xs text-indigo-600 mt-0.5 font-bold flex items-center justify-center gap-0.5">
                        Semua Kategori &rarr;
                    </span>
                </a>
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

            @php
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

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($units as $unit)
                    @php
                        $unitTitle = $unitDisplayNames[$unit->name] ?? (
                            str_contains($unit->name, 'PPLG') ? 'TEFA PPLG' : (
                            str_contains($unit->name, 'DKV') ? 'TEFA DKV' : (
                            str_contains($unit->name, 'TKJ') ? 'TEFA TKJ' : (
                            (str_contains(strtolower($unit->name), 'animasi') || str_contains(strtolower($unit->name), 'animation')) ? 'TEFA ANIMASI' : (
                            str_contains($unit->name, 'PSPT') ? 'TEFA PSPT' : $unit->name
                        )))));
                    @endphp
                    <div class="group bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
                        
                        <div class="h-36 sm:h-44 bg-slate-900 relative overflow-hidden">
                            @if($unit->banner_url)
                                <img src="{{ $unit->banner_url }}" alt="{{ $unitTitle }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-80">
                            @else
                                <div class="w-full h-full bg-gradient-to-tr from-indigo-900 to-slate-800"></div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                            
                            <div class="absolute bottom-2.5 left-3 w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-white p-1 shadow-lg flex items-center justify-center overflow-hidden">
                                @if($unit->logo_url)
                                    <img src="{{ $unit->logo_url }}" alt="Logo" class="w-full h-full object-cover rounded-lg sm:rounded-xl">
                                @else
                                    <span class="font-black text-indigo-600 text-base sm:text-lg">{{ substr($unitTitle, 0, 1) }}</span>
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
                                    {{ $unitTitle }}
                                </h3>
                                
                                <p class="text-xs text-slate-500 leading-relaxed line-clamp-2 mt-1">
                                    {{ $unit->description }}
                                </p>
                            </div>

                            <a href="{{ route('tefa.storefront', $unit->slug) }}" 
                               class="w-full text-center py-2 px-3 rounded-xl bg-slate-900 hover:bg-indigo-600 text-white font-bold text-xs shadow-sm transition-colors flex items-center justify-center gap-1 mt-auto">
                                Buka Profil &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- 5. TOP PRODUK & JASA PILIHAN (SLIDER 3 TERLARIS + 1 CARD LIHAT SEMUA) -->
    <section class="py-12 sm:py-20 bg-slate-50 border-t border-slate-200/80" x-data="{
        scrollSlider(direction) {
            const el = this.$refs.slider;
            if (!el) return;
            const card = el.querySelector('.slider-card');
            const step = card ? card.offsetWidth + 24 : 320;
            el.scrollBy({ left: direction * step, behavior: 'smooth' });
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex items-center justify-between gap-4 mb-8 sm:mb-12">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Top Rekomendasi</span>
                    <h2 class="text-xl sm:text-3xl font-black text-slate-900 tracking-tight mt-0.5">Produk & Layanan Terlaris</h2>
                </div>
                
                <!-- Slide Controls -->
                <div class="flex items-center gap-2">
                    <button type="button" @click="scrollSlider(-1)" aria-label="Geser ke kiri" class="w-10 h-10 rounded-xl bg-white border border-slate-200 hover:border-indigo-400 text-slate-700 hover:text-indigo-600 shadow-sm flex items-center justify-center transition active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button type="button" @click="scrollSlider(1)" aria-label="Geser ke kanan" class="w-10 h-10 rounded-xl bg-white border border-slate-200 hover:border-indigo-400 text-slate-700 hover:text-indigo-600 shadow-sm flex items-center justify-center transition active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            <!-- Slideable Cards Container (3 Top Products + 1 'Lihat Semua Layanan' Card) -->
            <div x-ref="slider" class="flex gap-4 sm:gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory no-scrollbar pb-4 pt-1 -mx-4 px-4 sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                @foreach($featuredItems->take(3) as $item)
                    <div class="slider-card flex-none w-[78vw] sm:w-[320px] lg:w-[calc(33.333%-16px)] snap-start group bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-xl hover:border-indigo-300 hover:-translate-y-1 transition-all duration-300 flex flex-col">
                        
                        <!-- Thumbnail -->
                        <div class="aspect-square bg-slate-100 relative overflow-hidden">
                            @if($item->thumbnail_url)
                                <img src="{{ $item->thumbnail_url }}" 
                                     alt="{{ $item->title }}" 
                                     loading="lazy"
                                     decoding="async"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500 opacity-0 blur-xs"
                                     onload="this.classList.remove('opacity-0', 'blur-xs'); this.classList.add('opacity-100', 'blur-0');">
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
                        <div class="p-3.5 sm:p-5 flex-1 flex flex-col justify-between space-y-2 sm:space-y-3">
                            
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

                <!-- Card 4: Lihat Semua Layanan -->
                <div class="slider-card flex-none w-[78vw] sm:w-[320px] lg:w-[calc(33.333%-16px)] snap-start group bg-gradient-to-br from-indigo-900 via-indigo-950 to-slate-900 rounded-2xl sm:rounded-3xl p-6 sm:p-8 text-white shadow-lg hover:shadow-2xl hover:shadow-indigo-500/20 transition-all duration-300 flex flex-col justify-between relative overflow-hidden">
                    <div class="absolute -right-12 -top-12 w-40 h-40 bg-indigo-500/20 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute -left-12 -bottom-12 w-40 h-40 bg-blue-500/20 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="relative z-10">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-indigo-300 mb-6 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold tracking-widest text-indigo-400 uppercase">Jelajahi Katalog</span>
                        <h3 class="text-xl sm:text-2xl font-black text-white mt-1 leading-snug">
                            Lihat Semua Layanan & Produk
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-300 mt-2 leading-relaxed">
                            Temukan berbagai macam pilihan layanan jasa dan karya unggulan siswa TEFA lainnya di katalog lengkap.
                        </p>
                    </div>

                    <div class="pt-6 mt-auto relative z-10">
                        <a href="{{ route('produk.list') }}" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-white text-slate-900 group-hover:bg-indigo-600 group-hover:text-white font-bold text-xs sm:text-sm transition-all duration-300 shadow-md">
                            <span>Buka Semua Layanan</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
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
                        
                        <a href="{{ route('portfolio.detail', $portfolio->id) }}" class="sm:w-1/2 aspect-video sm:aspect-auto bg-slate-950 relative overflow-hidden block flex-shrink-0">
                            @if($portfolio->thumbnail_url)
                                <img src="{{ $portfolio->thumbnail_url }}" 
                                     alt="{{ $portfolio->title }}" 
                                     loading="lazy"
                                     decoding="async"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-all duration-700 opacity-0 blur-xs"
                                     onload="this.classList.remove('opacity-0', 'blur-xs'); this.classList.add('opacity-100', 'blur-0');">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-600">No Image</div>
                            @endif
                        </a>

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

                            <div class="space-y-3">
                                <div class="pt-3 border-t border-slate-700/60 flex items-center justify-between text-xs text-slate-300">
                                    <span class="flex items-center gap-1 font-medium text-[11px] text-emerald-400">
                                        ✓ Terverifikasi
                                    </span>
                                    <span class="text-slate-400 text-[10px]">{{ $portfolio->client_name ?? 'Mitra Industri' }}</span>
                                </div>
                                <a href="{{ route('portfolio.detail', $portfolio->id) }}"
                                   class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl bg-white/10 hover:bg-indigo-600 text-white font-bold text-xs border border-white/10 hover:border-indigo-500 transition-all duration-200">
                                    Detail Proyek &amp; Tim Siswa
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
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