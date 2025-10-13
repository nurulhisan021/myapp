<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function home(Request $request)
    {
        $categories = Category::all();
        $selectedCategory = null;

        $productQuery = Product::query();

        if ($request->filled('category_id')) {
            $selectedCategory = Category::find($request->category_id);
            if ($selectedCategory) {
                $productQuery->where('category_id', $selectedCategory->id);
            }
            $products = $productQuery->latest()->get();
        } else {
            // Default: show latest 6 products
            $products = $productQuery->latest()->take(6)->get();
        }

        return view('shop.home', [
            'featured' => $products, // Rename to featured for view compatibility
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
        ]);
    }
}
