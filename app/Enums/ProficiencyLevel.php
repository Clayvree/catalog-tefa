<?php

declare(strict_types=1);

namespace App\Enums;

enum ProficiencyLevel: string
{
    case Beginner     = 'beginner';
    case Intermediate = 'intermediate';
    case Advanced     = 'advanced';

    public function label(): string
    {
        return match($this) {
            self::Beginner     => 'Pemula',
            self::Intermediate => 'Menengah',
            self::Advanced     => 'Mahir',
        };
    }
}
