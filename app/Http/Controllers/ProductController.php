<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $q = request('q');

        $products = Product::query()
            ->when($q, function ($qb) use ($q) {
                $qb->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                       ->orWhere('category', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('products.index', compact('products', 'q'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'category'    => 'nullable|string|max:50',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        // มาจาก admin → กลับแดชบอร์ด /admin
        $to = $request->routeIs('admin.*') ? 'admin.dashboard' : 'products.index';
        return redirect()->route($to)->with('ok', 'เพิ่มสินค้าแล้ว');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:150',
            'category'     => 'nullable|string|max:50',
            'price'        => 'required|numeric|min:0',
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

        $to = $request->routeIs('admin.*') ? 'admin.dashboard' : 'products.index';
        return redirect()->route($to)->with('ok', 'อัปเดตสินค้าแล้ว');
    }

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        $to = request()->routeIs('admin.*') ? 'admin.dashboard' : 'products.index';
        return redirect()->route($to)->with('ok', 'ลบสินค้าแล้ว');
    }
}
