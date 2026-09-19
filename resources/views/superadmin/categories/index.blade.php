<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Master Data</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-0.5">
                    Kelola Kategori Katalog
                </h2>
            </div>
            <span class="text-xs font-bold text-slate-500">
                Kategori ini tampil di sidebar & filter publik.
            </span>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Flash Message -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between">
                    <span>✓ {{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left Column: Add Category Form -->
                <div class="lg:col-span-4 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                    <div class="pb-3 border-b border-slate-100">
                        <h3 class="font-black text-base text-slate-900">+ Tambah Kategori Baru</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Buat kategori bidang untuk mengelompokkan produk & jasa.</p>
                    </div>

                    <form action="{{ route('superadmin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Kategori</label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Robotika & IoT" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                            @error('name')
                                <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tipe Standar</label>
                            <select name="type" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-bold">
                                <option value="jasa" @selected(old('type', 'jasa') === 'jasa')>🛠️ Layanan Jasa</option>
                                <option value="produk" @selected(old('type') === 'produk')>📦 Produk Fisik</option>
                                <option value="kegiatan" @selected(old('type') === 'kegiatan')>🎪 Event & Kegiatan</option>
                            </select>
                            @error('type')
                                <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Gambar Kategori</label>
                            <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="w-full rounded-xl border border-slate-200 text-xs file:mr-3 file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:font-bold">
                            <p class="text-[10px] text-slate-400 mt-1">JPG, PNG, atau WEBP. Maksimal 5 MB.</p>
                            @error('image')
                                <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs shadow-md shadow-indigo-600/20 transition">
                            Simpan Kategori
                        </button>
                    </form>
                </div>

                <!-- Right Column: Categories List Table -->
                <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="font-black text-base text-slate-900">Daftar Kategori Tersedia</h3>
                        <span class="text-xs font-bold text-indigo-600">{{ $categories->count() }} Kategori</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left">
                            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider text-[10px] border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-3.5">Gambar</th>
                                    <th class="px-6 py-3.5">Nama Kategori</th>
                                    <th class="px-6 py-3.5">Tipe Default</th>
                                    <th class="px-6 py-3.5 text-center">Jumlah Item Terkait</th>
                                    <th class="px-6 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                @forelse($categories as $category)
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="px-6 py-4">
                                            @if($category->image)
                                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200">
                                            @else
                                                <span class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-700 font-bold flex items-center justify-center text-xs">🏷️</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2.5">
                                                <span class="font-bold text-slate-900 text-sm">{{ $category->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 font-bold text-[10px] uppercase">
                                                {{ $category->type?->label() ?? 'Jasa' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center font-bold text-slate-800">
                                            {{ $category->catalog_items_count }} Item
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <details class="inline-block text-left align-middle mr-2" @if($errors->any() && old('editing_category_id') == $category->id) open @endif>
                                                <summary class="cursor-pointer px-2.5 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-bold text-[11px] transition list-none">Edit</summary>
                                                <div class="mt-3 w-72 max-w-[calc(100vw-3rem)] p-4 bg-white rounded-2xl border border-slate-200 shadow-xl">
                                                    <form action="{{ route('superadmin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="editing_category_id" value="{{ $category->id }}">
                                                        <input type="text" name="name" value="{{ old('editing_category_id') == $category->id ? old('name', $category->name) : $category->name }}" required class="w-full rounded-xl border-slate-200 text-xs">
                                                        @if($errors->any() && old('editing_category_id') == $category->id)
                                                            @error('name')
                                                                <p class="text-[10px] text-red-600">{{ $message }}</p>
                                                            @enderror
                                                        @endif
                                                        <select name="type" class="w-full rounded-xl border-slate-200 text-xs font-bold">
                                                            <option value="jasa" @selected((old('editing_category_id') == $category->id ? old('type') : $category->type?->value) === 'jasa')>Layanan Jasa</option>
                                                            <option value="produk" @selected((old('editing_category_id') == $category->id ? old('type') : $category->type?->value) === 'produk')>Produk Fisik</option>
                                                            <option value="kegiatan" @selected((old('editing_category_id') == $category->id ? old('type') : $category->type?->value) === 'kegiatan')>Event & Kegiatan</option>
                                                        </select>
                                                        @if($errors->any() && old('editing_category_id') == $category->id)
                                                            @error('type')
                                                                <p class="text-[10px] text-red-600">{{ $message }}</p>
                                                            @enderror
                                                        @endif
                                                        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="w-full text-[10px]">
                                                        <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs">Simpan Perubahan</button>
                                                    </form>
                                                </div>
                                            </details>
                                            <form action="{{ route('superadmin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2.5 py-1 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 font-bold text-[11px] transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada kategori.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>