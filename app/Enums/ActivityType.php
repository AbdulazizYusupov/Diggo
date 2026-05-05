<?php

namespace App\Enums;

enum ActivityType: string
{
    case Homework    = 'homework';
    case Exam        = 'exam';
    case Competition = 'competition';
    case Project     = 'project';
    case Other       = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Homework    => 'Uyga vazifa',
            self::Exam        => 'Imtihon',
            self::Competition => 'Musobaqa',
            self::Project     => 'Loyiha',
            self::Other       => 'Boshqa',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Homework    => 'blue',
            self::Exam        => 'red',
            self::Competition => 'purple',
            self::Project     => 'green',
            self::Other       => 'gray',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Homework    => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
            self::Exam        => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            self::Competition => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
            self::Project     => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
            self::Other       => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z',
        };
    }

    /** Tailwind gradient classes [from, to, shadow, bg, text, border] */
    public function gradients(): array
    {
        return match ($this) {
            self::Homework    => ['from-blue-500',    'to-blue-700',    'shadow-blue-200 dark:shadow-blue-900/40',    'bg-blue-100 dark:bg-blue-500/20',    'text-blue-700 dark:text-blue-300',    'border-blue-200 dark:border-blue-500/30'],
            self::Exam        => ['from-rose-500',    'to-rose-700',    'shadow-rose-200 dark:shadow-rose-900/40',    'bg-rose-100 dark:bg-rose-500/20',    'text-rose-700 dark:text-rose-300',    'border-rose-200 dark:border-rose-500/30'],
            self::Competition => ['from-purple-500',  'to-purple-700',  'shadow-purple-200 dark:shadow-purple-900/40',  'bg-purple-100 dark:bg-purple-500/20',  'text-purple-700 dark:text-purple-300',  'border-purple-200 dark:border-purple-500/30'],
            self::Project     => ['from-emerald-500', 'to-emerald-700', 'shadow-emerald-200 dark:shadow-emerald-900/40', 'bg-emerald-100 dark:bg-emerald-500/20', 'text-emerald-700 dark:text-emerald-300', 'border-emerald-200 dark:border-emerald-500/30'],
            self::Other       => ['from-gray-500',    'to-gray-700',    'shadow-gray-200 dark:shadow-gray-900/40',    'bg-gray-100 dark:bg-gray-500/20',    'text-gray-700 dark:text-gray-300',    'border-gray-200 dark:border-gray-500/30'],
        };
    }

    /** Types that require start_time and due_time */
    public function requiresTime(): bool
    {
        return in_array($this, [self::Exam, self::Competition]);
    }

    /** All values as array — useful for validation rules */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
