<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', function(){
    return response()->json([
        'message' => 'Api route create successfully.',
    ]);
});

//Auth Route
Route::prefix('v1')->group(function(){
    Route::post('/register', [App\Http\Controllers\api\auth\ApiAuthController::class, 'register']);
    Route::post('/login', [App\Http\Controllers\api\auth\ApiAuthController::class, 'login']);
});


Route::prefix('v1')->group(function(){
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