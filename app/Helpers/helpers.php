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
