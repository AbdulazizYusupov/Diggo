<?php

namespace App\Enums;

enum ActivityStatus: string
{
    case Draft     = 'draft';
    case Active    = 'active';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Draft     => 'Qoralama',
            self::Active    => 'Faol',
            self::Completed => 'Tugallangan',
            self::Cancelled => 'Bekor',
        };
    }

    public function dotClass(): string
    {
        return match ($this) {
            self::Draft     => 'bg-gray-400',
            self::Active    => 'bg-emerald-400',
            self::Completed => 'bg-blue-400',
            self::Cancelled => 'bg-rose-400',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Draft     => 'bg-gray-100 text-gray-700 dark:bg-gray-700/50 dark:text-gray-300 border-gray-200 dark:border-gray-600',
            self::Active    => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300 border-emerald-200 dark:border-emerald-500/30',
            self::Completed => 'bg-blue-50 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300 border-blue-200 dark:border-blue-500/30',
            self::Cancelled => 'bg-rose-50 text-rose-700 dark:bg-rose-500/20 dark:text-rose-300 border-rose-200 dark:border-rose-500/30',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
