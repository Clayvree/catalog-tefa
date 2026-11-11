<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioContributor extends Model
{
    use HasUuids;

    protected $fillable = [
        'portfolio_id',
        'worker_profile_id',
        'guest_name',
        'role',
    ];

    protected $appends = [
        'display_name',
        'avatar_url',
        'role_title',
        'linkedin_url',
        'social_url',
        'class_name',
    ];

    // --- Relationships -------------------------------------------
    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }

    public function workerProfile(): BelongsTo
    {
        return $this->belongsTo(WorkerProfile::class);
    }

    // --- Helpers -------------------------------------------------
    /** Nama yang tampil (akun TEFA atau nama tamu) */
    public function getDisplayNameAttribute(): string
    {
        return $this->workerProfile?->user?->name ?? $this->guest_name ?? 'Anonim';
    }

    /** URL avatar untuk tampilan publik */
    public function getAvatarUrlAttribute(): ?string
    {
        return $this->workerProfile?->avatar_url;
    }

    /** Judul peran siswa di proyek (mendukung JSON terstruktur maupun teks string biasa) */
    public function getRoleTitleAttribute(): string
    {
        if ($this->role && str_starts_with($this->role, '{')) {
            $data = json_decode($this->role, true);
            if (is_array($data) && !empty($data['role'])) {
                return (string) $data['role'];
            }
        }
        return $this->role ?: 'Kontributor Tim';
    }

    /** URL LinkedIn siswa jika diisi */
    public function getLinkedinUrlAttribute(): ?string
    {
        if ($this->role && str_starts_with($this->role, '{')) {
            $data = json_decode($this->role, true);
            if (is_array($data) && !empty($data['linkedin'])) {
                return (string) $data['linkedin'];
            }
        }
        return null;
    }

    /** URL Media Sosial (Instagram/GitHub/Portfolio) jika diisi */
    public function getSocialUrlAttribute(): ?string
    {
        if ($this->role && str_starts_with($this->role, '{')) {
            $data = json_decode($this->role, true);
            if (is_array($data) && !empty($data['social'])) {
                return (string) $data['social'];
            }
        }
        return null;
    }

    /** Kelas / Jurusan siswa (dari akun worker atau payload manual) */
    public function getClassNameAttribute(): ?string
    {
        if ($this->workerProfile?->class_name) {
            return $this->workerProfile->class_name;
        }
        if ($this->role && str_starts_with($this->role, '{')) {
            $data = json_decode($this->role, true);
            if (is_array($data) && !empty($data['class_name'])) {
                return (string) $data['class_name'];
            }
        }
        return null;
    }

    /** Helper untuk mengemas peran dan tautan medsos menjadi payload role */
    public static function formatRolePayload(?string $role, ?string $linkedin = null, ?string $social = null, ?string $className = null): string
    {
        $role = trim((string) $role);
        $linkedin = trim((string) $linkedin);
        $social = trim((string) $social);
        $className = trim((string) $className);

        if (empty($linkedin) && empty($social) && empty($className)) {
            return $role ?: 'Kontributor Tim';
        }

        return json_encode([
            'role'       => $role ?: 'Kontributor Tim',
            'linkedin'   => $linkedin ?: null,
            'social'     => $social ?: null,
            'class_name' => $className ?: null,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
