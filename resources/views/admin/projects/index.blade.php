<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">{{ $managedUnit->name ?? 'Teaching Factory' }}</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-0.5">
                    Riwayat & Monitoring Proyek
                </h2>
            </div>
            <a href="{{ route('admin.projects.import-wa.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-600/20 transition flex items-center gap-1.5 self-start sm:self-auto">
                <span>🤖 + Proyek Baru via AI (WA)</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen" x-data="{ 
        editModalOpen: false, 
        activeProject: { id: '', title: '', client_name: '', client_contact: '', description: '', final_price: '', deadline: '', status: 'active' } 
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Flash Message -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between">
                    <span>✓ {{ session('success') }}</span>
                </div>
            @endif

            <!-- Quick Status Filters -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <a href="{{ route('admin.projects.index') }}" class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-indigo-500 transition space-y-1 {{ !request('status') ? 'ring-2 ring-indigo-600' : '' }}">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Semua Proyek</span>
                    <p class="text-xl font-black text-slate-900">{{ $stats['total'] ?? 0 }}</p>
                </a>
                <a href="{{ route('admin.projects.index', ['status' => 'active']) }}" class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-indigo-500 transition space-y-1 {{ request('status') === 'active' ? 'ring-2 ring-indigo-600' : '' }}">
                    <span class="text-[10px] font-bold text-emerald-600 uppercase">Sedang Berjalan</span>
                    <p class="text-xl font-black text-emerald-600">{{ $stats['active'] ?? 0 }}</p>
                </a>
                <a href="{{ route('admin.projects.index', ['status' => 'draft']) }}" class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-indigo-500 transition space-y-1 {{ request('status') === 'draft' ? 'ring-2 ring-indigo-600' : '' }}">
                    <span class="text-[10px] font-bold text-amber-500 uppercase">Draft / Rancangan</span>
                    <p class="text-xl font-black text-amber-500">{{ $stats['draft'] ?? 0 }}</p>
                </a>
                <a href="{{ route('admin.projects.index', ['status' => 'completed']) }}" class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-indigo-500 transition space-y-1 {{ request('status') === 'completed' ? 'ring-2 ring-indigo-600' : '' }}">
                    <span class="text-[10px] font-bold text-slate-500 uppercase">Selesai Tuntas</span>
                    <p class="text-xl font-black text-slate-900">{{ $stats['completed'] ?? 0 }}</p>
                </a>
            </div>

            <!-- Project List Table -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="font-black text-base text-slate-900">Daftar Proyek & Progres Kerja</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Pantau status pengerjaan, edit rincian proyek, atau atur harga kesepakatan.</p>
                    </div>

                    <!-- Search Filter -->
                    <form action="{{ route('admin.projects.index') }}" method="GET" class="flex items-center gap-2">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul proyek / klien..." class="rounded-xl border-slate-200 text-xs py-2 px-3 focus:ring-indigo-600 focus:border-indigo-600">
                        <button type="submit" class="px-3 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold">Cari</button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider text-[10px] border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">Judul Proyek & Klien</th>
                                <th class="px-6 py-3.5">Nilai Kesepakatan</th>
                                <th class="px-6 py-3.5 text-center">Sub-Tugas Siswa</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse($projects as $project)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            <a href="{{ route('admin.projects.show', $project->id) }}" class="font-bold text-slate-900 text-sm hover:text-indigo-600 transition block">
                                                {{ $project->title }}
                                            </a>
                                            <p class="text-slate-500 text-[11px] line-clamp-1 max-w-sm">{{ $project->description }}</p>
                                            <div class="flex items-center gap-2 text-[10px] text-slate-400">
                                                <span>Klien: <strong class="text-slate-600">{{ $project->client_name }}</strong></span>
                                                @if($project->client_contact)
                                                    <span>({{ $project->client_contact }})</span>
                                                @endif
                                                <span>•</span>
                                                <span>{{ $project->created_at?->format('d M Y') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-black text-slate-900 text-sm">
                                        @if($project->final_price)
                                            Rp{{ number_format((float)$project->final_price, 0, ',', '.') }}
                                        @else
                                            <span class="text-slate-400 italic text-xs font-normal">Belum diset</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $totalTasks = $project->tasks->count();
                                            $doneTasks = $project->tasks->where('status', 'done')->count();
                                            $pct = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0;
                                        @endphp
                                        <div class="inline-flex flex-col items-center gap-1">
                                            <span class="font-bold text-slate-800 text-[11px]">{{ $doneTasks }}/{{ $totalTasks }} Selesai ({{ $pct }}%)</span>
                                            <div class="w-24 bg-slate-200 h-1.5 rounded-full overflow-hidden">
                                                <div class="bg-indigo-600 h-full rounded-full" style="width: {{ $pct }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($project->status->value === 'completed')
                                            <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-extrabold text-[10px] uppercase">✓ Selesai</span>
                                        @elseif($project->status->value === 'active')
                                            <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 font-extrabold text-[10px] uppercase">Sedang Berjalan</span>
                                        @elseif($project->status->value === 'draft')
                                            <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 font-extrabold text-[10px] uppercase">Draft</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full bg-red-50 text-red-700 font-extrabold text-[10px] uppercase">Dibatalkan</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button @click="activeProject = {
                                                id: '{{ $project->id }}',
                                                title: '{{ addslashes($project->title) }}',
                                                client_name: '{{ addslashes($project->client_name) }}',
                                                client_contact: '{{ addslashes($project->client_contact ?? '') }}',
                                                description: '{{ addslashes($project->description ?? '') }}',
                                                final_price: '{{ (int)$project->final_price }}',
                                                deadline: '{{ $project->deadline?->format('Y-m-d') ?? '' }}',
                                                status: '{{ $project->status->value }}'
                                            }; editModalOpen = true;" class="px-2.5 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-[11px] transition">
                                                Edit
                                            </button>
                                            <a href="{{ route('admin.projects.show', $project->id) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] transition">
                                                Detail
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada riwayat proyek di unit ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-slate-100">
                    {{ $projects->links() }}
                </div>
            </div>

        </div>

        <!-- Edit Project Modal -->
        <div x-show="editModalOpen" style="display:none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" @click="editModalOpen = false"></div>
                
                <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 sm:p-8 space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="font-black text-base text-slate-900">Edit Data & Deskripsi Proyek</h3>
                        <button @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 font-bold text-xl">×</button>
                    </div>

                    <form :action="'{{ url('admin/projects') }}/' + activeProject.id" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul Proyek</label>
                            <input type="text" name="title" x-model="activeProject.title" required class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Klien / Mitra</label>
                                <input type="text" name="client_name" x-model="activeProject.client_name" required class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">No. Kontak / WA</label>
                                <input type="text" name="client_contact" x-model="activeProject.client_contact" placeholder="0812..." class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi & Ruang Lingkup Proyek</label>
                            <textarea name="description" rows="3" x-model="activeProject.description" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium leading-relaxed"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Harga Kesepakatan (Rp)</label>
                                <input type="number" name="final_price" x-model="activeProject.final_price" placeholder="Contoh: 1500000" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tenggat Waktu (Deadline)</label>
                                <input type="date" name="deadline" x-model="activeProject.deadline" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status Proyek</label>
                            <select name="status" x-model="activeProject.status" required class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-bold">
                                <option value="draft">Draft (Rancangan)</option>
                                <option value="active">Aktif (Sedang Berjalan)</option>
                                <option value="completed">Completed (Selesai Tuntas)</option>
                                <option value="cancelled">Cancelled (Dibatalkan)</option>
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