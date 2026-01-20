<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Director\DirectorController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Role\RoleController;
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
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::resource('staffs', StaffController::class);
    Route::resource('directors', DirectorController::class);
    Route::resource('teachers', TeacherController::class);
    Route::resource('students', StudentController::class);
    Route::resource('roles', RoleController::class);
    // Route::resource('schools', SchoolController::class);
    // Route::resource('courses', CourseController::class);
    // Route::resource('groups', GroupController::class);
    // Route::resource('payments', PaymentController::class);
    // Route::resource('lesson-charges', LessonChargeController::class);
    // Route::resource('attendances', AttendanceController::class);
    // Route::resource('activities', ActivityController::class);
    // Route::resource('coin-transactions', CoinTransactionController::class);
});
