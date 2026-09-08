<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'catalog_item_id',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function catalogItem(): BelongsTo { return $this->belongsTo(CatalogItem::class); }
}
