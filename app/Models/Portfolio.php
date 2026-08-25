<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PortfolioStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Portfolio extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'worker_profile_id',
        'tefa_unit_id',
        'title',
        'description',
        'file_url',
        'thumbnail_url',
        'external_link',
        'status',
        'reviewed_by',
        'review_notes',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'status'      => PortfolioStatus::class,
            'reviewed_at' => 'datetime',
        ];
    }

    // --- Relationships -------------------------------------------
    public function worker(): BelongsTo
    {
        return $this->belongsTo(WorkerProfile::class, 'worker_profile_id');
    }

    public function tefaUnit(): BelongsTo
    {
        return $this->belongsTo(TefaUnit::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // --- Scopes -------------------------------------------------
    public function scopeApproved(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', PortfolioStatus::Approved);
    }
}
