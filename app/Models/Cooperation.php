<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cooperation extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'mitra_id',
        'title',
        'proposal_file_url',
        'status',
    ];

    // Relationships
    public function mitra()
    {
        return $this->belongsTo(MitraProfile::class, 'mitra_id');
    }
}
