<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'catalog_item_id',
        'item_title',
        'unit_price',
        'quantity',
        'subtotal',
        'price_at_purchase',
    ];

    protected function casts(): array
    {
        return [
            'quantity'          => 'integer',
            'unit_price'        => 'decimal:2',
            'subtotal'          => 'decimal:2',
            'price_at_purchase' => 'decimal:2',
        ];
    }

    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function catalogItem(): BelongsTo { return $this->belongsTo(CatalogItem::class); }
}
