<?php

use App\Http\Middleware\TeacherMiddleware;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':teacher'])->group(function(){

    Route::get('teacher/dashboard', [App\Http\Controllers\backend\teacher\TeacherController::class, 'index'])->name('teacher.dashboard');

}); 