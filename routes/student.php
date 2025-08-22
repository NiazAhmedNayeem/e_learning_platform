<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class. ':student'])->group(function(){
    //Student Dashboard Route
    Route::get('student/dashboard', [App\Http\Controllers\backend\student\StudentController::class, 'index'])->name('student.dashboard');

});


