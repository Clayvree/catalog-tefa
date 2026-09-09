<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiKnowledgeBase extends Model
{
    protected $fillable = [
        'tefa_unit_id',
        'title',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function tefaUnit(): BelongsTo
    {
        return $this->belongsTo(TefaUnit::class);
    }

    /**
     * Scope: ambil knowledge base yang relevan untuk unit tertentu + knowledge global (null unit)
     */
    public function scopeForUnit($query, ?string $tefaUnitId)
    {
        return $query->where('is_active', true)
            ->where(function ($q) use ($tefaUnitId) {
                $q->whereNull('tefa_unit_id');
                if ($tefaUnitId) {
                    $q->orWhere('tefa_unit_id', $tefaUnitId);
                }
            });
    }
}
