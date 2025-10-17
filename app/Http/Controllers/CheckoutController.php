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
        $isBuyNow = false;
        $cart = [];

        if (session()->has('buy_now_cart')) {
            $cart = session('buy_now_cart');
            $isBuyNow = true;
        } else {
            $cart = session('cart', []);
        }

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'ไม่มีสินค้าสำหรับชำระเงิน');
        }

        $productIds = array_keys($cart);
        $products = Product::find($productIds);

        $total = 0;
        $stockInfo = [];
        $isStockSufficient = true;
        $insufficientItems = [];

        foreach ($products as $product) {
            $qty = $cart[$product->id]['qty'];
            $total += $product->price * $qty;

            if ($product->stock < $qty) {
                $isStockSufficient = false;
                $insufficientItems[$product->name] = $product->stock;
            }
            $stockInfo[$product->id] = [
                'is_sufficient' => $product->stock >= $qty,
                'available' => $product->stock,
            ];
        }

        $bankAccount = BankAccount::where('is_active', true)->first();
        $savedAddresses = Auth::user()->addresses()->latest()->get();

        return view('checkout.index', compact('cart', 'products', 'total', 'bankAccount', 'savedAddresses', 'isBuyNow', 'stockInfo', 'isStockSufficient', 'insufficientItems'));
    }

    public function initiateBuyNow(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->input('product_id'));
        $qty = $request->input('qty');

        if ($product->stock < $qty) {
            return back()->with('error', 'สต็อกสินค้าไม่เพียงพอ (มีอยู่: ' . $product->stock . ' ชิ้น)');
        }

        // Create a temporary cart for the buy now process
        $buyNowCart = [
            $product->id => ['qty' => $qty]
        ];

        session(['buy_now_cart' => $buyNowCart]);

        return redirect()->route('checkout.index');
    }

    public function placeOrder(Request $request)
    {
        $mode = $request->input('checkout_mode', 'cart');
        $cart = session($mode === 'buy_now' ? 'buy_now_cart' : 'cart', []);

        if (empty($cart)) {
            return redirect()->route('shop.home');
        }

        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_address' => 'required|string',
            'shipping_phone' => 'required|string|max:20',
            'payment_slip' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'shipping_name.required' => 'กรุณากรอกชื่อผู้รับ',
            'shipping_address.required' => 'กรุณากรอกที่อยู่จัดส่ง',
            'shipping_phone.required' => 'กรุณากรอกเบอร์โทรศัพท์',
            'payment_slip.required' => 'กรุณาแนบสลิปการชำระเงิน',
            'payment_slip.image' => 'ไฟล์ที่แนบต้องเป็นรูปภาพเท่านั้น',
            'payment_slip.mimes' => 'รองรับไฟล์รูปภาพนามสกุล: jpg, jpeg, png, webp เท่านั้น',
            'payment_slip.max' => 'ขนาดของไฟล์ต้องไม่เกิน 2MB',
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
                    return redirect()->route('cart.index')->with('error', 'สินค้า \'' . $product->name . '\' มีในสต็อกไม่เพียงพอ (เหลือ: ' . $product->stock . ' ชิ้น)');
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

            // Clear the correct cart
            if ($mode === 'buy_now') {
                session()->forget('buy_now_cart');
            } else {
                session()->forget('cart');
            }

            return redirect()->route('account.orders.index')->with('success', 'สั่งซื้อสำเร็จแล้ว! คุณสามารถตรวจสอบสถานะได้ที่นี่');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error('Order placement failed: ' . $e->getMessage());
            return back()->with('error', 'เกิดข้อผิดพลาดในการสั่งซื้อ กรุณาลองใหม่อีกครั้ง')->withInput();
        }
    }
}
