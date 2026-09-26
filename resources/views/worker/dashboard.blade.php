<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Student Talent Portal</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-0.5">
                    Statistik & Ringkasan Siswa
                </h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('worker.tasks.index') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs shadow-md shadow-indigo-600/20 transition flex items-center gap-1.5 cursor-pointer">
                    <span>🎯 Buka Papan Tugas</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Flash Message -->
            @if (session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-sm">
                    <span>✓ {{ session('success') }}</span>
                </div>
            @endif

            <!-- 1. Student Profile Welcome Banner -->
            <div class="bg-gradient-to-tr from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-600 to-blue-500 text-white font-black text-2xl flex items-center justify-center shadow-lg shadow-indigo-600/40 flex-shrink-0">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <h3 class="text-xl sm:text-2xl font-black text-white">{{ Auth::user()->name }}</h3>
                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 text-[10px] font-extrabold border border-emerald-500/30">
                                Worker Aktif
                            </span>
                        </div>
                        <p class="text-xs text-indigo-200">
                            {{ $profile->class_name ?? 'Siswa Vokasi' }} • {{ $profile->tefaUnit->name ?? 'Teaching Factory' }}
                        </p>
                        <p class="text-[11px] text-slate-400 font-mono">NISN: {{ $profile->nisn ?? '-' }} • {{ Auth::user()->email }}</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <div class="bg-slate-800/80 p-3 rounded-2xl border border-slate-700 text-xs">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Tingkat Ketuntasan:</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <div class="w-24 bg-slate-700 h-2 rounded-full overflow-hidden">
                                <div class="bg-emerald-400 h-full rounded-full" style="width: {{ $stats['completion_rate'] }}%"></div>
                            </div>
                            <span class="font-black text-emerald-400">{{ $stats['completion_rate'] }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Personal Performance Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                
                <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Total Tugas Masuk</span>
                    <p class="text-2xl sm:text-3xl font-black text-slate-900">{{ $stats['total_assigned'] }}</p>
                    <p class="text-[10px] text-slate-500">Didelegasikan ke Anda</p>
                </div>

                <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-1">
                    <span class="text-[10px] font-bold text-indigo-600 uppercase">Antrean (TODO)</span>
                    <p class="text-2xl sm:text-3xl font-black text-indigo-600">{{ $stats['todo_tasks'] }}</p>
                    <p class="text-[10px] text-indigo-600 font-bold">Perlu Anda mulai</p>
                </div>

                <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-1">
                    <span class="text-[10px] font-bold text-amber-500 uppercase">Sedang Dikerjakan</span>
                    <p class="text-2xl sm:text-3xl font-black text-amber-500">{{ $stats['in_progress_tasks'] }}</p>
                    <p class="text-[10px] text-slate-500">In Progress</p>
                </div>

                <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-1">
                    <span class="text-[10px] font-bold text-emerald-600 uppercase">Tugas Selesai (Done)</span>
                    <p class="text-2xl sm:text-3xl font-black text-emerald-600">{{ $stats['completed_tasks'] }}</p>
                    <p class="text-[10px] text-emerald-600 font-bold">Terverifikasi Guru/Admin</p>
                </div>

            </div>

            <!-- 3. Skills & Priority Breakdown Section -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left: Urgent Tasks (8 cols) -->
                <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <h3 class="font-black text-base text-slate-900">Tugas yang Perlu Diselesaikan Segera</h3>
                            <p class="text-xs text-slate-500">Diurutkan berdasarkan tingkat urgensi dan prioritas proyek.</p>
                        </div>
                        <a href="{{ route('worker.tasks.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">
                            Lihat Semua Tugas &rarr;
                        </a>
                    </div>

                    <div class="space-y-4">
                        @forelse($urgentTasks as $task)
                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-indigo-300 transition space-y-3">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2">
                                    <div class="space-y-1">
                                        <span class="text-[10px] font-extrabold uppercase text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">{{ $task->project->title ?? 'Proyek TEFA' }}</span>
                                        <h4 class="font-black text-sm text-slate-900">{{ $task->title }}</h4>
                                    </div>
                                    <div class="flex items-center gap-2 self-start">
                                        @if($task->priority->value === 'high')
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-red-100 text-red-700">⚡ Prioritas Tinggi</span>
                                        @elseif($task->priority->value === 'medium')
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-amber-100 text-amber-700">Prioritas Sedang</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-slate-200 text-slate-700">Prioritas Normal</span>
                                        @endif

                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase {{ $task->status->value === 'in_progress' ? 'bg-blue-100 text-blue-800' : ($task->status->value === 'review' ? 'bg-purple-100 text-purple-800' : 'bg-slate-200 text-slate-700') }}">
                                            {{ $task->status->value }}
                                        </span>
                                    </div>
                                </div>

                                <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                                    {{ $task->description ?? 'Instruksi pengerjaan dari instruktur/AI.' }}
                                </p>

                                @if($task->ai_recommendation_notes)
                                    <div class="p-2.5 rounded-xl bg-indigo-50/70 border border-indigo-100 text-[11px] text-indigo-900">
                                        <span class="font-bold text-indigo-700">✦ AI Match:</span> {{ $task->ai_recommendation_notes }}
                                    </div>
                                @endif

                                <div class="pt-3 border-t border-slate-200/60 flex items-center justify-between text-xs">
                                    <span class="text-[11px] text-slate-400 font-mono">Skill: <strong>{{ $task->skill->name ?? 'Umum' }}</strong></span>
                                    
                                    <a href="{{ route('worker.tasks.show', $task->id) }}" class="px-3.5 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition flex items-center gap-1">
                                        <span>Buka Detail & Update Progres &rarr;</span>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center text-slate-400 bg-slate-50 rounded-2xl border border-dashed border-slate-200 space-y-2">
                                <span class="text-3xl">🎉</span>
                                <p class="text-sm font-bold text-slate-700">Semua tugas beres!</p>
                                <p class="text-xs text-slate-500">Tidak ada tugas yang tertunda. Tunggu delegasi proyek baru dari AI.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Right: Skills & Portfolio Summary (4 cols) -->
                <div class="lg:col-span-4 space-y-6">
                    
                    <!-- Skills Badge Card -->
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="font-black text-sm text-slate-900">Keahlian Saya (AI Matching)</h3>
                            <span class="text-xs font-bold text-indigo-600">{{ $profile->skills->count() }} Skill</span>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Keahlian ini digunakan oleh AI untuk memilih Anda saat ada proyek industri yang relevan.
                        </p>

                        <div class="flex flex-wrap gap-1.5 pt-1">
                            @forelse($profile->skills as $skill)
                                <span class="px-2.5 py-1 rounded-xl text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-100">
                                    {{ $skill->name }}
                                </span>
                            @empty
                                <p class="text-xs text-slate-400 italic">Belum ada skill terdaftar. Hubungi Admin Jurusan.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Portfolios Showcase Summary -->
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="font-black text-sm text-slate-900">Portofolio Karya Saya</h3>
                            <a href="{{ route('worker.portfolios.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">
                                Kelola &rarr;
                            </a>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Karya yang disetujui akan tampil di etalase publik profil jurusan TEFA.
                        </p>

                        <div class="space-y-3">
                            @forelse($recentPortfolios as $pf)
                                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-xs space-y-1">
                                    <p class="font-bold text-slate-900 line-clamp-1">{{ $pf->title }}</p>
                                    <div class="flex items-center justify-between pt-1">
                                        <span class="text-[10px] text-slate-400">{{ $pf->created_at?->format('d M Y') }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold {{ $pf->status->value === 'approved' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                            {{ ucfirst($pf->status->value) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic text-center py-3">Belum ada portofolio yang diajukan.</p>
                            @endforelse
                        </div>

                        <a href="{{ route('worker.portfolios.index') }}" class="block text-center py-3 bg-slate-900 hover:bg-indigo-600 text-white rounded-2xl font-bold text-xs transition shadow-md cursor-pointer">
                            + Ajukan Karya Baru
                        </a>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>