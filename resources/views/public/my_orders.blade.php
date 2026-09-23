<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pesanan Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Filter Navigasi -->
            <div class="flex gap-2 overflow-x-auto pb-2">
                <a href="{{ route('order.my_orders') }}" class="px-4 py-2 rounded-full text-xs font-bold {{ !request('type') ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">Semua</a>
                <a href="{{ route('order.my_orders', ['type' => 'physical']) }}" class="px-4 py-2 rounded-full text-xs font-bold {{ request('type') === 'physical' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">Produk Fisik</a>
                <a href="{{ route('order.my_orders', ['type' => 'digital']) }}" class="px-4 py-2 rounded-full text-xs font-bold {{ request('type') === 'digital' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">Produk Digital</a>
                <a href="{{ route('order.my_orders', ['type' => 'service']) }}" class="px-4 py-2 rounded-full text-xs font-bold {{ request('type') === 'service' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">Layanan Jasa</a>
            </div>
            @forelse($orders as $order)
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-4 pb-4 border-b border-gray-50">
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">#{{ substr($order->id, 0, 8) }}</span>
                            <h3 class="font-bold text-lg text-gray-900 mt-1">{{ $order->tefaUnit->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $order->order_date->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="flex flex-col sm:items-end gap-2">
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-{{ \App\Enums\OrderStatus::from($order->status)->badgeColor() }}-100 text-{{ \App\Enums\OrderStatus::from($order->status)->badgeColor() }}-800">
                                {{ \App\Enums\OrderStatus::from($order->status)->label() }}
                            </span>
                            @if($order->isDigital() || $order->fulfillment_method === 'digital_download')
                                {{-- Digital: tidak tampilkan status barang, cukup tombol download jika sudah bayar --}}
                                @if($order->payment_status === 'paid')
                                    @php
                                        $dlItem = $order->items->first()?->catalogItem;
                                        $dlUrl = $dlItem?->digital_file_url ?? '#';
                                    @endphp
                                    <a href="{{ $dlUrl }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 text-xs font-bold rounded-full bg-indigo-600 text-white hover:bg-indigo-700 transition">
                                        ⬇️ Download
                                    </a>
                                @else
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-800">⏳ Menunggu Pembayaran</span>
                                @endif
                            @else
                                @if($order->fulfillment_status)
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-{{ $order->fulfillment_status->badgeColor() }}-100 text-{{ $order->fulfillment_status->badgeColor() }}-800">
                                        {{ $order->fulfillment_status->label() }}
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-800">
                                        Menunggu Diproses
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h4 class="text-sm font-bold text-gray-700 mb-2">Item</h4>
                            <ul class="space-y-1 text-sm text-gray-600">
                                @foreach($order->items as $item)
                                    <li>{{ $item->quantity }}x {{ $item->item_title ?? $item->catalogItem?->title ?? 'Item' }}</li>
                                @endforeach
                            </ul>
                            <p class="mt-3 text-lg font-black text-indigo-600">Total: Rp{{ number_format($order->grand_total, 0, ',', '.') }}</p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-xl text-sm">
                            <h4 class="font-bold text-gray-700 mb-2">Info Pengiriman / Progress</h4>
                            @if($order->isDelivery())
                                <p><span class="text-gray-500">Tipe:</span> Pengiriman (Delivery)</p>
                                @if($order->tracking_number)
                                    <p><span class="text-gray-500">Resi:</span> <span class="font-mono font-bold">{{ $order->tracking_number }}</span></p>
                                @endif
                            @elseif($order->isPickup())
                                <p><span class="text-gray-500">Tipe:</span> Ambil di Workshop (Pickup)</p>
                            @elseif($order->isService())
                                <p><span class="text-gray-500">Tipe:</span> Layanan Jasa</p>
                            @elseif($order->isDigital())
                                <p><span class="text-gray-500">Tipe:</span> Produk Digital / Download</p>
                            @endif
                            
                            @if($order->estimated_ready_at)
                                <p class="mt-2 text-indigo-700 font-medium">Estimasi Tiba/Selesai: {{ $order->estimated_ready_at->format('d M Y, H:i') }}</p>
                            @endif

                            @if($order->fulfillment_notes)
                                <p class="mt-2 text-gray-600 italic">"{{ $order->fulfillment_notes }}"</p>
                            @endif
                            
                            @if($order->isDigital() && $order->fulfillment_status?->value === 'download_ready')
                                <div class="mt-4">
                                    <!-- In real app, this should link to a secure download route using the digital_access_token -->
                                    <a href="#" class="inline-block px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow-sm text-xs transition">
                                        &darr; Download File
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('order.invoice', $order->id) }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800">
                            Lihat Invoice Lengkap &rarr;
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-4xl mb-4">🛒</p>
                    <h3 class="text-lg font-bold text-gray-900">Belum Ada Pesanan</h3>
                    <p class="text-gray-500 mt-2">Anda belum melakukan pemesanan apapun.</p>
                    <a href="{{ route('produk.list') }}" class="inline-block mt-6 px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl shadow-lg hover:bg-indigo-700 transition">
                        Mulai Belanja
                    </a>
                </div>
            @endforelse

            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>

