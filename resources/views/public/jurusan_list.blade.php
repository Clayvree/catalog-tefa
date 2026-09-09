<x-public-layout>
    <div class="bg-slate-50 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Pusat Keunggulan Vokasi</span>
                <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">Daftar Unit Teaching Factory</h1>
                <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                    Pilih unit jurusan untuk melihat profil resmi, etalase produk, layanan jasa, dan karya portofolio siswa yang siap dipesan.
                </p>
            </div>

            <!-- Grid Jurusan -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($units as $unit)
                    <div class="group bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-2xl hover:border-indigo-300 hover:-translate-y-1.5 transition-all duration-300 flex flex-col">
                        
                        <!-- Banner -->
                        <div class="h-48 bg-slate-900 relative overflow-hidden">
                            @if($unit->banner_url)
                                <img src="{{ $unit->banner_url }}" alt="{{ $unit->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-80">
                            @else
                                <div class="w-full h-full bg-gradient-to-tr from-indigo-900 to-slate-800"></div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                            
                            <!-- Floating Logo -->
                            <div class="absolute bottom-4 left-5 w-14 h-14 rounded-2xl bg-white p-1.5 shadow-lg border border-slate-100 flex items-center justify-center overflow-hidden">
                                @if($unit->logo_url)
                                    <img src="{{ $unit->logo_url }}" alt="Logo" class="w-full h-full object-cover rounded-xl">
                                @else
                                    <span class="font-black text-indigo-600 text-xl">{{ substr($unit->name, 0, 1) }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                            <div>
                                <div class="flex items-center gap-2 text-xs font-bold text-indigo-600 mb-1.5">
                                    <span>Official TEFA</span>
                                    <span>•</span>
                                    <span class="text-slate-400 font-medium">{{ $unit->catalog_items_count ?? '0' }} Layanan</span>
                                    <span>•</span>
                                    <span class="text-slate-400 font-medium">{{ $unit->portfolios_count ?? '0' }} Portofolio</span>
                                </div>

                                <h3 class="font-black text-xl text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-1 mb-2">
                                    {{ $unit->name }}
                                </h3>

                                <p class="text-xs text-slate-500 leading-relaxed line-clamp-3">
                                    {{ $unit->description }}
                                </p>
                            </div>

                            <div class="pt-4 border-t border-slate-100 flex items-center gap-3">
                                <a href="{{ route('tefa.storefront', $unit->slug) }}" 
                                   class="flex-1 text-center py-3 px-4 rounded-xl bg-slate-900 hover:bg-indigo-600 text-white font-bold text-xs shadow-sm transition flex items-center justify-center gap-2">
                                    Buka Profil Storefront &rarr;
                                </a>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="col-span-full py-16 text-center text-slate-500 bg-white rounded-3xl border border-dashed border-slate-200">
                        Belum ada unit Teaching Factory yang aktif.
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $units->links() }}
            </div>

        </div>
    </div>
</x-public-layout>
