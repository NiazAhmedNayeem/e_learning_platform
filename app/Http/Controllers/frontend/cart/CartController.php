<?php

namespace App\Http\Controllers\frontend\cart;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function cart(){
        $user = auth()->user()->id;
        $cartItems = Cart::with('course')->where('user_id', $user)->get();
        //$cartItems = Cart::all();
        //$cartItems = Cart::get(); 
        //dd($cartItems->toArray());
        //dd(auth()->id(), Cart::all());

        //dd($cartItems);
        return view('frontend.courses.cart', compact('cartItems'));
    }

    public function addToCart($id){
 
        $user = auth()->user()->id;

        $exists = Cart::where('user_id', $user)->where('course_id', $id)->first();

        if($exists){
            return redirect()->back()->with('error', 'This course already in cart');
        }

        DB::beginTransaction();

        try{
            $cart = new Cart();
            $cart->user_id = $user;
            $cart->course_id = $id;
            //dd($cart);
            $cart->save();
            DB::commit();
            return redirect()->back()->with('success', 'Course added to cart. Thank you.');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function removed($id){
        $user = auth()->user()->id;
        $cartItems = Cart::where('id', $id)->where('user_id', $user)->first();
        if(!$cartItems){
            return redirect()->back()->with('error', 'Cart item not found.');
        }
        $cartItems->delete();
        return redirect()->back()->with('success', 'Item removed from cart.');
    }

    public function checkout()
    {
        $userId = auth()->id();
        $cartItems = Cart::with('course')
                        ->where('user_id', $userId)
                        ->get();

        $grandTotal = $cartItems->sum(function($item){
            return $item->course->final_price;
        });

        return view('frontend.courses.checkout', compact('cartItems', 'grandTotal'));
    }

    public function checkoutNow($slug)
    {
        $userId = auth()->id();
        $course = Course::where('slug', $slug)->first();
        return view('frontend.courses.checkout_now', compact('course'));
    }

}
