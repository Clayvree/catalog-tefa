<?php

declare(strict_types=1);

namespace App\Enums;

enum TaskPriority: string
{
    case Low    = 'low';
    case Medium = 'medium';
    case High   = 'high';

    public function label(): string
    {
        return match($this) {
            self::Low    => 'Rendah',
            self::Medium => 'Sedang',
            self::High   => 'Tinggi',
        };
    }

    public function badgeColor(): string
    {
        return match($this) {
            self::Low    => 'green',
            self::Medium => 'yellow',
            self::High   => 'red',
        };
    }
}
