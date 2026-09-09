<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiChatSession extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'tefa_unit_id',
        'session_token',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function tefaUnit(): BelongsTo { return $this->belongsTo(TefaUnit::class); }
    public function messages(): HasMany { return $this->hasMany(AiChatMessage::class, 'session_id'); }
}
