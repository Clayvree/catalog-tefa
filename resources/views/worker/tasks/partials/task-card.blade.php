<div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-sm hover:shadow-md hover:border-indigo-300 transition space-y-3 relative group">
    
    <!-- Top Metadata: Project & Priority -->
    <div class="flex items-start justify-between gap-2">
        <span class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-md border border-indigo-100 max-w-[170px] truncate">
            {{ $task->project->title ?? 'Proyek TEFA' }}
        </span>

        @php
            $priority = $task->priority?->value;
        @endphp
        @if($priority === 'high')
            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-red-50 text-red-700 border border-red-200">
                ⚡ Prioritas Tinggi
            </span>
        @elseif($priority === 'medium')
            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-amber-50 text-amber-700 border border-amber-200">
                Prioritas Sedang
            </span>
        @else
            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-slate-100 text-slate-600 border border-slate-200">
                Prioritas Normal
            </span>
        @endif
    </div>

    <!-- Task Title & Description -->
    <div>
        <h4 class="font-black text-sm text-slate-900 leading-snug group-hover:text-indigo-600 transition">
            {{ $task->title }}
        </h4>
        @if($task->description)
            <p class="text-xs text-slate-600 line-clamp-3 mt-1.5 leading-relaxed">
                {{ $task->description }}
            </p>
        @endif
    </div>

    <!-- AI Match Reasoning (If extracted by AI) -->
    @if($task->ai_recommendation_notes)
        <div class="p-2 rounded-xl bg-indigo-50/60 border border-indigo-100 text-[10px] text-indigo-900 leading-tight">
            <span class="font-bold text-indigo-700">🤖 AI Note:</span> {{ $task->ai_recommendation_notes }}
        </div>
    @endif

    <!-- Skill Tag -->
    @if($task->skill)
        <div class="flex items-center gap-1.5">
            <span class="text-[10px] text-slate-400 font-medium">Skill:</span>
            <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-bold text-[10px]">
                {{ $task->skill->name }}
            </span>
        </div>
    @endif

    <!-- Status Action Button Area -->
    <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
        @if ($task->status->value === 'todo')
            <button type="button" @click="submitStatus('{{ $task->id }}', 'in_progress')" class="w-full py-2.5 px-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition flex items-center justify-center gap-1.5 cursor-pointer">
                <span>▶️ Mulai Kerjakan</span>
            </button>
        @elseif ($task->status->value === 'in_progress')
            <button type="button" @click="openUploadModal({
                id: '{{ $task->id }}',
                title: '{{ addslashes($task->title) }}',
                project: '{{ addslashes($task->project->title ?? '') }}',
                proof_notes: '{{ addslashes($task->proof_notes ?? '') }}',
                proof_url: '{{ addslashes($task->proof_file_url ?? '') }}'
            })" class="w-full py-2.5 px-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-sm transition flex items-center justify-center gap-1.5 cursor-pointer">
                <span>📤 Kirim Bukti Pengerjaan</span>
            </button>
        @elseif ($task->status->value === 'review')
            <div class="p-2.5 rounded-xl bg-purple-50 border border-purple-200 text-center space-y-1">
                <span class="text-[10px] font-bold text-purple-700 block">⏳ Menunggu Verifikasi Guru/Admin</span>
                @if($task->proof_file_url)
                    <a href="{{ $task->proof_file_url }}" target="_blank" class="text-[10px] font-bold text-indigo-600 hover:underline block">
                        Lihat Bukti Terkirim &rarr;
                    </a>
                @endif
            </div>
        @elseif ($task->status->value === 'done')
            <div class="p-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-center">
                <span class="text-xs font-bold text-emerald-800">✓ Selesai & Terverifikasi</span>
                @if($task->proof_file_url)
                    <a href="{{ $task->proof_file_url }}" target="_blank" class="text-[10px] font-bold text-emerald-600 hover:underline block mt-0.5">
                        Lihat Hasil Kerja &rarr;
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>