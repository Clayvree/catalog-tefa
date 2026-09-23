@extends('layouts.public')

@section('title', 'Pemesanan — ' . $item->title)

@section('content')
<div class="bg-slate-900 py-12 text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-white mb-4 transition">
            ← Kembali ke Katalog
        </a>
        <div class="flex items-center gap-2 mb-2">
            @if($item->item_type->value === 'digital')
                <span class="px-3 py-1 rounded-full bg-purple-500/20 text-purple-300 font-extrabold text-[10px] uppercase border border-purple-500/30">
                    💻 Produk Digital & Unduhan Instan
                </span>
            @elseif($item->item_type->value === 'jasa')
                <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 font-extrabold text-[10px] uppercase border border-emerald-500/30">
                    🛠️ Layanan Jasa
                </span>
            @else
                <span class="px-3 py-1 rounded-full bg-blue-500/20 text-blue-300 font-extrabold text-[10px] uppercase border border-blue-500/30">
                    📦 Produk Fisik
                </span>
            @endif
        </div>
        <h1 class="text-2xl sm:text-4xl font-black tracking-tight">Form Pemesanan & Checkout</h1>
        <p class="text-xs sm:text-sm text-slate-400 mt-1">Selesaikan pemesanan untuk unit produksi Teaching Factory.</p>
    </div>
</div>

<div class="py-12 bg-slate-50 min-h-screen" x-data="{
    quantity: 1,
    stock: {{ (int) $item->stock }},
    trackStock: {{ $item->track_stock ? 'true' : 'false' }},
    unitPrice: {{ (int)$item->price }},
    itemType: '{{ $item->item_type->value }}',
    fulfillment: '{{ ($item->item_type->value === 'digital' || $item->fulfillment_type === 'digital_download') ? 'digital_download' : (($item->item_type->value === 'jasa') ? 'onsite_service' : 'delivery') }}',
    paymentMethod: 'wa',
    get subtotal() {
        return this.quantity * this.unitPrice;
    },
    formatRupiah(num) {
        return 'Rp' + num.toLocaleString('id-ID');
    }
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        @if($errors->any())
            <div class="mb-8 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-bold space-y-1 shadow-sm">
                <p class="font-extrabold text-sm">Gagal Memproses Pesanan:</p>
                <ul class="list-disc list-inside space-y-0.5 text-[11px] text-red-700 font-normal">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('order.store', $item->slug) }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            @csrf
            <input type="hidden" name="payment_method" value="wa">

            <!-- Left Column: Checkout Inputs (8 cols) -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- 1. Data Pemesan (Otomatis dari Akun) -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <span class="w-7 h-7 rounded-xl bg-indigo-600 text-white font-black text-xs flex items-center justify-center">1</span>
                        <h3 class="font-black text-base text-slate-900">Data Pemesan / Pembeli</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase mb-0.5">Nama Lengkap</span>
                            <p class="font-bold text-slate-900 text-sm">{{ auth()->user()->name }}</p>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase mb-0.5">Nomor WhatsApp / HP</span>
                            <p class="font-bold text-slate-900 text-sm">{{ auth()->user()->whatsapp_number ?? auth()->user()->phone ?? '-' }}</p>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400">Data diambil otomatis dari profil akun Anda.</p>
                </div>

                <!-- 2. Metode Penerimaan Produk (Fulfillment) -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <span class="w-7 h-7 rounded-xl bg-indigo-600 text-white font-black text-xs flex items-center justify-center">2</span>
                        <h3 class="font-black text-base text-slate-900">
                            Metode Pemenuhan / Pengiriman
                        </h3>
                    </div>

                    @if($item->item_type->value === 'digital')
                        <!-- DIGITAL PRODUCT FLOW -->
                        <div class="p-5 rounded-2xl border-2 border-purple-500 bg-purple-50/50 space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <input type="hidden" name="fulfillment_method" value="digital_download">
                                    <span class="font-black text-sm text-purple-900">💻 Akses Unduhan Digital Instan</span>
                                </div>
                                <span class="text-xs font-bold text-purple-700 bg-purple-100 px-2.5 py-0.5 rounded-full">Bebas Ongkir (Rp0)</span>
                            </div>
                            <p class="text-xs text-purple-800 leading-relaxed">
                                Link file digital / source code / aset akan otomatis aktif dan dapat diunduh langsung di halaman invoice setelah pembayaran berhasil.
                            </p>
                        </div>
                    @elseif($item->item_type->value === 'jasa')
                        <!-- SERVICE FLOW -->
                        <div class="p-5 rounded-2xl border-2 border-emerald-500 bg-emerald-50/50 space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <input type="hidden" name="fulfillment_method" value="onsite_service">
                                    <span class="font-black text-sm text-emerald-900">🛠️ Layanan Jasa / Servis</span>
                                </div>
                            </div>
                            <p class="text-xs text-emerald-800 leading-relaxed">
                                Detail pelaksanaan dan pengerjaan jasa akan disepakati lebih lanjut dengan Admin via WhatsApp.
                            </p>
                        </div>
                    @else
                        <!-- PHYSICAL PRODUCT FLOW (Deliver vs Pickup) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            
                            <!-- Option 1: Diantar Kurir -->
                            <label class="p-4 rounded-2xl border-2 cursor-pointer transition flex flex-col justify-between space-y-2" :class="fulfillment === 'delivery' ? 'border-indigo-600 bg-indigo-50/40' : 'border-slate-200 hover:border-slate-300'">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center gap-2">
                                        <input type="radio" name="fulfillment_method" value="delivery" x-model="fulfillment" class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="font-bold text-xs text-slate-900">🛵 Diantar Kurir</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-indigo-600 bg-indigo-100 px-2 py-0.5 rounded">Disepakati via WA</span>
                                </div>
                                <p class="text-[11px] text-slate-500 pl-6">Paket dikirim langsung ke alamat rumah / kantor Anda via ekspedisi kurir TEFA.</p>
                            </label>

                            <!-- Option 2: Ambil / Jemput di Workshop -->
                            <label class="p-4 rounded-2xl border-2 cursor-pointer transition flex flex-col justify-between space-y-2" :class="fulfillment === 'pickup_at_tefa' ? 'border-indigo-600 bg-indigo-50/40' : 'border-slate-200 hover:border-slate-300'">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center gap-2">
                                        <input type="radio" name="fulfillment_method" value="pickup_at_tefa" x-model="fulfillment" class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="font-bold text-xs text-slate-900">🏢 Jemput Sendiri di Workshop TEFA</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded">Rp0 (Gratis)</span>
                                </div>
                                <p class="text-[11px] text-slate-500 pl-6">Ambil langsung di workshop {{ $item->tefaUnit->name ?? 'TEFA' }} sekolah (Senin - Jumat 08:00 - 16:00).</p>
                            </label>

                        </div>

                        <!-- Shipping Address Inputs (Only when Delivery is active) -->
                        <div x-show="fulfillment === 'delivery'" class="space-y-4 pt-4 border-t border-slate-100">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Lengkap Pengantaran</label>
                                    <textarea name="shipping_address" rows="2" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan..." class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium"></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kota / Kabupaten</label>
                                    <input type="text" name="shipping_city" placeholder="Contoh: Surabaya / Sidoarjo" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Notes Input -->
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Catatan Tambahan untuk Penjual (Opsional)</label>
                        <input type="text" name="notes" placeholder="Contoh: Tolong dikemas dengan rapi..." class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                    </div>
                </div>

            </div>

            <!-- Right Column: Order Summary (4 cols) -->
            <div class="lg:col-span-4 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6 sticky top-28">
                <h3 class="font-black text-base text-slate-900 pb-3 border-b border-slate-100">Ringkasan Tagihan</h3>

                <div class="flex items-start gap-3">
                    <div class="w-16 h-16 rounded-2xl bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0">
                        <img src="{{ $item->thumbnail_url }}" class="w-full h-full object-cover">
                    </div>
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-indigo-600 uppercase">{{ $item->tefaUnit->name ?? 'TEFA Unit' }}</span>
                        <h4 class="font-bold text-xs text-slate-900 leading-snug">{{ $item->title }}</h4>
                        <p class="font-black text-xs text-slate-900" x-text="formatRupiah(unitPrice)"></p>
                    </div>
                </div>

                <!-- Quantity Counter -->
                <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100">
                    <div>
                        <span class="block text-xs font-bold text-slate-700">Jumlah Pembelian</span>
                        <span class="text-[10px] font-bold {{ !$item->track_stock || $item->stock > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ !$item->track_stock ? 'Stok unlimited' : ($item->stock > 0 ? 'Stok tersedia: ' . $item->stock : 'Stok habis') }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="if(quantity > 1) quantity--" class="w-7 h-7 rounded-lg bg-white border border-slate-200 text-slate-700 font-black text-sm flex items-center justify-center hover:bg-slate-100 cursor-pointer">-</button>
                        <input type="number" name="quantity" x-model.number="quantity" min="1" max="{{ $item->track_stock ? max(1, (int) $item->stock) : 100 }}" class="w-12 text-center text-xs font-bold py-1 border-0 bg-transparent focus:ring-0">
                        <button type="button" @click="if(!trackStock || quantity < stock) quantity++" class="w-7 h-7 rounded-lg bg-white border border-slate-200 text-slate-700 font-black text-sm flex items-center justify-center hover:bg-slate-100 cursor-pointer">+</button>
                    </div>
                </div>

                <!-- Calculation Breakdown -->
                <div class="space-y-2 text-xs pt-2">
                    <div class="flex items-center justify-between text-slate-500">
                        <span>Subtotal Barang</span>
                        <span class="font-bold text-slate-800" x-text="formatRupiah(subtotal)"></span>
                    </div>
                    <div class="flex items-center justify-between text-slate-500">
                        <span>Ongkos Kirim</span>
                        <span class="font-bold text-slate-800" x-show="fulfillment === 'delivery'">Disepakati via WA</span>
                        <span class="font-bold text-slate-800" x-show="fulfillment !== 'delivery'">Rp0</span>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-sm font-black text-slate-900">
                        <span>Total Pembayaran (Estimasi)</span>
                        <span class="text-indigo-600 text-base" x-text="formatRupiah(subtotal)"></span>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" @disabled($item->track_stock && $item->stock < 1) class="w-full py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition transform hover:-translate-y-0.5 cursor-pointer">
                    {{ !$item->track_stock || $item->stock > 0 ? 'Buat Pesanan & Hubungi Admin via WA →' : 'Produk Sedang Habis' }}
                </button>

                <p class="text-[10px] text-slate-400 text-center">
                    🔒 Transaksi resmi diverifikasi Teaching Factory & Sekolah.
                </p>
            </div>

        </form>

    </div>
</div>
@endsection