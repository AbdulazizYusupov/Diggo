<?php
const DAY_NAMES = [
    'monday'    => 'Du',
    'tuesday'   => 'Se',
    'wednesday' => 'Ch',
    'thursday'  => 'Pa',
    'friday'    => 'Ju',
    'saturday'  => 'Sh',
    'sunday'    => 'Ya',
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
}
