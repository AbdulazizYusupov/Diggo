<?php

/**
 * ENGLISH — Route permission name => label mapping.
 * File: config/permissions_map_en.php
 */

return [

    // STAFFS
    'staffs.index'   => "Staff list",
    'staffs.create'  => "Add staff page",
    'staffs.store'   => "Add staff",
    'staffs.show'    => "Staff details",
    'staffs.edit'    => "Edit staff page",
    'staffs.update'  => "Edit staff",
    'staffs.destroy' => "Delete staff",

    // DIRECTORS
    'directors.index'   => "Directors list",
    'directors.create'  => "Add director page",
    'directors.store'   => "Add director",
    'directors.show'    => "Director details",
    'directors.edit'    => "Edit director page",
    'directors.update'  => "Edit director",
    'directors.destroy' => "Delete director",

    // TEACHERS
    'teachers.index'   => "Teachers list",
    'teachers.create'  => "Add teacher page",
    'teachers.store'   => "Add teacher",
    'teachers.show'    => "Teacher details",
    'teachers.edit'    => "Edit teacher page",
    'teachers.update'  => "Edit teacher",
    'teachers.destroy' => "Delete teacher",

    // STUDENTS
    'students.index'   => "Students list",
    'students.create'  => "Add student page",
    'students.store'   => "Add student",
    'students.show'    => "Student details",
    'students.edit'    => "Edit student page",
    'students.update'  => "Edit student",
    'students.destroy' => "Delete student",

    // ROLES
    'roles.index'   => "Roles list",
    'roles.store'   => "Create role",
    'roles.update'  => "Edit role",
    'roles.destroy' => "Delete role",

    // PERMISSIONS
    'permissions.index'       => "Permissions list",
    'permissions.update'      => "Edit permission",
    'permissions.destroy'     => "Delete permission",
    'permissions.sync-routes' => "Sync routes",

    // ROLE-PERMISSIONS
    'role-permissions.index'       => "Role permissions list",
    'role-permissions.show'        => "View role permissions",
    'role-permissions.edit'        => "Edit role permissions page",
    'role-permissions.update'      => "Save role permissions",
    'role-permissions.copy'        => "Copy role permissions",
    'role-permissions.bulk-assign' => "Bulk assign permissions",
    'role-permissions.revoke-all'  => "Revoke all permissions",

    // SETTINGS
    'settings.index'       => "Settings",
    'settings.assign-role' => "Assign role to user",
    'settings.remove-role' => "Remove role from user",
    'settings.sync-roles'  => "Sync user roles",

    // SCHOOLS
    'schools.index'   => "Schools list",
    'schools.create'  => "Add school page",
    'schools.store'   => "Add school",
    'schools.show'    => "School details",
    'schools.edit'    => "Edit school page",
    'schools.update'  => "Edit school",
    'schools.destroy' => "Delete school",

    // COURSES
    'courses.index'   => "Courses list",
    'courses.create'  => "Add course page",
    'courses.store'   => "Add course",
    'courses.show'    => "Course details",
    'courses.edit'    => "Edit course page",
    'courses.update'  => "Edit course",
    'courses.destroy' => "Delete course",

    // GROUPS
    'groups.index'   => "Groups list",
    'groups.create'  => "Add group page",
    'groups.store'   => "Add group",
    'groups.show'    => "Group details",
    'groups.edit'    => "Edit group page",
    'groups.update'  => "Edit group",
    'groups.destroy' => "Delete group",

    // GROUP STUDENTS
    'groups.students.index'   => "Group students list",
    'groups.students.create'  => "Add student to group page",
    'groups.students.store'   => "Add student to group",
    'groups.students.destroy' => "Remove student from group",

    // GROUP TEACHERS
    'groups.teachers.index'   => "Group teachers list",
    'groups.teachers.create'  => "Add teacher to group page",
    'groups.teachers.store'   => "Add teacher to group",
    'groups.teachers.primary' => "Set primary teacher",
    'groups.teachers.destroy' => "Remove teacher from group",
    'group-teachers.index'    => "Group teachers (all)",
    'group-teachers.store'    => "Add group teacher",
    'group-teachers.destroy'  => "Delete group teacher",

    // PAYMENTS
    'payments.index'        => "Payments list",
    'payments.create'       => "Add payment page",
    'payments.store'        => "Add payment",
    'payments.show'         => "Payment details",
    'payments.edit'         => "Edit payment page",
    'payments.update'       => "Edit payment",
    'payments.destroy'      => "Delete payment",
    'payments.student-info' => "Student payment info",
    'payments.cancel'       => "Cancel payment",

    // ACTIVITIES
    'activities.index'   => "Activities list",
    'activities.create'  => "Add activity page",
    'activities.store'   => "Add activity",
    'activities.show'    => "Activity details",
    'activities.edit'    => "Edit activity page",
    'activities.update'  => "Edit activity",
    'activities.destroy' => "Delete activity",

    // ACTIVITY RESULTS
    'activityresults.index'   => "Activity results list",
    'activityresults.create'  => "Add activity result page",
    'activityresults.store'   => "Add activity result",
    'activityresults.show'    => "Activity result details",
    'activityresults.edit'    => "Edit activity result page",
    'activityresults.update'  => "Edit activity result",
    'activityresults.destroy' => "Delete activity result",

    // ATTENDANCE
    'attendance.index'      => "Attendance list",
    'attendance.show'       => "Group attendance",
    'attendance.store'      => "Save attendance",
    'attendance.bulk-store' => "Bulk save attendance",
    'attendance.statistics' => "Attendance statistics",

    // LESSONS
    'lessons.index'    => "Lessons list",
    'lessons.create'   => "Add lesson page",
    'lessons.store'    => "Add lesson",
    'lessons.show'     => "Lesson details",
    'lessons.edit'     => "Edit lesson page",
    'lessons.update'   => "Edit lesson",
    'lessons.destroy'  => "Delete lesson",
    'lessons.complete' => "Complete lesson",
    'lessons.cancel'   => "Cancel lesson",

    // TRANSACTIONS
    'transactions.index'  => "Transactions list",
    'transactions.show'   => "Transaction details",
    'transactions.export' => "Export transactions",

    // COIN TRANSACTIONS
    'coin-transactions.index' => "Coin transactions list",
    'coin-transactions.show'  => "Coin transaction details",

    // DIRECTOR DASHBOARD
    'director.dashboard'           => "Director dashboard",
    'director.school.show'         => "School details (Director)",
    'director.students'            => "Students list (Director)",
    'director.students.show'       => "Student details (Director)",
    'director.commissions'         => "Commissions (Director)",
    'director.commissions.history' => "Commission history (Director)",
    'director.groups'              => "Groups list (Director)",
    'director.groups.show'         => "Group details (Director)",
    'director.reports'             => "Reports (Director)",
    'director.reports.export'      => "Export reports (Director)",

    // STUDENT DASHBOARD
    'student.dashboard'           => "Student dashboard",
    'student.groups'              => "My groups",
    'student.groups.show'         => "Group details (Student)",
    'student.payments'            => "My payments",
    'student.payments.show'       => "Payment details (Student)",
    'student.coins'               => "My coins",
    'student.coins.show'          => "Coin details (Student)",
    'student.attendance'          => "My attendance",
    'student.attendance.calendar' => "Attendance calendar (Student)",
    'student.schedule'            => "Lesson schedule",
    'student.schedule.upcoming'   => "Upcoming lessons",
    'student.leaderboard'         => "Leaderboard",
    'student.statistics'          => "Student statistics",
    'student.profile'             => "Student profile",
    'student.profile.update'      => "Update profile (Student)",
    'student.profile.parent'      => "Update parent info",
    'student.profile.password'    => "Change password (Student)",
    'student.profile.statistics'  => "Profile statistics (Student)",
    'student.shop.index'          => "Shop (Student)",
    'student.shop.show'           => "Product details (Student)",
    'student.shop.order'          => "Place order",
    'student.shop.orders'         => "My orders",
    'student.shop.cancel'         => "Cancel order",
    'student.activities.index'    => "Activities list (Student)",
    'student.activities.show'     => "Activity details (Student)",

    // TEACHER DASHBOARD
    'teacher.dashboard'              => "Teacher dashboard",
    'teacher.groups.index'           => "My groups (Teacher)",
    'teacher.groups.show'            => "Group details (Teacher)",
    'teacher.groups.lessons.index'   => "Group lessons list",
    'teacher.groups.lessons.create'  => "Add lesson page (Teacher)",
    'teacher.groups.lessons.store'   => "Add lesson (Teacher)",
    'teacher.groups.lessons.edit'    => "Edit lesson (Teacher)",
    'teacher.groups.lessons.update'  => "Update lesson (Teacher)",
    'teacher.groups.lessons.destroy' => "Delete lesson (Teacher)",

    // ADMIN SHOP
    'admin.shop.categories.index'         => "Shop categories list",
    'admin.shop.categories.create'        => "Add category page",
    'admin.shop.categories.store'         => "Add category",
    'admin.shop.categories.show'          => "Category details",
    'admin.shop.categories.edit'          => "Edit category page",
    'admin.shop.categories.update'        => "Edit category",
    'admin.shop.categories.destroy'       => "Delete category",
    'admin.shop.categories.toggle-status' => "Toggle category status",
    'admin.shop.products.index'           => "Products list",
    'admin.shop.products.create'          => "Add product page",
    'admin.shop.products.store'           => "Add product",
    'admin.shop.products.show'            => "Product details",
    'admin.shop.products.edit'            => "Edit product page",
    'admin.shop.products.update'          => "Edit product",
    'admin.shop.products.destroy'         => "Delete product",
    'admin.shop.products.toggle-status'   => "Toggle product status",
    'admin.shop.products.toggle-featured' => "Toggle product featured",
    'admin.shop.orders.index'             => "Orders list (Admin)",
    'admin.shop.orders.show'              => "Order details (Admin)",
    'admin.shop.orders.approve'           => "Approve order",
    'admin.shop.orders.deliver'           => "Mark order as delivered",
    'admin.shop.orders.reject'            => "Reject order",

    // HELP
    'help.index'        => "Help center",
    'help.send'         => "Send request",
    'help.download-pdf' => "Download PDF guide",

    // ACTIVITY LOGS
    'activity-logs.index'   => "User activity log",
    'activity-logs.online'  => "Online users",
    'activity-logs.show'    => "User activity",
    'activity-logs.destroy' => "Delete activity record",
    'activity-logs.clear'   => "Clear old records",

    // LOGIN ATTEMPTS
    'login-attempts.index'         => "Login attempts list",
    'login-attempts.show-ip'       => "Login attempts by IP",
    'login-attempts.show-phone'    => "Login attempts by phone",
    'login-attempts.unblock-ip'    => "Unblock IP",
    'login-attempts.unblock-phone' => "Unblock phone",
    'login-attempts.destroy'       => "Delete login attempt",
    'login-attempts.destroy-old'   => "Delete old login attempts",

    // TRASHED USERS
    'trashed-users.index'        => "Deleted users",
    'trashed-users.restore'      => "Restore user",
    'trashed-users.force-delete' => "Permanently delete user",
    'trashed-users.restore-all'  => "Restore all deleted users",
];
