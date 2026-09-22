<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaImportDraft extends Model
{
    use HasFactory;

    protected $fillable = [
        'tefa_unit_id',
        'uploaded_by',
        'chat_file_path',
        'status',
        'ai_result',
        'error_message',
        'catalog_item_id',
    ];

    protected function casts(): array
    {
        return [
            'ai_result' => 'array',
        ];
    }

    public function tefaUnit(): BelongsTo
    {
        return $this->belongsTo(TefaUnit::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function catalogItem(): BelongsTo
    {
        return $this->belongsTo(CatalogItem::class);
    }
}
