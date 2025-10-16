<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    // แสดงตะกร้า
    public function index()
    {
        $cart = session('cart', []);
        $total = 0;
        $products = Product::whereIn('id', array_keys($cart))->get();

        foreach($products as $product) {
            $total += $product->price * $cart[$product->id]['qty'];
        }

        return view('cart.index', compact('cart', 'products', 'total'));
    }

    // เพิ่มสินค้าเข้าตะกร้า
    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'qty'        => 'nullable|integer|min:1',
        ], [
            'qty.integer' => 'จำนวนต้องเป็นตัวเลข',
            'qty.min' => 'จำนวนต้องมีค่าอย่างน้อย 1',
        ]);

        $product = Product::findOrFail($data['product_id']);
        $qtyToAdd = $data['qty'] ?? 1;

        $cart = session('cart', []);
        $currentQtyInCart = $cart[$product->id]['qty'] ?? 0;

        // Check stock availability
        if ($product->stock < ($currentQtyInCart + $qtyToAdd)) {
            return back()->with('error', 'สต็อกสินค้าไม่เพียงพอ (มีอยู่: ' . $product->stock . ' ชิ้น)');
        }

        if (isset($cart[$product->id])) {
            $cart[$product->id]['qty'] += $qtyToAdd;
        } else {
            $cart[$product->id] = [
                'qty'   => $qtyToAdd,
            ];
        }

        session(['cart' => $cart]);

        return back()->with('ok', 'เพิ่ม \'' . $product->name . '\' ลงตะกร้าแล้ว');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate(['qty' => 'required|integer|min:0'], [
            'qty.required' => 'กรุณากรอกจำนวน',
            'qty.integer' => 'จำนวนต้องเป็นตัวเลข',
            'qty.min' => 'จำนวนต้องไม่ติดลบ',
        ]);
        $cart = session('cart', []);
        if (!isset($cart[$product->id])) {
            return back()->with('error','ไม่พบสินค้าในตะกร้า');
        }

        if ($data['qty'] > $product->stock) {
            return back()->with('error', 'สต็อกสินค้าไม่เพียงพอ (มีอยู่: ' . $product->stock . ' ชิ้น)');
        }

        if ($data['qty'] == 0) {
            unset($cart[$product->id]);
        } else {
            $cart[$product->id]['qty'] = $data['qty'];
        }
        
        session(['cart' => $cart]);
        return back()->with('ok','อัปเดตจำนวนแล้ว');
    }

    public function remove(Product $product)
    {
        session()->forget('cart.' . $product->id);
        return back()->with('ok','ลบรายการแล้ว');
    }

    public function clear()
    {
        session()->forget('cart');
        return back()->with('ok','ล้างตะกร้าแล้ว');
    }
}
