<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class. ':student'])->group(function(){
    //Student Dashboard Route
    Route::get('student/dashboard', [App\Http\Controllers\student\StudentController::class, 'index'])->name('student.dashboard');

    //Student Profile edit update Route
    Route::get('/student/profile', [App\Http\Controllers\student\StudentController::class, 'profile'])->name('student.profile');
    // Route::get('/student/profile/edit', [App\Http\Controllers\student\StudentController::class, 'edit'])->name('student.profile.edit');
    Route::post('/student/profile/update', [App\Http\Controllers\student\StudentController::class, 'update'])->name('student.profile.update');

    
    Route::get('student/courses', [App\Http\Controllers\student\StudentController::class, 'myCourses'])->name('student.courses');

    Route::get('student/course/order', [App\Http\Controllers\student\StudentController::class, 'myCourseOrder'])->name('student.course.order');

    Route::get('/student/order/{order}/invoice', [App\Http\Controllers\Student\StudentController::class, 'invoice'])->name('student.order.invoice');


    Route::get('student/course/details/{slug}', [App\Http\Controllers\student\StudentController::class, 'myCourseDetails'])->name('student.course.details');
});


