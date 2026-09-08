<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatus: string
{
    case Pending   = 'pending';
    case Processed = 'processed';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Pending   => 'Menunggu',
            self::Processed => 'Diproses',
            self::Completed => 'Selesai',
            self::Cancelled => 'Dibatalkan',
        };
    }

    public function badgeColor(): string
    {
        return match($this) {
            self::Pending   => 'yellow',
            self::Processed => 'blue',
            self::Completed => 'green',
            self::Cancelled => 'red',
        };
    }
}
