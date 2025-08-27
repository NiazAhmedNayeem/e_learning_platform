<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});





Route::middleware(['auth'])->group(function () {
    //Teacher and Student profile Password Change Route
    Route::get('/settings/password',        [App\Http\Controllers\user_change_password\UserChangePasswordController::class, 'showChangeForm'])->name('password.change.form');
    Route::post('/settings/password/request',[App\Http\Controllers\user_change_password\UserChangePasswordController::class, 'requestChange'])->name('password.change.request');
    Route::get('/settings/password/otp',    [App\Http\Controllers\user_change_password\UserChangePasswordController::class, 'showOtpForm'])->name('password.change.otp');
    Route::post('/settings/password/verify',[App\Http\Controllers\user_change_password\UserChangePasswordController::class, 'verifyOtp'])->name('password.change.verify');
    Route::post('/settings/password/resend',[App\Http\Controllers\user_change_password\UserChangePasswordController::class, 'resendOtp'])->name('password.change.resend');

    //Notification
    Route::get('/profile/notifications', [App\Http\Controllers\notification\NotificationController::class, 'index'])->name('profile.notifications');
    Route::get('/profile/notifications/{id}/read', [App\Http\Controllers\notification\NotificationController::class, 'markAsRead'])->name('profile.notifications.read');

});


    




require __DIR__.'/auth.php';
