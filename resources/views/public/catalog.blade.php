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
                                        <img src="{{ asset('storage/' . $item->thumbnail_url) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">No Image</div>
                                    @endif
                                </div>
                                <div class="p-4 flex-1 flex flex-col">
                                    <h3 class="font-bold text-gray-900 line-clamp-1" title="{{ $item->title }}">{{ $item->title }}</h3>
                                    <p class="text-xs text-gray-500 mt-1 mb-3">{{ $item->category->name ?? 'Uncategorized' }}</p>
                                    <div class="flex items-center justify-between mt-auto">
                                        <span class="font-bold text-blue-600">Rp {{ number_format((float)$item->price, 0, ',', '.') }}</span>
                                        @auth
                                            <button class="bg-blue-50 text-blue-700 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-sm font-medium transition">Detail</button>
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
