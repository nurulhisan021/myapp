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
            $products = Product::with('category')->orderByDesc('id')->paginate(15);
            return view('admin.products.index', compact('products'));
        }

        // Public route
        $q = $request->input('q');

        $products = Product::with('category') // Eager load category
            ->when($q, function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhereHas('category', function ($catQuery) use ($q) {
                          $catQuery->where('name', 'like', "%{$q}%");
                      });
            })
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('products.index', compact('products', 'q'));
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

        if (Auth::check()) {
            $user = Auth::user();

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

        return view('products.show', compact('product', 'canReview', 'hasPurchased', 'hasReviewed'));
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
