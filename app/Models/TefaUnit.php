<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TefaUnit extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo_url',
        'banner_url',
        'contact_info',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'contact_info' => 'array',
            'is_active'    => 'boolean',
        ];
    }

    // --- Relationships -------------------------------------------
    public function admins(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tefa_unit_user');
    }

    public function catalogItems(): HasMany
    {
        return $this->hasMany(CatalogItem::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function workerProfiles(): HasMany
    {
        return $this->hasMany(WorkerProfile::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function cmsContents(): HasMany
    {
        return $this->hasMany(CmsContent::class);
    }
}
