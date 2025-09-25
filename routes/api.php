<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', function(){
    return response()->json([
        'message' => 'Api route create successfully.',
    ]);
});


Route::prefix('v1')->group(function(){
    Route::get('/user', [App\Http\Controllers\api\backend\admin\ApiUserController::class, 'index']);
    Route::post('/create-user', [App\Http\Controllers\api\backend\admin\ApiUserController::class, 'store']);
    Route::get('/show-user/{id}', [App\Http\Controllers\api\backend\admin\ApiUserController::class, 'show']);
    Route::put('/update-user/{id}', [App\Http\Controllers\api\backend\admin\ApiUserController::class, 'update']);
    Route::delete('/delete-user/{id}', [App\Http\Controllers\api\backend\admin\ApiUserController::class, 'delete']);

});