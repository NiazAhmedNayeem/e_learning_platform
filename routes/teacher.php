<?php

use App\Http\Middleware\TeacherMiddleware;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':teacher'])->group(function(){
    //Teacher Dashboard Route
    Route::get('teacher/dashboard', [App\Http\Controllers\backend\teacher\TeacherController::class, 'index'])->name('teacher.dashboard');

    //Teacher Dashboard Route
    Route::get('teacher/profile', [App\Http\Controllers\backend\teacher\TeacherController::class, 'profile'])->name('teacher.profile');
    Route::get('teacher/profile/edit', [App\Http\Controllers\backend\teacher\TeacherController::class, 'edit'])->name('teacher.profile.edit');
    Route::post('teacher/profile/update', [App\Http\Controllers\backend\teacher\TeacherController::class, 'update'])->name('teacher.profile.update');


    

}); 

// Route::middleware(['auth'])->group(function () {
//     Route::get('profile/change-password', [App\Http\Controllers\user_change_password\UserChangePasswordController::class, 'changePassword'])->name('user.profile.change-password');
//     Route::post('profile/change-password/request', [App\Http\Controllers\user_change_password\UserChangePasswordController::class, 'requestChangePassword'])->name('user.change-password.request');

    
//     Route::get('profile/change-password/otp-verification', [App\Http\Controllers\user_change_password\UserChangePasswordController::class, 'otpVerification'])->name('user.otp_verify.change-password');
//     Route::post('profile/change-password/otp-verification/request', [App\Http\Controllers\user_change_password\UserChangePasswordController::class, 'requestOtpVerification'])->name('user.otp_verify.change-password.request');


//     Route::post('/settings/password/resend',[PasswordChangeController::class, 'resendOtp'])->name('password.change.resend');
// }); 

Route::middleware(['auth'])->group(function () {
    Route::get('/settings/password',        [App\Http\Controllers\user_change_password\UserChangePasswordController::class, 'showChangeForm'])->name('password.change.form');
    Route::post('/settings/password/request',[App\Http\Controllers\user_change_password\UserChangePasswordController::class, 'requestChange'])->name('password.change.request');

    Route::get('/settings/password/otp',    [App\Http\Controllers\user_change_password\UserChangePasswordController::class, 'showOtpForm'])->name('password.change.otp');
    Route::post('/settings/password/verify',[App\Http\Controllers\user_change_password\UserChangePasswordController::class, 'verifyOtp'])->name('password.change.verify');
    Route::post('/settings/password/resend',[App\Http\Controllers\user_change_password\UserChangePasswordController::class, 'resendOtp'])->name('password.change.resend');
});