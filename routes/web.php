<?php

use App\Http\Controllers\Activity\ActivityController;
use App\Http\Controllers\ActivityResult\ActivityResultController;
use App\Http\Controllers\Admin\ShopCategoryController;
use App\Http\Controllers\Admin\ShopOrderController;
use App\Http\Controllers\Admin\ShopProductController;
use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CoinTransaction\CoinTransactionController;
use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Director\DirectorController;
use App\Http\Controllers\DirectorDashboard\DirectorCommissionsController;
use App\Http\Controllers\DirectorDashboard\DirectorDashboardController;
use App\Http\Controllers\DirectorDashboard\DirectorGroupsController;
use App\Http\Controllers\DirectorDashboard\DirectorReportsController;
use App\Http\Controllers\DirectorDashboard\DirectorStudentsController;
use App\Http\Controllers\Group\GroupController;
use App\Http\Controllers\GroupStudent\GroupStudentController;
use App\Http\Controllers\GroupTeacher\GroupTeacherController;
use App\Http\Controllers\Help\HelpController;
use App\Http\Controllers\Impersonate\ImpersonateController;
use App\Http\Controllers\Lesson\LessonController;
use App\Http\Controllers\Locale\LocaleController;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Permission\PermissionController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\RolePermission\RolePermissionController;
use App\Http\Controllers\School\SchoolController;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\Statitics\StatisticsController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\StudentDashboard\ShopController;
use App\Http\Controllers\StudentDashboard\StudentAttendanceController;
use App\Http\Controllers\StudentDashboard\StudentCoinsController;
use App\Http\Controllers\StudentDashboard\StudentDashboardController;
use App\Http\Controllers\StudentDashboard\StudentGroupsController;
use App\Http\Controllers\StudentDashboard\StudentLeaderboardController;
use App\Http\Controllers\StudentDashboard\StudentPaymentsController;
use App\Http\Controllers\StudentDashboard\StudentProfileController;
use App\Http\Controllers\StudentDashboard\StudentScheduleController;
use App\Http\Controllers\Teacher\TeacherController;
use App\Http\Controllers\TeacherDashboard\TeacherAttendanceController;
use App\Http\Controllers\TeacherDashboard\TeacherDashboardController;
use App\Http\Controllers\TeacherDashboard\TeacherGroupsController;
use App\Http\Controllers\TeacherDashboard\TeacherLessonsController;
use App\Http\Controllers\Transaction\TransactionController;
use App\Http\Controllers\UserActivityLog\UserActivityLogController;
use App\Http\Middleware\CheckPermission;
use Illuminate\Support\Facades\Route;


Route::get('/login', [AuthController::class, 'index'])->name('login.index');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::prefix('admin/impersonate')->name('impersonate.')->group(function () {
        Route::get('/', [ImpersonateController::class, 'index'])->name('index');
        // Impersonation boshlash
        Route::post('/{user}/start', [ImpersonateController::class, 'start'])->name('start');

        // Impersonation tugatish (istalgan yerdan)
        Route::post('/leave', [ImpersonateController::class, 'leave'])->name('leave');
    });
    Route::post('/locale/set', [LocaleController::class, 'set'])->name('locale.set');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');

    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');

    Route::middleware([CheckPermission::class])->group(function () {
        Route::resource('staffs', StaffController::class);
        Route::resource('directors', DirectorController::class);
        Route::resource('teachers', TeacherController::class);
        Route::resource('students', StudentController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        Route::post('sync-routes', [PermissionController::class, 'syncRoutes'])->name('permissions.sync-routes');
        Route::prefix('role-permissions')->name('role-permissions.')->group(function () {
            Route::get('/', [RolePermissionController::class, 'index'])->name('index');
            Route::get('/{role}', [RolePermissionController::class, 'show'])->name('show');
            Route::get('/{role}/edit', [RolePermissionController::class, 'edit'])->name('edit');
            Route::put('/{role}/update', [RolePermissionController::class, 'update'])->name('update');
            Route::post('/copy', [RolePermissionController::class, 'copyPermissions'])->name('copy');
            Route::post('/bulk-assign', [RolePermissionController::class, 'bulkAssign'])->name('bulk-assign');
            Route::delete('/{role}/revoke-all', [RolePermissionController::class, 'revokeAll'])->name('revoke-all');
        });
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingsController::class, 'index'])->name('index');
            Route::post('/assign-role', [SettingsController::class, 'assignRole'])->name('assign-role');
            Route::delete('/remove-role/{user}', [SettingsController::class, 'removeRole'])->name('remove-role');
            Route::post('/sync-roles/{user}', [SettingsController::class, 'syncRoles'])->name('sync-roles');
        });
        Route::resource('schools', SchoolController::class);
        Route::resource('courses', CourseController::class);
        Route::resource('groups', GroupController::class);
        Route::prefix('groups/{group}')->group(function () {
            // Students
            Route::get('students', [GroupStudentController::class, 'index'])->name('groups.students.index');

            Route::get('students/create', [GroupStudentController::class, 'create'])->name('groups.students.create');

            Route::post('students', [GroupStudentController::class, 'store'])->name('groups.students.store');

            Route::delete('students/{student}', [GroupStudentController::class, 'destroy'])->name('groups.students.destroy');

            // Teachers
            Route::get('teachers', [GroupTeacherController::class, 'index'])->name('groups.teachers.index');

            Route::get('teachers/create', [GroupTeacherController::class, 'create'])->name('groups.teachers.create');

            Route::post('teachers', [GroupTeacherController::class, 'store'])->name('groups.teachers.store');
            Route::patch('teachers/{teacher}/primary', [GroupTeacherController::class, 'makePrimaryTeacher'])->name('groups.teachers.primary');

            Route::delete('teachers/{teacher}', [GroupTeacherController::class, 'destroy'])->name('groups.teachers.destroy');
        });
        Route::resource('group-teachers', GroupTeacherController::class);
        Route::resource('payments', PaymentController::class);
        Route::get('payments/student-info', [PaymentController::class, 'getStudentInfo'])->name('payments.student-info');
        Route::post('payments/{payment}/cancel', [PaymentController::class, 'cancel'])->name('payments.cancel');  // ← YANGI
        Route::resource('activities', ActivityController::class);
        Route::resource('activityresults', ActivityResultController::class);

        Route::prefix('attendance')->name('attendance.')->group(function () {
            Route::get('/', [AttendanceController::class, 'index'])->name('index');
            Route::get('/group/{group}', [AttendanceController::class, 'show'])->name('show');
            Route::post('/store', [AttendanceController::class, 'store'])->name('store');
            Route::post('/bulk-store', [AttendanceController::class, 'bulkStore'])->name('bulk-store');
            Route::get('/group/{group}/statistics', [AttendanceController::class, 'statistics'])->name('statistics');
        });

        Route::prefix('director')->name('director.')->group(function () {
            Route::get('/', [DirectorDashboardController::class, 'index'])->name('dashboard');
            Route::get('/school/{id}', [DirectorDashboardController::class, 'show'])->name('school.show');

            Route::get('/students', [DirectorStudentsController::class, 'index'])->name('students');
            Route::get('/students/{id}', [DirectorStudentsController::class, 'show'])->name('students.show');
            // // Commissions
            Route::get('/commissions', [DirectorCommissionsController::class, 'index'])->name('commissions');
            Route::get('/commissions/history', [DirectorCommissionsController::class, 'history'])->name('commissions.history');

            // // Groups
            Route::get('/groups', [DirectorGroupsController::class, 'index'])->name('groups');
            Route::get('/groups/{id}', [DirectorGroupsController::class, 'show'])->name('groups.show');

            // Reports
            Route::get('/reports', [DirectorReportsController::class, 'index'])->name('reports');
            Route::get('/reports/export', [DirectorReportsController::class, 'export'])->name('reports.export');
        });

        Route::prefix('student')->name('student.')->group(function () {
            Route::get('/', [StudentDashboardController::class, 'index'])->name('dashboard');

            Route::get('/groups', [StudentGroupsController::class, 'index'])->name('groups');
            Route::get('/groups/{id}', [StudentGroupsController::class, 'show'])->name('groups.show');

            // Payments
            Route::get('/payments', [StudentPaymentsController::class, 'index'])->name('payments');
            Route::get('/payments/{id}', [StudentPaymentsController::class, 'show'])->name('payments.show');

            // Coins
            Route::get('/coins', [StudentCoinsController::class, 'index'])->name('coins');
            Route::get('/coins/{id}', [StudentCoinsController::class, 'show'])->name('coins.show');

            // Attendance
            Route::get('/attendance', [StudentAttendanceController::class, 'index'])->name('attendance');
            Route::get('/attendance/calendar', [StudentAttendanceController::class, 'calendar'])->name('attendance.calendar');

            // Schedule
            Route::get('/schedule', [StudentScheduleController::class, 'index'])->name('schedule');
            Route::get('/schedule/upcoming', [StudentScheduleController::class, 'upcoming'])->name('schedule.upcoming');

            Route::get('/leaderboard', [StudentLeaderboardController::class, 'index'])->name('leaderboard');
            // Statistics (Statistika)
            Route::get('/statistics', [StudentLeaderboardController::class, 'statistics'])->name('statistics');

            // Profile
            Route::get('/profile', [StudentProfileController::class, 'index'])->name('profile');
            Route::put('/profile', [StudentProfileController::class, 'updateProfile'])->name('profile.update');
            Route::put('/profile/parent', [StudentProfileController::class, 'updateParentInfo'])->name('profile.parent');
            Route::put('/profile/password', [StudentProfileController::class, 'updatePassword'])->name('profile.password');
            Route::get('/profile/statistics', [StudentProfileController::class, 'statistics'])->name('profile.statistics');

            Route::prefix('shop')->name('shop.')->group(function () {

                // Do'kon bosh sahifasi — mahsulotlar ro'yxati
                Route::get('/', [ShopController::class, 'index'])->name('index');

                // Mahsulot detail
                Route::get('/{product:slug}', [ShopController::class, 'show'])->name('show');

                // Buyurtma berish
                Route::post('/{product:slug}/order', [ShopController::class, 'order'])->name('order');

                // Buyurtmalar ro'yxati
                Route::get('/my/orders', [ShopController::class, 'orders'])->name('orders');

                // Buyurtmani bekor qilish
                Route::patch('/orders/{order}/cancel', [ShopController::class, 'cancel'])->name('cancel');
            });
        });

        Route::prefix('teacher')->name('teacher.')->group(function () {
            Route::get('/', [TeacherDashboardController::class, 'index'])->name('dashboard');

            // Groups
            Route::prefix('groups')->name('groups.')->group(function () {
                Route::get('/',        [TeacherGroupsController::class, 'index'])->name('index');
                Route::get('/{group}', [TeacherGroupsController::class, 'show'])->name('show');
            });
            // Lessons inside a group
            Route::prefix('groups/{group}/lessons')->name('groups.lessons.')->group(function () {
                Route::get('/', [TeacherLessonsController::class, 'index'])->name('index');
                Route::get('/create', [TeacherLessonsController::class, 'create'])->name('create');
                Route::post('/', [TeacherLessonsController::class, 'store'])->name('store');
                Route::get('/{lesson}/edit', [TeacherLessonsController::class, 'edit'])->name('edit');
                Route::put('/{lesson}', [TeacherLessonsController::class, 'update'])->name('update');
                Route::delete('/{lesson}', [TeacherLessonsController::class, 'destroy'])->name('destroy');
            });
            Route::prefix('groups/{group}/lessons/{lesson}/attendance')->name('attendance.')->group(function () {
                Route::get('/',  [TeacherAttendanceController::class, 'show'])->name('show');
                Route::post('/', [TeacherAttendanceController::class, 'store'])->name('store');
            });
        });



        Route::get('transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
        Route::resource('transactions', TransactionController::class)->only(['index', 'show']);

        // Coin Transactions
        Route::get('/coin-transactions', [CoinTransactionController::class, 'index'])->name('coin-transactions.index');
        Route::get('/coin-transactions/{id}', [CoinTransactionController::class, 'show'])->name('coin-transactions.show');

        Route::resource('lessons', LessonController::class);
        Route::patch('lessons/{lesson}/complete', [LessonController::class, 'complete'])->name('lessons.complete');
        Route::post('lessons/{lesson}/cancel', [LessonController::class, 'cancel'])->name('lessons.cancel');
        // Route::resource('attendances', AttendanceController::class);
        // Route::resource('lesson-charges', LessonChargeController::class);
        // Route::resource('coin-transactions', CoinTransactionController::class);

        Route::prefix('admin/shop')->name('admin.shop.')->group(function () {
            // ── Kategoriyalar ────────────────────────────────────
            Route::resource('categories', ShopCategoryController::class);
            Route::patch('categories/{category}/toggle-status', [ShopCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');

            // ── Mahsulotlar ──────────────────────────────────────
            Route::resource('products', ShopProductController::class);
            Route::patch('products/{product}/toggle-status',   [ShopProductController::class, 'toggleStatus'])->name('products.toggle-status');
            Route::patch('products/{product}/toggle-featured', [ShopProductController::class, 'toggleFeatured'])
                ->name('products.toggle-featured');

            // // ── Buyurtmalar ──────────────────────────────────────
            Route::get('orders',              [ShopOrderController::class, 'index'])->name('orders.index');
            Route::get('orders/{order}',      [ShopOrderController::class, 'show'])->name('orders.show');
            Route::patch('orders/{order}/approve', [ShopOrderController::class, 'approve'])->name('orders.approve');
            Route::patch('orders/{order}/deliver', [ShopOrderController::class, 'deliver'])->name('orders.deliver');
            Route::patch('orders/{order}/reject',  [ShopOrderController::class, 'reject'])->name('orders.reject');
        });
        Route::prefix('help')->name('help.')->group(function () {
            // Help & Support bosh sahifasi
            Route::get('/', [HelpController::class, 'index'])->name('index');

            // Murojaat formasini yuborish
            Route::post('/send', [HelpController::class, 'send'])->name('send');

            // PDF qo'llanmani yuklab olish
            Route::get('/download-pdf', [HelpController::class, 'downloadPdf'])->name('download-pdf');
        });
        // User Activity Logs
        Route::prefix('activity-logs')->name('activity-logs.')->group(function () {
            Route::get('/', [UserActivityLogController::class, 'index'])->name('index');
            Route::get('/online', [UserActivityLogController::class, 'online'])->name('online');
            Route::get('/user/{userId}', [UserActivityLogController::class, 'show'])->name('show');
            Route::delete('/{id}', [UserActivityLogController::class, 'destroy'])->name('destroy');
            Route::delete('/clear/old', [UserActivityLogController::class, 'destroyAll'])->name('clear');
        });
    });
});
