<x-app-layout>
    <div x-data="{ 
        editModalOpen: false, 
        addModalOpen: false,
        activeWorker: { id: '', name: '', email: '', nisn: '', class_name: '', bio: '', skills_text: '' } 
    }">

        <!-- Custom Header inside Alpine scope -->
        <div class="bg-white border-b border-slate-200/80">
            <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">{{ $managedUnit->name ?? 'Teaching Factory' }}</span>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-0.5">
                        Kelola Data Siswa (Worker)
                    </h2>
                </div>
                <button type="button" @click="addModalOpen = true" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-600/20 transition flex items-center gap-1.5 self-start sm:self-auto cursor-pointer">
                    <span>+ Tambah Siswa Baru</span>
                </button>
            </div>
        </div>

        <div class="py-8 bg-slate-50 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                
                <!-- Flash Success Message -->
                @if(session('success'))
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-sm">
                        <span class="flex items-center gap-2">✓ {{ session('success') }}</span>
                    </div>
                @endif

                <!-- Validation Errors Alert -->
                @if($errors->any())
                    <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-bold space-y-1 shadow-sm">
                        <p class="font-extrabold text-sm">Gagal Menyimpan Data:</p>
                        <ul class="list-disc list-inside space-y-0.5 text-[11px] text-red-700 font-normal">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Worker Table Card -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h3 class="font-black text-base text-slate-900">Daftar Siswa & Keahlian</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Siswa ini akan diprioritaskan oleh AI ketika mendelegasikan tugas proyek yang cocok dengan kata kunci skill mereka.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-indigo-600">{{ $workers->count() }} Siswa Terdaftar</span>
                            <button type="button" @click="addModalOpen = true" class="px-3 py-1.5 bg-slate-900 hover:bg-indigo-600 text-white font-bold text-xs rounded-xl transition flex items-center gap-1 cursor-pointer">
                                <span>+ Tambah Siswa</span>
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left">
                            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider text-[10px] border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-3.5">Nama & NISN Siswa</th>
                                    <th class="px-6 py-3.5">Kelas / Rombel</th>
                                    <th class="px-6 py-3.5">Keahlian (Skills AI)</th>
                                    <th class="px-6 py-3.5 text-center">Tugas Aktif</th>
                                    <th class="px-6 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                @forelse($workers as $worker)
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-700 font-bold flex items-center justify-center text-sm flex-shrink-0">
                                                    {{ substr($worker->user->name ?? 'S', 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-slate-900 text-sm">{{ $worker->user->name ?? '-' }}</p>
                                                    <div class="flex items-center gap-2 text-[10px] text-slate-400 font-mono">
                                                        <span>NISN: {{ $worker->nisn ?? '-' }}</span>
                                                        <span>•</span>
                                                        <span>{{ $worker->user->email ?? '' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 font-bold text-slate-800 text-[11px]">
                                                {{ $worker->class_name }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 max-w-sm">
                                            <div class="flex flex-wrap gap-1">
                                                @forelse($worker->skills as $skill)
                                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold text-indigo-700 bg-indigo-50 border border-indigo-100">
                                                        {{ $skill->name }}
                                                    </span>
                                                @empty
                                                    <span class="text-slate-400 italic text-[10px]">Belum ada keahlian</span>
                                                @endforelse
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center font-bold text-slate-800">
                                            {{ $worker->tasks->where('status', '!=', 'done')->count() }} Tugas
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <button type="button" @click="activeWorker = {
                                                    id: '{{ $worker->id }}',
                                                    name: '{{ addslashes($worker->user->name ?? '') }}',
                                                    email: '{{ addslashes($worker->user->email ?? '') }}',
                                                    nisn: '{{ addslashes($worker->nisn ?? '') }}',
                                                    class_name: '{{ addslashes($worker->class_name ?? '') }}',
                                                    bio: '{{ addslashes($worker->bio ?? '') }}',
                                                    skills_text: '{{ addslashes($worker->skills->pluck('name')->implode(', ')) }}'
                                                }; editModalOpen = true;" class="px-2.5 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-[11px] transition cursor-pointer">
                                                    Edit
                                                </button>
                                                <form action="{{ route('admin.workers.destroy', $worker->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?')" class="inline">
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
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada data siswa di jurusan ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Add Worker Modal -->
            <div x-show="addModalOpen" style="display:none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" @click="addModalOpen = false"></div>
                    
                    <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 sm:p-8 space-y-6">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="font-black text-base text-slate-900">Tambah Siswa (Worker) Baru</h3>
                            <button type="button" @click="addModalOpen = false" class="text-slate-400 hover:text-slate-600 font-bold text-xl cursor-pointer">×</button>
                        </div>

                        <form action="{{ route('admin.workers.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap Siswa</label>
                                <input type="text" name="name" required placeholder="Contoh: Muhammad Fajar" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Login Siswa</label>
                                    <input type="email" name="email" required placeholder="siswa@tefa.id" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Password</label>
                                    <input type="password" name="password" required placeholder="Min 6 Karakter" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">NISN Siswa</label>
                                    <input type="text" name="nisn" placeholder="0051234567" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kelas / Rombel</label>
                                    <input type="text" name="class_name" required placeholder="Contoh: XII RPL 1" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                </div>
                            </div>

                            <!-- Free-Form Typed Skills -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Keahlian Utama</label>
                                <input type="text" name="skills_text" placeholder="Contoh: Laravel, Tailwind CSS, Vue.js, REST API, Figma" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                <p class="text-[10px] text-slate-400 mt-1">💡 Ketik keahlian dipisahkan dengan tanda koma ( , ). AI akan membaca kata kunci ini saat mendelegasikan tugas.</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Bio / Catatan Prestasi</label>
                                <textarea name="bio" rows="2" placeholder="Spesialisasi atau portofolio yang pernah dikerjakan..." class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium"></textarea>
                            </div>

                            <div class="pt-4 flex items-center justify-end gap-2 border-t border-slate-100">
                                <button type="button" @click="addModalOpen = false" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold cursor-pointer">Batal</button>
                                <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md cursor-pointer">Simpan Siswa</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Worker Modal -->
            <div x-show="editModalOpen" style="display:none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" @click="editModalOpen = false"></div>
                    
                    <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 sm:p-8 space-y-6">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="font-black text-base text-slate-900">Edit Data Siswa (Worker)</h3>
                            <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 font-bold text-xl cursor-pointer">×</button>
                        </div>

                        <form :action="'{{ url('admin/workers') }}/' + activeWorker.id" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap</label>
                                <input type="text" name="name" x-model="activeWorker.name" required class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Login</label>
                                    <input type="email" name="email" x-model="activeWorker.email" required class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Password Baru (Opsional)</label>
                                    <input type="password" name="password" placeholder="Kosongkan jika tidak ganti" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">NISN</label>
                                    <input type="text" name="nisn" x-model="activeWorker.nisn" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kelas</label>
                                    <input type="text" name="class_name" x-model="activeWorker.class_name" required class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                </div>
                            </div>

                            <!-- Free-form Typed Skills on Edit -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Keahlian (Skills AI)</label>
                                <input type="text" name="skills_text" x-model="activeWorker.skills_text" placeholder="Contoh: Laravel, Tailwind CSS, Vue.js, REST API" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                <p class="text-[10px] text-slate-400 mt-1">💡 Pisahkan dengan koma ( , ).</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Bio</label>
                                <textarea name="bio" rows="2" x-model="activeWorker.bio" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium"></textarea>
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
    </div>
</x-app-layout>