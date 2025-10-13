<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // 1. Stat Cards Data
        $totalRevenue = Order::whereIn('status', ['delivered', 'shipped', 'processing'])->sum('total_amount');
        $todayRevenue = Order::whereDate('created_at', today())->whereIn('status', ['delivered', 'shipped', 'processing'])->sum('total_amount');
        $totalOrders = Order::count();
        $todayOrders = Order::whereDate('created_at', today())->count();

        // 2. Filterable Recent Orders
        $period = $request->input('period', 'all');
        $orderQuery = Order::with('user')->latest();

        match ($period) {
            'today' => $orderQuery->whereDate('created_at', today()),
            'week' => $orderQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
            'month' => $orderQuery->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year),
            'year' => $orderQuery->whereYear('created_at', now()->year),
            default => null,
        };

        $recentOrders = $orderQuery->paginate(20)->withQueryString();

        return view('admin.reports.index', compact(
            'totalRevenue', 
            'todayRevenue', 
            'totalOrders', 
            'todayOrders', 
            'recentOrders',
            'period'
        ));
    }
}
