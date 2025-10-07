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
        $items = [];
        $subtotal = 0;

        foreach ($cart as $pid => $row) {
            $line = (float)$row['price'] * (int)$row['qty'];
            $subtotal += $line;
            $items[] = [
                'id'    => $pid,
                'name'  => $row['name'],
                'price' => (float)$row['price'],
                'qty'   => (int)$row['qty'],
                'image' => $row['image'] ?? null,
                'line'  => $line,
            ];
        }

        return view('cart.index', compact('items','subtotal'));
    }

    // เพิ่มสินค้าเข้าตะกร้า
    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'qty'        => 'nullable|integer|min:1',
        ]);

        $qty = $data['qty'] ?? 1;
        $product = Product::findOrFail($data['product_id']);

        $cart = session('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['qty'] += $qty;
        } else {
            $cart[$product->id] = [
                'name'  => $product->name,
                'price' => (float)$product->price,
                'qty'   => $qty,
                'image' => $product->image, // ถ้าฟิลด์ชื่ออื่น แก้ให้ตรง
            ];
        }

        session(['cart' => $cart]);

        return back()->with('ok', 'เพิ่มลงตะกร้าแล้ว');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate(['qty' => 'required|integer|min:0']);
        $cart = session('cart', []);
        if (!isset($cart[$product->id])) {
            return back()->with('error','ไม่พบสินค้าในตะกร้า');
        }
        if ($data['qty'] === 0) unset($cart[$product->id]);
        else $cart[$product->id]['qty'] = $data['qty'];
        session(['cart' => $cart]);
        return back()->with('ok','อัปเดตจำนวนแล้ว');
    }

    public function remove(Product $product)
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);
        return back()->with('ok','ลบรายการแล้ว');
    }

    public function clear()
    {
        session()->forget('cart');
        return back()->with('ok','ล้างตะกร้าแล้ว');
    }
}
