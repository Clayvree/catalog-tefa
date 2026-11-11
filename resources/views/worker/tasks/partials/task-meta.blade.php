@php
    /** @var \App\Models\Task $task */
    $deadline = $task->effectiveDeadline();
    $isDone = ($task->status?->value ?? $task->status) === 'done';
    $daysLeft = $deadline ? (int) now()->startOfDay()->diffInDays($deadline->copy()->startOfDay(), false) : null;
    $role = $task->roleFor(auth()->user()?->workerProfile?->id);

    if (! $deadline) {
        $deadlineClass = 'bg-slate-50 text-slate-500 border-slate-200';
        $deadlineText = 'Tanpa tenggat';
    } elseif ($isDone) {
        $deadlineClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
        $deadlineText = 'Tenggat ' . $deadline->translatedFormat('d M Y');
    } elseif ($daysLeft < 0) {
        $deadlineClass = 'bg-rose-50 text-rose-700 border-rose-200';
        $deadlineText = 'Terlambat ' . abs($daysLeft) . ' hari (' . $deadline->translatedFormat('d M') . ')';
    } elseif ($daysLeft <= 3) {
        $deadlineClass = 'bg-amber-50 text-amber-700 border-amber-200';
        $deadlineText = ($daysLeft === 0 ? 'Tenggat hari ini' : $daysLeft . ' hari lagi') . ' (' . $deadline->translatedFormat('d M') . ')';
    } else {
        $deadlineClass = 'bg-slate-50 text-slate-600 border-slate-200';
        $deadlineText = 'Tenggat ' . $deadline->translatedFormat('d M Y') . ' · ' . $daysLeft . ' hari lagi';
    }
@endphp
<div class="flex flex-wrap items-center gap-1.5 {{ $class ?? '' }}">
    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full border text-[10px] font-bold {{ $deadlineClass }}">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        {{ $deadlineText }}
    </span>
    @if($role === 'leader')
        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full border border-amber-200 bg-amber-50 text-amber-800 text-[10px] font-bold">👑 Ketua Tim</span>
    @elseif($role === 'member')
        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full border border-indigo-200 bg-indigo-50 text-indigo-700 text-[10px] font-bold">Anggota Tim</span>
    @endif
</div>
