<?php

use App\Http\Controllers\backend\admin\DashboardController;
use App\Http\Controllers\backend\admin\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


//Admin Dashboard Route
Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

//Admin Student management Route
Route::get('admin/student/index', [StudentController::class, 'index'])->name('admin.student.index');
Route::get('admin/student/create', [StudentController::class, 'create'])->name('admin.student.create');
Route::post('admin/student/store', [StudentController::class, 'store'])->name('admin.student.store');
Route::get('admin/student/edit/{id}', [StudentController::class, 'edit'])->name('admin.student.edit');
Route::post('admin/student/update/{id}', [StudentController::class, 'update'])->name('admin.student.update');
Route::delete('admin/student/delete/{id}', [StudentController::class, 'delete'])->name('admin.student.delete');