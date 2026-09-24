<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Unit Production Control</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-0.5">
                    {{ $managedUnit->name ?? 'Dashboard Admin Jurusan' }}
                </h2>
            </div>
            <div class="flex items-center gap-3">
                @if($managedUnit)
                    <a href="{{ route('tefa.storefront', $managedUnit->slug) }}" target="_blank" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                        <span>Lihat Profil Publik</span>
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- AI Delegasi WhatsApp Banner -->
            <div class="bg-gradient-to-tr from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-slate-800 relative overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-2 max-w-xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 text-[10px] font-extrabold uppercase tracking-widest border border-indigo-500/30">
                            ✦ AI Integration • Gemini Flash
                        </div>
                        <h3 class="text-xl sm:text-2xl font-black tracking-tight">Otomasi Delegasi Tugas dari Chat WhatsApp</h3>
                        <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                            Upload file export chat (.txt) percakapan Anda dengan klien. AI akan mengekstrak kesepakatan harga, judul proyek, dan memecahnya ke tugas siswa otomatis.
                        </p>
                    </div>

                    <a href="{{ route('admin.projects.import-wa.create') }}" class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-xs sm:text-sm shadow-lg shadow-indigo-600/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 flex-shrink-0">
                        <span>Mulai Import Chat WA</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                
                <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase">Proyek Aktif</span>
                    <p class="text-2xl font-black text-slate-900">{{ $stats['active_projects'] }}</p>
                    <p class="text-[10px] text-emerald-600 font-bold">Sedang dikerjakan siswa</p>
                </div>

                <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase">Total Tugas</span>
                    <p class="text-2xl font-black text-indigo-600">{{ $stats['total_tasks'] }}</p>
                    <p class="text-[10px] text-slate-500">Tiket pengerjaan</p>
                </div>

                <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase">Siswa Terdaftar</span>
                    <p class="text-2xl font-black text-slate-900">{{ $stats['total_workers'] }}</p>
                    <p class="text-[10px] text-slate-500">Talenta siap ditugaskan</p>
                </div>

                <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase">Review Portofolio</span>
                    <p class="text-2xl font-black text-amber-500">{{ $stats['pending_portfolios'] }}</p>
                    <p class="text-[10px] text-slate-500">Menunggu verifikasi Anda</p>
                </div>

            </div>

            <!-- Proyek & Tasks List -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Active Projects List (Left Column) -->
                <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <h3 class="font-black text-base text-slate-900">Proyek Berjalan di Jurusan Ini</h3>
                            <p class="text-xs text-slate-500">Hasil ekstraksi chat atau pesanan langsung.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @forelse($projects as $project)
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-indigo-200 transition space-y-3">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                    <div>
                                        <h4 class="font-black text-sm text-slate-900">{{ $project->title }}</h4>
                                        <p class="text-xs text-slate-500">Klien: <strong class="text-slate-700">{{ $project->client_name }}</strong></p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-extrabold uppercase">
                                            {{ $project->status->value ?? 'Active' }}
                                        </span>
                                        @if($project->final_price)
                                            <span class="text-xs font-black text-slate-900">Rp{{ number_format((float)$project->final_price, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                                    {{ $project->description }}
                                </p>

                                <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between text-xs text-slate-500">
                                    <span>{{ $project->tasks->count() }} Sub-Tugas Siswa</span>
                                    <span class="text-indigo-600 font-bold">Terpantau di Kanban</span>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center text-slate-400 bg-slate-50 rounded-2xl border border-dashed border-slate-200 space-y-2">
                                <p class="text-sm font-bold text-slate-700">Belum ada proyek aktif</p>
                                <p class="text-xs text-slate-500">Gunakan fitur Import Chat WhatsApp untuk membuat proyek baru secara instan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Students & Portfolios (Right Column) -->
                <div class="lg:col-span-4 space-y-6">
                    
                    <!-- Siswa Worker List -->
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="font-black text-sm text-slate-900">Siswa (Worker) Terdaftar</h3>
                            <span class="text-xs font-bold text-indigo-600">{{ $workers->count() }} Siswa</span>
                        </div>

                        <div class="space-y-3">
                            @forelse($workers as $worker)
                                <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-xs">
                                            {{ substr($worker->user->name ?? 'S', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900">{{ $worker->user->name }}</p>
                                            <p class="text-[10px] text-slate-400">{{ $worker->class_name ?? 'Siswa' }}</p>
                                        </div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-bold">
                                        Siap Kerja
                                    </span>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic">Belum ada siswa di jurusan ini.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Portofolio Approval -->
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="font-black text-sm text-slate-900">Kurasi Portofolio Siswa</h3>
                            <span class="text-xs font-bold text-amber-500">{{ $pendingPortfolios->count() }} Menunggu</span>
                        </div>

                        @forelse($pendingPortfolios as $pf)
                            <div class="p-3 rounded-2xl bg-amber-50/50 border border-amber-200/60 text-xs space-y-2">
                                <p class="font-bold text-slate-900">{{ $pf->title }}</p>
                                <p class="text-[10px] text-slate-500">Diajukan oleh: {{ $pf->worker->user->name ?? 'Siswa' }}</p>
                                <div class="flex items-center gap-2 pt-1">
                                    <button class="px-3 py-1 bg-emerald-600 text-white rounded-lg font-bold text-[10px]">Approve</button>
                                    <button class="px-3 py-1 bg-slate-200 text-slate-700 rounded-lg font-bold text-[10px]">Tolak</button>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic text-center py-4">Semua portofolio siswa sudah direview.</p>
                        @endforelse
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>