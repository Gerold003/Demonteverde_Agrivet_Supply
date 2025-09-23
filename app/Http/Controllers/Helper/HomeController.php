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

        $todayItems = OrderItem::whereHas('order', function($query) {
            $query->where('helper_id', auth()->id());
        })
        ->whereBetween('created_at', [$today, $endOfDay])
        ->count();

        $pendingOrders = Order::where('helper_id', auth()->id())
            ->whereIn('status', ['prepared', 'ready_for_pickup'])
            ->count();

        $recentOrders = Order::where('helper_id', auth()->id())
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('helper.dashboard', compact(
            'todayOrders',
            'todayItems',
            'pendingOrders',
            'recentOrders'
        ));
    }
}
