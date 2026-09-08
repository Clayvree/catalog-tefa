<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <a href="{{ route('worker.tasks.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">
                        ← Kembali ke Papan Tugas
                    </a>
                    <span class="text-slate-300">•</span>
                    <span class="text-[10px] font-extrabold uppercase text-slate-400 font-mono">Tugas #{{ substr($task->id, 0, 8) }}</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                    {{ $task->title }}
                </h2>
            </div>
            <div class="flex items-center gap-2">
                @if($task->status->value === 'done')
                    <span class="px-4 py-2 rounded-2xl bg-emerald-50 text-emerald-700 font-black text-xs border border-emerald-200">
                        ✓ Tuntas & Disetujui
                    </span>
                @elseif($task->status->value === 'review')
                    <span class="px-4 py-2 rounded-2xl bg-purple-50 text-purple-700 font-black text-xs border border-purple-200">
                        ⏳ Menunggu Review Guru/Admin
                    </span>
                @elseif($task->status->value === 'in_progress')
                    <span class="px-4 py-2 rounded-2xl bg-blue-50 text-blue-700 font-black text-xs border border-blue-200 animate-pulse">
                        ⚡ Sedang Dikerjakan
                    </span>
                @else
                    <span class="px-4 py-2 rounded-2xl bg-slate-100 text-slate-700 font-black text-xs">
                        📋 Antrean (TODO)
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Flash Message -->
            @if (session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-sm">
                    <span>✓ {{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-bold space-y-1 shadow-sm">
                    <p class="font-extrabold text-sm">Gagal Mengupdate Tugas:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-[11px] text-red-700 font-normal">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left Column: Task Details & Instructions (7 cols) -->
                <div class="lg:col-span-7 space-y-6">
                    
                    <!-- Task Brief Card -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-5">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-600">Instruksi Pengerjaan</span>
                                <h3 class="font-black text-lg text-slate-900 mt-0.5">{{ $task->title }}</h3>
                            </div>

                            @if($task->priority->value === 'high')
                                <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase bg-red-50 text-red-700 border border-red-200">
                                    ⚡ Prioritas Tinggi
                                </span>
                            @elseif($task->priority->value === 'medium')
                                <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase bg-amber-50 text-amber-700 border border-amber-200">
                                    Prioritas Sedang
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase bg-slate-100 text-slate-600">
                                    Prioritas Normal
                                </span>
                            @endif
                        </div>

                        <!-- Full Description -->
                        <div class="space-y-2">
                            <span class="text-xs font-bold text-slate-400 uppercase">Deskripsi & Kriteria Selesai:</span>
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-700 leading-relaxed whitespace-pre-line">
                                {{ $task->description ?? 'Tidak ada catatan tambahan untuk tugas ini.' }}
                            </div>
                        </div>

                        <!-- AI Matching Note -->
                        @if($task->ai_recommendation_notes)
                            <div class="p-4 rounded-2xl bg-indigo-50/70 border border-indigo-100 space-y-1">
                                <div class="flex items-center gap-1.5 text-xs font-bold text-indigo-900">
                                    <span>🤖 Analisis AI Talent Match:</span>
                                </div>
                                <p class="text-xs text-indigo-800 leading-relaxed">
                                    {{ $task->ai_recommendation_notes }}
                                </p>
                            </div>
                        @endif

                        <!-- Skill Matched -->
                        @if($task->skill)
                            <div class="flex items-center gap-2 pt-2">
                                <span class="text-xs font-bold text-slate-400">Keahlian Terkait:</span>
                                <span class="px-3 py-1 rounded-xl bg-indigo-50 text-indigo-700 font-bold text-xs border border-indigo-100">
                                    {{ $task->skill->name }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Submitted Proof Review Card (If any) -->
                    @if($task->proof_file_url || $task->proof_notes)
                        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-4">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                <h3 class="font-black text-sm text-slate-900">Bukti Hasil Kerja Terkirim</h3>
                                <span class="text-[10px] font-bold text-purple-600">Status: {{ strtoupper($task->status->value) }}</span>
                            </div>

                            @if($task->proof_notes)
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">Catatan Pengerjaan Siswa:</span>
                                    <p class="text-xs text-slate-700 mt-1 leading-relaxed bg-slate-50 p-3.5 rounded-xl border border-slate-100 whitespace-pre-line">
                                        {{ $task->proof_notes }}
                                    </p>
                                </div>
                            @endif

                            @if($task->proof_file_url)
                                <div class="pt-2">
                                    <a href="{{ $task->proof_file_url }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        <span>Buka File / Link Bukti Kerja &rarr;</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                </div>

                <!-- Right Column: Action Form & Project Context (5 cols) -->
                <div class="lg:col-span-5 space-y-6">
                    
                    <!-- Update Progress & Submit Proof Form -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="font-black text-base text-slate-900">Update Progres & Bukti</h3>
                            <span class="text-xs font-bold text-indigo-600">Action</span>
                        </div>

                        <!-- 1. Quick Status Changer -->
                        <form action="{{ route('worker.tasks.status.update', $task->id) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Ubah Status Tugas</label>
                                <div class="flex items-center gap-2">
                                    <select name="status" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-bold">
                                        <option value="todo" {{ $task->status->value === 'todo' ? 'selected' : '' }}>📋 Antrean (TODO)</option>
                                        <option value="in_progress" {{ $task->status->value === 'in_progress' ? 'selected' : '' }}>⚡ Sedang Dikerjakan (In Progress)</option>
                                        <option value="review" {{ $task->status->value === 'review' ? 'selected' : '' }}>⏳ Minta Review Guru/Admin</option>
                                        <option value="done" {{ $task->status->value === 'done' ? 'selected' : '' }}>✓ Tandai Selesai</option>
                                    </select>
                                    <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition cursor-pointer">
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="border-t border-slate-100 pt-4"></div>

                        <!-- 2. Submit Proof Form -->
                        <form action="{{ route('worker.tasks.proof.upload', $task->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            
                            <h4 class="font-black text-xs uppercase tracking-wider text-indigo-700">Kirim Bukti Hasil Pengerjaan</h4>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Catatan Hasil Pengerjaan</label>
                                <textarea name="proof_notes" rows="3" required placeholder="Jelaskan apa yang sudah selesai, kendala yang diatasi, atau link demo..." class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium leading-relaxed">{{ $task->proof_notes }}</textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Link Demo / GitHub / Figma (Opsional)</label>
                                <input type="url" name="proof_url" value="{{ $task->proof_file_url && str_starts_with($task->proof_file_url, 'http') ? $task->proof_file_url : '' }}" placeholder="https://github.com/... atau https://figma.com/..." class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Unggah Lampiran File (PDF/ZIP/Gambar)</label>
                                <input type="file" name="proof_file" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                                <p class="text-[10px] text-slate-400 mt-1">Maksimal 20MB.</p>
                            </div>

                            <button type="submit" class="w-full py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition cursor-pointer">
                                📤 Kirim Bukti & Ajukan Review Guru/Admin
                            </button>
                        </form>
                    </div>

                    <!-- Project Context Info Card -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="font-black text-sm text-slate-900">Konteks Proyek Induk</h3>
                            <span class="text-[10px] font-bold text-indigo-600 uppercase">{{ $task->project->tefaUnit->name ?? 'TEFA' }}</span>
                        </div>

                        <div class="space-y-2 text-xs">
                            <div>
                                <span class="text-slate-400 font-bold uppercase text-[10px]">Nama Proyek:</span>
                                <p class="font-black text-slate-900 text-sm mt-0.5">{{ $task->project->title ?? 'Proyek Industri' }}</p>
                            </div>
                            
                            @if($task->project->client_name)
                                <div class="pt-1">
                                    <span class="text-slate-400 font-bold uppercase text-[10px]">Klien:</span>
                                    <p class="font-bold text-slate-700">{{ $task->project->client_name }}</p>
                                </div>
                            @endif

                            @if($task->project->deadline)
                                <div class="pt-1">
                                    <span class="text-slate-400 font-bold uppercase text-[10px]">Target Selesai Proyek:</span>
                                    <p class="font-bold text-indigo-600">{{ $task->project->deadline->format('d F Y') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>