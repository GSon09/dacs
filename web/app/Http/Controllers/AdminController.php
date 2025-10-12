<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class AdminController extends Controller
{
    public function index()
    {
        // Statistics
        $totalOrders = Order::count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $waitingPickupOrders = Order::where('status', 'waiting_pickup')->count();
        $canceledOrders = Order::where('status', 'canceled')->count();

        $totalIncome = Order::where('status', 'delivered')->sum('total');
        $pendingOrderAmount = Order::where('status', 'pending')->sum('total');
        $canceledOrderAmount = Order::where('status', 'canceled')->sum('total');

        // Recent orders
        $recentOrders = Order::orderBy('order_date', 'desc')->limit(10)->get();

        // Revenue chart data (last 7 days)
        $revenueChart = Order::where('status', 'delivered')
            ->where('order_date', '>=', now()->subDays(7))
            ->selectRaw('DATE(order_date) as date, SUM(total) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.index', compact(
            'totalOrders',
            'deliveredOrders',
            'pendingOrders',
            'waitingPickupOrders',
            'canceledOrders',
            'totalIncome',
            'pendingOrderAmount',
            'canceledOrderAmount',
            'revenueChart',
            'recentOrders'
        ));
    }
}
