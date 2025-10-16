<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist.
     */
    public function index()
    {
        $products = Auth::user()->wishlist()->latest()->paginate(12);

        return view('account.wishlist.index', compact('products'));
    }

    /**
     * Add or remove a product from the user's wishlist.
     */
    public function toggle(Product $product)
    {
        $user = Auth::user();
        $result = $user->wishlist()->toggle($product->id);

        $message = 'เกิดข้อผิดพลาด';
        if (!empty($result['attached'])) {
            $message = 'เพิ่ม \'' . $product->name . '\' ในรายการที่อยากได้แล้ว';
        } elseif (!empty($result['detached'])) {
            $message = 'นำ \'' . $product->name . '\' ออกจากรายการที่อยากได้แล้ว';
        }

        return back()->with('ok', $message);
    }
}