<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Project;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProjectRepositoryInterface
{
    public function paginateForUnit(string $tefaUnitId, int $perPage = 15): LengthAwarePaginator;
    public function findById(string $id): ?Project;
    public function createWithTasks(array $projectData, array $tasksData): Project;
}
