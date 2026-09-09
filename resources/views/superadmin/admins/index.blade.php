<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">User Management</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-0.5">
                    Kelola Akun Admin Jurusan
                </h2>
            </div>
            <button @click="$dispatch('open-add-admin-modal')" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-600/20 transition flex items-center gap-1.5 self-start sm:self-auto">
                <span>+ Tambah Admin Jurusan</span>
            </button>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen" x-data="{ 
        editModalOpen: false, 
        addModalOpen: false,
        activeEditAdmin: { id: '', name: '', email: '', tefa_unit_id: '' } 
    }" @open-add-admin-modal.window="addModalOpen = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Flash Message -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between">
                    <span>✓ {{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-bold flex items-center justify-between">
                    <span>⚠️ {{ session('error') }}</span>
                </div>
            @endif

            <!-- Admin Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-black text-base text-slate-900">Daftar Admin Penanggung Jawab Jurusan</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Setiap admin memiliki akses penuh ke katalog, pendelegasian AI WhatsApp, dan siswa di jurusannya.</p>
                    </div>
                    <span class="text-xs font-bold text-slate-400">{{ $admins->count() }} Pengurus</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider text-[10px] border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">Nama Admin</th>
                                <th class="px-6 py-3.5">Email Login</th>
                                <th class="px-6 py-3.5">Unit Jurusan yang Dikelola</th>
                                <th class="px-6 py-3.5">Tanggal Terdaftar</th>
                                <th class="px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse($admins as $adminUser)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-blue-600 text-white font-bold flex items-center justify-center text-xs flex-shrink-0">
                                                {{ substr($adminUser->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-900 text-sm">{{ $adminUser->name }}</p>
                                                <span class="text-[10px] text-blue-600 font-bold">Admin Jurusan</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-slate-600">
                                        {{ $adminUser->email }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($adminUser->managedUnits->isNotEmpty())
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 font-bold text-xs">
                                                 {{ $adminUser->managedUnits->first()->name }}
                                            </span>
                                        @else
                                            <span class="text-amber-500 font-bold italic">Belum dihubungkan ke unit</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-400">
                                        {{ $adminUser->created_at?->format('d M Y') ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button @click="activeEditAdmin = {
                                                id: '{{ $adminUser->id }}',
                                                name: '{{ addslashes($adminUser->name) }}',
                                                email: '{{ addslashes($adminUser->email) }}',
                                                tefa_unit_id: '{{ $adminUser->managedUnits->first()?->id ?? '' }}'
                                            }; editModalOpen = true;" class="px-2.5 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-[11px] transition">
                                                Edit
                                            </button>
                                            <form action="{{ route('superadmin.admins.destroy', $adminUser->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun Admin ini?')" class="inline">
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
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada akun Admin Jurusan yang dibuat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Add Admin Modal -->
        <div x-show="addModalOpen" style="display:none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" @click="addModalOpen = false"></div>
                
                <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 sm:p-8 space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="font-black text-base text-slate-900">Buat Akun Admin Jurusan Baru</h3>
                        <button @click="addModalOpen = false" class="text-slate-400 hover:text-slate-600 font-bold text-xl">×</button>
                    </div>

                    <form action="{{ route('superadmin.admins.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap & Gelar</label>
                            <input type="text" name="name" required placeholder="Contoh: Bpk. Hendra S.Kom" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Login</label>
                            <input type="email" name="email" required placeholder="admin.jurusan@tefa.id" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Password</label>
                            <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pilih Unit TEFA (Jurusan yang Dikelola)</label>
                            <select name="tefa_unit_id" required class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-bold">
                                <option value="">-- Pilih Unit TEFA --</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-2 border-t border-slate-100">
                            <button type="button" @click="addModalOpen = false" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold">Batal</button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md">Simpan Admin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Admin Modal -->
        <div x-show="editModalOpen" style="display:none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" @click="editModalOpen = false"></div>
                
                <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 sm:p-8 space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="font-black text-base text-slate-900">Edit Data Admin Jurusan</h3>
                        <button @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 font-bold text-xl">×</button>
                    </div>

                    <form :action="'{{ url('superadmin/admins') }}/' + activeEditAdmin.id" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap</label>
                            <input type="text" name="name" x-model="activeEditAdmin.name" required class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Login</label>
                            <input type="email" name="email" x-model="activeEditAdmin.email" required class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Password Baru (Kosongkan jika tidak diubah)</label>
                            <input type="password" name="password" placeholder="Biarkan kosong jika tidak ganti" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pilih Unit TEFA</label>
                            <select name="tefa_unit_id" x-model="activeEditAdmin.tefa_unit_id" required class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-bold">
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
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