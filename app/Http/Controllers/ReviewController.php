<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ], [
            'rating.required' => 'กรุณาให้คะแนนสินค้า',
            'rating.integer' => 'คะแนนต้องเป็นตัวเลข',
            'rating.min' => 'คะแนนต้องมีค่าอย่างน้อย 1',
            'rating.max' => 'คะแนนต้องมีค่าไม่เกิน 5',
            'comment.required' => 'กรุณากรอกความคิดเห็น',
            'comment.string' => 'ความคิดเห็นต้องเป็นข้อความ',
        ]);

        $product_id = $request->input('product_id');

        // Check if the user has purchased the product and the order is completed.
        $hasPurchased = Order::where('user_id', Auth::id())
            ->where('status', 'delivered') // Assuming 'completed' is the status for delivered orders.
            ->whereHas('items', function ($query) use ($product_id) {
                $query->where('product_id', $product_id);
            })
            ->exists();

        if (!$hasPurchased) {
            return back()->with('error', 'คุณสามารถรีวิวสินค้าที่เคยสั่งซื้อและได้รับแล้วเท่านั้น');
        }

        // Check if the user has already reviewed this product.
        $hasReviewed = Review::where('user_id', Auth::id())
            ->where('product_id', $product_id)
            ->exists();

        if ($hasReviewed) {
            return back()->with('error', 'คุณเคยรีวิวสินค้านี้ไปแล้ว');
        }

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $product_id,
            'rating' => $request->input('rating'),
            'comment' => $request->input('comment'),
        ]);

        return back()->with('success', 'ขอบคุณสำหรับรีวิวของคุณ!');
    }
}