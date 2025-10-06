<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', function(){
    return response()->json([
        'message' => 'Api route create successfully.',
    ]);
});

//Auth Route
Route::prefix('v1')->group(function(){
    //URL be like-  http://localhost/e_learning_platform/api/v1/register
    Route::post('/register', [App\Http\Controllers\api\auth\ApiAuthController::class, 'register']);
    Route::post('/login', [App\Http\Controllers\api\auth\ApiAuthController::class, 'login']);
    Route::post('/logout', [App\Http\Controllers\api\auth\ApiAuthController::class, 'logout'])->middleware('auth:sanctum');
});


//User profile route
Route::prefix('v1')->middleware('auth:sanctum')->group(function(){
    ///Teacher profile
    Route::get('/teacher/profile', [App\Http\Controllers\api\profile\ApiTeacherProfileController::class, 'profile']);
    Route::patch('/teacher/profile', [App\Http\Controllers\api\profile\ApiTeacherProfileController::class, 'update']);
    ///Student profile
    Route::get('/student/profile', [App\Http\Controllers\api\profile\ApiStudentProfileController::class, 'profile']);
    Route::patch('/student/profile', [App\Http\Controllers\api\profile\ApiStudentProfileController::class, 'update']);
});


Route::prefix('v1')->middleware('auth:sanctum')->group(function(){
    ///User Management Route
    Route::get('/user', [App\Http\Controllers\api\backend\admin\ApiUserController::class, 'index']);
    Route::post('/create-user', [App\Http\Controllers\api\backend\admin\ApiUserController::class, 'store']);
    Route::get('/show-user/{id}', [App\Http\Controllers\api\backend\admin\ApiUserController::class, 'show']);
    Route::put('/update-user/{id}', [App\Http\Controllers\api\backend\admin\ApiUserController::class, 'update']);
    Route::patch('/update-user/{id}', [App\Http\Controllers\api\backend\admin\ApiUserController::class, 'update']);
    Route::delete('/delete-user/{id}', [App\Http\Controllers\api\backend\admin\ApiUserController::class, 'delete']);

    ///Category Management Route
    Route::get('/categories', [App\Http\Controllers\api\backend\admin\ApiCategoryController::class, 'index']);
    Route::post('/create-category', [App\Http\Controllers\api\backend\admin\ApiCategoryController::class, 'store']);
    Route::get('/show-category/{id}', [App\Http\Controllers\api\backend\admin\ApiCategoryController::class, 'show']);
    Route::put('/update-category/{id}', [App\Http\Controllers\api\backend\admin\ApiCategoryController::class, 'update']);
    Route::delete('/delete-category/{id}', [App\Http\Controllers\api\backend\admin\ApiCategoryController::class, 'delete']);


});