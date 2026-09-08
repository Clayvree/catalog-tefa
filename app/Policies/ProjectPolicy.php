<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::SuperAdmin || $user->role === UserRole::AdminJurusan;
    }

    public function view(User $user, Project $project): bool
    {
        if ($user->role === UserRole::SuperAdmin) {
            return true;
        }

        return $user->role === UserRole::AdminJurusan && $user->managedUnits()->where('tefa_units.id', $project->tefa_unit_id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::AdminJurusan;
    }

    public function update(User $user, Project $project): bool
    {
        return $user->role === UserRole::AdminJurusan && $user->managedUnits()->where('tefa_units.id', $project->tefa_unit_id)->exists();
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->role === UserRole::AdminJurusan && $user->managedUnits()->where('tefa_units.id', $project->tefa_unit_id)->exists();
    }
}
