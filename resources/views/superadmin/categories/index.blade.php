<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Master Data</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-0.5">
                    Kelola Kategori Katalog
                </h2>
            </div>
            <span class="text-xs font-bold text-red-600">
                * Kategori ini tampil di sidebar & filter publik.
            </span>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen" x-data="{
        editModalOpen: false,
        activeEditCategory: { id: '', name: '', type: 'jasa' }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Flash Message -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                        {{ session('success') }}
                    </span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left Column: Add Category Form -->
                <div class="lg:col-span-4 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                    <div class="pb-3 border-b border-slate-100">
                        <h3 class="font-black text-base text-slate-900">Tambah Kategori Baru</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Buat kategori bidang untuk mengelompokkan produk & jasa.</p>
                    </div>

                    <form action="{{ route('superadmin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Kategori</label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Robotika & IoT" class="w-full rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium py-2.5 px-3.5">
                            @error('name')
                                <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tipe Standar</label>
                            <select name="type" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-white focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 text-xs font-bold text-slate-700 py-2.5 px-3.5 shadow-sm transition cursor-pointer">
                                <option value="jasa" @selected(old('type', 'jasa') === 'jasa')>Layanan Jasa</option>
                                <option value="produk" @selected(old('type') === 'produk')>Produk Fisik</option>
                                <option value="kegiatan" @selected(old('type') === 'kegiatan')>Event & Kegiatan</option>
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
                                                <span class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41 11 3.83A2 2 0 0 0 9.59 3.24L3 3v6.59a2 2 0 0 0 .59 1.41l9.59 9.59a2 2 0 0 0 2.82 0l4.59-4.59a2 2 0 0 0 0-2.83z"/><circle cx="7.5" cy="7.5" r="1.5"/></svg>
                                                </span>
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
                                        <td class="px-6 py-4 text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end gap-2">
                                                <button type="button" @click="activeEditCategory = {
                                                    id: '{{ $category->id }}',
                                                    name: '{{ addslashes($category->name) }}',
                                                    type: '{{ $category->type?->value ?? 'jasa' }}'
                                                }; editModalOpen = true;" class="px-2.5 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-[11px] transition">
                                                    Edit
                                                </button>
                                                <form action="{{ route('superadmin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')" class="inline m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-2.5 py-1 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 font-bold text-[11px] transition">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
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

        <!-- Edit Category Modal -->
        <div x-show="editModalOpen" style="display:none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" @click="editModalOpen = false"></div>

                <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full p-6 sm:p-8 space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="font-black text-base text-slate-900">Edit Kategori Katalog</h3>
                        <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 font-bold text-xl leading-none">&times;</button>
                    </div>

                    <form :action="'{{ url('superadmin/categories') }}/' + activeEditCategory.id" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Kategori</label>
                            <input type="text" name="name" x-model="activeEditCategory.name" required class="w-full rounded-xl border border-slate-200 text-xs py-2.5 px-3.5 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tipe Default</label>
                            <select name="type" x-model="activeEditCategory.type" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-white text-xs font-bold py-2.5 px-3.5 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 cursor-pointer transition">
                                <option value="jasa">Layanan Jasa</option>
                                <option value="produk">Produk Fisik</option>
                                <option value="kegiatan">Event & Kegiatan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Ganti Gambar (Opsional)</label>
                            <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="w-full text-xs file:mr-3 file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:rounded-xl file:font-bold">
                            <p class="text-[10px] text-slate-400 mt-1">Biarkan kosong jika tidak ingin mengganti gambar.</p>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-2 border-t border-slate-100">
                            <button type="button" @click="editModalOpen = false" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 transition">Batal</button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>