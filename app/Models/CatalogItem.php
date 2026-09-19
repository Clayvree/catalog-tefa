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