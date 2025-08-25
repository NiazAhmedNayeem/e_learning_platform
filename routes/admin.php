<?php

use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

//Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

Route::get('inactive/dashboard', [App\Http\Controllers\backend\admin\DashboardController::class, 'inactive'])->name('inactive.dashboard');
//Admin middleware Route Start
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':admin'])->group(function(){


    //Admin Dashboard Route
    Route::get('admin/dashboard', [App\Http\Controllers\backend\admin\DashboardController::class, 'index'])->name('admin.dashboard');

    //Admin Student management Route
    Route::get('admin/student/index', [App\Http\Controllers\backend\users\student\StudentController::class, 'index'])->name('admin.student.index');
    Route::get('admin/student/create', [App\Http\Controllers\backend\users\student\StudentController::class, 'create'])->name('admin.student.create');
    Route::post('admin/student/store', [App\Http\Controllers\backend\users\student\StudentController::class, 'store'])->name('admin.student.store');
    Route::get('admin/student/edit/{id}', [App\Http\Controllers\backend\users\student\StudentController::class, 'edit'])->name('admin.student.edit');
    Route::post('admin/student/update/{id}', [App\Http\Controllers\backend\users\student\StudentController::class, 'update'])->name('admin.student.update');
    Route::delete('admin/student/delete/{id}', [App\Http\Controllers\backend\users\student\StudentController::class, 'delete'])->name('admin.student.delete');

    ///Admin Teacher management Route
    Route::get('/admin/all-teacher', [App\Http\Controllers\backend\users\teacher\TeacherController::class, 'index'])->name('admin.all-teacher');
    Route::get('admin/create/teacher', [App\Http\Controllers\backend\users\teacher\TeacherController::class, 'create'])->name('admin.create.teacher');
    Route::post('admin/store/teacher', [App\Http\Controllers\backend\users\teacher\TeacherController::class, 'store'])->name('admin.store.teacher');
    Route::get('admin/edit/teacher/{id}', [App\Http\Controllers\backend\users\teacher\TeacherController::class, 'edit'])->name('admin.edit.teacher');
    Route::post('admin/update/teacher/{id}', [App\Http\Controllers\backend\users\teacher\TeacherController::class, 'update'])->name('admin.update.teacher');
    Route::delete('admin/delete/teacher/{id}', [App\Http\Controllers\backend\users\teacher\TeacherController::class, 'delete'])->name('admin.delete.teacher');


    //User Admin management Route
    Route::get('all/admin/index', [App\Http\Controllers\backend\users\admin\AdminController::class, 'index'])->name('user.admin.index');
    Route::get('admin/create', [App\Http\Controllers\backend\users\admin\AdminController::class, 'create'])->name('user.admin.create');
    Route::post('admin/store', [App\Http\Controllers\backend\users\admin\AdminController::class, 'store'])->name('user.admin.store');
    Route::get('admin/edit/{id}', [App\Http\Controllers\backend\users\admin\AdminController::class, 'edit'])->name('user.admin.edit');
    Route::post('admin/update/{id}', [App\Http\Controllers\backend\users\admin\AdminController::class, 'update'])->name('user.admin.update');
    Route::delete('admin/delete/{id}', [App\Http\Controllers\backend\users\admin\AdminController::class, 'delete'])->name('user.admin.delete');

   
    ///Admin Category management Route
    Route::get('/admin/all-categories', [App\Http\Controllers\backend\category\CategoryController::class, 'index'])->name('admin.category.index');
    Route::get('admin/create/category', [App\Http\Controllers\backend\category\CategoryController::class, 'create'])->name('admin.category.create');
    Route::post('admin/store/category', [App\Http\Controllers\backend\category\CategoryController::class, 'store'])->name('admin.category.store');
    Route::get('admin/edit/category/{id}', [App\Http\Controllers\backend\category\CategoryController::class, 'edit'])->name('admin.category.edit');
    Route::post('admin/update/category/{id}', [App\Http\Controllers\backend\category\CategoryController::class, 'update'])->name('admin.category.update');
    Route::delete('admin/delete/category/{id}', [App\Http\Controllers\backend\category\CategoryController::class, 'delete'])->name('admin.category.delete');


    ///Admin course management Route
    Route::get('/admin/all-courses', [App\Http\Controllers\backend\course\CourseController::class, 'index'])->name('admin.course.index');
    Route::get('admin/course/create', [App\Http\Controllers\backend\course\CourseController::class, 'create'])->name('admin.course.create');
    Route::post('admin/course/store', [App\Http\Controllers\backend\course\CourseController::class, 'store'])->name('admin.course.store');
    Route::get('admin/course/edit/{id}', [App\Http\Controllers\backend\course\CourseController::class, 'edit'])->name('admin.course.edit');
    Route::post('admin/course/update/{id}', [App\Http\Controllers\backend\course\CourseController::class, 'update'])->name('admin.course.update');
    Route::delete('admin/course/delete/{id}', [App\Http\Controllers\backend\course\CourseController::class, 'delete'])->name('admin.course.delete');





}); //Admin middleware Route End