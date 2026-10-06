<x-public-layout>
    <div class="bg-gray-50 py-8 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-12 text-center">
                <h1 class="text-3xl font-bold text-gray-900 mt-4">Galeri Portofolio</h1>
                <p class="text-gray-600 mt-2 max-w-2xl mx-auto">Hasil karya dan proyek nyata yang telah diselesaikan oleh para siswa dari {{ $unit->name }}.</p>
                <div class="mt-6">
                    <a href="{{ route('tefa.storefront', $unit->slug) }}" class="inline-block bg-white text-gray-700 hover:text-blue-600 border border-gray-300 px-4 py-2 rounded-lg font-medium shadow-sm transition">&larr; Kembali ke Profil</a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($portfolios as $portfolio)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition duration-300">
                        <div class="aspect-[4/3] bg-gray-200 relative group">
                            @if($portfolio->thumbnail_url)
                                <img src="{{ asset('storage/' . $portfolio->thumbnail_url) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">No Image</div>
                            @endif
                            
                            <!-- Hover Overlay -->
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                @if($portfolio->external_link)
                                    <a href="{{ $portfolio->external_link }}" target="_blank" class="bg-white text-gray-900 hover:text-blue-600 font-bold py-2 px-4 rounded-lg text-sm">Lihat Proyek</a>
                                @else
                                    <span class="text-white font-medium">Internal Project</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="font-bold text-gray-900 mb-1 line-clamp-1">{{ $portfolio->title }}</h3>
                            <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $portfolio->description }}</p>
                            
                            @if($portfolio->review_notes)
                            <div class="mb-4 p-3 bg-blue-50/50 rounded-lg border border-blue-100">
                                <div class="flex items-center gap-1.5 mb-1.5">
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    <span class="text-[10px] font-bold text-blue-700 uppercase tracking-wider">Direkomendasikan Guru</span>
                                </div>
                                <p class="text-xs text-blue-800 line-clamp-2 italic">"{{ $portfolio->review_notes }}"</p>
                            </div>
                            @endif
                            
                            <div class="flex items-start gap-3 pt-3 border-t border-gray-100">
                                {{-- Avatar stack semua kontributor --}}
                                @php
                                    $allContributors = collect();
                                    // Pengaju utama
                                    $allContributors->push([
                                        'name'       => $portfolio->worker->user->name ?? 'Anonim',
                                        'avatar_url' => $portfolio->worker->avatar_url ?? null,
                                        'role'       => 'Pengaju',
                                    ]);
                                    // Kontributor tambahan
                                    foreach ($portfolio->contributors as $c) {
                                        $allContributors->push([
                                            'name'       => $c->display_name,
                                            'avatar_url' => $c->avatar_url,
                                            'role'       => $c->role ?: 'Kontributor',
                                        ]);
                                    }
                                @endphp

                                <div class="flex items-center flex-shrink-0">
                                    @foreach($allContributors->take(4) as $i => $person)
                                        @php
                                            $avatarColors = ['bg-blue-100 text-blue-700','bg-violet-100 text-violet-700','bg-pink-100 text-pink-700','bg-amber-100 text-amber-700'];
                                            $color = $avatarColors[$i % count($avatarColors)];
                                        @endphp
                                        <div title="{{ $person['name'] }} · {{ $person['role'] }}"
                                             class="w-8 h-8 rounded-full ring-2 ring-white overflow-hidden {{ $color }} flex items-center justify-center text-xs font-bold flex-shrink-0 {{ $i > 0 ? '-ml-2' : '' }}">
                                            @if($person['avatar_url'])
                                                <img src="{{ asset('storage/' . $person['avatar_url']) }}" class="w-full h-full object-cover">
                                            @else
                                                {{ substr($person['name'], 0, 1) }}
                                            @endif
                                        </div>
                                    @endforeach
                                    @if($allContributors->count() > 4)
                                        <div class="w-8 h-8 rounded-full ring-2 ring-white bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 -ml-2">
                                            +{{ $allContributors->count() - 4 }}
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    @if($allContributors->count() === 1)
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $allContributors->first()['name'] }}</p>
                                        <p class="text-xs text-gray-500 truncate">Siswa / Worker</p>
                                    @else
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $allContributors->first()['name'] }}
                                            <span class="text-gray-400 font-normal">& {{ $allContributors->count() - 1 }} lainnya</span>
                                        </p>
                                        <p class="text-xs text-gray-500 truncate">Tim Kolaborasi</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-white rounded-xl border border-dashed border-gray-300">
                        <p class="text-gray-500">Belum ada portofolio yang disetujui untuk ditampilkan.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-12">
                {{ $portfolios->links() }}
            </div>
        </div>
    </div>
</x-public-layout>