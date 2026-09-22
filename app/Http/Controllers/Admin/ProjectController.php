<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\TefaUnit;
use App\Enums\ProjectStatus;
use Illuminate\Http\Request;
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
        $project->load(['tasks.leader.user', 'tasks.members.user', 'tasks.skill', 'tefaUnit', 'creator']);
        return view('admin.projects.show', compact('project'));
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
