<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioContributor extends Model
{
    use HasUuids;

    protected $fillable = [
        'portfolio_id',
        'worker_profile_id',
        'guest_name',
        'role',
    ];

    // --- Relationships -------------------------------------------
    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }

    public function workerProfile(): BelongsTo
    {
        return $this->belongsTo(WorkerProfile::class);
    }

    // --- Helpers -------------------------------------------------
    /** Nama yang tampil (akun TEFA atau nama tamu) */
    public function getDisplayNameAttribute(): string
    {
        return $this->workerProfile?->user?->name ?? $this->guest_name ?? 'Anonim';
    }

    /** URL avatar untuk tampilan publik */
    public function getAvatarUrlAttribute(): ?string
    {
        return $this->workerProfile?->avatar_url;
    }
}
