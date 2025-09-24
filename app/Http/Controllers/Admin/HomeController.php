<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Order;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Sales Data from Cashier System
        $todaySales = Transaction::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->sum('total_amount');

        $todayTransactions = Transaction::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->count();

        $recentTransactions = Transaction::with('cashier')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Inventory Data from Inventory System
        $lowStockCount = Product::where('current_stock_sack', '<=', 2)->count();
        $lowStockProducts = Product::where('current_stock_sack', '<=', 2)
            ->orderBy('current_stock_sack', 'asc')
            ->take(5)
            ->get();

        $totalProducts = Product::count();

        // Today's stock movements
        $todayStockIns = StockIn::whereDate('created_at', $today)->count();
        $todayStockOuts = StockOut::whereDate('created_at', $today)->count();

        // Helper Data from Helper System
        $todayOrders = Order::whereDate('created_at', $today)->count();
        $readyOrders = Order::where('status', 'ready_for_pickup')->count();
        $pendingOrders = Order::where('status', 'pending')->count();

        // Active users by role
        $activeCashiers = User::where('role', 'cashier')
            ->where('last_login_at', '>=', Carbon::now()->subHours(24))
            ->count();

        $activeHelpers = User::where('role', 'helper')
            ->where('last_login_at', '>=', Carbon::now()->subHours(24))
            ->count();

        $activeInventory = User::where('role', 'inventory')
            ->where('last_login_at', '>=', Carbon::now()->subHours(24))
            ->count();

        // Weekly comparison
        $lastWeek = Carbon::now()->subWeek();
        $lastWeekSales = Transaction::whereBetween('created_at', [
            $lastWeek->startOfWeek(),
            $lastWeek->endOfWeek()
        ])->where('status', 'completed')->sum('total_amount');

        $salesGrowth = $lastWeekSales > 0 ?
            (($todaySales - $lastWeekSales) / $lastWeekSales) * 100 : 0;

        // Best performing products this week
        $bestSellingProducts = \DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->whereDate('transactions.created_at', '>=', $today->startOfWeek())
            ->where('transactions.status', 'completed')
            ->select('products.name', \DB::raw('SUM(transaction_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        // System health metrics
        $totalUsers = User::count();
        $completedTransactions = Transaction::where('status', 'completed')->count();
        $totalOrders = Order::count();

        // Recent activities across all systems
        $recentActivities = collect();

        // Recent transactions
        $recentTransactionsActivity = Transaction::with('cashier')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($transaction) {
                return [
                    'type' => 'sale',
                    'message' => "Sale #{$transaction->id} by {$transaction->cashier->name}",
                    'time' => $transaction->created_at,
                    'icon' => 'bi-cash',
                    'color' => 'success'
                ];
            });

        // Recent orders
        $recentOrdersActivity = Order::with('helper')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($order) {
                $helperName = $order->helper ? $order->helper->name : 'Unknown';
                return [
                    'type' => 'order',
                    'message' => "Order #{$order->id} prepared by {$helperName}",
                    'time' => $order->created_at,
                    'icon' => 'bi-box',
                    'color' => 'primary'
                ];
            });

        // Recent stock movements
        $recentStockActivity = StockIn::with('product')
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get()
            ->map(function($stock) {
                return [
                    'type' => 'stock_in',
                    'message' => "Stock in: {$stock->product->name} ({$stock->quantity} {$stock->unit})",
                    'time' => $stock->created_at,
                    'icon' => 'bi-arrow-down-circle',
                    'color' => 'info'
                ];
            });

        $recentActivities = $recentTransactionsActivity
            ->concat($recentOrdersActivity)
            ->concat($recentStockActivity)
            ->sortByDesc('time')
            ->take(8);

        return view('admin.dashboard', compact(
            'todaySales',
            'todayTransactions',
            'recentTransactions',
            'lowStockCount',
            'lowStockProducts',
            'totalProducts',
            'todayStockIns',
            'todayStockOuts',
            'todayOrders',
            'readyOrders',
            'pendingOrders',
            'activeCashiers',
            'activeHelpers',
            'activeInventory',
            'salesGrowth',
            'bestSellingProducts',
            'totalUsers',
            'completedTransactions',
            'totalOrders',
            'recentActivities'
        ));
    }
}
