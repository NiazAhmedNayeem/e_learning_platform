<?php

use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

//Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

Route::get('inactive/dashboard', [App\Http\Controllers\backend\admin\DashboardController::class, 'inactive'])->name('inactive.dashboard');
//Admin middleware Route Start
Route::middleware([AdminMiddleware::class])->group(function(){


    //Admin Dashboard Route
    Route::get('admin/dashboard', [App\Http\Controllers\backend\admin\DashboardController::class, 'index'])->name('admin.dashboard');

    //Admin Student management Route
    Route::get('admin/student/index', [App\Http\Controllers\backend\admin\StudentController::class, 'index'])->name('admin.student.index');
    Route::get('admin/student/create', [App\Http\Controllers\backend\admin\StudentController::class, 'create'])->name('admin.student.create');
    Route::post('admin/student/store', [App\Http\Controllers\backend\admin\StudentController::class, 'store'])->name('admin.student.store');
    Route::get('admin/student/edit/{id}', [App\Http\Controllers\backend\admin\StudentController::class, 'edit'])->name('admin.student.edit');
    Route::post('admin/student/update/{id}', [App\Http\Controllers\backend\admin\StudentController::class, 'update'])->name('admin.student.update');
    Route::delete('admin/student/delete/{id}', [App\Http\Controllers\backend\admin\StudentController::class, 'delete'])->name('admin.student.delete');



    //User Admin management Route
    Route::get('all/admin/index', [App\Http\Controllers\backend\users\admin\AdminController::class, 'index'])->name('user.admin.index');
    Route::get('admin/create', [App\Http\Controllers\backend\users\admin\AdminController::class, 'create'])->name('user.admin.create');
    Route::post('admin/store', [App\Http\Controllers\backend\users\admin\AdminController::class, 'store'])->name('user.admin.store');
    Route::get('admin/edit/{id}', [App\Http\Controllers\backend\users\admin\AdminController::class, 'edit'])->name('user.admin.edit');
    Route::post('admin/update/{id}', [App\Http\Controllers\backend\users\admin\AdminController::class, 'update'])->name('user.admin.update');
    Route::delete('admin/delete/{id}', [App\Http\Controllers\backend\users\admin\AdminController::class, 'delete'])->name('user.admin.delete');

    //User Student management Route
    Route::get('admin/user/all-students', [App\Http\Controllers\backend\users\student\StudentController::class, 'index'])->name('admin.user.student.index');
    // Route::get('admin/create', [App\Http\Controllers\backend\users\admin\AdminController::class, 'create'])->name('user.admin.create');
    // Route::post('admin/store', [App\Http\Controllers\backend\users\admin\AdminController::class, 'store'])->name('user.admin.store');
    // Route::get('admin/edit/{id}', [App\Http\Controllers\backend\users\admin\AdminController::class, 'edit'])->name('user.admin.edit');
    // Route::post('admin/update/{id}', [App\Http\Controllers\backend\users\admin\AdminController::class, 'update'])->name('user.admin.update');
    // Route::delete('admin/delete/{id}', [App\Http\Controllers\backend\users\admin\AdminController::class, 'delete'])->name('user.admin.delete');
}); //Admin middleware Route End