<?php

namespace App\Http\Controllers\backend\order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\OrderStatusNotification;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request){
        $search = $request->input('search');
        $orders = Order::paginate(5);
        return view('backend.order.index', compact('orders', 'search'));
    }

    public function invoice($orderId)
    {
        $order = Order::where('id', $orderId)->firstOrFail();

         return view('backend.order.invoice', compact('order'));
    }

    public function status(Request $request, $id){
        $order = Order::find($id);
        $order->status = $request->status;
        //dd($order);
        $order->update();

        if($order->status == 'approved'){
            $order->user->notify(new OrderStatusNotification('#'.$order->unique_order_id . ' - Your order is approved by admin'));
        }elseif($order->status == 'pending'){
            $order->user->notify(new OrderStatusNotification('#'.$order->unique_order_id  . ' - Your order is pending by admin'));
        }elseif($order->status == 'rejected'){
            $order->user->notify(new OrderStatusNotification('#'.$order->unique_order_id  . ' - Your order is rejected by admin'));
        }
        
        return redirect()->route('admin.order.index')->with('success', 'Order status change successfully.');
    }
}
