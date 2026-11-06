<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ItemType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'tefa_unit_id',
        'name',
        'type',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'type' => ItemType::class,
        ];
    }

    protected static function booted(): void
    {
        static::updating(function (Category $category) {
            if ($category->isDirty('image')) {
                $old = $category->getRawOriginal('image');
                if ($old && !str_starts_with((string) $old, 'http')) {
                    $oldPath = ltrim(str_replace('/storage/', '', (string) $old), '/');
                    if ($oldPath) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                    }
                }
            }
        });

        static::deleting(function (Category $category) {
            $raw = $category->getRawOriginal('image');
            if ($raw && !str_starts_with((string) $raw, 'http')) {
                $oldPath = ltrim(str_replace('/storage/', '', (string) $raw), '/');
                if ($oldPath) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                }
            }
        });
    }

    public function getImageAttribute(?string $value): ?string
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

    public function tefaUnit(): BelongsTo
    {
        return $this->belongsTo(TefaUnit::class);
    }

    public function catalogItems(): HasMany
    {
        return $this->hasMany(CatalogItem::class);
    }

    public function catalogItemsMany(): BelongsToMany
    {
        return $this->belongsToMany(CatalogItem::class, 'catalog_item_category');
    }
}
