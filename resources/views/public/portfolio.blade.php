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
                            
                            <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
                                <img src="{{ $portfolio->worker->avatar_url ? asset('storage/'.$portfolio->worker->avatar_url) : 'https://ui-avatars.com/api/?name='.urlencode($portfolio->worker->user->name ?? 'Siswa').'&color=1D4ED8&background=DBEAFE' }}" alt="Avatar" class="w-8 h-8 rounded-full">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $portfolio->worker->user->name ?? 'Anonim' }}</p>
                                    <p class="text-xs text-gray-500 truncate">Siswa / Worker</p>
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
