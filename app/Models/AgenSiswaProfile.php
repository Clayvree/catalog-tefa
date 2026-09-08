<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgenSiswaProfile extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'nisn_nip',
        'school_dept',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
