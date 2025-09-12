<?php

use App\Http\Middleware\ChackStatus;
use Illuminate\Support\Facades\Route;

//Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':admin'])->group(function(){
    //Admin Profile Route
    Route::get('/admin/profile', [App\Http\Controllers\backend\admin\profile\ProfileController::class, 'profile'])->name('admin.profile');
    Route::post('/admin/profile/update', [App\Http\Controllers\backend\admin\profile\ProfileController::class, 'update'])->name('admin.profile.update');

});


//Admin middleware Route Start
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':admin', \App\Http\Middleware\CheckStatus::class])->group(function(){

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
    // New Teacher Status Change rote
    Route::post('/admin/teacher-inactive/{id}', [App\Http\Controllers\backend\users\teacher\TeacherController::class, 'inactiveTeacher'])->name('admin.teacher.inactive');
    Route::post('/admin/teacher-pending/{id}', [App\Http\Controllers\backend\users\teacher\TeacherController::class, 'pendingTeacher'])->name('admin.teacher.pending');
    Route::post('/admin/teacher-approve/{id}', [App\Http\Controllers\backend\users\teacher\TeacherController::class, 'approveTeacher'])->name('admin.teacher.approve');
    Route::post('/admin/teacher-reject/{id}', [App\Http\Controllers\backend\users\teacher\TeacherController::class, 'rejectTeacher'])->name('admin.teacher.reject');

   
    ///Admin Category management Route
    Route::get('/admin/all-categories', [App\Http\Controllers\backend\category\CategoryController::class, 'index'])->name('admin.category.index');
    Route::get('/categories', [App\Http\Controllers\backend\category\CategoryController::class, 'data']);
    Route::post('/category-store', [App\Http\Controllers\backend\category\CategoryController::class, 'store']);
    Route::get('/categories/{id}', [App\Http\Controllers\backend\category\CategoryController::class, 'edit']);
    Route::post('/category-update/{id}', [App\Http\Controllers\backend\category\CategoryController::class, 'update']);
    Route::delete('/category-delete/{id}', [App\Http\Controllers\backend\category\CategoryController::class, 'delete']);


    ///Admin course management Route
    Route::get('/admin/all-courses', [App\Http\Controllers\backend\course\CourseController::class, 'index'])->name('admin.course.index');
    Route::get('admin/course/create', [App\Http\Controllers\backend\course\CourseController::class, 'create'])->name('admin.course.create');
    Route::post('admin/course/store', [App\Http\Controllers\backend\course\CourseController::class, 'store'])->name('admin.course.store');
    Route::get('admin/course/edit/{id}', [App\Http\Controllers\backend\course\CourseController::class, 'edit'])->name('admin.course.edit');
    Route::post('admin/course/update/{id}', [App\Http\Controllers\backend\course\CourseController::class, 'update'])->name('admin.course.update');
    Route::delete('admin/course/delete/{id}', [App\Http\Controllers\backend\course\CourseController::class, 'delete'])->name('admin.course.delete');

    //course videos management route
    Route::get('/admin/course/video-index/{id}', [App\Http\Controllers\backend\course\CourseVideoController::class, 'index'])->name('admin.course.manage-videos');
    Route::get('/admin/course/video-data/{id}', [App\Http\Controllers\backend\course\CourseVideoController::class, 'data']);
    Route::post('/admin/course/video-store', [App\Http\Controllers\backend\course\CourseVideoController::class, 'store']);
    




    Route::get('admin/course/video/{id}', [App\Http\Controllers\backend\course\CourseVideoController::class, 'videoPlayer'])->name('admin.course.video-player');






    ///Admin course Assign management Route
    Route::get('/admin/all-assign-courses', [App\Http\Controllers\backend\course\AssignCourseController::class, 'index'])->name('admin.course_assign.index');
    Route::post('admin/course/assign/store', [App\Http\Controllers\backend\course\AssignCourseController::class, 'store'])->name('admin.course_assign.store');
    Route::post('admin/course/assign/update/{id}', [App\Http\Controllers\backend\course\AssignCourseController::class, 'update'])->name('admin.course_assign.update');
    Route::delete('admin/course/assign/delete/{id}', [App\Http\Controllers\backend\course\AssignCourseController::class, 'delete'])->name('admin.course_assign.delete');
    //Ajax route
    Route::get('get-teachers-by-category', [App\Http\Controllers\backend\course\AssignCourseController::class, 'getTeachersByCategory'])
    ->name('admin.getTeachersByCategory');



    ///Admin order management Route
    Route::get('/admin/orders', [App\Http\Controllers\backend\order\OrderController::class, 'index'])->name('admin.order.index');
    Route::get('/admin/order/{order}/invoice', [App\Http\Controllers\backend\order\OrderController::class, 'invoice'])->name('admin.order.invoice');
    Route::post('/admin/order/status/{id}', [App\Http\Controllers\backend\order\OrderController::class, 'status'])->name('admin.order.status');







}); //Admin middleware Route End


Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':admin', 
                            \App\Http\Middleware\CheckSuper::class,
                            \App\Http\Middleware\CheckStatus::class])->group(function(){
    //User Admin management Route
    Route::get('/all/admin/index', [App\Http\Controllers\backend\users\admin\AdminController::class, 'index'])->name('user.admin.index');
    Route::get('/all/admin/data', [App\Http\Controllers\backend\users\admin\AdminController::class, 'data']);
    // Route::get('/admin/create', [App\Http\Controllers\backend\users\admin\AdminController::class, 'create'])->name('user.admin.create');
    Route::post('/admin/store', [App\Http\Controllers\backend\users\admin\AdminController::class, 'store'])->name('user.admin.store');
    Route::get('/admin/edit/{id}', [App\Http\Controllers\backend\users\admin\AdminController::class, 'edit'])->name('user.admin.edit');
    Route::post('/admin/update/{id}', [App\Http\Controllers\backend\users\admin\AdminController::class, 'update'])->name('user.admin.update');
    Route::delete('/admin/delete/{id}', [App\Http\Controllers\backend\users\admin\AdminController::class, 'delete'])->name('user.admin.delete');


    //clear-all-cache route
    Route::get('/clear-all-cache', function() {
    Illuminate\Support\Facades\Artisan::call('route:clear');
    Illuminate\Support\Facades\Artisan::call('config:clear');
    Illuminate\Support\Facades\Artisan::call('cache:clear');
    Illuminate\Support\Facades\Artisan::call('view:clear');

        // return redirect()->back()->with('success', 'All caches cleared successfully!');
        return response()->json(['status' => 'success', 'message' => 'All caches cleared successfully!']);

    });
});



