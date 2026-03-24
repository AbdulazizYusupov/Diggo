<?php

/**
 * RUSSIAN — Route permission name => label mapping.
 * File: config/permissions_map_ru.php
 */

return [

    // STAFFS
    'staffs.index'   => "Список сотрудников",
    'staffs.create'  => "Страница добавления сотрудника",
    'staffs.store'   => "Добавить сотрудника",
    'staffs.show'    => "Данные сотрудника",
    'staffs.edit'    => "Страница редактирования сотрудника",
    'staffs.update'  => "Редактировать сотрудника",
    'staffs.destroy' => "Удалить сотрудника",

    // DIRECTORS
    'directors.index'   => "Список директоров",
    'directors.create'  => "Страница добавления директора",
    'directors.store'   => "Добавить директора",
    'directors.show'    => "Данные директора",
    'directors.edit'    => "Страница редактирования директора",
    'directors.update'  => "Редактировать директора",
    'directors.destroy' => "Удалить директора",

    // TEACHERS
    'teachers.index'   => "Список учителей",
    'teachers.create'  => "Страница добавления учителя",
    'teachers.store'   => "Добавить учителя",
    'teachers.show'    => "Данные учителя",
    'teachers.edit'    => "Страница редактирования учителя",
    'teachers.update'  => "Редактировать учителя",
    'teachers.destroy' => "Удалить учителя",

    // STUDENTS
    'students.index'   => "Список учеников",
    'students.create'  => "Страница добавления ученика",
    'students.store'   => "Добавить ученика",
    'students.show'    => "Данные ученика",
    'students.edit'    => "Страница редактирования ученика",
    'students.update'  => "Редактировать ученика",
    'students.destroy' => "Удалить ученика",

    // ROLES
    'roles.index'   => "Список ролей",
    'roles.store'   => "Создать роль",
    'roles.update'  => "Редактировать роль",
    'roles.destroy' => "Удалить роль",

    // PERMISSIONS
    'permissions.index'       => "Список разрешений",
    'permissions.update'      => "Редактировать разрешение",
    'permissions.destroy'     => "Удалить разрешение",
    'permissions.sync-routes' => "Синхронизировать маршруты",

    // ROLE-PERMISSIONS
    'role-permissions.index'       => "Список прав роли",
    'role-permissions.show'        => "Просмотр прав роли",
    'role-permissions.edit'        => "Страница редактирования прав роли",
    'role-permissions.update'      => "Сохранить права роли",
    'role-permissions.copy'        => "Копировать права роли",
    'role-permissions.bulk-assign' => "Массовое назначение прав",
    'role-permissions.revoke-all'  => "Отозвать все права",

    // SETTINGS
    'settings.index'       => "Настройки",
    'settings.assign-role' => "Назначить роль пользователю",
    'settings.remove-role' => "Снять роль с пользователя",
    'settings.sync-roles'  => "Синхронизировать роли пользователя",

    // SCHOOLS
    'schools.index'   => "Список школ",
    'schools.create'  => "Страница добавления школы",
    'schools.store'   => "Добавить школу",
    'schools.show'    => "Данные школы",
    'schools.edit'    => "Страница редактирования школы",
    'schools.update'  => "Редактировать школу",
    'schools.destroy' => "Удалить школу",

    // COURSES
    'courses.index'   => "Список курсов",
    'courses.create'  => "Страница добавления курса",
    'courses.store'   => "Добавить курс",
    'courses.show'    => "Данные курса",
    'courses.edit'    => "Страница редактирования курса",
    'courses.update'  => "Редактировать курс",
    'courses.destroy' => "Удалить курс",

    // GROUPS
    'groups.index'   => "Список групп",
    'groups.create'  => "Страница добавления группы",
    'groups.store'   => "Добавить группу",
    'groups.show'    => "Данные группы",
    'groups.edit'    => "Страница редактирования группы",
    'groups.update'  => "Редактировать группу",
    'groups.destroy' => "Удалить группу",

    // GROUP STUDENTS
    'groups.students.index'   => "Список учеников группы",
    'groups.students.create'  => "Страница добавления ученика в группу",
    'groups.students.store'   => "Добавить ученика в группу",
    'groups.students.destroy' => "Удалить ученика из группы",

    // GROUP TEACHERS
    'groups.teachers.index'   => "Список учителей группы",
    'groups.teachers.create'  => "Страница добавления учителя в группу",
    'groups.teachers.store'   => "Добавить учителя в группу",
    'groups.teachers.primary' => "Назначить основного учителя",
    'groups.teachers.destroy' => "Удалить учителя из группы",
    'group-teachers.index'    => "Учителя групп (общий список)",
    'group-teachers.store'    => "Добавить учителя группы",
    'group-teachers.destroy'  => "Удалить учителя группы",

    // PAYMENTS
    'payments.index'        => "Список платежей",
    'payments.create'       => "Страница добавления платежа",
    'payments.store'        => "Добавить платёж",
    'payments.show'         => "Данные платежа",
    'payments.edit'         => "Страница редактирования платежа",
    'payments.update'       => "Редактировать платёж",
    'payments.destroy'      => "Удалить платёж",
    'payments.student-info' => "Данные платежей ученика",
    'payments.cancel'       => "Отменить платёж",

    // ACTIVITIES
    'activities.index'   => "Список активностей",
    'activities.create'  => "Страница добавления активности",
    'activities.store'   => "Добавить активность",
    'activities.show'    => "Данные активности",
    'activities.edit'    => "Страница редактирования активности",
    'activities.update'  => "Редактировать активность",
    'activities.destroy' => "Удалить активность",

    // ACTIVITY RESULTS
    'activityresults.index'   => "Список результатов активностей",
    'activityresults.create'  => "Страница добавления результата",
    'activityresults.store'   => "Добавить результат активности",
    'activityresults.show'    => "Данные результата активности",
    'activityresults.edit'    => "Страница редактирования результата",
    'activityresults.update'  => "Редактировать результат активности",
    'activityresults.destroy' => "Удалить результат активности",

    // ATTENDANCE
    'attendance.index'      => "Список посещаемости",
    'attendance.show'       => "Посещаемость группы",
    'attendance.store'      => "Сохранить посещаемость",
    'attendance.bulk-store' => "Массовое сохранение посещаемости",
    'attendance.statistics' => "Статистика посещаемости",

    // LESSONS
    'lessons.index'    => "Список уроков",
    'lessons.create'   => "Страница добавления урока",
    'lessons.store'    => "Добавить урок",
    'lessons.show'     => "Данные урока",
    'lessons.edit'     => "Страница редактирования урока",
    'lessons.update'   => "Редактировать урок",
    'lessons.destroy'  => "Удалить урок",
    'lessons.complete' => "Завершить урок",
    'lessons.cancel'   => "Отменить урок",

    // TRANSACTIONS
    'transactions.index'  => "Список транзакций",
    'transactions.show'   => "Данные транзакции",
    'transactions.export' => "Экспорт транзакций",

    // COIN TRANSACTIONS
    'coin-transactions.index' => "Список монетных транзакций",
    'coin-transactions.show'  => "Данные монетной транзакции",

    // DIRECTOR DASHBOARD
    'director.dashboard'           => "Главная страница директора",
    'director.school.show'         => "Подробные данные школы (Директор)",
    'director.students'            => "Список учеников (Директор)",
    'director.students.show'       => "Данные ученика (Директор)",
    'director.commissions'         => "Комиссии (Директор)",
    'director.commissions.history' => "История комиссий (Директор)",
    'director.groups'              => "Список групп (Директор)",
    'director.groups.show'         => "Данные группы (Директор)",
    'director.reports'             => "Отчёты (Директор)",
    'director.reports.export'      => "Экспорт отчётов (Директор)",

    // STUDENT DASHBOARD
    'student.dashboard'           => "Главная страница ученика",
    'student.groups'              => "Мои группы",
    'student.groups.show'         => "Данные группы (Ученик)",
    'student.payments'            => "Мои платежи",
    'student.payments.show'       => "Детали платежа (Ученик)",
    'student.coins'               => "Мои монеты",
    'student.coins.show'          => "Детали монет (Ученик)",
    'student.attendance'          => "Моя посещаемость",
    'student.attendance.calendar' => "Календарь посещаемости (Ученик)",
    'student.schedule'            => "Расписание уроков",
    'student.schedule.upcoming'   => "Предстоящие уроки",
    'student.leaderboard'         => "Таблица лидеров",
    'student.statistics'          => "Статистика ученика",
    'student.profile'             => "Профиль ученика",
    'student.profile.update'      => "Обновить профиль (Ученик)",
    'student.profile.parent'      => "Обновить данные родителя",
    'student.profile.password'    => "Изменить пароль (Ученик)",
    'student.profile.statistics'  => "Статистика профиля (Ученик)",
    'student.shop.index'          => "Магазин (Ученик)",
    'student.shop.show'           => "Детали товара (Ученик)",
    'student.shop.order'          => "Сделать заказ",
    'student.shop.orders'         => "Мои заказы",
    'student.shop.cancel'         => "Отменить заказ",
    'student.activities.index'    => "Список активностей (Ученик)",
    'student.activities.show'     => "Детали активности (Ученик)",

    // TEACHER DASHBOARD
    'teacher.dashboard'              => "Главная страница учителя",
    'teacher.groups.index'           => "Мои группы (Учитель)",
    'teacher.groups.show'            => "Данные группы (Учитель)",
    'teacher.groups.lessons.index'   => "Список уроков группы",
    'teacher.groups.lessons.create'  => "Страница добавления урока (Учитель)",
    'teacher.groups.lessons.store'   => "Добавить урок (Учитель)",
    'teacher.groups.lessons.edit'    => "Редактировать урок (Учитель)",
    'teacher.groups.lessons.update'  => "Обновить урок (Учитель)",
    'teacher.groups.lessons.destroy' => "Удалить урок (Учитель)",

    // ADMIN SHOP
    'admin.shop.categories.index'         => "Список категорий магазина",
    'admin.shop.categories.create'        => "Страница добавления категории",
    'admin.shop.categories.store'         => "Добавить категорию",
    'admin.shop.categories.show'          => "Данные категории",
    'admin.shop.categories.edit'          => "Страница редактирования категории",
    'admin.shop.categories.update'        => "Редактировать категорию",
    'admin.shop.categories.destroy'       => "Удалить категорию",
    'admin.shop.categories.toggle-status' => "Изменить статус категории",
    'admin.shop.products.index'           => "Список товаров",
    'admin.shop.products.create'          => "Страница добавления товара",
    'admin.shop.products.store'           => "Добавить товар",
    'admin.shop.products.show'            => "Данные товара",
    'admin.shop.products.edit'            => "Страница редактирования товара",
    'admin.shop.products.update'          => "Редактировать товар",
    'admin.shop.products.destroy'         => "Удалить товар",
    'admin.shop.products.toggle-status'   => "Изменить статус товара",
    'admin.shop.products.toggle-featured' => "Сделать товар рекомендованным",
    'admin.shop.orders.index'             => "Список заказов (Админ)",
    'admin.shop.orders.show'              => "Детали заказа (Админ)",
    'admin.shop.orders.approve'           => "Подтвердить заказ",
    'admin.shop.orders.deliver'           => "Отметить заказ как доставленный",
    'admin.shop.orders.reject'            => "Отклонить заказ",

    // HELP
    'help.index'        => "Центр помощи",
    'help.send'         => "Отправить обращение",
    'help.download-pdf' => "Скачать PDF-руководство",

    // ACTIVITY LOGS
    'activity-logs.index'   => "Журнал активности пользователей",
    'activity-logs.online'  => "Онлайн пользователи",
    'activity-logs.show'    => "Активность пользователя",
    'activity-logs.destroy' => "Удалить запись активности",
    'activity-logs.clear'   => "Очистить старые записи",

    // LOGIN ATTEMPTS
    'login-attempts.index'         => "Список попыток входа",
    'login-attempts.show-ip'       => "Попытки входа по IP",
    'login-attempts.show-phone'    => "Попытки входа по телефону",
    'login-attempts.unblock-ip'    => "Разблокировать IP",
    'login-attempts.unblock-phone' => "Разблокировать телефон",
    'login-attempts.destroy'       => "Удалить попытку входа",
    'login-attempts.destroy-old'   => "Удалить старые попытки входа",

    // TRASHED USERS
    'trashed-users.index'        => "Удалённые пользователи",
    'trashed-users.restore'      => "Восстановить пользователя",
    'trashed-users.force-delete' => "Окончательно удалить пользователя",
    'trashed-users.restore-all'  => "Восстановить всех удалённых",
];
