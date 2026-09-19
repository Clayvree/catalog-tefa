@extends('layouts.public')

@section('title', 'Tagihan Pesanan #' . substr($order->id, 0, 8))

@section('content')
<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Flash Message -->
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-sm">
                <span>✓ {{ session('success') }}</span>
            </div>
        @endif

        <!-- Invoice Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/80 shadow-xl space-y-8">
            
            <!-- Top Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                <div>
                    <span class="text-xs font-extrabold uppercase tracking-wider text-indigo-600">INVOICE PEMBAYARAN</span>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 mt-0.5">Pesanan #{{ substr($order->id, 0, 8) }}</h2>
                    <p class="text-xs text-slate-400 mt-1">{{ $order->order_date?->format('d F Y, H:i') }} WIB</p>
                </div>

                <div>
                    @if($order->payment_status === 'paid')
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-2xl bg-emerald-50 text-emerald-700 font-black text-xs border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> PEMBAYARAN LUNAS
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-2xl bg-amber-50 text-amber-700 font-black text-xs border border-amber-200 animate-pulse">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span> MENUNGGU PEMBAYARAN
                        </span>
                    @endif
                </div>
            </div>

            <!-- Customer & Fulfillment Details -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 bg-slate-50 p-5 rounded-2xl border border-slate-100 text-xs">
                <div class="space-y-1">
                    <span class="text-slate-400 font-bold uppercase text-[10px]">Data Pelanggan</span>
                    <p class="font-black text-slate-900 text-sm">{{ $order->customer_name }}</p>
                    <p class="text-slate-600 font-mono">{{ $order->customer_contact }}</p>
                </div>

                <div class="space-y-1">
                    <span class="text-slate-400 font-bold uppercase text-[10px]">Metode Penerimaan</span>
                    @if($order->fulfillment_method === 'delivery')
                        <p class="font-bold text-slate-900">🛵 Diantar Kurir ke Alamat</p>
                        <p class="text-slate-600 leading-relaxed">{{ $order->shipping_address }}, {{ $order->shipping_city }}</p>
                    @elseif($order->fulfillment_method === 'pickup_at_tefa')
                        <p class="font-bold text-slate-900">🏢 Ambil Sendiri di Workshop TEFA</p>
                        <p class="text-slate-500">Unit: {{ $order->tefaUnit->name ?? 'Teaching Factory' }}</p>
                    @elseif($order->fulfillment_method === 'digital_download')
                        <p class="font-bold text-slate-900">💻 Unduhan File Digital Instan</p>
                        <p class="text-slate-500">Akses langsung di halaman ini setelah lunas.</p>
                    @else
                        <p class="font-bold text-slate-900">🛠️ Layanan Jasa Industri</p>
                    @endif
                </div>
            </div>

            <!-- Item Ordered -->
            <div class="space-y-3">
                <h4 class="font-black text-xs uppercase tracking-wider text-slate-400">Rincian Barang</h4>
                @foreach($order->items as $item)
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                        <div>
                            <p class="font-bold text-slate-900 text-sm">{{ $item->item_title }}</p>
                            <span class="text-slate-400">{{ $item->quantity }}x @ Rp{{ number_format((float)$item->unit_price, 0, ',', '.') }}</span>
                        </div>
                        <span class="font-black text-slate-900 text-sm">Rp{{ number_format((float)$item->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Total Price Summary -->
            <div class="p-5 rounded-2xl bg-indigo-50/50 border border-indigo-100 space-y-2 text-xs">
                <div class="flex items-center justify-between text-slate-600">
                    <span>Metode Pembayaran</span>
                    <span class="font-bold uppercase font-mono text-indigo-700">{{ $order->payment_method }}</span>
                </div>
                <div class="flex items-center justify-between text-slate-600">
                    <span>Biaya Ongkir / Penanganan</span>
                    <span class="font-bold text-slate-800">{{ $order->fulfillment_method === 'delivery' ? 'Rp15.000' : 'Rp0' }}</span>
                </div>
                <div class="pt-3 border-t border-indigo-200/60 flex items-center justify-between text-sm sm:text-base font-black text-slate-900">
                    <span>Total Tagihan</span>
                    <span class="text-indigo-600 text-lg">Rp{{ number_format((float)$order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Payment Action / QRIS Simulator (If Unpaid) -->
            @if($order->payment_status !== 'paid')
                <div class="p-6 rounded-3xl bg-slate-900 text-white space-y-6 text-center">
                    <div class="space-y-1">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-400">Instruksi Pembayaran</span>
                        <h3 class="text-lg font-black">Scan QRIS untuk Menyelesaikan Pembayaran</h3>
                        <p class="text-xs text-slate-400">Buka aplikasi mobile banking atau e-wallet (BCA, GoPay, OVO, Dana) Anda.</p>
                    </div>

                    <!-- Dynamic QR Code Simulator -->
                    <div class="inline-block p-4 bg-white rounded-2xl shadow-lg">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=TEFAHUB-PAY-{{ $order->id }}-{{ (int)$order->total_price }}" alt="QRIS Code" class="w-44 h-44 mx-auto">
                        <p class="text-[10px] font-mono text-slate-800 font-bold mt-2">NMID: ID102024TEFAHUB</p>
                    </div>

                    <!-- Simulation Trigger Button for Demo -->
                    <form action="{{ route('order.pay.simulate', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-3.5 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-slate-900 font-black text-xs shadow-lg transition transform hover:-translate-y-0.5 cursor-pointer">
                            ⚡ Klik Di Sini untuk Konfirmasi Pembayaran Lunas (Simulasi Demo)
                        </button>
                    </form>
                </div>
            @else
                <!-- Paid Success Banner & Instant Access -->
                <div class="p-6 sm:p-8 rounded-3xl bg-emerald-50 border border-emerald-200 text-center space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white font-black text-xl flex items-center justify-center mx-auto shadow-md">
                        ✓
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-lg font-black text-emerald-900">Pembayaran Berhasil Dikonfirmasi!</h3>
                        <p class="text-xs text-emerald-700">Terima kasih telah bertransaksi di Teaching Factory.</p>
                    </div>

                    @if($order->isDigital() || $order->fulfillment_method === 'digital_download')
                        @php
                            $firstItem = $order->items->first()?->catalogItem;
                            $downloadUrl = $firstItem?->digital_file_url ?? '#';
                        @endphp
                        <div class="pt-3">
                            <a href="{{ $downloadUrl }}" target="_blank" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs shadow-lg shadow-indigo-600/30 transition">
                                <span>⬇️ Akses & Download File Digital</span>
                            </a>
                        </div>
                    @else
                        <div class="p-4 rounded-2xl bg-white border border-emerald-200 text-xs text-slate-600 text-left space-y-2 mt-4">
                            <span class="font-bold text-slate-900 block mb-1">Status Fulfillment:</span>
                            @if($order->fulfillment_status)
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-{{ $order->fulfillment_status->badgeColor() }}-100 text-{{ $order->fulfillment_status->badgeColor() }}-800">
                                    {{ $order->fulfillment_status->label() }}
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-800">Menunggu Diproses</span>
                            @endif

                            @if($order->tracking_number)
                                <p class="mt-2"><span class="font-bold">Resi:</span> {{ $order->tracking_number }}</p>
                            @endif
                            @if($order->estimated_ready_at)
                                <p class="mt-1"><span class="font-bold">Estimasi:</span> {{ $order->estimated_ready_at->format('d M Y, H:i') }}</p>
                            @endif
                            @if($order->fulfillment_notes)
                                <p class="mt-1 italic">"{{ $order->fulfillment_notes }}"</p>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                <a href="{{ route('home') }}" class="text-xs font-bold text-indigo-600 hover:underline">
                    ← Kembali ke Halaman Utama
                </a>
                <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                    🖨️ Cetak Invoice
                </button>
            </div>

        </div>

    </div>
</div>
@endsection