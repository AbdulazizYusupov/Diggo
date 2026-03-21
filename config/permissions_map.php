<?php

/**
 * Route permission name => Uzbek label mapping.
 *
 * IMPORTANT: Only routes inside CheckPermission middleware belong here.
 * Public/auth routes (login, logout, dashboard, profile, notifications,
 * locale, impersonate) are on the BLACKLIST in RoutePermissionFilter
 * and must NOT appear in this file.
 */

return [

    // STAFFS
    'staffs.index'   => "Xodimlar ro'yxati",
    'staffs.create'  => "Xodim qo'shish sahifasi",
    'staffs.store'   => "Xodim qo'shish",
    'staffs.show'    => "Xodim ma'lumotlari",
    'staffs.edit'    => "Xodimni tahrirlash sahifasi",
    'staffs.update'  => "Xodimni tahrirlash",
    'staffs.destroy' => "Xodimni o'chirish",

    // DIRECTORS
    'directors.index'   => "Direktorlar ro'yxati",
    'directors.create'  => "Direktor qo'shish sahifasi",
    'directors.store'   => "Direktor qo'shish",
    'directors.show'    => "Direktor ma'lumotlari",
    'directors.edit'    => "Direktorni tahrirlash sahifasi",
    'directors.update'  => "Direktorni tahrirlash",
    'directors.destroy' => "Direktorni o'chirish",

    // TEACHERS
    'teachers.index'   => "O'qituvchilar ro'yxati",
    'teachers.create'  => "O'qituvchi qo'shish sahifasi",
    'teachers.store'   => "O'qituvchi qo'shish",
    'teachers.show'    => "O'qituvchi ma'lumotlari",
    'teachers.edit'    => "O'qituvchini tahrirlash sahifasi",
    'teachers.update'  => "O'qituvchini tahrirlash",
    'teachers.destroy' => "O'qituvchini o'chirish",

    // STUDENTS
    'students.index'   => "O'quvchilar ro'yxati",
    'students.create'  => "O'quvchi qo'shish sahifasi",
    'students.store'   => "O'quvchi qo'shish",
    'students.show'    => "O'quvchi ma'lumotlari",
    'students.edit'    => "O'quvchini tahrirlash sahifasi",
    'students.update'  => "O'quvchini tahrirlash",
    'students.destroy' => "O'quvchini o'chirish",

    // ROLES
    'roles.index'   => "Rollar ro'yxati",
    'roles.store'   => "Rol yaratish",
    'roles.update'  => "Rolni tahrirlash",
    'roles.destroy' => "Rolni o'chirish",

    // PERMISSIONS
    'permissions.index'        => "Ruxsatlar ro'yxati",
    'permissions.update'       => "Ruxsatni tahrirlash",
    'permissions.destroy'      => "Ruxsatni o'chirish",
    'permissions.sync-routes'  => "Routelarni sinxronlash",

    // ROLE-PERMISSIONS
    'role-permissions.index'       => "Rol ruxsatlari ro'yxati",
    'role-permissions.show'        => "Rol ruxsatlarini ko'rish",
    'role-permissions.edit'        => "Rol ruxsatlarini tahrirlash sahifasi",
    'role-permissions.update'      => "Rol ruxsatlarini saqlash",
    'role-permissions.copy'        => "Rol ruxsatlarini nusxalash",
    'role-permissions.bulk-assign' => "Ruxsatlarni ommaviy biriktirish",
    'role-permissions.revoke-all'  => "Barcha ruxsatlarni bekor qilish",

    // SETTINGS
    'settings.index'       => "Sozlamalar",
    'settings.assign-role' => "Foydalanuvchiga rol berish",
    'settings.remove-role' => "Foydalanuvchidan rolni olib tashlash",
    'settings.sync-roles'  => "Foydalanuvchi rollarini sinxronlash",

    // SCHOOLS
    'schools.index'   => "Maktablar ro'yxati",
    'schools.create'  => "Maktab qo'shish sahifasi",
    'schools.store'   => "Maktab qo'shish",
    'schools.show'    => "Maktab ma'lumotlari",
    'schools.edit'    => "Maktabni tahrirlash sahifasi",
    'schools.update'  => "Maktabni tahrirlash",
    'schools.destroy' => "Maktabni o'chirish",

    // COURSES
    'courses.index'   => "Kurslar ro'yxati",
    'courses.create'  => "Kurs qo'shish sahifasi",
    'courses.store'   => "Kurs qo'shish",
    'courses.show'    => "Kurs ma'lumotlari",
    'courses.edit'    => "Kursni tahrirlash sahifasi",
    'courses.update'  => "Kursni tahrirlash",
    'courses.destroy' => "Kursni o'chirish",

    // GROUPS
    'groups.index'   => "Guruhlar ro'yxati",
    'groups.create'  => "Guruh qo'shish sahifasi",
    'groups.store'   => "Guruh qo'shish",
    'groups.show'    => "Guruh ma'lumotlari",
    'groups.edit'    => "Guruhni tahrirlash sahifasi",
    'groups.update'  => "Guruhni tahrirlash",
    'groups.destroy' => "Guruhni o'chirish",

    // GROUP STUDENTS
    'groups.students.index'   => "Guruhdagi o'quvchilar ro'yxati",
    'groups.students.create'  => "Guruhga o'quvchi qo'shish sahifasi",
    'groups.students.store'   => "Guruhga o'quvchi qo'shish",
    'groups.students.destroy' => "Guruhdan o'quvchini chiqarish",

    // GROUP TEACHERS
    'groups.teachers.index'   => "Guruhdagi o'qituvchilar ro'yxati",
    'groups.teachers.create'  => "Guruhga o'qituvchi qo'shish sahifasi",
    'groups.teachers.store'   => "Guruhga o'qituvchi qo'shish",
    'groups.teachers.primary' => "Asosiy o'qituvchini belgilash",
    'groups.teachers.destroy' => "Guruhdan o'qituvchini chiqarish",
    'group-teachers.index'    => "Guruh o'qituvchilari (umumiy)",
    'group-teachers.store'    => "Guruh o'qituvchisi qo'shish",
    'group-teachers.destroy'  => "Guruh o'qituvchisini o'chirish",

    // PAYMENTS
    'payments.index'        => "To'lovlar ro'yxati",
    'payments.create'       => "To'lov qo'shish sahifasi",
    'payments.store'        => "To'lov qo'shish",
    'payments.show'         => "To'lov ma'lumotlari",
    'payments.edit'         => "To'lovni tahrirlash sahifasi",
    'payments.update'       => "To'lovni tahrirlash",
    'payments.destroy'      => "To'lovni o'chirish",
    'payments.student-info' => "O'quvchi to'lov ma'lumotlari",
    'payments.cancel'       => "To'lovni bekor qilish",

    // ACTIVITIES
    'activities.index'   => "Faoliyatlar ro'yxati",
    'activities.create'  => "Faoliyat qo'shish sahifasi",
    'activities.store'   => "Faoliyat qo'shish",
    'activities.show'    => "Faoliyat ma'lumotlari",
    'activities.edit'    => "Faoliyatni tahrirlash sahifasi",
    'activities.update'  => "Faoliyatni tahrirlash",
    'activities.destroy' => "Faoliyatni o'chirish",

    // ACTIVITY RESULTS
    'activityresults.index'   => "Faoliyat natijalari ro'yxati",
    'activityresults.create'  => "Faoliyat natijasi qo'shish sahifasi",
    'activityresults.store'   => "Faoliyat natijasi qo'shish",
    'activityresults.show'    => "Faoliyat natijasi ma'lumotlari",
    'activityresults.edit'    => "Faoliyat natijasini tahrirlash sahifasi",
    'activityresults.update'  => "Faoliyat natijasini tahrirlash",
    'activityresults.destroy' => "Faoliyat natijasini o'chirish",

    // ATTENDANCE
    'attendance.index'      => "Davomat ro'yxati",
    'attendance.show'       => "Guruh davomati",
    'attendance.store'      => "Davomat saqlash",
    'attendance.bulk-store' => "Ommaviy davomat saqlash",
    'attendance.statistics' => "Davomat statistikasi",

    // LESSONS
    'lessons.index'    => "Darslar ro'yxati",
    'lessons.create'   => "Dars qo'shish sahifasi",
    'lessons.store'    => "Dars qo'shish",
    'lessons.show'     => "Dars ma'lumotlari",
    'lessons.edit'     => "Darsni tahrirlash sahifasi",
    'lessons.update'   => "Darsni tahrirlash",
    'lessons.destroy'  => "Darsni o'chirish",
    'lessons.complete' => "Darsni yakunlash",
    'lessons.cancel'   => "Darsni bekor qilish",

    // TRANSACTIONS
    'transactions.index'  => "Tranzaksiyalar ro'yxati",
    'transactions.show'   => "Tranzaksiya ma'lumotlari",
    'transactions.export' => "Tranzaksiyalarni eksport qilish",

    // COIN TRANSACTIONS
    'coin-transactions.index' => "Tanga tranzaksiyalari ro'yxati",
    'coin-transactions.show'  => "Tanga tranzaksiyasi ma'lumotlari",

    // DIRECTOR DASHBOARD
    'director.dashboard'           => "Direktor bosh sahifasi",
    'director.school.show'         => "Maktab batafsil ma'lumotlari (Direktor)",
    'director.students'            => "O'quvchilar ro'yxati (Direktor)",
    'director.students.show'       => "O'quvchi ma'lumotlari (Direktor)",
    'director.commissions'         => "Komissiyalar (Direktor)",
    'director.commissions.history' => "Komissiyalar tarixi (Direktor)",
    'director.groups'              => "Guruhlar ro'yxati (Direktor)",
    'director.groups.show'         => "Guruh ma'lumotlari (Direktor)",
    'director.reports'             => "Hisobotlar (Direktor)",
    'director.reports.export'      => "Hisobotlarni eksport qilish (Direktor)",

    // STUDENT DASHBOARD
    'student.dashboard'           => "O'quvchi bosh sahifasi",
    'student.groups'              => "Mening guruhlarim",
    'student.groups.show'         => "Guruh ma'lumotlari (O'quvchi)",
    'student.payments'            => "Mening to'lovlarim",
    'student.payments.show'       => "To'lov tafsilotlari (O'quvchi)",
    'student.coins'               => "Mening tangalarim",
    'student.coins.show'          => "Tanga tafsilotlari (O'quvchi)",
    'student.attendance'          => "Mening davomatim",
    'student.attendance.calendar' => "Davomat kalendari (O'quvchi)",
    'student.schedule'            => "Dars jadvali",
    'student.schedule.upcoming'   => "Kelgusi darslar",
    'student.leaderboard'         => "Reyting jadvali",
    'student.statistics'          => "O'quvchi statistikasi",
    'student.profile'             => "O'quvchi profili",
    'student.profile.update'      => "Profilni yangilash (O'quvchi)",
    'student.profile.parent'      => "Ota-ona ma'lumotlarini yangilash",
    'student.profile.password'    => "Parolni o'zgartirish (O'quvchi)",
    'student.profile.statistics'  => "Profil statistikasi (O'quvchi)",
    'student.shop.index'          => "Do'kon (O'quvchi)",
    'student.shop.show'           => "Mahsulot tafsilotlari (O'quvchi)",
    'student.shop.order'          => "Buyurtma berish",
    'student.shop.orders'         => "Mening buyurtmalarim",
    'student.shop.cancel'         => "Buyurtmani bekor qilish",
    'student.activities.index'    => "Faoliyatlar ro'yxati (O'quvchi)",
    'student.activities.show'     => "Faoliyat tafsilotlari (O'quvchi)",

    // TEACHER DASHBOARD
    'teacher.dashboard'              => "O'qituvchi bosh sahifasi",
    'teacher.groups.index'           => "Mening guruhlarim (O'qituvchi)",
    'teacher.groups.show'            => "Guruh ma'lumotlari (O'qituvchi)",
    'teacher.groups.lessons.index'   => "Guruh darslari ro'yxati",
    'teacher.groups.lessons.create'  => "Dars qo'shish sahifasi (O'qituvchi)",
    'teacher.groups.lessons.store'   => "Dars qo'shish (O'qituvchi)",
    'teacher.groups.lessons.edit'    => "Darsni tahrirlash (O'qituvchi)",
    'teacher.groups.lessons.update'  => "Darsni yangilash (O'qituvchi)",
    'teacher.groups.lessons.destroy' => "Darsni o'chirish (O'qituvchi)",

    // ADMIN SHOP
    'admin.shop.categories.index'         => "Do'kon kategoriyalari ro'yxati",
    'admin.shop.categories.create'        => "Kategoriya qo'shish sahifasi",
    'admin.shop.categories.store'         => "Kategoriya qo'shish",
    'admin.shop.categories.show'          => "Kategoriya ma'lumotlari",
    'admin.shop.categories.edit'          => "Kategoriyani tahrirlash sahifasi",
    'admin.shop.categories.update'        => "Kategoriyani tahrirlash",
    'admin.shop.categories.destroy'       => "Kategoriyani o'chirish",
    'admin.shop.categories.toggle-status' => "Kategoriya holatini o'zgartirish",
    'admin.shop.products.index'           => "Mahsulotlar ro'yxati",
    'admin.shop.products.create'          => "Mahsulot qo'shish sahifasi",
    'admin.shop.products.store'           => "Mahsulot qo'shish",
    'admin.shop.products.show'            => "Mahsulot ma'lumotlari",
    'admin.shop.products.edit'            => "Mahsulotni tahrirlash sahifasi",
    'admin.shop.products.update'          => "Mahsulotni tahrirlash",
    'admin.shop.products.destroy'         => "Mahsulotni o'chirish",
    'admin.shop.products.toggle-status'   => "Mahsulot holatini o'zgartirish",
    'admin.shop.products.toggle-featured' => "Mahsulotni tavsiya etilgan qilish",
    'admin.shop.orders.index'             => "Buyurtmalar ro'yxati (Admin)",
    'admin.shop.orders.show'              => "Buyurtma tafsilotlari (Admin)",
    'admin.shop.orders.approve'           => "Buyurtmani tasdiqlash",
    'admin.shop.orders.deliver'           => "Buyurtmani yetkazib berildi deb belgilash",
    'admin.shop.orders.reject'            => "Buyurtmani rad etish",

    // HELP
    'help.index'        => "Yordam markazi",
    'help.send'         => "Murojaat yuborish",
    'help.download-pdf' => "PDF qo'llanmani yuklab olish",

    // ACTIVITY LOGS
    'activity-logs.index'   => "Foydalanuvchi faoliyat jurnali",
    'activity-logs.online'  => "Onlayn foydalanuvchilar",
    'activity-logs.show'    => "Foydalanuvchi faoliyati",
    'activity-logs.destroy' => "Faoliyat yozuvini o'chirish",
    'activity-logs.clear'   => "Eski yozuvlarni tozalash",

    // LOGIN ATTEMPTS
    'login-attempts.index'         => "Kirish urinishlari ro'yxati",
    'login-attempts.show-ip'       => "IP bo'yicha kirish urinishlari",
    'login-attempts.show-phone'    => "Telefon bo'yicha kirish urinishlari",
    'login-attempts.unblock-ip'    => "IP blokdan chiqarish",
    'login-attempts.unblock-phone' => "Telefon blokdan chiqarish",
    'login-attempts.destroy'       => "Kirish urinishini o'chirish",
    'login-attempts.destroy-old'   => "Eski kirish urinishlarini o'chirish",

    // TRASHED USERS
    'trashed-users.index'       => "O'chirilgan foydalanuvchilar",
    'trashed-users.restore'     => "Foydalanuvchini tiklash",
    'trashed-users.force-delete'=> "Foydalanuvchini butunlay o'chirish",
    'trashed-users.restore-all' => "Barcha o'chirilganlarni tiklash",
];