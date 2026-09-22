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
        'goals',
        'team_notes',
        'progress_percentage',
        'leader_id',
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
            'progress_percentage' => 'integer',
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

    public function leader(): BelongsTo
    {
        return $this->belongsTo(WorkerProfile::class, 'leader_id');
    }

    public function members(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(WorkerProfile::class, 'task_worker')
                    ->withTimestamps()
                    ->withPivot('member_task_note');
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    // --- Scopes -------------------------------------------------
    public function scopeForWorker(\Illuminate\Database\Eloquent\Builder $query, string $workerProfileId): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where(function ($q) use ($workerProfileId) {
            $q->where('leader_id', $workerProfileId)
              ->orWhereHas('members', function($sub) use ($workerProfileId) {
                  $sub->where('worker_profiles.id', $workerProfileId);
              });
        });
    }

    public function scopeForUnit(\Illuminate\Database\Eloquent\Builder $query, string $tefaUnitId): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('tefa_unit_id', $tefaUnitId);
    }
}
