<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class HomeController extends Controller
{
    public function index()
    {
        // Get today's orders for the authenticated helper
        $today = now()->startOfDay();
        $endOfDay = now()->endOfDay();

        $todayOrders = Order::where('helper_id', auth()->id())
            ->whereBetween('created_at', [$today, $endOfDay])
            ->count();

        // Items prepared today: items in prepared orders created today
        $preparedOrdersToday = Order::where('helper_id', auth()->id())
            ->where('status', 'prepared')
            ->whereBetween('created_at', [$today, $endOfDay])
            ->with('items')
            ->get();
        $todayItems = $preparedOrdersToday->sum(function($order) {
            return $order->items->count();
        });

        $pendingOrders = Order::where('helper_id', auth()->id())
            ->where('status', 'pending')
            ->count();

        $recentOrders = Order::where('helper_id', auth()->id())
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // All-time status counts for overview
        $totalOrders = Order::where('helper_id', auth()->id())->count();
        $preparedCount = Order::where('helper_id', auth()->id())->where('status', 'prepared')->count();
        $readyCount = Order::where('helper_id', auth()->id())->where('status', 'ready_for_pickup')->count();
        $completedCount = Order::where('helper_id', auth()->id())->where('status', 'completed')->count();
        $cancelledCount = Order::where('helper_id', auth()->id())->where('status', 'cancelled')->count();

        return view('helper.dashboard', compact(
            'todayOrders',
            'todayItems',
            'pendingOrders',
            'recentOrders',
            'totalOrders',
            'preparedCount',
            'readyCount',
            'completedCount',
            'cancelledCount'
        ));
    }
}
