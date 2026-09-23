<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">{{ $managedUnit->name ?? 'Teaching Factory' }}</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-0.5">
                    Kelola Katalog Produk & Layanan
                </h2>
            </div>
            <button @click="$dispatch('open-add-product-modal')" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-600/20 transition flex items-center gap-1.5 self-start sm:self-auto cursor-pointer">
                <span>+ Tambah Produk / Jasa</span>
            </button>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen" x-data="{ 
        editModalOpen: false, 
        addModalOpen: false,
        activeProduct: { id: '', title: '', category_ids: [], item_type: 'produk', fulfillment_type: 'both', track_stock: true, stock: 10, weight_gram: '', digital_file_url: '', thumbnail_url: '', description: '', status: 'published' } 
    }" @open-add-product-modal.window="addModalOpen = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Flash Message -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-sm">
                    <span>✓ {{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-bold space-y-1 shadow-sm">
                    <p class="font-extrabold text-sm">Gagal Menyimpan:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-[11px] text-red-700 font-normal">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <a href="{{ route('admin.products.index') }}" class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-indigo-500 transition space-y-1 {{ !request('type') ? 'ring-2 ring-indigo-600' : '' }}">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Semua Katalog</span>
                    <p class="text-xl font-black text-slate-900">{{ $stats['total'] }}</p>
                </a>
                <a href="{{ route('admin.products.index', ['type' => 'produk']) }}" class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-indigo-500 transition space-y-1 {{ request('type') === 'produk' ? 'ring-2 ring-indigo-600' : '' }}">
                    <span class="text-[10px] font-bold text-indigo-600 uppercase">📦 Produk Fisik</span>
                    <p class="text-xl font-black text-indigo-600">{{ $stats['physical'] }}</p>
                </a>
                <a href="{{ route('admin.products.index', ['type' => 'jasa']) }}" class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-indigo-500 transition space-y-1 {{ request('type') === 'jasa' ? 'ring-2 ring-indigo-600' : '' }}">
                    <span class="text-[10px] font-bold text-emerald-600 uppercase">🛠️ Jasa & Digital</span>
                    <p class="text-xl font-black text-emerald-600">{{ $stats['service_digital'] }}</p>
                </a>
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Tayang Publik</span>
                    <p class="text-xl font-black text-slate-900">{{ $stats['published'] }}</p>
                </div>
            </div>

            <!-- Products Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="font-black text-base text-slate-900">Daftar Produk & Layanan Jurusan</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Kelola tipe produk fisik (kirim/ambil di workshop) atau produk digital (download instan).</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="button" @click="addModalOpen = true" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition flex items-center gap-1 cursor-pointer">
                            <span>+ Tambah Baru</span>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider text-[10px] border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">Produk / Layanan</th>
                                <th class="px-6 py-3.5">Tipe & Opsi Penerimaan</th>
                                <th class="px-6 py-3.5">Harga</th>
                                <th class="px-6 py-3.5 text-center">Stok</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse($products as $product)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0">
                                                <img src="{{ $product->thumbnail_url }}" alt="{{ $product->title }}" class="w-full h-full object-cover">
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-900 text-sm line-clamp-1">{{ $product->title }}</p>
                                                <span class="text-[10px] text-indigo-600 font-bold">{{ $product->categories->pluck('name')->join(', ') ?: ($product->category->name ?? 'Umum') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            @if($product->item_type->value === 'produk')
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 font-bold text-[10px]">
                                                    📦 Produk Fisik
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-bold text-[10px]">
                                                    🛠️ Jasa / Digital
                                                </span>
                                            @endif

                                            <div class="text-[10px] text-slate-400">
                                                @if($product->fulfillment_type === 'both')
                                                    🛵 Dikirim & 🏢 Ambil di Workshop
                                                @elseif($product->fulfillment_type === 'shipping_only')
                                                    🛵 Khusus Dikirim Kurir
                                                @elseif($product->fulfillment_type === 'pickup_only')
                                                    🏢 Khusus Ambil di Workshop
                                                @elseif($product->fulfillment_type === 'digital_download')
                                                    💻 Download File Digital
                                                @else
                                                    🛠️ Layanan Booking
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-black text-slate-900 text-sm">
                                        Rp{{ number_format((float)$product->price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-slate-800">
                                        @if($product->item_type->value === 'jasa' || !$product->track_stock)
                                            <span class="text-emerald-600">Unlimited</span>
                                        @else
                                            {{ $product->stock }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($product->status->value === 'published')
                                            <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[10px]">
                                                Tayang
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 font-bold text-[10px]">
                                                Draft
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('order.checkout', $product->slug) }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] transition">
                                                Test Beli
                                            </a>
                                            <button type="button" @click="activeProduct = {
                                                id: '{{ $product->id }}',
                                                title: '{{ addslashes($product->title) }}',
                                                category_ids: @js($product->categories->pluck('id')->values()->all() ?: ($product->category_id ? [$product->category_id] : [''])),
                                                item_type: '{{ $product->item_type->value }}',
                                                fulfillment_type: '{{ $product->fulfillment_type }}',
                                                price: '{{ (int)$product->price }}',
                                                track_stock: {{ $product->track_stock ? 'true' : 'false' }},
                                                stock: '{{ $product->stock ?? '' }}',
                                                weight_gram: '{{ $product->weight_gram ?? '' }}',
                                                digital_file_url: '{{ addslashes($product->digital_file_url ?? '') }}',
                                                thumbnail_url: '{{ addslashes($product->thumbnail_url) }}',
                                                description: '{{ addslashes($product->description ?? '') }}',
                                                status: '{{ $product->status->value }}'
                                            }; editModalOpen = true;" class="px-2.5 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-[11px] transition cursor-pointer">
                                                Edit
                                            </button>
                                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus item ini?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2.5 py-1 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 font-bold text-[11px] transition cursor-pointer">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">Belum ada item produk di unit ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-slate-100">
                    {{ $products->links() }}
                </div>
            </div>

        </div>

        <!-- Add Product Modal -->
        <div x-show="addModalOpen" style="display:none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" @click="addModalOpen = false"></div>
                
                <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full p-6 sm:p-8 space-y-6" x-data="{ currentType: 'produk', currentFulfillment: 'both', trackStock: true, categoryRows: [''] }">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="font-black text-base text-slate-900">Tambah Item Produk / Jasa Baru</h3>
                        <button type="button" @click="addModalOpen = false" class="text-slate-400 hover:text-slate-600 font-bold text-xl cursor-pointer">×</button>
                    </div>

                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Produk / Layanan</label>
                            <input type="text" name="title" required placeholder="Contoh: Roti Hampers Artisan / Jasa Pembuatan Website" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase">Kategori</label>
                                    <button type="button" @click="categoryRows.push('')" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800">+ Tambah kategori</button>
                                </div>
                                <template x-for="(category, index) in categoryRows" :key="index">
                                    <div class="flex items-center gap-2 mb-2">
                                        <select name="category_ids[]" x-model="categoryRows[index]" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-bold">
                                            <option value="">-- Pilih Kategori --</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" x-show="categoryRows.length > 1" @click="categoryRows.splice(index, 1)" class="text-red-500 hover:text-red-700 font-bold text-lg" title="Hapus kategori">×</button>
                                    </div>
                                </template>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tipe Produk / Layanan</label>
                                <select name="item_type" x-model="currentType" required class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-bold">
                                    <option value="produk">📦 Produk Fisik (Antar / Jemput)</option>
                                    <option value="digital">💻 Produk Digital (Auto Payment Gateway)</option>
                                    <option value="jasa">🛠️ Layanan Jasa (Nego Admin WA)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div x-show="currentType === 'produk'" x-cloak>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Metode Pengambilan / Penerimaan</label>
                                <select name="fulfillment_type" x-model="currentFulfillment" :disabled="currentType !== 'produk'" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-bold">
                                    <option value="both">🛵 Bisa Diantar & 🏢 Ambil di Workshop</option>
                                    <option value="pickup_only">🏢 Khusus Ambil di Workshop TEFA</option>
                                    <option value="shipping_only">🛵 Khusus Dikirim Kurir</option>
                                    <option value="digital_download">💻 Download Aset / File Digital</option>
                                    <option value="service_booking">🛠️ Booking Layanan Jasa</option>
                                </select>
                            </div>

                            <div x-show="currentType !== 'produk'" x-cloak class="p-3 rounded-xl bg-slate-50 text-[11px] text-slate-500 font-medium">
                                <span x-show="currentType === 'digital'">Produk digital memakai akses download dan tidak memerlukan pengantaran.</span>
                                <span x-show="currentType === 'jasa'">Layanan jasa memakai konsultasi/booking, tanpa pengantaran barang.</span>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Harga Satuan (Rp)</label>
                                <input type="number" name="price" required placeholder="50000" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div x-show="currentType === 'produk'" x-cloak>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Stok Tersedia</label>
                                <label class="flex items-center gap-2 mb-2 text-[11px] font-bold text-slate-600">
                                    <input type="checkbox" name="track_stock" value="1" checked x-model="trackStock" class="rounded text-indigo-600"> Stok terbatas
                                </label>
                                <input type="number" name="stock" value="10" min="0" :disabled="!trackStock" :required="trackStock" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                <p class="text-[10px] text-slate-400 mt-1">Matikan untuk stok unlimited.</p>
                            </div>
                            <div x-show="currentType === 'produk'" x-cloak>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Berat Barang (Gram)</label>
                                <input type="number" name="weight_gram" min="0" :disabled="currentType !== 'produk'" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                <p class="text-[10px] text-slate-400 mt-1">Opsional, hanya untuk barang fisik.</p>
                            </div>
                        </div>

                        <!-- Digital File URL (Shown if digital) -->
                        <div x-show="currentType === 'digital'" x-cloak>
                            <label class="block text-xs font-bold text-indigo-700 uppercase mb-1">Link Download Aset Digital (Google Drive / GitHub / ZIP)</label>
                            <input type="url" name="digital_file_url" :disabled="currentType !== 'digital'" :required="currentType === 'digital'" placeholder="https://drive.google.com/..." class="w-full rounded-xl border-indigo-200 bg-indigo-50/50 focus:border-indigo-600 text-xs font-medium">
                            <p class="text-[10px] text-slate-400 mt-1">Link ini akan otomatis terbuka untuk pembeli setelah pembayaran terkonfirmasi.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Foto Thumbnail</label>
                            <input type="file" name="thumbnail_file" accept="image/jpeg,image/png,image/webp" required class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                            <p class="text-[10px] text-slate-400 mt-1">JPG, PNG, atau WEBP. Maksimal 5 MB.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi Lengkap Produk</label>
                            <textarea name="description" rows="3" placeholder="Jelaskan spesifikasi produk, keunggulan, bahan baku, atau ketentuan layanan..." class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium leading-relaxed"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status Publikasi</label>
                            <select name="status" required class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-bold">
                                <option value="published">Tayang di Katalog Publik</option>
                                <option value="draft">Simpan Sebagai Draft</option>
                            </select>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-2 border-t border-slate-100">
                            <button type="button" @click="addModalOpen = false" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold cursor-pointer">Batal</button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md cursor-pointer">Simpan Produk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Product Modal -->
        <div x-show="editModalOpen" style="display:none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" @click="editModalOpen = false"></div>
                
                <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full p-6 sm:p-8 space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="font-black text-base text-slate-900">Edit Item Katalog</h3>
                        <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 font-bold text-xl cursor-pointer">×</button>
                    </div>

                    <form :action="'{{ url('admin/products') }}/' + activeProduct.id" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Produk / Layanan</label>
                            <input type="text" name="title" x-model="activeProduct.title" required class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase">Kategori</label>
                                    <button type="button" @click="activeProduct.category_ids.push('')" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800">+ Tambah kategori</button>
                                </div>
                                <template x-for="(category, index) in activeProduct.category_ids" :key="index">
                                    <div class="flex items-center gap-2 mb-2">
                                        <select name="category_ids[]" x-model="activeProduct.category_ids[index]" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-bold">
                                            <option value="">-- Pilih Kategori --</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" x-show="activeProduct.category_ids.length > 1" @click="activeProduct.category_ids.splice(index, 1)" class="text-red-500 hover:text-red-700 font-bold text-lg" title="Hapus kategori">×</button>
                                    </div>
                                </template>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tipe Item</label>
                                <select name="item_type" x-model="activeProduct.item_type" required class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-bold">
                                    <option value="produk">📦 Produk Fisik (Antar / Jemput)</option>
                                    <option value="digital">💻 Produk Digital (Auto Payment Gateway)</option>
                                    <option value="jasa">🛠️ Layanan Jasa (Nego Admin WA)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div x-show="activeProduct.item_type === 'produk'" x-cloak>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Metode Pengambilan</label>
                                <select name="fulfillment_type" x-model="activeProduct.fulfillment_type" :disabled="activeProduct.item_type !== 'produk'" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-bold">
                                    <option value="both">🛵 Bisa Diantar & 🏢 Ambil di Workshop</option>
                                    <option value="pickup_only">🏢 Khusus Ambil di Workshop TEFA</option>
                                    <option value="shipping_only">🛵 Khusus Dikirim Kurir</option>
                                    <option value="digital_download">💻 Download Aset / File Digital</option>
                                    <option value="service_booking">🛠️ Booking Layanan Jasa</option>
                                </select>
                            </div>

                            <div x-show="activeProduct.item_type !== 'produk'" x-cloak class="p-3 rounded-xl bg-slate-50 text-[11px] text-slate-500 font-medium">
                                <span x-show="activeProduct.item_type === 'digital'">Produk digital memakai akses download dan tidak memerlukan pengantaran.</span>
                                <span x-show="activeProduct.item_type === 'jasa'">Layanan jasa memakai konsultasi/booking, tanpa pengantaran barang.</span>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Harga Satuan (Rp)</label>
                                <input type="number" name="price" x-model="activeProduct.price" required class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div x-show="activeProduct.item_type === 'produk'" x-cloak>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Stok</label>
                                <label class="flex items-center gap-2 mb-2 text-[11px] font-bold text-slate-600">
                                    <input type="checkbox" name="track_stock" value="1" x-model="activeProduct.track_stock" class="rounded text-indigo-600"> Stok terbatas
                                </label>
                                <input type="number" name="stock" x-model="activeProduct.stock" min="0" :disabled="!activeProduct.track_stock" :required="activeProduct.track_stock" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                <p class="text-[10px] text-slate-400 mt-1">Matikan untuk stok unlimited.</p>
                            </div>
                            <div x-show="activeProduct.item_type === 'produk'" x-cloak>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Berat (Gram)</label>
                                <input type="number" name="weight_gram" x-model="activeProduct.weight_gram" min="0" :disabled="activeProduct.item_type !== 'produk'" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                <p class="text-[10px] text-slate-400 mt-1">Opsional, hanya untuk barang fisik.</p>
                            </div>
                        </div>

                        <div x-show="activeProduct.item_type === 'digital'" x-cloak>
                            <label class="block text-xs font-bold text-indigo-700 uppercase mb-1">Link File / Download Aset Digital</label>
                            <input type="url" name="digital_file_url" x-model="activeProduct.digital_file_url" :disabled="activeProduct.item_type !== 'digital'" :required="activeProduct.item_type === 'digital'" placeholder="https://drive.google.com/..." class="w-full rounded-xl border-indigo-200 bg-indigo-50/50 focus:border-indigo-600 text-xs font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Thumbnail Foto</label>
                            <input type="file" name="thumbnail_file" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                            <p class="text-[10px] text-slate-400 mt-1">Kosongkan jika tidak ingin mengganti gambar. Maksimal 5 MB.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi</label>
                            <textarea name="description" rows="3" x-model="activeProduct.description" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium leading-relaxed"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status Publikasi</label>
                            <select name="status" x-model="activeProduct.status" required class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-bold">
                                <option value="published">Tayang di Katalog Publik</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-2 border-t border-slate-100">
                            <button type="button" @click="editModalOpen = false" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold cursor-pointer">Batal</button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md cursor-pointer">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>