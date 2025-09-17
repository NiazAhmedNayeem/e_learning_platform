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

        // Per page from request, default 10
        $perPage = $request->perPage ?? 10;

        // Filter by status
        if ($request->filter && $request->filter != 'all') {
            $query->where('status', $request->filter);
        }

        // Date filter
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('unique_order_id', 'like', '%' . $search . '%')
                ->orWhere('number', 'like', '%' . $search . '%')
                ->orWhere('transaction_id', 'like', '%' . $search . '%');
            });
        }

        // Paginate with dynamic per page
        $orders = $query->orderBy('id', 'desc')->paginate($perPage);

        // Total counts (all pages)
        $counts = [
            'all' => Order::count(),
            'approved' => Order::where('status', 'approved')->count(),
            'pending' => Order::where('status', 'pending')->count(),
            'rejected' => Order::where('status', 'rejected')->count()
        ];

        return response()->json([
            'data' => $orders->items(),        // table data
            'from' => $orders->firstItem() ?? 1,// SL start
            'last_page' => $orders->lastPage(),// total pages
            'current_page' => $orders->currentPage(), 
            'total' => $orders->total(),
            'counts' => $counts
        ]);
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

        ///for user notification
        if($order->status == 'approved'){
            $order->user->notify(new OrderStatusNotification('#'.$order->unique_order_id . ' - Your order is approved by admin'));
        }elseif($order->status == 'pending'){
            $order->user->notify(new OrderStatusNotification('#'.$order->unique_order_id  . ' - Your order is pending by admin'));
        }elseif($order->status == 'rejected'){
            $order->user->notify(new OrderStatusNotification('#'.$order->unique_order_id  . ' - Your order is rejected by admin'));
        }

        $counts = [
            'all' => Order::count(),
            'approved' => Order::where('status','approved')->count(),
            'pending' => Order::where('status','pending')->count(),
            'rejected' => Order::where('status','rejected')->count(),
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Order status update successfully.',
            'order' => $order,
            'counts' => $counts,
        ]);
        
        // return redirect()->route('admin.order.index')->with('success', 'Order status change successfully.');
    }
}
