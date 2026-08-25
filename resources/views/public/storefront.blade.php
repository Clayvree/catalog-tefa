<x-public-layout>
    <script>window.TEFA_UNIT_ID = "{{ $unit->id }}";</script>

    <!-- Header Jurusan (Store Profile Hero) -->
    <div class="bg-white border-b border-slate-200/80">
        <div class="h-64 sm:h-80 w-full relative bg-slate-900 overflow-hidden">
            @if($unit->banner_url)
                <img src="{{ $unit->banner_url }}" alt="Banner" class="w-full h-full object-cover opacity-75">
            @else
                <div class="w-full h-full bg-gradient-to-r from-indigo-900 via-slate-900 to-slate-950"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative -mt-24 sm:-mt-32 pb-8 flex flex-col md:flex-row items-center md:items-end gap-6 sm:gap-8">
                
                <!-- Avatar Logo -->
                <div class="w-36 h-36 sm:w-44 sm:h-44 rounded-3xl border-4 border-white bg-white shadow-2xl overflow-hidden flex-shrink-0 z-10 p-2">
                    @if($unit->logo_url)
                        <img src="{{ $unit->logo_url }}" alt="{{ $unit->name }}" class="w-full h-full object-cover rounded-2xl">
                    @else
                        <div class="w-full h-full bg-indigo-50 flex items-center justify-center text-5xl font-black text-indigo-600 rounded-2xl">
                            {{ substr($unit->name, 0, 1) }}
                        </div>
                    @endif
                </div>

                <!-- Info Details -->
                <div class="text-center md:text-left flex-1 z-10 space-y-2">
                    <div class="flex flex-col md:flex-row md:items-center gap-3 justify-center md:justify-start">
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white tracking-tight">{{ $unit->name }}</h1>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold border border-emerald-500/30 backdrop-blur-md mx-auto md:mx-0 w-max">
                            ✓ Terverifikasi Industri
                        </span>
                    </div>
                    <p class="text-slate-300 text-xs sm:text-sm max-w-2xl leading-relaxed">
                        {{ $unit->description }}
                    </p>
                </div>

                <!-- Actions -->
                <div class="z-10 flex gap-3 w-full md:w-auto">
                    <a href="{{ route('tefa.catalog', $unit->slug) }}" 
                       class="flex-1 md:flex-none text-center bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-2xl font-bold text-xs sm:text-sm shadow-xl shadow-indigo-600/30 transition">
                        Semua Katalog Unit Ini &rarr;
                    </a>
                </div>

            </div>

            <!-- Storefront Quick Stats -->
            <div class="grid grid-cols-3 gap-4 py-6 border-t border-slate-100 text-center md:text-left">
                <div>
                    <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Katalog</span>
                    <span class="text-xl font-black text-slate-900">{{ $unit->catalog_items_count ?? '0' }} Layanan</span>
                </div>
                <div>
                    <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Portofolio Karya</span>
                    <span class="text-xl font-black text-slate-900">{{ $unit->portfolios_count ?? '0' }} Proyek</span>
                </div>
                <div>
                    <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Layanan Konsultasi</span>
                    <span class="text-xl font-black text-emerald-600">Aktif (AI / Chat)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Storefront Body -->
    <div class="py-16 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            
            <!-- Featured Products -->
            <section class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Etalase Unggulan</span>
                        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Katalog Produk & Jasa</h2>
                    </div>
                    <a href="{{ route('tefa.catalog', $unit->slug) }}" class="text-xs sm:text-sm font-bold text-indigo-600 hover:underline">
                        Lihat Selengkapnya &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($featuredItems as $item)
                        <div class="group bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-xl hover:border-indigo-300 transition-all duration-300 flex flex-col">
                            <div class="aspect-[4/3] bg-slate-100 relative overflow-hidden">
                                @if($item->thumbnail_url)
                                    <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">No Image</div>
                                @endif
                                <div class="absolute top-3 left-3 bg-slate-900/90 text-white px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wider">
                                    {{ $item->item_type->label() }}
                                </div>
                            </div>
                            <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                                <div class="space-y-2">
                                    <span class="text-[10px] font-bold text-indigo-600 uppercase">{{ $item->category->name ?? 'Kategori' }}</span>
                                    <h3 class="font-black text-base text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-2">
                                        {{ $item->title }}
                                    </h3>
                                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                        {{ $item->description }}
                                    </p>
                                </div>
                                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                    <div>
                                        <span class="block text-[10px] font-bold text-slate-400 uppercase">Harga</span>
                                        <span class="text-lg font-black text-slate-900">Rp{{ number_format((float)$item->price, 0, ',', '.') }}</span>
                                    </div>
                                    @auth
                                        <button class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-indigo-600 text-white text-xs font-bold transition">
                                            Pesan
                                        </button>
                                    @else
                                        <a href="{{ route('login') }}" class="px-3.5 py-2 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition">
                                            Login u/ Pesan
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center text-slate-400 bg-white rounded-3xl border border-dashed border-slate-200">
                            Belum ada item unggulan untuk unit ini.
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- Portfolios -->
            <section class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Bukti Pengerjaan</span>
                        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Portofolio Karya Siswa</h2>
                    </div>
                    <a href="{{ route('tefa.portfolio', $unit->slug) }}" class="text-xs sm:text-sm font-bold text-indigo-600 hover:underline">
                        Galeri Portofolio &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($featuredPortfolios as $portfolio)
                        <div class="group bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
                            <div class="aspect-[4/3] bg-slate-900 relative overflow-hidden">
                                @if($portfolio->thumbnail_url)
                                    <img src="{{ $portfolio->thumbnail_url }}" alt="{{ $portfolio->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-500">No Image</div>
                                @endif
                            </div>
                            <div class="p-6 flex-1 flex flex-col justify-between space-y-3">
                                <div>
                                    <h3 class="font-black text-base text-slate-900 mb-1.5">{{ $portfolio->title }}</h3>
                                    <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed">{{ $portfolio->description }}</p>
                                </div>
                                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                                    <span class="text-emerald-600 font-bold">✓ Terverifikasi</span>
                                    <span>{{ $portfolio->client_name ?? 'Mitra Industri' }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center text-slate-400 bg-white rounded-3xl border border-dashed border-slate-200">
                            Belum ada karya portofolio yang dipublikasikan.
                        </div>
                    @endforelse
                </div>
            </section>

        </div>
    </div>
</x-public-layout>
