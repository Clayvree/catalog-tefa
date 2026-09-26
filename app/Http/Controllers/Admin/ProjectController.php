<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\TefaUnit;
use App\Models\WorkerProfile;
use App\Enums\TaskPriority;
use App\Enums\ProjectStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $managedUnit = $request->user()->managedUnits()->first() ?? TefaUnit::first();
        $unitId = $managedUnit?->id;

        $query = Project::with(['tasks.leader.user', 'tasks.members.user', 'creator'])->forUnit($unitId);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $keyword = trim((string) $request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('client_name', 'like', "%{$keyword}%");
            });
        }

        $projects = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => Project::forUnit($unitId)->count(),
            'active' => Project::forUnit($unitId)->where('status', ProjectStatus::Active->value)->count(),
            'draft' => Project::forUnit($unitId)->where('status', ProjectStatus::Draft->value)->count(),
            'completed' => Project::forUnit($unitId)->where('status', ProjectStatus::Completed->value)->count(),
        ];

        return view('admin.projects.index', compact('projects', 'managedUnit', 'stats'));
    }

    public function show(Project $project)
    {
        $project->load(['tasks.leader.user', 'tasks.members.user', 'tasks.skill', 'tefaUnit', 'creator', 'orders']);
        $workers = WorkerProfile::with('user')
            ->where('tefa_unit_id', $project->tefa_unit_id)
            ->orderBy('user_id')
            ->get();

        return view('admin.projects.show', compact('project', 'workers'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'client_contact' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'final_price' => 'nullable|numeric|min:0',
            'deadline' => 'nullable|date',
            'status' => ['required', new Enum(ProjectStatus::class)],
        ]);

        $project->update([
            'title' => $validated['title'],
            'client_name' => $validated['client_name'],
            'client_contact' => $validated['client_contact'] ?? null,
            'description' => $validated['description'] ?? null,
            'final_price' => $validated['final_price'] ?? $project->final_price,
            'deadline' => $validated['deadline'] ?? $project->deadline,
            'status' => $validated['status'],
        ]);

        $order = $project->orders()->first();
        if ($order && isset($validated['final_price'])) {
            $order->update(['total_price' => $validated['final_price']]);

            $orderItem = $order->items()->first();
            if ($orderItem) {
                $orderItem->update([
                    'unit_price' => $validated['final_price'],
                    'subtotal' => $validated['final_price'] * $orderItem->quantity,
                ]);
            }
        }

        return redirect()->back()->with('success', "Proyek '{$project->title}' berhasil diperbarui!");
    }

    public function updateStatus(Request $request, Project $project)
    {
        $validated = $request->validate([
            'status' => ['required', new Enum(ProjectStatus::class)],
        ]);

        $project->status = $validated['status'];
        $project->save();

        return redirect()->back()->with('success', "Status proyek '{$project->title}' berhasil diubah ke {$project->status->value}!");
    }

    public function updateTask(Request $request, Task $task)
    {
        $managedUnit = $request->user()->managedUnits()->first();
        abort_if(!$managedUnit || $task->tefa_unit_id !== $managedUnit->id, 403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'goals' => 'nullable|string',
            'reasoning' => 'nullable|string',
            'leader_id' => [
                'nullable',
                'uuid',
                Rule::exists('worker_profiles', 'id')->where('tefa_unit_id', $managedUnit->id),
            ],
            'member_ids' => 'nullable|array',
            'member_ids.*' => [
                'uuid',
                Rule::exists('worker_profiles', 'id')->where('tefa_unit_id', $managedUnit->id),
            ],
            'priority' => ['required', new \Illuminate\Validation\Rules\Enum(TaskPriority::class)],
            'due_date' => 'nullable|date',
        ]);

        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'goals' => $validated['goals'] ?? null,
            'ai_recommendation_notes' => $validated['reasoning'] ?? null,
            'leader_id' => $validated['leader_id'] ?? null,
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'] ?? null,
        ]);
        $task->members()->sync($validated['member_ids'] ?? []);

        return redirect()->back()->with('success', "Rincian tugas '{$task->title}' berhasil diperbarui.");
    }

    public function destroy(Project $project)
    {
        $project->tasks()->delete();
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil dihapus.');
    }

    public function approveTask(Request $request, \App\Models\Task $task)
    {
        $task->update([
            'status' => 'done',
            'completed_at' => now(),
            'progress_percentage' => 100,
        ]);

        return redirect()->back()->with('success', "Tugas '{$task->title}' berhasil di-ACC (Selesai).");
    }
}
