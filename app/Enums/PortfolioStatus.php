<?php

declare(strict_types=1);

namespace App\Enums;

enum PortfolioStatus: string
{
    case Pending  = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match($this) {
            self::Pending  => 'Menunggu Review',
            self::Approved => 'Disetujui',
            self::Rejected => 'Ditolak',
        };
    }

    public function badgeColor(): string
    {
        return match($this) {
            self::Pending  => 'yellow',
            self::Approved => 'green',
            self::Rejected => 'red',
        };
    }
}
