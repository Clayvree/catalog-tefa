<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TefaUnit extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo_url',
        'banner_url',
        'contact_info',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'contact_info' => 'array',
            'is_active'    => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::updating(function (TefaUnit $unit) {
            foreach (['banner_url', 'logo_url'] as $field) {
                if ($unit->isDirty($field)) {
                    $old = $unit->getRawOriginal($field);
                    if ($old && !str_starts_with((string) $old, 'http')) {
                        $oldPath = ltrim(str_replace('/storage/', '', (string) $old), '/');
                        if ($oldPath) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                        }
                    }
                }
            }
        });

        static::deleting(function (TefaUnit $unit) {
            // Jika force deleting atau hard delete
            foreach (['banner_url', 'logo_url'] as $field) {
                $val = $unit->getRawOriginal($field);
                if ($val && !str_starts_with((string) $val, 'http')) {
                    $path = ltrim(str_replace('/storage/', '', (string) $val), '/');
                    if ($path) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                    }
                }
            }
        });
    }

    public function getLogoUrlAttribute(?string $value): ?string
    {
        if (!$value) {
            return null;
        }
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        $cleaned = ltrim(str_replace('/storage/', '', $value), '/');
        if (str_starts_with($cleaned, 'assets/') || str_starts_with($cleaned, 'images/')) {
            return asset($cleaned);
        }
        return asset('storage/' . $cleaned);
    }

    public function getBannerUrlAttribute(?string $value): ?string
    {
        if (!$value) {
            return null;
        }
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        $cleaned = ltrim(str_replace('/storage/', '', $value), '/');
        if (str_starts_with($cleaned, 'assets/') || str_starts_with($cleaned, 'images/')) {
            return asset($cleaned);
        }
        return asset('storage/' . $cleaned);
    }

    // --- Relationships -------------------------------------------
    public function admins(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tefa_unit_user');
    }

    public function catalogItems(): HasMany
    {
        return $this->hasMany(CatalogItem::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function workerProfiles(): HasMany
    {
        return $this->hasMany(WorkerProfile::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function cmsContents(): HasMany
    {
        return $this->hasMany(CmsContent::class);
    }
}
