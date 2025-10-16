<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Review;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Admin route
        if ($request->routeIs('admin.*')) {
            $categories = Category::orderBy('name')->get();
            $selectedCategory = $request->input('category');

            $products = Product::with('category')
                ->when($selectedCategory, function ($query, $selectedCategory) {
                    return $query->where('category_id', $selectedCategory);
                })
                ->orderByDesc('id')
                ->paginate(15)
                ->withQueryString();

            return view('admin.products.index', compact('products', 'categories', 'selectedCategory'));
        }

        // Public route
        $categories = Category::orderBy('name')->get();

        $productsQuery = Product::with('category');

        // Search by name
        if ($request->filled('search')) {
            $productsQuery->where('name', 'like', '%' . $request->input('search') . '%');
        }

        // Filter by category
        if ($request->filled('category')) {
            $productsQuery->where('category_id', $request->input('category'));
        }

        // Sorting
        $sort = $request->input('sort');
        switch ($sort) {
            case 'price_asc':
                $productsQuery->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $productsQuery->orderBy('price', 'desc');
                break;
            default:
                $productsQuery->orderByDesc('id'); // Newest first
                break;
        }

        $products = $productsQuery->paginate(12)->withQueryString();

        $wishlistIds = [];
        if (Auth::check()) {
            $wishlistIds = Auth::user()->wishlist()->pluck('products.id')->toArray();
        }

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'request' => $request, // Pass request to easily access old input
            'wishlistIds' => $wishlistIds,
        ]);
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'category_id' => 'nullable|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ], [
            'name.required' => 'กรุณากรอกชื่อสินค้า',
            'price.required' => 'กรุณากรอกราคาสินค้า',
            'price.numeric' => 'ราคาต้องเป็นตัวเลข',
            'price.min' => 'ราคาต้องไม่ติดลบ',
            'stock.required' => 'กรุณากรอกจำนวนสต็อก',
            'stock.integer' => 'สต็อกต้องเป็นตัวเลขจำนวนเต็ม',
            'stock.min' => 'สต็อกต้องไม่ติดลบ',
            'image.image' => 'ไฟล์ต้องเป็นรูปภาพ',
            'image.mimes' => 'รองรับไฟล์รูปภาพนามสกุล: jpg, jpeg, png, webp เท่านั้น',
            'image.max' => 'ขนาดของไฟล์ต้องไม่เกิน 3MB',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'เพิ่มสินค้าแล้ว');
    }

    public function show(Product $product)
    {
        $product->load('reviews.user');

        $canReview = false;
        $hasPurchased = false;
        $hasReviewed = false;

        $wishlistIds = [];
        if (Auth::check()) {
            $user = Auth::user();
            $wishlistIds = $user->wishlist()->pluck('products.id')->toArray();

            $hasPurchased = Order::where('user_id', $user->id)
                ->where('status', 'delivered')
                ->whereHas('items', function ($query) use ($product) {
                    $query->where('product_id', $product->id);
                })
                ->exists();

            $hasReviewed = Review::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->exists();

            $canReview = $hasPurchased && !$hasReviewed;
        }

        return view('products.show', compact('product', 'canReview', 'hasPurchased', 'hasReviewed', 'wishlistIds'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:150',
            'category_id'  => 'nullable|exists:categories,id',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'description'  => 'nullable|string',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'remove_image' => 'sometimes|boolean',
        ], [
            'name.required' => 'กรุณากรอกชื่อสินค้า',
            'price.required' => 'กรุณากรอกราคาสินค้า',
            'price.numeric' => 'ราคาต้องเป็นตัวเลข',
            'price.min' => 'ราคาต้องไม่ติดลบ',
            'stock.required' => 'กรุณากรอกจำนวนสต็อก',
            'stock.integer' => 'สต็อกต้องเป็นตัวเลขจำนวนเต็ม',
            'stock.min' => 'สต็อกต้องไม่ติดลบ',
            'image.image' => 'ไฟล์ต้องเป็นรูปภาพ',
            'image.mimes' => 'รองรับไฟล์รูปภาพนามสกุล: jpg, jpeg, png, webp เท่านั้น',
            'image.max' => 'ขนาดของไฟล์ต้องไม่เกิน 3MB',
        ]);

        $old    = $product->image;
        $remove = (bool) $request->input('remove_image');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        } elseif ($remove) {
            $data['image'] = null;
        }

        $product->update($data);

        if (($request->hasFile('image') || $remove) && $old && Storage::disk('public')->exists($old)) {
            Storage::disk('public')->delete($old);
        }

        return redirect()->route('admin.products.index')->with('success', 'อัปเดตสินค้าแล้ว');
    }

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'ลบสินค้าแล้ว');
    }
}
