<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PortfolioStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    protected static function booted(): void
    {
        static::updating(function (Portfolio $portfolio) {
            if ($portfolio->isDirty('thumbnail_url')) {
                $old = $portfolio->getRawOriginal('thumbnail_url');
                if ($old && !str_starts_with((string) $old, 'http')) {
                    $oldPath = ltrim(str_replace('/storage/', '', (string) $old), '/');
                    if ($oldPath) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                    }
                }
            }
        });

        static::deleting(function (Portfolio $portfolio) {
            $raw = $portfolio->getRawOriginal('thumbnail_url');
            if ($raw && !str_starts_with((string) $raw, 'http')) {
                $path = ltrim(str_replace('/storage/', '', (string) $raw), '/');
                if ($path) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                }
            }
        });
    }

    public function getThumbnailUrlAttribute(?string $value): ?string
    {
        if (!$value) {
            return null;
        }
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        if (str_starts_with($value, '/storage/') || str_starts_with($value, 'storage/')) {
            return asset(ltrim($value, '/'));
        }
        if (str_starts_with($value, 'assets/') || str_starts_with($value, 'images/')) {
            return asset($value);
        }
        return asset('storage/' . ltrim($value, '/'));
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

    public function contributors(): HasMany
    {
        return $this->hasMany(PortfolioContributor::class);
    }

    // --- Scopes -------------------------------------------------
    public function scopeApproved(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', PortfolioStatus::Approved);
    }
}
