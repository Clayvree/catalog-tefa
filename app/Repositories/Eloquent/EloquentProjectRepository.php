<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Project;
use App\Models\Task;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EloquentProjectRepository implements ProjectRepositoryInterface
{
    public function paginateForUnit(string $tefaUnitId, int $perPage = 15): LengthAwarePaginator
    {
        return Project::with(['creator', 'tasks'])->forUnit($tefaUnitId)->latest()->paginate($perPage);
    }

    public function findById(string $id): ?Project
    {
        return Project::with(['tasks.assignedWorker.user', 'tasks.skill'])->find($id);
    }

    public function createWithTasks(array $projectData, array $tasksData): Project
    {
        return DB::transaction(function () use ($projectData, $tasksData) {
            $project = Project::create($projectData);

            foreach ($tasksData as $taskData) {
                $taskData['project_id'] = $project->id;
                $taskData['tefa_unit_id'] = $project->tefa_unit_id;
                Task::create($taskData);
            }

            return $project->load('tasks');
        });
    }
}
