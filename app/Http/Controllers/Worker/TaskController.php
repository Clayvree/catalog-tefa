<?php

declare(strict_types=1);

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Enums\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $workerProfile = $request->user()->workerProfile;
        
        if (!$workerProfile) {
            return redirect()->route('worker.dashboard')->with('error', 'Profil siswa belum lengkap.');
        }

        $workerId = $workerProfile->id;
        
        $query = Task::with(['project', 'skill'])->forWorker($workerId);

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('q')) {
            $keyword = trim((string) $request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        $allTasks = (clone $query)->latest()->get();
        $tasksByStatus = $allTasks->groupBy(fn ($task) => $task->status->value);
        $paginatedTasks = (clone $query)->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => Task::forWorker($workerId)->count(),
            'todo' => Task::forWorker($workerId)->where('status', TaskStatus::Todo->value)->count(),
            'in_progress' => Task::forWorker($workerId)->where('status', TaskStatus::InProgress->value)->count(),
            'review' => Task::forWorker($workerId)->where('status', TaskStatus::Review->value)->count(),
            'done' => Task::forWorker($workerId)->where('status', TaskStatus::Done->value)->count(),
            'high' => Task::forWorker($workerId)->where('priority', 'high')->where('status', '!=', TaskStatus::Done->value)->count(),
        ];

        return view('worker.tasks.index', [
            'tasks' => $tasksByStatus,
            'paginatedTasks' => $paginatedTasks,
            'workerProfile' => $workerProfile,
            'stats' => $stats,
        ]);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        $task->load(['project.tefaUnit', 'skill', 'leader.user', 'members.user']);
        return view('worker.tasks.show', ['task' => $task]);
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'status' => ['required', new Enum(TaskStatus::class)]
        ]);

        $task->update([
            'status' => $validated['status'],
            'completed_at' => $validated['status'] === TaskStatus::Done->value ? now() : null
        ]);

        return redirect()->back()->with('success', "Status tugas '{$task->title}' berhasil diubah ke {$task->status->value}!");
    }
    
    public function updateNotes(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'team_notes' => 'nullable|string',
            'progress_percentage' => 'required|integer|min:0|max:100',
            'member_notes' => 'nullable|array',
            'member_notes.*' => 'nullable|string'
        ]);

        $task->update([
            'team_notes' => $validated['team_notes'],
            'progress_percentage' => $validated['progress_percentage'],
        ]);

        if (isset($validated['member_notes'])) {
            $syncData = [];
            foreach ($validated['member_notes'] as $memberId => $note) {
                // Get the existing members and preserve their timestamps/other pivot data
                $syncData[$memberId] = ['member_task_note' => $note];
            }
            // Use syncWithoutDetaching to only update the pivot for provided members
            $task->members()->syncWithoutDetaching($syncData);
        }

        return redirect()->back()->with('success', 'Catatan kolaborasi dan tugas anggota berhasil diupdate!');
    }

    public function uploadProof(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $request->validate([
            'proof_file' => 'nullable|file|mimes:pdf,jpg,png,jpeg,zip,webp|max:20480',
            'proof_url' => 'nullable|url|max:255',
            'proof_notes' => 'nullable|string|max:1500'
        ]);

        $proofUrl = $request->input('proof_url');

        if ($request->hasFile('proof_file')) {
            $path = $request->file('proof_file')->store('task_proofs', 'public');
            $proofUrl = '/storage/' . $path;
        }

        $task->update([
            'proof_file_url' => $proofUrl ?? $task->proof_file_url,
            'proof_notes' => $request->input('proof_notes') ?? $task->proof_notes,
            'status' => TaskStatus::Review
        ]);

        return redirect()->back()->with('success', "Bukti pengerjaan '{$task->title}' berhasil dikirim! Menunggu verifikasi Guru/Admin.");
    }
}