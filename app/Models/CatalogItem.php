<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ItemStatus;
use App\Enums\ItemType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CatalogItem extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'tefa_unit_id',
        'category_id',
        'title',
        'slug',
        'description',
        'price',
        'stock',
        'track_stock',
        'thumbnail_url',
        'digital_file_url',
        'fulfillment_type',
        'weight_gram',
        'item_type',
        'status',
        'embedding',
    ];

    protected function casts(): array
    {
        return [
            'price'     => 'decimal:2',
            'stock'     => 'integer',
            'track_stock' => 'boolean',
            'weight_gram' => 'integer',
            'item_type' => ItemType::class,
            'status'    => ItemStatus::class,
        ];
    }

    protected static function booted(): void
    {
        static::updating(function (CatalogItem $item) {
            if ($item->isDirty('thumbnail_url')) {
                $old = $item->getRawOriginal('thumbnail_url');
                if ($old && !str_starts_with((string) $old, 'http')) {
                    $oldPath = ltrim(str_replace('/storage/', '', (string) $old), '/');
                    if ($oldPath) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                    }
                }
            }
        });

        static::deleting(function (CatalogItem $item) {
            $raw = $item->getRawOriginal('thumbnail_url');
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

    public function scopePublished($query)
    {
        return $query->where('status', ItemStatus::Published);
    }

    public function scopeForUnit($query, string $tefaUnitId)
    {
        return $query->where('tefa_unit_id', $tefaUnitId);
    }

    public function tefaUnit(): BelongsTo
    {
        return $this->belongsTo(TefaUnit::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'catalog_item_category');
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(ItemGallery::class)->orderBy('sort_order');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(ProductLike::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}