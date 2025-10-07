<?php

namespace App\Http\Controllers;

use App\Models\Product;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
        ];
        return view('admin.dashboard', compact('stats'));
    }
}
