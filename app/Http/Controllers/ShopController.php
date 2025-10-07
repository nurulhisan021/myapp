<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ShopController extends Controller
{
    public function home()
    {
        // เอาสินค้าล่าสุด 6 ชิ้นมาโชว์เป็น “ยอดนิยม”
        $featured = Product::orderByDesc('id')->take(6)->get();
        return view('shop.home', compact('featured'));
    }
}
