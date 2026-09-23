<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">{{ $managedUnit->name ?? 'Teaching Factory' }}</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-0.5">
                    Kelola Pesanan Masuk & Pengiriman (Fulfillment)
                </h2>
            </div>
            <span class="text-xs font-bold text-slate-500">
                Memantau pesanan fisik (kurir/ambil di workshop) & digital.
            </span>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Flash Message -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-sm">
                    <span>✓ {{ session('success') }}</span>
                </div>
            @endif

            <!-- Quick Status Filters -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <a href="{{ route('admin.orders.index') }}" class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-indigo-500 transition space-y-1 {{ !request('type') && !request('status') ? 'ring-2 ring-indigo-600' : '' }}">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Semua Pesanan</span>
                    <p class="text-xl font-black text-slate-900">{{ $stats['total'] }}</p>
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-indigo-500 transition space-y-1 {{ request('status') === 'pending' ? 'ring-2 ring-indigo-600' : '' }}">
                    <span class="text-[10px] font-bold text-amber-500 uppercase">⏳ Perlu Diproses</span>
                    <p class="text-xl font-black text-amber-500">{{ $stats['pending'] }}</p>
                </a>
                <a href="{{ route('admin.orders.index', ['type' => 'physical']) }}" class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-indigo-500 transition space-y-1 {{ request('type') === 'physical' ? 'ring-2 ring-indigo-600' : '' }}">
                    <span class="text-[10px] font-bold text-indigo-600 uppercase">🛵 Pengiriman Kurir / Ambil</span>
                    <p class="text-xl font-black text-indigo-600">{{ $stats['delivery'] + $stats['pickup'] }}</p>
                </a>
                <a href="{{ route('admin.orders.index', ['type' => 'digital']) }}" class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-indigo-500 transition space-y-1 {{ request('type') === 'digital' ? 'ring-2 ring-indigo-600' : '' }}">
                    <span class="text-[10px] font-bold text-emerald-600 uppercase">💻 Produk Digital</span>
                    <p class="text-xl font-black text-emerald-600">{{ $stats['digital'] }}</p>
                </a>
            </div>

            <!-- Orders Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="font-black text-base text-slate-900">Daftar Transaksi Pesanan Konsumen</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Kelola metode penerimaan barang dan verifikasi status pembayaran.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider text-[10px] border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">Customer & Kontak</th>
                                <th class="px-6 py-3.5">Item yang Dipesan</th>
                                <th class="px-6 py-3.5">Metode Penerimaan (Fulfillment)</th>
                                <th class="px-6 py-3.5">Pembayaran</th>
                                <th class="px-6 py-3.5">Status Pesanan</th>
                                <th class="px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse($orders as $order)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="font-bold text-slate-900 text-sm">{{ $order->customer_name }}</p>
                                            <p class="text-[11px] text-slate-500 font-mono">{{ $order->customer_contact }}</p>
                                            <span class="text-[10px] text-slate-400">#{{ substr($order->id, 0, 8) }} • {{ $order->created_at?->format('d M Y, H:i') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            @foreach($order->items as $orderItem)
                                                <p class="font-bold text-slate-900 line-clamp-1">{{ $orderItem->item_title }} <span class="text-indigo-600 font-normal">({{ $orderItem->quantity }}x)</span></p>
                                            @endforeach
                                            <p class="text-xs font-black text-slate-900 pt-0.5">
                                                Rp{{ number_format((float)$order->total_price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1 max-w-xs">
                                            @if($order->fulfillment_method === 'delivery')
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 font-bold text-[10px]">
                                                    🛵 Diantar Kurir ke Alamat
                                                </span>
                                                <p class="text-[11px] text-slate-600 line-clamp-2 leading-relaxed">
                                                    {{ $order->shipping_address }}, {{ $order->shipping_city }}
                                                </p>
                                            @elseif($order->fulfillment_method === 'pickup_at_tefa')
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 font-bold text-[10px]">
                                                    🏢 Ambil di Workshop Jurusan TEFA
                                                </span>
                                                <p class="text-[10px] text-slate-400">Diambil langsung oleh pembeli</p>
                                            @elseif($order->fulfillment_method === 'digital_download')
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-bold text-[10px]">
                                                    💻 Akses Download Digital
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 font-bold text-[10px]">
                                                    🛠️ Booking Layanan Jasa
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            @if($order->payment_status === 'paid')
                                                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-extrabold text-[10px] uppercase">
                                                    ✓ Lunas
                                                </span>
                                                <form action="{{ route('admin.orders.payment.confirm', $order->id) }}" method="POST" class="inline block pt-1" onsubmit="return confirm('Anda yakin ingin membatalkan status lunas?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="payment_status" value="unpaid">
                                                    <button type="submit" class="text-[10px] text-slate-500 font-bold hover:underline cursor-pointer">
                                                        [Batalkan Lunas]
                                                    </button>
                                                </form>
                                            @else
                                                <span class="px-2.5 py-1 rounded-full bg-red-50 text-red-700 font-extrabold text-[10px] uppercase">
                                                    Belum Bayar
                                                </span>
                                                <form action="{{ route('admin.orders.payment.confirm', $order->id) }}" method="POST" class="inline block pt-1" onsubmit="return confirm('Anda yakin pesanan ini sudah dibayar lunas?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="payment_status" value="paid">
                                                    <button type="submit" class="text-[10px] text-indigo-600 font-bold hover:underline cursor-pointer">
                                                        [Tandai Lunas]
                                                    </button>
                                                </form>
                                            @endif
                                            <p class="text-[10px] text-slate-400 uppercase font-mono">{{ $order->payment_method }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('admin.orders.status.update', $order->id) }}" method="POST" class="inline-flex items-center">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()" class="rounded-lg border-slate-200 text-[10px] py-1 pl-2 pr-6 font-bold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="processed" {{ $order->status === 'processed' ? 'selected' : '' }}>Diproses / Disiapkan</option>
                                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai (Diantar/Diambil)</option>
                                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex flex-col gap-1 items-end">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="px-2.5 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-[11px] transition text-center inline-block min-w-20">
                                                Detail &rarr;
                                            </a>
                                            <a href="{{ route('order.invoice', $order->id) }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] transition text-center inline-block min-w-20">
                                                Invoice ⧉
                                            </a>
                                            @if($order->customer_contact && strlen(preg_replace('/[^0-9]/', '', $order->customer_contact)) >= 10)
                                                @php
                                                    $phone = preg_replace('/[^0-9]/', '', $order->customer_contact);
                                                    if (substr($phone, 0, 1) === '0') $phone = '62' . substr($phone, 1);
                                                    $waUrl = "https://wa.me/{$phone}?text=" . urlencode("Halo {$order->customer_name}, ini Admin TEFA. Saya ingin menginformasikan terkait pesanan kamu dengan ID #" . substr($order->id, 0, 8) . "...");
                                                @endphp
                                                <a href="{{ $waUrl }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-[11px] transition flex items-center gap-1 min-w-20 justify-center">
                                                    💬 Chat
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">Belum ada pesanan masuk di jurusan ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-slate-100">
                    {{ $orders->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>