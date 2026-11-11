@extends('layouts.public')

@section('title', 'Pemesanan — ' . $item->title)

@section('content')
<div class="bg-slate-900 py-10 sm:py-12 text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-white mb-4 transition">
            ← Kembali ke Katalog
        </a>
        <div class="flex items-center gap-2 mb-2 flex-wrap">
            @if($item->item_type->value === 'digital')
                <span class="px-3 py-1 rounded-full bg-purple-500/20 text-purple-300 font-extrabold text-[10px] uppercase border border-purple-500/30">
                    Produk Digital & Unduhan Instan
                </span>
            @elseif($item->item_type->value === 'jasa')
                <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 font-extrabold text-[10px] uppercase border border-emerald-500/30">
                    Layanan Jasa & Konsultasi
                </span>
            @else
                <span class="px-3 py-1 rounded-full bg-blue-500/20 text-blue-300 font-extrabold text-[10px] uppercase border border-blue-500/30">
                    Produk Fisik
                </span>
            @endif
            <span class="text-xs font-bold text-slate-400">• {{ $item->tefaUnit->name ?? 'Teaching Factory' }}</span>
        </div>
        <h1 class="text-2xl sm:text-4xl font-black tracking-tight">Form Pemesanan & Detail Produk</h1>
        <p class="text-xs sm:text-sm text-slate-400 mt-1">Periksa spesifikasi lengkap produk dan selesaikan pemesanan resmi Teaching Factory.</p>
    </div>
</div>

<div class="py-10 bg-slate-50 min-h-screen" x-data="{
    quantity: {{ max(1, (int) old('quantity', 1)) }},
    stock: {{ (int) $item->stock }},
    trackStock: {{ $item->track_stock ? 'true' : 'false' }},
    unitPrice: {{ (int)$item->price }},
    itemType: '{{ $item->item_type->value }}',
    fulfillment: '{{ old('fulfillment_method') ?: (($item->item_type->value === 'digital' || $item->fulfillment_type === 'digital_download') ? 'digital_download' : (($item->item_type->value === 'jasa') ? 'onsite_service' : 'delivery')) }}',
    paymentMethod: 'wa',
    get subtotal() {
        return Math.max(1, this.quantity) * this.unitPrice;
    },
    formatRupiah(num) {
        return 'Rp' + Number(num).toLocaleString('id-ID');
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

            <!-- Left Column: Product Details + Checkout Inputs (8 cols) -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- 0. CARD DETAIL & SPESIFIKASI PRODUK LENGKAP -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
                    <div class="flex flex-col sm:flex-row gap-6 items-start">
                        <!-- Foto Produk -->
                        <div class="w-full sm:w-48 sm:h-48 aspect-video sm:aspect-square rounded-2xl bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0 relative group">
                            @if($item->thumbnail_url)
                                <img src="{{ $item->thumbnail_url }}" 
                                     alt="{{ $item->title }}" 
                                     loading="lazy" 
                                     decoding="async" 
                                     class="w-full h-full object-cover opacity-0 blur-xs transition-all duration-500"
                                     onload="this.classList.remove('opacity-0', 'blur-xs'); this.classList.add('opacity-100', 'blur-0');">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400 font-bold text-xs">
                                    No Image
                                </div>
                            @endif
                            <div class="absolute top-2 left-2">
                                <span class="px-2 py-0.5 rounded-lg bg-slate-900/85 backdrop-blur-sm text-white text-[10px] font-black uppercase">
                                    {{ $item->item_type->label() }}
                                </span>
                            </div>
                        </div>

                        <!-- Info Utama -->
                        <div class="flex-1 space-y-2.5">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-xs font-black uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg">
                                    {{ $item->tefaUnit->name ?? 'Unit TEFA' }}
                                </span>
                                <span class="text-xs font-bold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg">
                                    {{ $item->category->name ?? 'Kategori Umum' }}
                                </span>
                            </div>

                            <h2 class="text-xl sm:text-2xl font-black text-slate-900 leading-snug">
                                {{ $item->title }}
                            </h2>

                            <div class="pt-1">
                                <span class="text-[10px] font-bold text-slate-400 uppercase block mb-0.5">Harga Satuan</span>
                                <span class="text-2xl sm:text-3xl font-black text-indigo-600">
                                    Rp{{ number_format((float)$item->price, 0, ',', '.') }}
                                </span>
                            </div>

                            <!-- Status Stok -->
                            <div class="pt-1">
                                @if($item->item_type->value === 'jasa')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-xl">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Layanan Aktif — Siap Konsultasi & Nego
                                    </span>
                                @elseif(!$item->track_stock)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-xl">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        Stok Selalu Tersedia (Unlimited)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold {{ $item->stock > 0 ? 'text-emerald-700 bg-emerald-50' : 'text-red-700 bg-red-50' }} px-3 py-1 rounded-xl">
                                        <span class="w-2 h-2 rounded-full {{ $item->stock > 0 ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                        {{ $item->stock > 0 ? 'Stok Tersedia: ' . $item->stock . ' unit' : 'Stok Sedang Habis' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- DESKRIPSI LENGKAP PRODUK -->
                    <div class="pt-5 border-t border-slate-100 space-y-2">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-black uppercase tracking-wider text-slate-800 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Deskripsi & Rincian Produk
                            </h3>
                        </div>
                        <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-100 text-xs sm:text-sm text-slate-700 leading-relaxed font-normal whitespace-pre-line">
{{ trim($item->description) }}
                        </div>
                    </div>

                    <!-- SPESIFIKASI & DETAIL TAMBAHAN -->
                    <div class="pt-2">
                        <h3 class="text-xs font-black uppercase tracking-wider text-slate-800 mb-3">
                            Spesifikasi & Informasi Tambahan
                        </h3>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div class="p-3.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs space-y-1">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase">Tipe Penawaran</span>
                                <p class="text-xs font-bold text-slate-800">{{ $item->item_type->label() }}</p>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs space-y-1">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase">Kategori</span>
                                <p class="text-xs font-bold text-slate-800 truncate">{{ $item->category->name ?? 'Umum' }}</p>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs space-y-1">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase">Metode Serah</span>
                                <p class="text-xs font-bold text-slate-800">
                                    {{ $item->item_type->value === 'digital' ? 'Download Instan' : ($item->item_type->value === 'jasa' ? 'Pelaksanaan Jasa' : 'Kurir / Jemput') }}
                                </p>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs space-y-1">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase">
                                    {{ $item->item_type->value === 'digital' ? 'Format File' : 'Berat Pengiriman' }}
                                </span>
                                <p class="text-xs font-bold text-slate-800">
                                    @if($item->item_type->value === 'digital')
                                        Digital File (.zip)
                                    @elseif($item->weight_gram)
                                        {{ $item->weight_gram }} gram
                                    @else
                                        Standar Paket
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

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
                                    <span class="font-black text-sm text-purple-900"> Akses Unduhan Digital Instan</span>
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
                                    <span class="font-black text-sm text-emerald-900"> Layanan Jasa / Servis</span>
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
                                        <span class="font-bold text-xs text-slate-900"> Diantar Kurir</span>
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
                                        <span class="font-bold text-xs text-slate-900"> Jemput Sendiri di Workshop TEFA</span>
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
                                    <label for="shipping_address" class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Lengkap Pengantaran</label>
                                    <textarea id="shipping_address" name="shipping_address" rows="2" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan..." class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium {{ $errors->has('shipping_address') ? 'border-rose-400 bg-rose-50/40' : '' }}">{{ old('shipping_address') }}</textarea>
                                    @error('shipping_address')<p class="mt-1 text-[11px] font-semibold text-rose-600">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="shipping_city" class="block text-xs font-bold text-slate-700 uppercase mb-1">Kota / Kabupaten</label>
                                    <input id="shipping_city" type="text" name="shipping_city" value="{{ old('shipping_city') }}" placeholder="Contoh: Surabaya / Sidoarjo" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium {{ $errors->has('shipping_city') ? 'border-rose-400 bg-rose-50/40' : '' }}">
                                    @error('shipping_city')<p class="mt-1 text-[11px] font-semibold text-rose-600">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Notes Input -->
                    <div class="pt-2">
                        <label for="notes" class="block text-xs font-bold text-slate-700 uppercase mb-1">Catatan Tambahan untuk Penjual (Opsional)</label>
                        <input id="notes" type="text" name="notes" value="{{ old('notes') }}" maxlength="500" placeholder="Contoh: Tolong dikemas dengan rapi / konfirmasi via WA..." class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                    </div>
                </div>

            </div>

            <!-- Right Column: Order Summary & Quantity Counter (4 cols) -->
            <div class="lg:col-span-4 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6 sticky top-28">
                <h3 class="font-black text-base text-slate-900 pb-3 border-b border-slate-100 flex items-center justify-between">
                    <span>Ringkasan Tagihan</span>
                    <span class="text-xs font-bold text-indigo-600">{{ $item->item_type->label() }}</span>
                </h3>

                <div class="flex items-start gap-3.5">
                    <div class="w-16 h-16 rounded-2xl bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0">
                        @if($item->thumbnail_url)
                            <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400 font-bold text-[10px]">No Image</div>
                        @endif
                    </div>
                    <div class="space-y-0.5 min-w-0 flex-1">
                        <span class="text-[10px] font-bold text-indigo-600 uppercase truncate block">{{ $item->tefaUnit->name ?? 'TEFA Unit' }}</span>
                        <h4 class="font-bold text-xs text-slate-900 leading-snug line-clamp-2">{{ $item->title }}</h4>
                        <p class="font-black text-sm text-slate-900 pt-0.5" x-text="formatRupiah(unitPrice)"></p>
                    </div>
                </div>

                <!-- QUANTITY COUNTER (RESPONSIVE & ANTI-CLIPPING FOR MULTI-DIGITS) -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="block text-xs font-bold text-slate-800">Jumlah Pembelian</span>
                        <span class="text-[10px] font-bold {{ !$item->track_stock || $item->stock > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ !$item->track_stock ? 'Stok unlimited' : ($item->stock > 0 ? 'Sisa: ' . $item->stock : 'Stok habis') }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <span class="text-[11px] text-slate-500">Kuantitas Item:</span>
                        
                        <div class="inline-flex items-center gap-1.5 p-1 bg-white rounded-2xl border border-slate-200 shadow-xs">
                            <!-- Tombol Minus -->
                            <button type="button" 
                                    aria-label="Kurangi jumlah" @click="if(quantity > 1) quantity--" 
                                    class="w-8 h-8 rounded-xl bg-slate-50 hover:bg-indigo-50 border border-slate-200/80 text-slate-700 hover:text-indigo-600 font-black text-base flex items-center justify-center transition cursor-pointer select-none">
                                −
                            </button>
                            
                            <!-- Input Angka (Lebar cukup, angka terlihat jelas walau > 1 digit, no spin clipping) -->
                            <input type="number" 
                                   name="quantity" aria-label="Jumlah pembelian" 
                                   x-model.number="quantity" 
                                   min="1" 
                                   max="{{ $item->track_stock ? max(1, (int) $item->stock) : 999 }}" 
                                   class="w-16 sm:w-20 text-center text-sm font-black text-slate-900 py-1 px-1 bg-slate-50/50 rounded-xl border-0 focus:ring-2 focus:ring-indigo-600 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                            
                            <!-- Tombol Plus -->
                            <button type="button" 
                                    aria-label="Tambah jumlah" @click="if(!trackStock || quantity < stock) quantity++" 
                                    class="w-8 h-8 rounded-xl bg-slate-50 hover:bg-indigo-50 border border-slate-200/80 text-slate-700 hover:text-indigo-600 font-black text-base flex items-center justify-center transition cursor-pointer select-none">
                                +
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Calculation Breakdown -->
                <div class="space-y-2.5 text-xs pt-1">
                    <div class="flex items-center justify-between text-slate-600">
                        <span>Subtotal (<span x-text="quantity"></span> unit)</span>
                        <span class="font-bold text-slate-900" x-text="formatRupiah(subtotal)"></span>
                    </div>
                    <div class="flex items-center justify-between text-slate-600">
                        <span>Ongkos Kirim</span>
                        <span class="font-bold text-slate-900" x-show="fulfillment === 'delivery'">Disepakati via WA</span>
                        <span class="font-bold text-slate-900" x-show="fulfillment !== 'delivery'">Rp0</span>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-sm font-black text-slate-900">
                        <span>Total Pembayaran (Estimasi)</span>
                        <span class="text-indigo-600 text-lg" x-text="formatRupiah(subtotal)"></span>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" @disabled($item->track_stock && $item->stock < 1) class="w-full py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition transform hover:-translate-y-0.5 cursor-pointer">
                    {{ !$item->track_stock || $item->stock > 0 ? 'Buat Pesanan & Hubungi Admin via WA →' : 'Produk Sedang Habis' }}
                </button>

                <p class="text-[10px] text-slate-400 text-center leading-relaxed">
                    🛡️ Transaksi resmi Teaching Factory & diverifikasi langsung oleh sekolah.
                </p>
            </div>

        </form>

    </div>
</div>
@endsection
