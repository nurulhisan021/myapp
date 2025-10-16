<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'tracking_number' => 'nullable|string|max:255|required_if:status,shipped',
            'shipping_carrier' => 'nullable|string|max:255|required_if:status,shipped',
        ], [
            'status.required' => 'กรุณาเลือกสถานะ',
            'status.in' => 'สถานะที่เลือกไม่ถูกต้อง',
            'tracking_number.required_if' => 'กรุณากรอกเลขพัสดุเมื่อสถานะเป็น จัดส่งแล้ว',
            'shipping_carrier.required_if' => 'กรุณากรอกชื่อบริษัทขนส่งเมื่อสถานะเป็น จัดส่งแล้ว',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->input('status');

        try {
            DB::transaction(function () use ($order, $oldStatus, $newStatus, $request) {
                // Update order status and tracking number
                $order->status = $newStatus;
                if ($newStatus === 'shipped') {
                    $order->tracking_number = $request->input('tracking_number');
                    $order->shipping_carrier = $request->input('shipping_carrier');
                } else {
                    $order->tracking_number = null;
                    $order->shipping_carrier = null;
                }
                $order->save();

                // Restore stock if order is cancelled
                if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                    foreach ($order->items as $item) {
                        // Check if product exists before trying to increment stock
                        if ($item->product) {
                            $item->product->increment('stock', $item->quantity);
                        }
                    }
                }
            });
        } catch (\Exception $e) {
            // Log::error('Failed to update order status: ' . $e->getMessage());
            return back()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตสถานะ');
        }

        return back()->with('success', 'อัปเดตสถานะเรียบร้อยแล้ว');
    }
}
