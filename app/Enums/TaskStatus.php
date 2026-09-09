<?php

declare(strict_types=1);

namespace App\Enums;

enum TaskStatus: string
{
    case Todo       = 'todo';
    case InProgress = 'in_progress';
    case Review     = 'review';
    case Done       = 'done';

    public function label(): string
    {
        return match($this) {
            self::Todo       => 'Todo',
            self::InProgress => 'In Progress',
            self::Review     => 'Review',
            self::Done       => 'Done',
        };
    }

    public function badgeColor(): string
    {
        return match($this) {
            self::Todo       => 'gray',
            self::InProgress => 'blue',
            self::Review     => 'yellow',
            self::Done       => 'green',
        };
    }
}
