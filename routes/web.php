<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');

    Route::get('/student/register', [RegisteredUserController::class, 'create'])->name('student.register');
});

Route::middleware('auth')->group(function () {
    Route::match(['post', 'get'], '/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course:slug}', [CourseController::class, 'show'])->name('courses.show');

Route::get('/course/{course:slug}', [CourseController::class, 'show'])->name('course.show');

Route::middleware('auth')->scopeBindings()->group(function () {
    Route::get('/courses/{course:slug}/lessons/{lesson:slug}', [LessonController::class, 'show'])
        ->name('lessons.show');

    Route::get('/course/{course:slug}/player/{lesson:slug}', [LessonController::class, 'show'])
        ->name('course.player');
});
