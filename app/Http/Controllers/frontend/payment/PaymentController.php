<?php

namespace App\Http\Controllers\frontend\payment;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Course;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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


    public function order(Request $request){

        $user = auth()->user()->id;

        $request->validate([
            'course_id' => 'required|array',
            'course_id.*' => 'exists:courses,id',
            'payment_method' => 'required|in:bkash,nagad',
            'transaction_id' => 'required|string|max:100',
        ]);

        //dd($request->all());
        
        DB::beginTransaction();

        try{

            $order = new Order();
            $order->user_id = $user;
            $order->payment_method = $request->payment_method;
            $order->number = $request->number;
            $order->transaction_id = $request->transaction_id;
            $order->status = 'pending';

            $totalAmount = 0;

            foreach($request->course_id as $courseId){
                $course = Course::find($courseId);
                $totalAmount += $course->final_price;
            }

            $order->amount = $totalAmount;

            //dd($order);
            $order->save();


            foreach($request->course_id as $courseId){

                $course = Course::findOrFail($courseId);
                //dd($course);
                $orderItems = new OrderItem();
                $orderItems->order_id = $order->id;
                $orderItems->course_id = $course->id;
                $orderItems->price = $course->final_price;
                //dd($orderItems);
                $orderItems->save();
            }

            Cart::where('user_id', $user)->delete();


            DB::commit();
            return redirect()->route('frontend.courses')
            ->with('success', 'Order successfully, please wait for admin approval. Tank you');

        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
