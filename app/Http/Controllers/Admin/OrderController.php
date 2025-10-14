<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $orders = Order::with('user')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product.reviews.user', 'user');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ], [
            'status.required' => 'กรุณาเลือกสถานะ',
            'status.in' => 'สถานะที่เลือกไม่ถูกต้อง',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'อัปเดตสถานะเรียบร้อยแล้ว');
    }
}
