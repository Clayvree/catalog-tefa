<x-public-layout>
    <div class="bg-slate-50 py-10 sm:py-16 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-10 sm:mb-14">
                <div class="flex items-center gap-2 text-xs font-medium text-slate-500 mb-4">
                    <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Beranda</a>
                    <span class="text-slate-300">/</span>
                    <a href="{{ route('tefa.storefront', $unit->slug) }}" class="hover:text-indigo-600 transition">{{ $unit->name }}</a>
                    <span class="text-slate-300">/</span>
                    <span class="text-slate-800 font-bold">Galeri Portofolio</span>
                </div>
                <div class="flex items-end justify-between gap-4 flex-wrap">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Bukti Karya Nyata</span>
                        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-1">Galeri Portofolio</h1>
                        <p class="text-slate-500 text-sm mt-1.5 max-w-2xl leading-relaxed">
                            Hasil karya dan proyek nyata yang telah diselesaikan oleh para siswa dari <span class="font-bold text-slate-700">{{ $unit->name }}</span>.
                        </p>
                    </div>
                    <a href="{{ route('tefa.storefront', $unit->slug) }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:border-indigo-400 text-slate-700 hover:text-indigo-600 font-bold text-xs shadow-sm transition whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Kembali ke Profil
                    </a>
                </div>
            </div>

            {{-- Grid Portofolio --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-6">
                @forelse($portfolios as $portfolio)
                    @php
                        $allContributors = collect();
                        $allContributors->push([
                            'name'       => $portfolio->worker->user->name ?? 'Anonim',
                            'avatar_url' => $portfolio->worker->avatar_url ?? null,
                            'role'       => 'Pengaju',
                        ]);
                        foreach ($portfolio->contributors as $c) {
                            $allContributors->push([
                                'name'       => $c->display_name,
                                'avatar_url' => $c->avatar_url,
                                'role'       => $c->role ?: 'Kontributor',
                            ]);
                        }
                        $avatarColors = ['bg-blue-100 text-blue-700','bg-violet-100 text-violet-700','bg-pink-100 text-pink-700','bg-amber-100 text-amber-700'];
                    @endphp
                    <div class="group bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 flex flex-col">

                        {{-- Thumbnail (clickable) --}}
                        <a href="{{ route('portfolio.detail', $portfolio->id) }}" class="block aspect-[4/3] bg-slate-900 relative overflow-hidden">
                            @if($portfolio->thumbnail_url)
                                <img src="{{ $portfolio->thumbnail_url }}" 
                                     alt="{{ $portfolio->title }}" 
                                     loading="lazy"
                                     decoding="async"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-all duration-700 opacity-0 blur-xs"
                                     onload="this.classList.remove('opacity-0', 'blur-xs'); this.classList.add('opacity-100', 'blur-0');"
                                     onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800&auto=format&fit=crop&q=80';">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-500">
                                    <svg class="w-10 h-10 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                            {{-- Hover overlay --}}
                            <div class="absolute inset-0 bg-indigo-900/0 group-hover:bg-indigo-900/50 transition-all duration-300 flex items-center justify-center">
                                <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-white font-bold text-xs bg-white/20 backdrop-blur-sm border border-white/30 px-4 py-2 rounded-xl flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Lihat Detail
                                </span>
                            </div>
                        </a>

                        {{-- Card Body --}}
                        <div class="p-5 flex flex-col flex-1 space-y-3">
                            <div>
                                <h3 class="font-bold text-slate-900 mb-1 line-clamp-1 group-hover:text-indigo-600 transition-colors text-sm">{{ $portfolio->title }}</h3>
                                <p class="text-xs text-slate-500 line-clamp-2">{{ $portfolio->description }}</p>
                            </div>

                            @if($portfolio->review_notes)
                                <div class="p-2.5 bg-blue-50/60 rounded-lg border border-blue-100">
                                    <div class="flex items-center gap-1.5 mb-1">
                                        <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        <span class="text-[10px] font-black text-blue-700 uppercase tracking-wider">Direkomendasikan Guru</span>
                                    </div>
                                    <p class="text-[11px] text-blue-800 line-clamp-2 italic">"{{ $portfolio->review_notes }}"</p>
                                </div>
                            @endif

                            {{-- Avatar Stack + Tim --}}
                            <div class="flex items-start gap-3 pt-3 border-t border-slate-100 mt-auto">
                                <div class="flex items-center flex-shrink-0">
                                    @foreach($allContributors->take(4) as $i => $person)
                                        @php $color = $avatarColors[$i % count($avatarColors)]; @endphp
                                        <div title="{{ $person['name'] }} · {{ $person['role'] }}"
                                             class="w-7 h-7 rounded-full ring-2 ring-white overflow-hidden {{ $color }} flex items-center justify-center text-[10px] font-bold flex-shrink-0 {{ $i > 0 ? '-ml-2' : '' }}">
                                            @if($person['avatar_url'])
                                                <img src="{{ asset('storage/' . $person['avatar_url']) }}" class="w-full h-full object-cover">
                                            @else
                                                {{ substr($person['name'], 0, 1) }}
                                            @endif
                                        </div>
                                    @endforeach
                                    @if($allContributors->count() > 4)
                                        <div class="w-7 h-7 rounded-full ring-2 ring-white bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-600 -ml-2">
                                            +{{ $allContributors->count() - 4 }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    @if($allContributors->count() === 1)
                                        <p class="text-xs font-semibold text-slate-800 truncate">{{ $allContributors->first()['name'] }}</p>
                                        <p class="text-[10px] text-slate-400">Siswa / Worker</p>
                                    @else
                                        <p class="text-xs font-semibold text-slate-800 truncate">
                                            {{ $allContributors->first()['name'] }}
                                            <span class="text-slate-400 font-normal">& {{ $allContributors->count() - 1 }} lainnya</span>
                                        </p>
                                        <p class="text-[10px] text-slate-400">Tim Kolaborasi</p>
                                    @endif
                                </div>
                            </div>

                            {{-- CTA Button --}}
                            <a href="{{ route('portfolio.detail', $portfolio->id) }}"
                               class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl bg-slate-900 hover:bg-indigo-600 text-white font-bold text-xs transition-colors duration-200 shadow-sm mt-auto">
                                Detail Proyek &amp; Tim Siswa
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-white rounded-2xl border border-dashed border-slate-300">
                        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-slate-500 font-medium">Belum ada portofolio yang disetujui untuk ditampilkan.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-12">
                {{ $portfolios->links() }}
            </div>
        </div>
    </div>
</x-public-layout>
