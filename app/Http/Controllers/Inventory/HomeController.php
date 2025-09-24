<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\Transaction;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // Get dashboard statistics
        $totalProducts = Product::count();
        $lowStockCount = Product::where('current_stock_sack', '<=', 2)->count();

        // Today's stock movements
        $today = Carbon::today();
        $todayStockIns = StockIn::whereDate('created_at', $today)->count();
        $todayStockOuts = StockOut::whereDate('created_at', $today)->count();

        // Low stock products
        $lowStockProducts = Product::where('current_stock_sack', '<=', 2)
            ->orderBy('current_stock_sack', 'asc')
            ->take(10)
            ->get();

        // Recent stock movements (last 10)
        $recentMovements = collect();
        $recentStockIns = StockIn::with('product')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $recentStockOuts = StockOut::with('product')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentMovements = $recentStockIns->concat($recentStockOuts)
            ->sortByDesc('created_at')
            ->take(10);

        // Transaction monitoring data
        $pendingTransactions = Transaction::where('status', '!=', 'completed')
            ->with(['order', 'cashier', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $pendingTransactionsCount = $pendingTransactions->count();

        // Today's sales transactions that affect inventory
        $todaySalesTransactions = Transaction::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->count();

        return view('inventory.dashboard', compact(
            'totalProducts',
            'lowStockCount',
            'todayStockIns',
            'todayStockOuts',
            'lowStockProducts',
            'recentMovements',
            'pendingTransactions',
            'pendingTransactionsCount',
            'todaySalesTransactions'
        ));
    }
}
