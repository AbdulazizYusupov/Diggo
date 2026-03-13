<?php
const FLAGS = [
    'uz' => 'UZ',
    'ru' => 'RU',
    'en' => 'EN',
];
const LABELS = [
    'uz' => 'Uzbek',
    'ru' => 'Russian',
    'en' => 'English',
];
const DAY_NAMES = [
    'monday'    => 'Du',
    'tuesday'   => 'Se',
    'wednesday' => 'Ch',
    'thursday'  => 'Pa',
    'friday'    => 'Ju',
    'saturday'  => 'Sh',
    'sunday'    => 'Ya',
];
const statusColors = [
    'paid' =>
    'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
    'pending' =>
    'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
    'failed' =>
    'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
    'cancelled' =>
    'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400',
];
const statusNames = [
    'paid' => 'To\'langan',
    'pending' => 'Kutilmoqda',
    'failed' => 'Muvaffaqiyatsiz',
    'cancelled' => 'Bekor qilingan',
];
const  typeColors = [
    'cash' =>
    'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
    'card' =>
    'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
    'transfer' =>
    'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
    'other' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400',
];
const typeNames = [
    'cash' => 'Naqd',
    'card' => 'Karta',
    'transfer' => 'O\'tkazma',
    'other' => 'Boshqa',
];
if (!function_exists('get_day_name')) {
    function get_day_name($day)
    {
        // Kelayotgan kunni kichik harfga o'giramiz (masalan: Monday -> monday)
        $day = strtolower($day);

        // Constantdan qidiramiz, topilmasa kunning o'zini qaytaramiz
        return DAY_NAMES[$day] ?? $day;
    }
}

if (!function_exists('get_month_name')) {
    /**
     * Oy raqami bo'yicha o'zbekcha nomini qaytaradi
     */
    function get_month_name($monthNumber)
    {
        $months = [
            1 => 'Yanvar',
            2 => 'Fevral',
            3 => 'Mart',
            4 => 'Aprel',
            5 => 'May',
            6 => 'Iyun',
            7 => 'Iyul',
            8 => 'Avgust',
            9 => 'Sentyabr',
            10 => 'Oktyabr',
            11 => 'Noyabr',
            12 => 'Dekabr'
        ];

        return $months[(int)$monthNumber] ?? '';
    }
}

if (!function_exists('get_attendance_years')) {
    /**
     * Davomat uchun yillar ro'yxatini qaytaradi
     */
    function get_attendance_years()
    {
        $currentYear = date('Y');
        // Masalan: o'tgan yildan boshlab, kelgusi yilgacha
        return range($currentYear - 1, $currentYear + 1);
    }
}

if (!function_exists('roleInfo')) {
    /**
     * Rol nomi bo'yicha badge ma'lumotlarini qaytarish
     *
     * @param string $role
     * @return array{label: string, icon: string, class: string}
     */
    function roleInfo(string $role): array
    {
        return match ($role) {
            'admin'    => [
                'label' => 'Admin',
                'icon'  => '🛡️',
                'class' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
            ],
            'teacher'  => [
                'label' => "O'qituvchi",
                'icon'  => '👨‍🏫',
                'class' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
            ],
            'student'  => [
                'label' => "O'quvchi",
                'icon'  => '🎓',
                'class' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
            ],
            'director' => [
                'label' => 'Direktor',
                'icon'  => '🏢',
                'class' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
            ],
            'staff'    => [
                'label' => 'Xodim',
                'icon'  => '👤',
                'class' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
            ],
            default    => [
                'label' => ucfirst($role),
                'icon'  => '👤',
                'class' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
            ],
        };
    }
    // helpers.php

    /**
     * Returns coin tier display config for the attendance system.
     */
    function attendance_coin_tiers(): array
    {
        return [
            [
                'bg'         => 'bg-emerald-50 dark:bg-emerald-500/10',
                'border'     => 'border-emerald-100 dark:border-emerald-500/20',
                'textColor'  => 'text-emerald-600 dark:text-emerald-400',
                'valueColor' => 'text-emerald-700 dark:text-emerald-300',
                'icon'       => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'label'      => 'Vaqtida',
                'value'      => '+2',
            ],
            [
                'bg'         => 'bg-emerald-50 dark:bg-emerald-500/10',
                'border'     => 'border-emerald-100 dark:border-emerald-500/20',
                'textColor'  => 'text-emerald-600 dark:text-emerald-400',
                'valueColor' => 'text-emerald-700 dark:text-emerald-300',
                'icon'       => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'label'      => '0-15 min',
                'value'      => '+2',
            ],
            [
                'bg'         => 'bg-amber-50 dark:bg-amber-500/10',
                'border'     => 'border-amber-100 dark:border-amber-500/20',
                'textColor'  => 'text-amber-600 dark:text-amber-400',
                'valueColor' => 'text-amber-700 dark:text-amber-300',
                'icon'       => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'label'      => '15-30 min',
                'value'      => '+1',
            ],
            [
                'bg'         => 'bg-blue-50 dark:bg-blue-500/10',
                'border'     => 'border-blue-100 dark:border-blue-500/20',
                'textColor'  => 'text-blue-600 dark:text-blue-400',
                'valueColor' => 'text-blue-700 dark:text-blue-300',
                'icon'       => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'label'      => 'Uy vazifa',
                'value'      => '0-3',
            ],
            [
                'bg'         => 'bg-purple-50 dark:bg-purple-500/10',
                'border'     => 'border-purple-100 dark:border-purple-500/20',
                'textColor'  => 'text-purple-600 dark:text-purple-400',
                'valueColor' => 'text-purple-700 dark:text-purple-300',
                'icon'       => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
                'label'      => 'Faollik',
                'value'      => '0-3',
            ],
        ];
    }

    /**
     * Returns a formatted time string (HH:mm) from a lesson's start_time, or empty string.
     */
    function lesson_time_label(?string $startTime): string
    {
        if (! $startTime) return '';
        return substr($startTime, 0, 5);
    }

    /**
     * Returns initials from surname and name strings.
     */
    function user_initials(?string $surname, ?string $name): string
    {
        return substr($surname ?? 'N', 0, 1) . substr($name ?? 'A', 0, 1);
    }
}
