<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.projects.index') }}" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                    ←
                </a>
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Detail Proyek TEFA</span>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                        {{ $project->title }}
                    </h2>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.projects.import-wa.create', ['project_id' => $project->id]) }}" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-1.5">
                    <span>✦ Ekstrak Chat WA (AI)</span>
                </a>
                <button @click="$dispatch('open-edit-project-modal')" class="px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs rounded-xl transition flex items-center gap-1.5">
                    <span>✏️ Edit Judul & Rincian</span>
                </button>

                <!-- Quick Status Change Form -->
                <form action="{{ route('admin.projects.status.update', $project->id) }}" method="POST" class="flex items-center gap-1.5">
                    @csrf
                    @method('PATCH')
                    <select name="status" onchange="this.form.submit()" class="rounded-xl border-slate-200 text-xs font-bold py-2 pl-3 pr-8 text-slate-800 focus:ring-indigo-600 focus:border-indigo-600">
                        <option value="draft" {{ $project->status->value === 'draft' ? 'selected' : '' }}>Draft (Rancangan)</option>
                        <option value="active" {{ $project->status->value === 'active' ? 'selected' : '' }}>Aktif (Sedang Berjalan)</option>
                        <option value="completed" {{ $project->status->value === 'completed' ? 'selected' : '' }}>Completed (Selesai Tuntas)</option>
                        <option value="cancelled" {{ $project->status->value === 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                    </select>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen" x-data="{ editModalOpen: false }" @open-edit-project-modal.window="editModalOpen = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Flash Message -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between">
                    <span>✓ {{ session('success') }}</span>
                </div>
            @endif

            <!-- Project Overview Banner Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pb-6 border-b border-slate-100">
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase">Nama Klien / Mitra</span>
                        <p class="text-base font-black text-slate-900 mt-0.5">{{ $project->client_name }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <p class="text-xs text-slate-500 font-medium">{{ $project->client_contact ?? '-' }}</p>
                            @if($project->client_contact && strlen(preg_replace('/[^0-9]/', '', $project->client_contact)) >= 10)
                                @php
                                    $phone = preg_replace('/[^0-9]/', '', $project->client_contact);
                                    if (substr($phone, 0, 1) === '0') $phone = '62' . substr($phone, 1);
                                    $waUrl = "https://wa.me/{$phone}?text=" . urlencode("Halo {$project->client_name}, ini Admin TEFA. Saya ingin mengobrol mengenai penawaran proyek '{$project->title}'...");
                                @endphp
                                <a href="{{ $waUrl }}" target="_blank" class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-700 font-bold text-[10px] hover:bg-emerald-200 transition inline-flex items-center gap-1">
                                    💬 Chat WA
                                </a>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase">Nilai Kesepakatan Akhir</span>
                        <p class="text-xl font-black text-indigo-600 mt-0.5">
                            Rp{{ number_format((float)$project->final_price, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-slate-400">Estimasi Awal: Rp{{ number_format((float)$project->estimated_price, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase">Tenggat Waktu (Deadline)</span>
                        <p class="text-base font-black text-slate-900 mt-0.5">{{ $project->deadline?->format('d F Y') ?? 'Fleksibel' }}</p>
                        <p class="text-xs text-slate-400">Dibuat: {{ $project->created_at?->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Deskripsi & Ruang Lingkup Proyek</h4>
                        <button @click="editModalOpen = true" class="text-indigo-600 font-bold text-xs hover:underline">Edit Deskripsi</button>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-700 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100 whitespace-pre-line">
                        {{ $project->description }}
                    </p>
                </div>
            </div>

            <!-- Sub-Tasks Breakdown -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="font-black text-base text-slate-900">Daftar Sub-Tugas Siswa (Task Breakdown)</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Tugas yang dibagikan kepada siswa beserta bukti progres kerja.</p>
                    </div>
                    <span class="text-xs font-bold text-indigo-600">{{ $project->tasks->count() }} Tugas</span>
                </div>

                <div class="space-y-4">
                    @forelse($project->tasks as $task)
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-8 h-8 rounded-xl bg-indigo-600 text-white font-bold flex items-center justify-center text-xs">
                                        {{ $loop->iteration }}
                                    </span>
                                    <div>
                                        <h4 class="font-bold text-sm text-slate-900">{{ $task->title }}</h4>
                                        <p class="text-[11px] text-slate-400">Prioritas: <strong class="text-slate-600">{{ $task->priority->label() }}</strong></p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase {{ $task->status->value === 'done' ? 'bg-emerald-100 text-emerald-800' : 'bg-indigo-100 text-indigo-800' }}">
                                    {{ $task->status->value }}
                                </span>
                            </div>

                            <p class="text-xs text-slate-600 leading-relaxed">
                                {{ $task->description }}
                            </p>

                            <div class="pt-3 border-t border-slate-200/60 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs text-slate-500">
                                <div>
                                    <span>Pekerja Ditugaskan:</span>
                                    <div class="space-y-1 mt-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-4 h-4 rounded-full bg-indigo-500 text-white flex items-center justify-center text-[8px]">👑</span>
                                            <strong class="text-slate-800">{{ $task->leader->user->name ?? 'Belum Ditugaskan' }}</strong>
                                            @if($task->leader)
                                                <span class="text-[10px] text-slate-400">({{ $task->leader->class_name }})</span>
                                            @endif
                                        </div>
                                        @if($task->members->count() > 0)
                                            <div class="pl-5 flex flex-wrap gap-1">
                                                @foreach($task->members as $member)
                                                    <span class="text-[9px] bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded border border-slate-200">{{ explode(' ', $member->user->name)[0] }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                @if($task->status->value === 'review')
                                    <form action="{{ route('admin.tasks.approve', $task->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" onclick="return confirm('ACC tugas ini menjadi Selesai (100%)?')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[10px] uppercase tracking-wider rounded-lg shadow-sm">
                                            ? ACC / Selesai
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                                @if($task->proof_file_url)
                                    <a href="{{ $task->proof_file_url }}" target="_blank" class="text-indigo-600 font-bold hover:underline flex items-center gap-1">
                                        <span>📎 Lihat Bukti Pengerjaan</span>
                                    </a>
                                @endif
                                @if($task->status->value === 'review')
                                    <form action="{{ route('admin.tasks.approve', $task->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" onclick="return confirm('ACC tugas ini menjadi Selesai (100%)?')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[10px] uppercase tracking-wider rounded-lg shadow-sm">
                                            ? ACC / Selesai
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic text-center py-8">Belum ada sub-tugas yang terdaftar untuk proyek ini.</p>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Edit Modal in Show Page -->
        <div x-show="editModalOpen" style="display:none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" @click="editModalOpen = false"></div>
                
                <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 sm:p-8 space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="font-black text-base text-slate-900">Edit Data & Deskripsi Proyek</h3>
                        <button @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 font-bold text-xl">&times;</button>
                    </div>

                    <form action="{{ route('admin.projects.update', $project->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul Proyek</label>
                            <input type="text" name="title" value="{{ $project->title }}" required class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Klien / Mitra</label>
                                <input type="text" name="client_name" value="{{ $project->client_name }}" required class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">No. Kontak / WA</label>
                                <input type="text" name="client_contact" value="{{ $project->client_contact }}" placeholder="0812..." class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi & Ruang Lingkup Proyek</label>
                            <textarea name="description" rows="4" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium leading-relaxed">{{ $project->description }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Harga Kesepakatan (Rp)</label>
                                <input type="number" name="final_price" value="{{ (int)$project->final_price }}" placeholder="Contoh: 1500000" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tenggat Waktu (Deadline)</label>
                                <input type="date" name="deadline" value="{{ $project->deadline?->format('Y-m-d') }}" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status Proyek</label>
                            <select name="status" required class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-bold">
                                <option value="draft" {{ $project->status->value === 'draft' ? 'selected' : '' }}>Draft (Rancangan)</option>
                                <option value="active" {{ $project->status->value === 'active' ? 'selected' : '' }}>Aktif (Sedang Berjalan)</option>
                                <option value="completed" {{ $project->status->value === 'completed' ? 'selected' : '' }}>Completed (Selesai Tuntas)</option>
                                <option value="cancelled" {{ $project->status->value === 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
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

