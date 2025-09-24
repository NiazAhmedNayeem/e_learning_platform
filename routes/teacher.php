<?php

use App\Http\Middleware\CheckStatus;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':teacher'])->group(function(){
    //Teacher Dashboard Route
    Route::get('/teacher/dashboard', [App\Http\Controllers\teacher\TeacherController::class, 'index'])->name('teacher.dashboard');

    //Teacher Profile Route
    Route::get('/teacher/profile', [App\Http\Controllers\teacher\TeacherController::class, 'profile'])->name('teacher.profile');
    Route::post('/teacher/profile/update', [App\Http\Controllers\teacher\TeacherController::class, 'update'])->name('teacher.profile.update');

    //Assigned course list
    Route::get('/teacher/assign/courses', [App\Http\Controllers\teacher\TeacherController::class, 'assignedCourses'])->name('teacher.assign.courses')->middleware([CheckStatus::class]);
    // Route::get('/teacher/assign/course/details/{slug}', [App\Http\Controllers\teacher\TeacherController::class, 'assignedCoursesDetails'])->name('teacher.assign.course.details')->middleware([CheckStatus::class]);
    //Total course students count
    Route::get('/teacher/course/students', [App\Http\Controllers\teacher\TeacherController::class, 'totalCourseStudent'])->name('teacher.course.student')->middleware([CheckStatus::class]);
    Route::get('/teacher/course/students-data', [App\Http\Controllers\teacher\TeacherController::class, 'studentsData'])->middleware([CheckStatus::class]);


    //course videos management route
    Route::get('/teacher/course/video-index/{id}', [App\Http\Controllers\teacher\TeacherController::class, 'videoIndex'])->name('teacher.course.manage-videos')->middleware([CheckStatus::class]);
    Route::get('/teacher/course/video-data/{id}', [App\Http\Controllers\teacher\TeacherController::class, 'videoData'])->middleware([CheckStatus::class]);
    Route::post('/teacher/course/video-store', [App\Http\Controllers\teacher\TeacherController::class, 'videoStore'])->middleware([CheckStatus::class]);
    Route::get('/teacher/course/video-edit/{id}', [App\Http\Controllers\teacher\TeacherController::class, 'videoEdit'])->middleware([CheckStatus::class]);
    Route::post('/teacher/course/video-update/{id}', [App\Http\Controllers\teacher\TeacherController::class, 'videoUpdate'])->middleware([CheckStatus::class]);
    Route::delete('/teacher/course/video-delete/{id}', [App\Http\Controllers\teacher\TeacherController::class, 'videoDelete'])->middleware([CheckStatus::class]);
    ///video player route
    Route::get('teacher/course/video/{id}', [App\Http\Controllers\teacher\TeacherController::class, 'videoPlayer'])->name('teacher.course.video-player')->middleware([CheckStatus::class]);



    ///Notice management route
    Route::get('/teacher/notice/index', [App\Http\Controllers\teacher\NoticeController::class, 'index'])->name('teacher.notice.index');
    Route::get('/teacher/notice/data', [App\Http\Controllers\teacher\NoticeController::class, 'noticeData']);
    Route::post('/teacher/notice/store', [App\Http\Controllers\teacher\NoticeController::class, 'store']);
    Route::get('/teacher/notice/details/{id}', [App\Http\Controllers\teacher\NoticeController::class, 'details']);
    Route::get('/teacher/notice/edit/{id}', [App\Http\Controllers\teacher\NoticeController::class, 'edit']);
    Route::post('/teacher/notice/update', [App\Http\Controllers\teacher\NoticeController::class, 'update']);
    Route::delete('/teacher/notice/delete/{id}', [App\Http\Controllers\teacher\NoticeController::class, 'delete']);



}); 




