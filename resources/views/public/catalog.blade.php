<x-public-layout>
    <div class="bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <a href="{{ route('tefa.storefront', $unit->slug) }}" class="text-blue-600 hover:underline text-sm font-medium">&larr; Kembali ke Profil {{ $unit->name }}</a>
                <h1 class="text-3xl font-bold text-gray-900 mt-4">Katalog Lengkap</h1>
                <p class="text-gray-600">Semua produk dan layanan dari {{ $unit->name }}</p>
            </div>

            <div class="flex flex-col md:flex-row gap-8">
                <!-- Sidebar Filters (Mockup) -->
                <div class="w-full md:w-64 flex-shrink-0">
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 sticky top-24">
                        <h3 class="font-bold text-gray-900 mb-4">Filter</h3>
                        
                        <div class="space-y-3">
                            <h4 class="text-sm font-medium text-gray-700">Tipe Layanan</h4>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="type" value="" {{ !request('type') ? 'checked' : '' }} onchange="window.location.href='{{ route('tefa.catalog', $unit->slug) }}'" class="text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-600">Semua Tipe</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="type" value="produk" {{ request('type') === 'produk' ? 'checked' : '' }} onchange="window.location.href='{{ route('tefa.catalog', ['slug' => $unit->slug, 'type' => 'produk']) }}'" class="text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-600">Produk Fisik</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="type" value="digital" {{ request('type') === 'digital' ? 'checked' : '' }} onchange="window.location.href='{{ route('tefa.catalog', ['slug' => $unit->slug, 'type' => 'digital']) }}'" class="text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-600">Produk Digital</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="type" value="jasa" {{ request('type') === 'jasa' ? 'checked' : '' }} onchange="window.location.href='{{ route('tefa.catalog', ['slug' => $unit->slug, 'type' => 'jasa']) }}'" class="text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-600">Jasa / Servis</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="flex-1">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($items as $item)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-md transition">
                                <div class="aspect-square bg-gray-100 relative">
                                    @if($item->thumbnail_url)
                                        <img src="{{ $item->thumbnail_url }}" 
                                             alt="{{ $item->title }}" 
                                             loading="lazy"
                                             decoding="async"
                                             class="w-full h-full object-cover opacity-0 blur-xs transition-all duration-500"
                                             onload="this.classList.remove('opacity-0', 'blur-xs'); this.classList.add('opacity-100', 'blur-0');"
                                             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800&auto=format&fit=crop&q=80';">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">No Image</div>
                                    @endif
                                </div>
                                <div class="p-4 flex-1 flex flex-col">
                                    <h3 class="font-bold text-gray-900 line-clamp-1" title="{{ $item->title }}">{{ $item->title }}</h3>
                                    <p class="text-xs text-gray-500 mt-0.5 mb-2">{{ $item->category->name ?? 'Uncategorized' }}</p>
                                    
                                    <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed mb-3">
                                        {{ $item->description }}
                                    </p>

                                    @if($item->item_type->value === 'jasa')
                                        <p class="text-xs font-bold text-emerald-600 mb-3">Tersedia untuk konsultasi</p>
                                    @elseif(!$item->track_stock)
                                        <p class="text-xs font-bold text-emerald-600 mb-3">Stok unlimited</p>
                                    @else
                                        <p class="text-xs font-bold {{ $item->stock > 0 ? 'text-emerald-600' : 'text-red-600' }} mb-3">
                                            {{ $item->stock > 0 ? 'Stok tersedia: ' . $item->stock : 'Stok habis' }}
                                        </p>
                                    @endif
                                    <div class="flex items-center justify-between mt-auto pt-2 border-t border-gray-100">
                                        <span class="font-bold text-indigo-600 text-sm">Rp {{ number_format((float)$item->price, 0, ',', '.') }}</span>
                                        @auth
                                            @if($item->item_type->value === 'jasa')
                                                <a href="{{ route('jasa.nego', $item->slug) }}" class="bg-emerald-50 text-emerald-700 hover:bg-emerald-100 px-3 py-1.5 rounded-lg text-xs font-bold transition">Konsultasi</a>
                                            @else
                                                <a href="{{ route('order.checkout', $item->slug) }}" class="bg-indigo-50 text-indigo-700 hover:bg-indigo-100 px-3 py-1.5 rounded-lg text-xs font-bold transition">Detail & Pesan</a>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" class="bg-gray-100 text-gray-500 hover:bg-gray-200 px-3 py-1.5 rounded-lg text-xs font-medium transition">Login</a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-12 text-center bg-white rounded-xl border border-gray-100">
                                <p class="text-gray-500">Katalog kosong atau tidak ada item yang cocok dengan filter.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-8">
                        {{ $items->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>
