<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Director\DirectorController;
use App\Http\Controllers\Permission\PermissionController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\RolePermission\RolePermissionController;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Teacher\TeacherController;
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
    Route::resource('staffs', StaffController::class);
    Route::resource('directors', DirectorController::class);
    Route::resource('teachers', TeacherController::class);
    Route::resource('students', StudentController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::post('sync-routes', [PermissionController::class, 'syncRoutes'])->name('permissions.sync-routes');
    Route::get('/role-permissions', [RolePermissionController::class, 'index'])->name('role-permissions.index');
    Route::get('/role-permissions/{role}', [RolePermissionController::class, 'show'])->name('role-permissions.show');
    Route::get('/role-permissions/{role}/edit', [RolePermissionController::class, 'edit'])->name('role-permissions.edit');
    Route::put('/{role}', [RolePermissionController::class, 'update'])->name('update');
    Route::post('/role-permissions/copy', [RolePermissionController::class, 'copyPermissions'])->name('role-permissions.copy');
    Route::post('/role-permissions/bulk-assign', [RolePermissionController::class, 'bulkAssign'])->name('role-permissions.bulk-assign');
    Route::delete('/role-permissions/{role}/revoke-all', [RolePermissionController::class, 'revokeAll'])->name('role-permissions.revoke-all');
    Route::put('/role-permissions/{role}/update', [RolePermissionController::class, 'update'])->name('role-permissions.update');
    // Route::resource('schools', SchoolController::class);
    // Route::resource('courses', CourseController::class);
    // Route::resource('groups', GroupController::class);
    // Route::resource('payments', PaymentController::class);
    // Route::resource('lesson-charges', LessonChargeController::class);
    // Route::resource('attendances', AttendanceController::class);
    // Route::resource('activities', ActivityController::class);
    // Route::resource('coin-transactions', CoinTransactionController::class);
});
