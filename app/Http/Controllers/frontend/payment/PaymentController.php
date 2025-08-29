<?php

namespace App\Http\Controllers\frontend\payment;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Course;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function singlePayment($id){
        $course = Course::find($id);
        $amount = $course->final_price;
        //dd($amount);
        return view('frontend.courses.payment', compact('course', 'amount'));
    }

    public function cartPayment(){

        $user = auth()->user()->id;

        $cartItems = Cart::with('course')->where('user_id', $user)->get();
        //dd($cartItems);

        if(!$cartItems){
            return redirect()->route('frontend.courses')->with('error', 'Your cart is empty, please any select item.');
        }

        $amount = $cartItems->sum(fn($item) => $item->course->final_price);
        //dd($amount);
        return view('frontend.courses.payment', compact('cartItems', 'amount'));
    }
}
