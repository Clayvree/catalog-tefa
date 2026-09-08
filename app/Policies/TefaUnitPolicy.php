<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\TefaUnit;
use App\Models\User;

class TefaUnitPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::SuperAdmin || $user->role === UserRole::AdminJurusan;
    }

    public function view(User $user, TefaUnit $tefaUnit): bool
    {
        if ($user->role === UserRole::SuperAdmin) {
            return true;
        }

        return $user->managedUnits()->where('tefa_units.id', $tefaUnit->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }

    public function update(User $user, TefaUnit $tefaUnit): bool
    {
        if ($user->role === UserRole::SuperAdmin) {
            return true;
        }

        return $user->role === UserRole::AdminJurusan && $user->managedUnits()->where('tefa_units.id', $tefaUnit->id)->exists();
    }

    public function delete(User $user, TefaUnit $tefaUnit): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }
}
