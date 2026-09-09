<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, HasUuids, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'role'              => UserRole::class,
        ];
    }

    // --- Helpers ------------------------------------------------
    public function isSuperAdmin(): bool
    {
        return $this->role === UserRole::SuperAdmin;
    }

    public function isAdminJurusan(): bool
    {
        return $this->role === UserRole::AdminJurusan;
    }

    public function isWorker(): bool
    {
        return $this->role === UserRole::Worker;
    }

    // --- Relationships -------------------------------------------
    public function workerProfile(): HasOne
    {
        return $this->hasOne(WorkerProfile::class);
    }

    public function managedUnits(): BelongsToMany
    {
        return $this->belongsToMany(TefaUnit::class, 'tefa_unit_user');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function productLikes(): HasMany
    {
        return $this->hasMany(ProductLike::class);
    }

    public function aiChatSessions(): HasMany
    {
        return $this->hasMany(AiChatSession::class);
    }

    public function approvedPortfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class, 'reviewed_by');
    }
}
