<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Task $task): bool
    {
        if ($user->role === UserRole::SuperAdmin) {
            return true;
        }

        if ($user->role === UserRole::AdminJurusan) {
             return $user->managedUnits()->where('tefa_units.id', $task->tefa_unit_id)->exists();
        }

        if ($user->role === UserRole::Worker) {
            return $task->assigned_worker_id === $user->workerProfile?->id 
                || $task->tefa_unit_id === $user->workerProfile?->tefa_unit_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::AdminJurusan;
    }

    public function update(User $user, Task $task): bool
    {
        if ($user->role === UserRole::AdminJurusan) {
             return $user->managedUnits()->where('tefa_units.id', $task->tefa_unit_id)->exists();
        }

        if ($user->role === UserRole::Worker) {
            return $task->assigned_worker_id === $user->workerProfile?->id
                || $task->tefa_unit_id === $user->workerProfile?->tefa_unit_id;
        }

        return false;
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->role === UserRole::AdminJurusan && $user->managedUnits()->where('tefa_units.id', $task->tefa_unit_id)->exists();
    }
}