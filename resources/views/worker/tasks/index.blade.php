<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Student Task Manager</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-0.5">
                    Daftar Tugas & Papan Kanban
                </h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('worker.dashboard') }}" class="px-3.5 py-2 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs rounded-xl shadow-sm transition">
                    ← Kembali ke Statistik
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-100 min-h-screen" x-data="{
        viewMode: 'kanban',
        uploadModalOpen: false,
        activeTask: { id: '', title: '', project: '', proof_notes: '', proof_url: '' },
        
        submitStatus(taskId, newStatus) {
            let form = document.getElementById('status-update-form');
            form.action = '{{ url('worker/tasks') }}/' + taskId + '/status';
            form.querySelector('[name=status]').value = newStatus;
            form.submit();
        },

        openUploadModal(taskData) {
            this.activeTask = taskData;
            this.uploadModalOpen = true;
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Flash Message -->
            @if (session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-sm">
                    <span>✓ {{ session('success') }}</span>
                </div>
            @endif

            <!-- Filter & View Switcher Bar -->
            <div class="bg-white rounded-3xl p-4 sm:p-5 border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                
                <!-- Priority Filters -->
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('worker.tasks.index') }}" class="px-3 py-1.5 rounded-xl font-bold text-xs transition {{ !request('priority') ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Semua ({{ $stats['total'] }})
                    </a>
                    <a href="{{ route('worker.tasks.index', ['priority' => 'high']) }}" class="px-3 py-1.5 rounded-xl font-bold text-xs transition flex items-center gap-1 {{ request('priority') === 'high' ? 'bg-red-600 text-white shadow-sm' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">
                        <span>⚡ Prioritas Tinggi ({{ $stats['high'] }})</span>
                    </a>
                    <a href="{{ route('worker.tasks.index', ['priority' => 'medium']) }}" class="px-3 py-1.5 rounded-xl font-bold text-xs transition {{ request('priority') === 'medium' ? 'bg-amber-500 text-white shadow-sm' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                        Prioritas Sedang
                    </a>
                    <a href="{{ route('worker.tasks.index', ['priority' => 'low']) }}" class="px-3 py-1.5 rounded-xl font-bold text-xs transition {{ request('priority') === 'low' ? 'bg-slate-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Prioritas Normal
                    </a>
                </div>

                <!-- View Mode Switcher -->
                <div class="flex items-center gap-2 self-end md:self-auto">
                    <div class="flex items-center p-1 rounded-2xl bg-slate-100 border border-slate-200 text-xs">
                        <button type="button" @click="viewMode = 'kanban'" :class="viewMode === 'kanban' ? 'bg-white text-slate-900 shadow-sm font-black' : 'text-slate-500 hover:text-slate-900'" class="px-3 py-1.5 rounded-xl transition cursor-pointer flex items-center gap-1.5">
                            <span>🎯 Papan Kanban</span>
                        </button>
                        <button type="button" @click="viewMode = 'table'" :class="viewMode === 'table' ? 'bg-white text-slate-900 shadow-sm font-black' : 'text-slate-500 hover:text-slate-900'" class="px-3 py-1.5 rounded-xl transition cursor-pointer flex items-center gap-1.5">
                            <span>📋 Daftar Tabel</span>
                        </button>
                    </div>
                </div>

            </div>

            <!-- 1. KANBAN BOARD VIEW -->
            <div x-show="viewMode === 'kanban'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-start">
                
                <!-- 1. TODO COLUMN -->
                <div class="bg-slate-200/70 rounded-3xl p-4 sm:p-5 border border-slate-300/80 space-y-4 min-h-[600px] flex flex-col">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-300">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-slate-500"></span>
                            <h3 class="font-black text-xs uppercase tracking-wider text-slate-700">Antrean (TODO)</h3>
                        </div>
                        <span class="px-2 py-0.5 rounded-full bg-white text-slate-700 font-black text-xs shadow-sm">
                            {{ count($tasks->get('todo', [])) }}
                        </span>
                    </div>

                    <div class="space-y-4 flex-1">
                        @forelse ($tasks->get('todo', []) as $task)
                            @include('worker.tasks.partials.task-card', ['task' => $task])
                        @empty
                            <div class="h-48 border-2 border-dashed border-slate-300 rounded-2xl flex flex-col items-center justify-center text-slate-400 text-xs p-4 text-center">
                                <span class="text-2xl mb-1">🎉</span>
                                <span>Tidak ada antrean tugas baru</span>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- 2. IN PROGRESS COLUMN -->
                <div class="bg-blue-50/80 rounded-3xl p-4 sm:p-5 border border-blue-200/80 space-y-4 min-h-[600px] flex flex-col">
                    <div class="flex items-center justify-between pb-3 border-b border-blue-200">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-500 animate-pulse"></span>
                            <h3 class="font-black text-xs uppercase tracking-wider text-blue-900">Sedang Dikerjakan</h3>
                        </div>
                        <span class="px-2 py-0.5 rounded-full bg-white text-blue-700 font-black text-xs shadow-sm">
                            {{ count($tasks->get('in_progress', [])) }}
                        </span>
                    </div>

                    <div class="space-y-4 flex-1">
                        @forelse ($tasks->get('in_progress', []) as $task)
                            @include('worker.tasks.partials.task-card', ['task' => $task])
                        @empty
                            <div class="h-48 border-2 border-dashed border-blue-200 rounded-2xl flex flex-col items-center justify-center text-blue-300 text-xs p-4 text-center">
                                <span>Belum ada tugas yang aktif</span>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- 3. REVIEW COLUMN -->
                <div class="bg-purple-50/80 rounded-3xl p-4 sm:p-5 border border-purple-200/80 space-y-4 min-h-[600px] flex flex-col">
                    <div class="flex items-center justify-between pb-3 border-b border-purple-200">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                            <h3 class="font-black text-xs uppercase tracking-wider text-purple-900">Review Admin</h3>
                        </div>
                        <span class="px-2 py-0.5 rounded-full bg-white text-purple-700 font-black text-xs shadow-sm">
                            {{ count($tasks->get('review', [])) }}
                        </span>
                    </div>

                    <div class="space-y-4 flex-1">
                        @forelse ($tasks->get('review', []) as $task)
                            @include('worker.tasks.partials.task-card', ['task' => $task])
                        @empty
                            <div class="h-48 border-2 border-dashed border-purple-200 rounded-2xl flex flex-col items-center justify-center text-purple-300 text-xs p-4 text-center">
                                <span>Tidak ada tugas dalam review</span>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- 4. DONE COLUMN -->
                <div class="bg-emerald-50/80 rounded-3xl p-4 sm:p-5 border border-emerald-200/80 space-y-4 min-h-[600px] flex flex-col">
                    <div class="flex items-center justify-between pb-3 border-b border-emerald-200">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <h3 class="font-black text-xs uppercase tracking-wider text-emerald-900">Tuntas (Done)</h3>
                        </div>
                        <span class="px-2 py-0.5 rounded-full bg-white text-emerald-700 font-black text-xs shadow-sm">
                            {{ count($tasks->get('done', [])) }}
                        </span>
                    </div>

                    <div class="space-y-4 flex-1">
                        @forelse ($tasks->get('done', []) as $task)
                            @include('worker.tasks.partials.task-card', ['task' => $task])
                        @empty
                            <div class="h-48 border-2 border-dashed border-emerald-200 rounded-2xl flex flex-col items-center justify-center text-emerald-300 text-xs p-4 text-center">
                                <span>Belum ada tugas selesai</span>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- 2. TABLE LIST VIEW -->
            <div x-show="viewMode === 'table'" style="display:none;" class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider text-[10px] border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">Judul Tugas & Proyek</th>
                                <th class="px-6 py-3.5">Prioritas</th>
                                <th class="px-6 py-3.5">Skill Cocok</th>
                                <th class="px-6 py-3.5">Status Pengerjaan</th>
                                <th class="px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse($paginatedTasks as $task)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-6 py-4">
                                        <div>
                                            <span class="text-[10px] font-bold text-indigo-600 uppercase">{{ $task->project->title ?? 'Proyek TEFA' }}</span>
                                            <h4 class="font-bold text-slate-900 text-sm mt-0.5">{{ $task->title }}</h4>
                                            @if($task->ai_recommendation_notes)
                                                <p class="text-[10px] text-slate-500 mt-1">✦  {{ $task->ai_recommendation_notes }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($task->priority->value === 'high')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-red-50 text-red-700 border border-red-200">⚡ Tinggi</span>
                                        @elseif($task->priority->value === 'medium')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-amber-50 text-amber-700 border border-amber-200">Sedang</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-slate-100 text-slate-600">Normal</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-bold text-[10px]">
                                            {{ $task->skill->name ?? 'Umum' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase {{ $task->status->value === 'done' ? 'bg-emerald-100 text-emerald-800' : ($task->status->value === 'in_progress' ? 'bg-blue-100 text-blue-800' : ($task->status->value === 'review' ? 'bg-purple-100 text-purple-800' : 'bg-slate-200 text-slate-700')) }}">
                                            {{ $task->status->value }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('worker.tasks.show', $task->id) }}" class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs transition">
                                                Detail & Update &rarr;
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada tugas dalam filter ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-slate-100">
                    {{ $paginatedTasks->links() }}
                </div>
            </div>

        </div>

        <!-- Hidden Global Form for 1-Click Status Transitions -->
        <form id="status-update-form" method="POST" class="hidden">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="">
        </form>

        <!-- Proof Upload Modal -->
        <div x-show="uploadModalOpen" style="display:none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" @click="uploadModalOpen = false"></div>
                
                <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 sm:p-8 space-y-6">
                    
                    <div class="flex items-start justify-between pb-3 border-b border-slate-100">
                        <div>
                            <span class="text-[10px] font-bold text-indigo-600 uppercase" x-text="activeTask.project"></span>
                            <h3 class="font-black text-base text-slate-900 mt-0.5">Submit Bukti Pengerjaan Tugas</h3>
                        </div>
                        <button type="button" @click="uploadModalOpen = false" class="text-slate-400 hover:text-slate-600 font-bold text-xl cursor-pointer">×</button>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-indigo-50/60 border border-indigo-100 text-xs">
                        <span class="font-bold text-slate-700">Tugas:</span>
                        <p class="font-black text-slate-900 mt-0.5" x-text="activeTask.title"></p>
                    </div>

                    <form :action="'{{ url('worker/tasks') }}/' + activeTask.id + '/proof'" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Catatan Hasil Pengerjaan</label>
                            <textarea name="proof_notes" rows="3" required placeholder="Jelaskan apa saja yang telah dikerjakan, fitur yang selesai, atau kendala yang diselesaikan..." class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium leading-relaxed"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Link Repositori / Demo / Drive (Opsional)</label>
                            <input type="url" name="proof_url" placeholder="https://github.com/... atau https://figma.com/..." class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Unggah File Bukti / Lampiran (Opsional)</label>
                            <input type="file" name="proof_file" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                            <p class="text-[10px] text-slate-400 mt-1">Format: PDF, ZIP, JPG, PNG, WEBP (Maksimal 20MB).</p>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-2 border-t border-slate-100">
                            <button type="button" @click="uploadModalOpen = false" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold cursor-pointer">Batal</button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md cursor-pointer">Kirim ke Review Admin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>