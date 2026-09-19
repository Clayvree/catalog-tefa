<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FulfillmentStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'tefa_unit_id',
        'user_id',
        'project_id',
        'order_type',
        'fulfillment_method',
        'shipping_address',
        'shipping_city',
        'shipping_courier',
        'payment_method',
        'payment_status',
        'payment_proof_url',
        'digital_access_token',
        'customer_name',
        'customer_contact',
        'notes',
        'total_price',
        'status',
        'fulfillment_status',
        'estimated_ready_at',
        'tracking_number',
        'fulfillment_notes',
        'order_date',
    ];

    protected function casts(): array
    {
        return [
            'total_price'        => 'decimal:2',
            'order_date'         => 'datetime',
            'estimated_ready_at' => 'datetime',
            'fulfillment_status' => FulfillmentStatus::class,
        ];
    }

    public function tefaUnit(): BelongsTo
    {
        return $this->belongsTo(TefaUnit::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // --- Helpers ---
    public function isDigital(): bool
    {
        return $this->order_type === 'digital';
    }

    public function isService(): bool
    {
        return $this->order_type === 'service';
    }

    public function isDelivery(): bool
    {
        return $this->fulfillment_method === 'delivery';
    }

    public function isPickup(): bool
    {
        return $this->fulfillment_method === 'pickup_at_tefa';
    }
}