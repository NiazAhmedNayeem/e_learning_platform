<?php

namespace App\Http\Controllers\backend\order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\OrderStatusNotification;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(){
        return view('backend.order.index');
    }

    public function orderData(Request $request)
    {
        $query = Order::with('user'); 

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('unique_order_id', 'like', '%' . $search . '%')
                ->orWhere('number', 'like', '%' . $search . '%')
                ->orWhere('transaction_id', 'like', '%' . $search . '%');
            });
        }

        $orders = $query->orderBy('id', 'desc')->paginate(10);

        return response()->json($orders);
    }

    public function show($id)
    {
        $order = Order::with(['user','orderItems.course'])->findOrFail($id);

        $html = view('backend.order.partials.details', compact('order'))->render();

        return response()->json(['html' => $html]);
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

        return response()->json([
            'status' => 'success',
            'message' => 'Order status update successfully.',
            'order' => $order,
        ]);
        
        // return redirect()->route('admin.order.index')->with('success', 'Order status change successfully.');
    }
}
