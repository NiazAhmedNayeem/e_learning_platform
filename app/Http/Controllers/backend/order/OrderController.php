<?php

namespace App\Http\Controllers\backend\order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request){
        $search = $request->input('search');
        $orders = Order::paginate(5);
        return view('backend.order.index', compact('orders', 'search'));
    }
}
