<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <a href="{{ route('worker.tasks.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">
                        &larr; Kembali ke Papan Tugas
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
                        ? Tuntas & Disetujui
                    </span>
                @elseif($task->status->value === 'review')
                    <span class="px-4 py-2 rounded-2xl bg-purple-50 text-purple-700 font-black text-xs border border-purple-200">
                        ? Menunggu Review Guru/Admin
                    </span>
                @elseif($task->status->value === 'in_progress')
                    <span class="px-4 py-2 rounded-2xl bg-blue-50 text-blue-700 font-black text-xs border border-blue-200 animate-pulse">
                        ? Sedang Dikerjakan
                    </span>
                @else
                    <span class="px-4 py-2 rounded-2xl bg-slate-100 text-slate-700 font-black text-xs">
                        ?? Antrean (TODO)
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            @if (session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-sm">
                    <span>? {{ session('success') }}</span>
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

            <!-- Team Info Banner -->
            <div class="bg-indigo-900 rounded-3xl p-6 text-white shadow-lg flex flex-col md:flex-row items-center gap-6 justify-between">
                <div>
                    <h3 class="text-xs font-bold text-indigo-300 uppercase tracking-wider mb-2">Struktur Tim Pengerjaan</h3>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center font-bold text-sm shadow">??</span>
                            <div>
                                <p class="text-sm font-bold">{{ $task->leader->user->name ?? '-' }}</p>
                                <p class="text-[10px] text-indigo-300">Ketua Tim (Leader)</p>
                            </div>
                        </div>
                        @if($task->members->count() > 0)
                            <div class="h-8 w-px bg-indigo-700 mx-2"></div>
                            <div class="flex flex-wrap items-center gap-2">
                                @foreach($task->members as $member)
                                    <div class="flex items-center gap-1.5 bg-indigo-800 px-3 py-1.5 rounded-xl border border-indigo-700">
                                        <div class="w-5 h-5 rounded-full bg-indigo-600 text-[10px] flex items-center justify-center font-bold">{{ substr($member->user->name, 0, 1) }}</div>
                                        <span class="text-xs font-medium">{{ explode(' ', $member->user->name)[0] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <div class="flex-shrink-0 text-right">
                    <div class="text-4xl font-black text-indigo-200">{{ $task->progress_percentage }}%</div>
                    <div class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider mt-1">Progress Keseluruhan</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left Column: Task Details & Instructions (7 cols) -->
                <div class="lg:col-span-7 space-y-6">
                    
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-5">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-600">Instruksi & Target</span>
                                <h3 class="font-black text-lg text-slate-900 mt-0.5">{{ $task->title }}</h3>
                            </div>
                        </div>

                        @if($task->goals)
                            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-100 space-y-1">
                                <span class="text-[10px] font-extrabold uppercase text-amber-700">?? Target (Goals) Tugas:</span>
                                <p class="text-sm font-bold text-amber-900">{{ $task->goals }}</p>
                            </div>
                        @endif

                        <div class="space-y-2">
                            <span class="text-xs font-bold text-slate-400 uppercase">Deskripsi Pekerjaan:</span>
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-700 leading-relaxed whitespace-pre-line">
                                {{ $task->description ?? 'Tidak ada deskripsi tambahan.' }}
                            </div>
                        </div>

                        @if($task->ai_recommendation_notes)
                            <div class="p-4 rounded-2xl bg-indigo-50/70 border border-indigo-100 space-y-1">
                                <span class="flex items-center gap-1.5 text-[10px] font-extrabold text-indigo-800 uppercase">?? Alasan Pemilihan Tim oleh AI:</span>
                                <p class="text-xs text-indigo-900 leading-relaxed italic">"{{ $task->ai_recommendation_notes }}"</p>
                            </div>
                        @endif
                    </div>

                    <!-- Team Notes / Collaboration Room -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="font-black text-sm text-slate-900 flex items-center gap-2">?? Catatan Kolaborasi Tim</h3>
                            @if(auth()->user()->workerProfile && auth()->user()->workerProfile->id === $task->leader_id)
                                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">Akses Edit: Ketua</span>
                            @else
                                <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded-lg">Akses Lihat: Anggota</span>
                            @endif
                        </div>
                        
                        @if(auth()->user()->workerProfile && auth()->user()->workerProfile->id === $task->leader_id)
                            <form action="{{ route('worker.tasks.notes.update', $task->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                
                                <div class="space-y-4 mb-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Catatan Umum / Pengumuman</label>
                                        <textarea name="team_notes" rows="4" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:bg-white focus:ring-1 focus:ring-indigo-500 text-sm p-4" placeholder="Ketua tim: Catat hal yang perlu diperhatikan anggota di sini...">{{ $task->team_notes }}</textarea>
                                    </div>

                                    @if($task->members->count() > 0)
                                        <div class="pt-4 border-t border-slate-100">
                                            <label class="block text-xs font-bold text-slate-700 uppercase mb-3">Tugas Spesifik untuk Anggota</label>
                                            <div class="grid grid-cols-1 gap-3">
                                                @foreach($task->members as $member)
                                                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-3">
                                                        <label class="block text-xs font-bold text-indigo-700 mb-1">
                                                            {{ $member->user->name }}
                                                        </label>
                                                        <textarea name="member_notes[{{ $member->id }}]" rows="2" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs p-2" placeholder="Contoh: Tolong buatkan database schema untuk halaman login...">{{ $member->pivot->member_task_note }}</textarea>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4">
                                    <div class="flex items-center gap-3">
                                        <label class="text-xs font-bold text-slate-700">Persentase Progress:</label>
                                        <input type="range" name="progress_percentage" min="0" max="100" value="{{ $task->progress_percentage }}" class="w-32 accent-indigo-600" oninput="this.nextElementSibling.innerText = this.value + '%'">
                                        <span class="text-sm font-black text-indigo-700 w-10">{{ $task->progress_percentage }}%</span>
                                    </div>
                                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md cursor-pointer">Simpan Catatan & Progress</button>
                                </div>
                            </form>
                        @else
                            <div class="space-y-4">
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-sm text-slate-700 whitespace-pre-line">
                                    <strong class="block mb-2 text-slate-900">Catatan Umum:</strong>
                                    {{ $task->team_notes ?: 'Belum ada catatan umum dari ketua tim.' }}
                                </div>
                                
                                @php
                                    $myNote = $task->members->where('id', auth()->user()->workerProfile->id)->first()?->pivot->member_task_note;
                                @endphp
                                @if($myNote)
                                    <div class="p-4 rounded-2xl bg-indigo-50 border border-indigo-100 text-sm text-indigo-900 whitespace-pre-line">
                                        <strong class="block mb-2 text-indigo-900">Tugas Khusus Untukmu:</strong>
                                        {{ $myNote }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                </div>

                <!-- Right Column: Action Form & Project Context (5 cols) -->
                <div class="lg:col-span-5 space-y-6">
                    
                    @if(auth()->user()->workerProfile && auth()->user()->workerProfile->id === $task->leader_id)
                        <!-- Update Progress & Submit Proof Form (LEADER ONLY) -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                <h3 class="font-black text-base text-slate-900">Kirim & Ajukan Status</h3>
                                <span class="text-[10px] font-bold text-white bg-indigo-600 px-2 py-1 rounded-lg uppercase">Ketua Saja</span>
                            </div>

                            <form action="{{ route('worker.tasks.status.update', $task->id) }}" method="POST" class="space-y-3">
                                @csrf
                                @method('PATCH')
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status Pengerjaan Tim</label>
                                    <div class="flex items-center gap-2">
                                        <select name="status" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 text-xs font-bold">
                                            <option value="todo" {{ $task->status->value === 'todo' ? 'selected' : '' }}>?? Antrean (TODO)</option>
                                            <option value="in_progress" {{ $task->status->value === 'in_progress' ? 'selected' : '' }}>? Sedang Dikerjakan</option>
                                            <option value="review" {{ $task->status->value === 'review' ? 'selected' : '' }}>? Ajukan Review Ke Guru</option>
                                        </select>
                                        <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl cursor-pointer">Set Status</button>
                                    </div>
                                </div>
                            </form>

                            <div class="border-t border-slate-100 pt-4"></div>

                            <form action="{{ route('worker.tasks.proof.upload', $task->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <h4 class="font-black text-xs uppercase tracking-wider text-indigo-700">Kirim Bukti Akhir Proyek</h4>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Catatan Hasil (Pesan untuk Guru)</label>
                                    <textarea name="proof_notes" rows="2" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 text-xs font-medium">{{ $task->proof_notes }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Link Demo (Opsional)</label>
                                    <input type="url" name="proof_url" value="{{ $task->proof_file_url && str_starts_with($task->proof_file_url, 'http') ? $task->proof_file_url : '' }}" class="w-full rounded-xl border-slate-200 text-xs">
                                </div>
                                <button type="submit" class="w-full py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs cursor-pointer">?? Simpan Bukti Final</button>
                            </form>
                        </div>
                    @else
                        <!-- MEMBER VIEW FOR STATUS -->
                        <div class="bg-slate-900 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4 text-white">
                            <h3 class="font-black text-base text-indigo-300">Status Pengiriman</h3>
                            <p class="text-sm">Hanya Ketua Tim ({{ $task->leader->user->name ?? '-' }}) yang dapat mengubah status tugas dan mengunggah bukti hasil pekerjaan ke guru.</p>
                            @if($task->status->value === 'review')
                                <div class="mt-4 p-4 rounded-xl bg-slate-800 border border-slate-700">
                                    <span class="text-xs font-bold text-emerald-400">? Telah Diajukan ke Guru</span>
                                    <p class="text-xs text-slate-300 mt-1 italic">{{ $task->proof_notes }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>


