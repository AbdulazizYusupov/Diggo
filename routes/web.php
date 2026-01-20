<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Director\DirectorController;
use App\Http\Controllers\Permission\PermissionController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\RolePermission\RolePermissionController;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Teacher\TeacherController;
use App\Http\Middleware\CheckPermission;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', [AuthController::class, 'index'])->name('login.index');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
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
        // Route::resource('schools', SchoolController::class);
        // Route::resource('courses', CourseController::class);
        // Route::resource('groups', GroupController::class);
        // Route::resource('payments', PaymentController::class);
        // Route::resource('lesson-charges', LessonChargeController::class);
        // Route::resource('attendances', AttendanceController::class);
        // Route::resource('activities', ActivityController::class);
        // Route::resource('coin-transactions', CoinTransactionController::class);
    });
});
