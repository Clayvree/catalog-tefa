<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'catalog_item_id',
        'image_url',
        'sort_order',
    ];

    protected function casts(): array
    {
        return ['sort_order' => 'integer'];
    }

    public function catalogItem(): BelongsTo { return $this->belongsTo(CatalogItem::class); }
}
