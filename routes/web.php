<?php

use App\Http\Controllers\ProfileController;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', function () {
    $user = auth()->user();

    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'student':
            return redirect()->route('student.dashboard');
        case 'teacher':
            return redirect()->route('teacher.dashboard');
        default:
            abort(403, 'Unauthorized access.');
    }
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


Route::middleware('auth')->group(function() {
    ///Courses page for frontend 
    Route::get('/courses', [App\Http\Controllers\home\HomeController::class, 'courses'])->name('frontend.courses');


    Route::post('/add-to-cart/{id}', [App\Http\Controllers\frontend\cart\CartController::class, 'addToCart'])->name('frontend.add_to_cart');
    
    Route::get('/cart', [App\Http\Controllers\frontend\cart\CartController::class, 'cart'])->name('frontend.cart');
    
    Route::post('/cart/removed/{id}', [App\Http\Controllers\frontend\cart\CartController::class, 'removed'])->name('frontend.cart.removed');



    Route::get('/course/checkout', [App\Http\Controllers\frontend\cart\CartController::class, 'checkout'])->name('frontend.checkout');
    Route::get('/course/checkout-now/{slug}', [App\Http\Controllers\frontend\cart\CartController::class, 'checkoutNow'])->name('frontend.checkout.now');

    //for Buy now button
    Route::get('/single-payment/{id}', [App\Http\Controllers\frontend\payment\PaymentController::class, 'singlePayment'])->name('frontend.payment.now');
    
    Route::get('/cart-payment', [App\Http\Controllers\frontend\payment\PaymentController::class, 'cartPayment'])->name('frontend.cart.payment');

    Route::post('/order', [App\Http\Controllers\frontend\payment\PaymentController::class, 'order'])->name('frontend.order.store');






    // Route::get('/courses', [App\Http\Controllers\frontend\courses\CoursePurchaseController::class, 'index'])->name('courses.index');
});
    
///Message route start here
Route::middleware(['auth'])->group(function () {
    Route::get('/messages', [App\Http\Controllers\message\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/fetch/{id}', [App\Http\Controllers\message\MessageController::class, 'fetchMessages'])->name('messages.fetch');
    Route::post('/messages/send', [App\Http\Controllers\message\MessageController::class, 'sendMessage'])->name('messages.send');
    Route::get('/navbar/unread-messages', [App\Http\Controllers\message\MessageController::class, 'navbarUnreadMessages'])->name('navbar.unread');
    Route::post('/messages/mark-read', [App\Http\Controllers\message\MessageController::class,'markRead'])->name('messages.markRead');
    Route::get('/messages/sidebar', [App\Http\Controllers\message\MessageController::class, 'sidebar'])->name('messages.sidebar');

});










    ///ajax test
    Route::get('/ajax-test', [App\Http\Controllers\backend\admin\DashboardController::class, 'ajaxTest'])->name('ajax.test');
  



        Route::post('/form-submit', function(Illuminate\Http\Request $request){
            $validator = validator::make($request->all(), [
                'name'  => 'required|string|max:100',
                'email' => 'required|email|max:100',
                'age'   => 'required|numeric|min:1|max:120',
            ]);

            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors(),
                ], 422);
            }
            
            
            return response()->json([
                'status' => 'success',
                'message' => 'Info added successfully.',
                'data' => [
                    'name' => $request->name,
                    'email' => $request->email,
                    'age' => $request->age,
                    'greeting' => 'Hello '. $request->name.'! Your email is: '.$request->email.' and age is: '.$request->age,
                ],

            ]);
        });






require __DIR__.'/auth.php';
