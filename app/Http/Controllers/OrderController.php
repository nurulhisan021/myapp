<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()->withCount('items')->latest()->paginate(10);
        return view('account.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Ensure the user can only see their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Eager load items and their product details
        $order->load('items.product');

        return view('account.orders.show', compact('order'));
    }
}
