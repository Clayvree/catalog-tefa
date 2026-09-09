<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'project_id',
        'tefa_unit_id',
        'title',
        'description',
        'assigned_worker_id',
        'skill_id',
        'ai_recommendation_notes',
        'status',
        'priority',
        'proof_file_url',
        'proof_notes',
        'due_date',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'status'       => TaskStatus::class,
            'priority'     => TaskPriority::class,
            'due_date'     => 'date',
            'completed_at' => 'datetime',
        ];
    }

    // --- Relationships -------------------------------------------
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function tefaUnit(): BelongsTo
    {
        return $this->belongsTo(TefaUnit::class);
    }

    public function assignedWorker(): BelongsTo
    {
        return $this->belongsTo(WorkerProfile::class, 'assigned_worker_id');
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    // --- Scopes -------------------------------------------------
    public function scopeForWorker(\Illuminate\Database\Eloquent\Builder $query, string $workerProfileId): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('assigned_worker_id', $workerProfileId);
    }

    public function scopeForUnit(\Illuminate\Database\Eloquent\Builder $query, string $tefaUnitId): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('tefa_unit_id', $tefaUnitId);
    }
}
