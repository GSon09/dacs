<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(Request $request)
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

        // Determine date range from request or default to last 7 days
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::today()->endOfDay();
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : (clone $endDate)->subDays(6)->startOfDay();

        // Revenue raw data grouped by date between the chosen range
        $rawRevenue = Order::where('status', 'delivered')
            ->whereBetween(DB::raw('DATE(order_date)'), [$startDate->toDateString(), $endDate->toDateString()])
            ->selectRaw('DATE(order_date) as date, SUM(total) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Normalize data so every date in the period has an entry (0 if none)
        $period = CarbonPeriod::create($startDate->toDateString(), $endDate->toDateString());
        $revMap = $rawRevenue->pluck('revenue', 'date')->toArray();
        $revenueChart = collect();
        foreach ($period as $dt) {
            $d = $dt->format('Y-m-d');
            $revenueChart->push((object)[
                'date' => $d,
                'revenue' => isset($revMap[$d]) ? (float)$revMap[$d] : 0,
            ]);
        }

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
            'startDate',
            'endDate',
            'recentOrders'
        ));
    }
}
