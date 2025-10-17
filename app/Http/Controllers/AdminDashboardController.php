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
        $salesToday = Order::whereDate('created_at', today())->where('status', '!=', 'cancelled')->sum('total_amount');
        $salesThisMonth = Order::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->where('status', '!=', 'cancelled')->sum('total_amount');
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalCustomers = \App\Models\User::where('is_admin', false)->count();

        // Low Stock Products
        $lowStockThreshold = 10;
        $lowStockProducts = Product::where('stock', '<', $lowStockThreshold)->orderBy('stock', 'asc')->limit(5)->get();

        // Sales Chart Data (Last 7 Days)
        $salesData = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as total')
        )
        ->where('created_at', '>=', now()->subDays(6))
        ->where('status', '!=', 'cancelled')
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();

        // Format data for Chart.js
        $chartLabels = [];
        $chartData = [];
        $period = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $period->push($date);
        }

        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');
            $chartLabels[] = $date->format('D, M j');
            $sale = $salesData->firstWhere('date', $dateString);
            $chartData[] = $sale ? $sale->total : 0;
        }

        // Best Selling Products (This Month)
        $bestSellingProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select('products.name', 'products.id', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->where('orders.status', '!=', 'cancelled')
            ->whereMonth('orders.created_at', now()->month)
            ->whereYear('orders.created_at', now()->year)
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'salesToday',
            'salesThisMonth',
            'pendingOrders',
            'totalCustomers',
            'lowStockProducts',
            'chartLabels',
            'chartData',
            'bestSellingProducts'
        ));
    }

    public function getNotifications()
    {
        $unreadCount = Order::whereNull('read_at')->count();
        // Fetch latest 10 orders, regardless of read status
        $notifications = Order::latest()->take(10)->get();

        return response()->json([
            'count' => $unreadCount,
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(Order $order)
    {
        if ($order->read_at === null) {
            $order->forceFill(['read_at' => now()])->save();
        }

        return response()->json(['status' => 'success']);
    }
}
