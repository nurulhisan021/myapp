<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Stat Cards Data
        $totalRevenue = Order::whereIn('status', ['delivered', 'shipped', 'processing'])->sum('total_amount');
        $todayRevenue = Order::whereDate('created_at', today())->whereIn('status', ['delivered', 'shipped', 'processing'])->sum('total_amount');
        $totalOrders = Order::count();
        $todayOrders = Order::whereDate('created_at', today())->count();

        // Filterable Orders List
        $period = $request->input('period', 'all');
        $orderQuery = Order::with('user')->latest();

        match ($period) {
            'today' => $orderQuery->whereDate('created_at', today()),
            'week' => $orderQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
            'month' => $orderQuery->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year),
            'year' => $orderQuery->whereYear('created_at', now()->year),
            default => null,
        };

        $orders = $orderQuery->paginate(20)->withQueryString();

        return view('admin.dashboard', compact(
            'totalRevenue', 
            'todayRevenue', 
            'totalOrders', 
            'todayOrders', 
            'orders',
            'period'
        ));
    }
}
