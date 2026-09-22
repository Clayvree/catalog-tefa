<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'tefa_unit_id',
        'title',
        'client_name',
        'client_contact',
        'description',
        'estimated_price',
        'catalog_item_id',
        'source_chat_file_url',
        'ai_extraction_data',
        'final_price',
        'status',
        'deadline',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'ai_extraction_data' => 'array',
            'estimated_price'    => 'decimal:2',
            'final_price'        => 'decimal:2',
            'status'             => ProjectStatus::class,
            'deadline'           => 'date',
        ];
    }

    // --- Relationships -------------------------------------------
    public function catalogItem(): BelongsTo
    {
        return $this->belongsTo(CatalogItem::class);
    }

    public function tefaUnit(): BelongsTo
    {
        return $this->belongsTo(TefaUnit::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // --- Scopes -------------------------------------------------
    public function scopeForUnit(\Illuminate\Database\Eloquent\Builder $query, string $tefaUnitId): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('tefa_unit_id', $tefaUnitId);
    }
}
