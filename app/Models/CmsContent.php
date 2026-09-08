<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'label',
        'value',
        'type',
        'tefa_unit_id',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'string',
        ];
    }

    public function tefaUnit(): BelongsTo
    {
        return $this->belongsTo(TefaUnit::class);
    }

    public static function get(string $key, ?string $tefaUnitId = null, string $default = ''): string
    {
        return static::where('key', $key)
            ->where('tefa_unit_id', $tefaUnitId)
            ->value('value') ?? $default;
    }
}
