<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Manajemen Master</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-0.5">
                    Kelola Unit Teaching Factory (Jurusan)
                </h2>
            </div>
            <button @click="$dispatch('open-add-unit-modal')" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-600/20 transition flex items-center gap-1.5 self-start sm:self-auto">
                <span>+ Tambah Unit Jurusan</span>
            </button>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen" x-data="{ 
        editModalOpen: false, 
        addModalOpen: false,
        activeEditUnit: { id: '', name: '', description: '', banner_url: '', logo_url: '', is_active: 1 } 
    }" @open-add-unit-modal.window="addModalOpen = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Flash Message -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between">
                    <span>✓ {{ session('success') }}</span>
                </div>
            @endif

            <!-- Unit List Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-black text-base text-slate-900">Daftar Seluruh Unit TEFA</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Edit profil, aktifkan/nonaktifkan unit, atau hapus jurusan.</p>
                    </div>
                    <span class="text-xs font-bold text-slate-400">{{ $units->count() }} Unit Terdaftar</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider text-[10px] border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">Unit Jurusan</th>
                                <th class="px-6 py-3.5">Deskripsi</th>
                                <th class="px-6 py-3.5 text-center">Katalog</th>
                                <th class="px-6 py-3.5 text-center">Siswa</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse($units as $unit)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center font-black text-indigo-600 text-xs overflow-hidden flex-shrink-0">
                                                @if($unit->logo_url)
                                                    <img src="{{ $unit->logo_url }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ substr($unit->name, 0, 1) }}
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-900 text-sm">{{ $unit->name }}</p>
                                                <p class="text-[10px] text-slate-400 font-mono">/tefa/{{ $unit->slug }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs">
                                        <p class="text-slate-500 line-clamp-2 text-xs leading-relaxed">
                                            {{ $unit->description }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-slate-800">
                                        {{ $unit->catalogItems->count() }}
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-slate-800">
                                        {{ $unit->workerProfiles->count() }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($unit->is_active)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold">
                                                Non-Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('tefa.storefront', $unit->slug) }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] transition">
                                                Storefront
                                            </a>
                                            <button @click="activeEditUnit = {
                                                id: '{{ $unit->id }}',
                                                name: '{{ addslashes($unit->name) }}',
                                                description: '{{ addslashes($unit->description) }}',
                                                banner_url: '{{ addslashes($unit->banner_url) }}',
                                                logo_url: '{{ addslashes($unit->logo_url) }}',
                                                is_active: {{ $unit->is_active ? 1 : 0 }}
                                            }; editModalOpen = true;" class="px-2.5 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-[11px] transition">
                                                Edit
                                            </button>
                                            <form action="{{ route('superadmin.units.destroy', $unit->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus unit TEFA ini? Seluruh data produk dan worker terkait akan terhapus.')" class="inline">
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
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">Belum ada unit TEFA yang dibuat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Add Unit Modal -->
        <div x-show="addModalOpen" style="display:none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" @click="addModalOpen = false"></div>
                
                <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 sm:p-8 space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="font-black text-base text-slate-900">Tambah Unit TEFA Baru</h3>
                        <button @click="addModalOpen = false" class="text-slate-400 hover:text-slate-600 font-bold text-xl">×</button>
                    </div>

                    <form action="{{ route('superadmin.units.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Unit / Jurusan</label>
                            <input type="text" name="name" required placeholder="Contoh: TEFA RPL Software House" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi Lengkap</label>
                            <textarea name="description" rows="3" placeholder="Profil keahlian dan bidang layanan jurusan ini..." class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Foto Banner</label>
                            <input type="file" name="banner_file" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-slate-500 file:mr-3 file:rounded-xl file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:font-bold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Logo Jurusan</label>
                            <input type="file" name="logo_file" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-slate-500 file:mr-3 file:rounded-xl file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:font-bold">
                        </div>

                        <div class="flex items-center gap-2 pt-2">
                            <input type="checkbox" name="is_active" value="1" checked id="add_active" class="rounded text-indigo-600 focus:ring-indigo-500">
                            <label for="add_active" class="text-xs font-bold text-slate-700">Status Aktif (Tampil di Katalog Publik)</label>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-2 border-t border-slate-100">
                            <button type="button" @click="addModalOpen = false" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold">Batal</button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md">Simpan Unit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Unit Modal -->
        <div x-show="editModalOpen" style="display:none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" @click="editModalOpen = false"></div>
                
                <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 sm:p-8 space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="font-black text-base text-slate-900">Edit Profil Unit TEFA</h3>
                        <button @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 font-bold text-xl">×</button>
                    </div>

                    <form :action="'{{ url('superadmin/units') }}/' + activeEditUnit.id" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Unit / Jurusan</label>
                            <input type="text" name="name" x-model="activeEditUnit.name" required class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi</label>
                            <textarea name="description" rows="3" x-model="activeEditUnit.description" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Ganti Banner Cover</label>
                            <input type="file" name="banner_file" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-slate-500 file:mr-3 file:rounded-xl file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:font-bold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Ganti Logo Jurusan</label>
                            <input type="file" name="logo_file" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-slate-500 file:mr-3 file:rounded-xl file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:font-bold">
                        </div>

                        <div class="flex items-center gap-2 pt-2">
                            <input type="checkbox" name="is_active" value="1" :checked="activeEditUnit.is_active" id="edit_active" class="rounded text-indigo-600 focus:ring-indigo-500">
                            <label for="edit_active" class="text-xs font-bold text-slate-700">Status Aktif</label>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-2 border-t border-slate-100">
                            <button type="button" @click="editModalOpen = false" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold">Batal</button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>