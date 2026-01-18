<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Director\DirectorController;
use App\Http\Controllers\Staff\StaffController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', [AuthController::class, 'index'])->name('login.index');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
    Route::resource('staffs', StaffController::class);
    Route::resource('directors', DirectorController::class);
    // Route::resource('teachers', TeacherController::class);
    // Route::resource('students', StudentController::class);
    // Route::resource('schools', SchoolController::class);
    // Route::resource('courses', CourseController::class);
    // Route::resource('groups', GroupController::class);
    // Route::resource('payments', PaymentController::class);
    // Route::resource('lesson-charges', LessonChargeController::class);
    // Route::resource('attendances', AttendanceController::class);
    // Route::resource('activities', ActivityController::class);
    // Route::resource('coin-transactions', CoinTransactionController::class);
});
