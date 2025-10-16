<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()->with('items.product')->withCount('items')->oldest()->paginate(10);
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

        // Get a list of product IDs that the user has reviewed
        $reviewedProductIds = Auth::user()
            ->reviews()
            ->pluck('product_id')
            ->toArray();

        return view('account.orders.show', compact('order', 'reviewedProductIds'));
    }

    public function cancel(Order $order)
    {
        // 1. Check ownership
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // 2. Check if status is cancellable
        if ($order->status !== 'pending') {
            return back()->with('error', 'ไม่สามารถยกเลิกคำสั่งซื้อที่กำลังดำเนินการแล้วได้');
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($order) {
                // 3. Update status
                $order->update(['status' => 'cancelled']);

                // 4. Restore stock
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
            });
        } catch (\Exception $e) {
            // Log::error('Failed to cancel order: ' . $e->getMessage());
            return back()->with('error', 'เกิดข้อผิดพลาดในการยกเลิกคำสั่งซื้อ');
        }

        return back()->with('ok', 'ยกเลิกคำสั่งซื้อ #'.$order->id.' เรียบร้อยแล้ว');
    }
}
