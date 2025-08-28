<?php

use App\Http\Middleware\CheckStatus;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':teacher'])->group(function(){
    //Teacher Dashboard Route
    Route::get('teacher/dashboard', [App\Http\Controllers\teacher\TeacherController::class, 'index'])->name('teacher.dashboard');

    //Teacher Profile Route
    Route::get('teacher/profile', [App\Http\Controllers\teacher\TeacherController::class, 'profile'])->name('teacher.profile');
    Route::get('teacher/profile/edit', [App\Http\Controllers\teacher\TeacherController::class, 'edit'])->name('teacher.profile.edit');
    Route::post('teacher/profile/update', [App\Http\Controllers\teacher\TeacherController::class, 'update'])->name('teacher.profile.update');

    //Assigned course list
    Route::get('teacher/assign/courses', [App\Http\Controllers\teacher\TeacherController::class, 'assignedCourses'])->name('teacher.assign.courses')->middleware([CheckStatus::class]);
    Route::get('teacher/assign/course/details/{slug}', [App\Http\Controllers\teacher\TeacherController::class, 'assignedCoursesDetails'])->name('teacher.assign.course.details')->middleware([CheckStatus::class]);

}); 




