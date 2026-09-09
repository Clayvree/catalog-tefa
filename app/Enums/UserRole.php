<?php

declare(strict_types=1);

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin   = 'superadmin';
    case AdminJurusan = 'admin_jurusan';
    case Worker       = 'worker';
    case Public       = 'public';

    public function label(): string
    {
        return match($this) {
            self::SuperAdmin   => 'Super Admin',
            self::AdminJurusan => 'Admin Jurusan',
            self::Worker       => 'Worker / Siswa',
            self::Public       => 'Publik',
        };
    }

    public function canManageUnit(): bool
    {
        return in_array($this, [self::SuperAdmin, self::AdminJurusan]);
    }
}
