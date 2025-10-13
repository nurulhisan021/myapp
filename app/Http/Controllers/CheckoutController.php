<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'ตะกร้าสินค้าของคุณว่างเปล่า');
        }

        // Eager load products to get details
        $productIds = array_keys($cart);
        $products = Product::find($productIds);

        $total = 0;
        foreach ($products as $product) {
            $total += $product->price * $cart[$product->id]['qty'];
        }

        $bankAccount = BankAccount::where('is_active', true)->first();

        return view('checkout.index', compact('cart', 'products', 'total', 'bankAccount'));
    }

    public function placeOrder(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop.home');
        }

        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_address' => 'required|string',
            'shipping_phone' => 'required|string|max:20',
            'payment_slip' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $productIds = array_keys($cart);
            $products = Product::whereIn('id', $productIds)->get();

            // Final stock check before creating order
            foreach ($products as $product) {
                $qtyInCart = $cart[$product->id]['qty'];
                if ($product->stock < $qtyInCart) {
                    DB::rollBack();
                    return redirect()->route('cart.index')->with('error', 'สินค้า \'' . $product->name . '\' มีในสต็อกไม่เพียงพอ (เหลือ: ' . $product->stock . ' ชิ้น) กรุณาปรับจำนวนในตะกร้า');
                }
            }

            $slipPath = $request->file('payment_slip')->store('slips', 'public');

            $total = 0;
            foreach ($products as $product) {
                $total += $product->price * $cart[$product->id]['qty'];
            }

            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'shipping_name' => $request->shipping_name,
                'shipping_address' => $request->shipping_address,
                'shipping_phone' => $request->shipping_phone,
                'status' => 'pending', // Initial status
                'payment_slip_path' => $slipPath,
            ]);

            foreach ($products as $product) {
                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $cart[$product->id]['qty'],
                    'price' => $product->price, // Price at time of purchase
                ]);

                // Decrement stock
                $product->decrement('stock', $cart[$product->id]['qty']);
            }

            DB::commit();

            // Clear the cart
            session()->forget('cart');

            return redirect()->route('account.orders.index')->with('success', 'สั่งซื้อสำเร็จแล้ว! คุณสามารถตรวจสอบสถานะได้ที่นี่');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error('Order placement failed: ' . $e->getMessage());
            return back()->with('error', 'เกิดข้อผิดพลาดในการสั่งซื้อ กรุณาลองใหม่อีกครั้ง')->withInput();
        }
    }
}
