<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MitraProfile extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'company_name',
        'address',
        'verification_status',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function catalogItems()
    {
        return $this->hasMany(CatalogItem::class, 'mitra_id');
    }

    public function cooperations()
    {
        return $this->hasMany(Cooperation::class, 'mitra_id');
    }
}
