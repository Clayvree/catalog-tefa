<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.orders.index') }}" class="text-gray-500 hover:text-gray-700">
                &larr; Kembali
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Pesanan #{{ substr($order->id, 0, 8) }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Info Utama -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h3 class="text-lg font-bold">Informasi Pelanggan</h3>
                                <p class="text-sm text-gray-500">Dipesan pada {{ $order->order_date->format('d M Y H:i') }}</p>
                            </div>
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-{{ \App\Enums\OrderStatus::from($order->status)->badgeColor() }}-100 text-{{ \App\Enums\OrderStatus::from($order->status)->badgeColor() }}-800">
                                {{ \App\Enums\OrderStatus::from($order->status)->label() }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 text-sm mb-6">
                            <div>
                                <span class="block text-gray-500">Nama</span>
                                <span class="font-bold">{{ $order->customer_name }}</span>
                            </div>
                            <div>
                                <span class="block text-gray-500">Kontak (WA)</span>
                                <span class="font-bold">{{ $order->customer_contact ?? '-' }}</span>
                            </div>
                            @if($order->isDelivery())
                            <div class="col-span-2 mt-2">
                                <span class="block text-gray-500">Alamat Pengiriman</span>
                                <span class="font-bold">{{ $order->shipping_address }}, {{ $order->shipping_city }}</span>
                            </div>
                            @endif
                        </div>
                        
                        <h3 class="text-lg font-bold mb-4">Item Pesanan</h3>
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-500">
                                <tr>
                                    <th class="px-4 py-2 rounded-l-lg">Produk</th>
                                    <th class="px-4 py-2">Harga</th>
                                    <th class="px-4 py-2">Qty</th>
                                    <th class="px-4 py-2 rounded-r-lg text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr class="border-b border-gray-50">
                                    <td class="px-4 py-3 font-medium">{{ $item->item_title ?? $item->catalogItem->title ?? 'Item' }}</td>
                                    <td class="px-4 py-3">Rp{{ number_format($item->unit_price ?? $item->price_at_purchase, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">{{ $item->quantity }}</td>
                                    <td class="px-4 py-3 text-right font-bold">Rp{{ number_format($item->subtotal ?? ($item->price_at_purchase * $item->quantity), 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-right text-gray-500">Total Pembayaran</td>
                                    <td class="px-4 py-3 text-right font-black text-lg text-indigo-600">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Sidebar Status -->
                <div class="space-y-6">
                    <!-- Status Pembayaran -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <h3 class="text-lg font-bold mb-4">Pembayaran</h3>
                        
                        <form action="{{ route('admin.orders.payment.confirm', $order->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <select name="payment_status" class="w-full rounded-lg border-gray-300 text-sm mb-3">
                                <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                                <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                                <option value="expired" {{ $order->payment_status === 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                                <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refund</option>
                            </select>
                            <button type="submit" class="w-full bg-slate-800 text-white font-bold py-2 rounded-lg text-sm hover:bg-slate-700">Update Pembayaran</button>
                        </form>
                    </div>

                    <!-- Progress / Pengiriman -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <h3 class="text-lg font-bold mb-4">Pengiriman / Progress</h3>
                        
                        <form action="{{ route('admin.orders.fulfillment.update', $order->id) }}" method="POST">
                            @csrf @method('PATCH')
                            
                            <div class="mb-3">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Status Fulfillment</label>
                                <select name="fulfillment_status" class="w-full rounded-lg border-gray-300 text-sm">
                                    <option value="">Belum Diproses</option>
                                    @if($order->isDelivery())
                                        <option value="packing" {{ $order->fulfillment_status?->value === 'packing' ? 'selected' : '' }}>Sedang Dikemas</option>
                                        <option value="shipped" {{ $order->fulfillment_status?->value === 'shipped' ? 'selected' : '' }}>Dalam Pengiriman</option>
                                        <option value="delivered" {{ $order->fulfillment_status?->value === 'delivered' ? 'selected' : '' }}>Sudah Diterima</option>
                                    @elseif($order->isPickup())
                                        <option value="packing" {{ $order->fulfillment_status?->value === 'packing' ? 'selected' : '' }}>Sedang Dikemas</option>
                                        <option value="ready_for_pickup" {{ $order->fulfillment_status?->value === 'ready_for_pickup' ? 'selected' : '' }}>Siap Diambil</option>
                                        <option value="picked_up" {{ $order->fulfillment_status?->value === 'picked_up' ? 'selected' : '' }}>Sudah Diambil</option>
                                    @elseif($order->isService())
                                        <option value="in_progress" {{ $order->fulfillment_status?->value === 'in_progress' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                                        <option value="review" {{ $order->fulfillment_status?->value === 'review' ? 'selected' : '' }}>Menunggu Review Klien</option>
                                        <option value="revision" {{ $order->fulfillment_status?->value === 'revision' ? 'selected' : '' }}>Revisi</option>
                                        <option value="completed" {{ $order->fulfillment_status?->value === 'completed' ? 'selected' : '' }}>Selesai</option>
                                    @elseif($order->isDigital())
                                        <option value="awaiting_payment" {{ $order->fulfillment_status?->value === 'awaiting_payment' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                        <option value="download_ready" {{ $order->fulfillment_status?->value === 'download_ready' ? 'selected' : '' }}>Siap Diunduh</option>
                                    @endif
                                </select>
                            </div>

                            @if($order->isDelivery())
                            <div class="mb-3">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Nomor Resi</label>
                                <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" class="w-full rounded-lg border-gray-300 text-sm">
                            </div>
                            @endif

                            <div class="mb-3">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Estimasi Selesai / Tiba</label>
                                <input type="datetime-local" name="estimated_ready_at" value="{{ $order->estimated_ready_at ? $order->estimated_ready_at->format('Y-m-d\TH:i') : '' }}" class="w-full rounded-lg border-gray-300 text-sm">
                            </div>

                            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-2 rounded-lg text-sm hover:bg-indigo-700">Update Progress</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
