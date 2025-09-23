<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            if ($user->role === 'admin') {
                return $this->adminDashboard();
            } elseif ($user->role === 'cashier') {
                return $this->cashierDashboard();
            } elseif ($user->role === 'helper') {
                return $this->helperDashboard();
            } elseif ($user->role === 'inventory') {
                return $this->inventoryDashboard();
            }
        }
        
        return view('auth.login');
    }
    
    private function adminDashboard()
    {
        $today = Carbon::today();
        
        $todaySales = Transaction::whereDate('created_at', $today)->sum('total_amount');
        $todayTransactions = Transaction::whereDate('created_at', $today)->count();
        $lowStockCount = Product::where('current_stock_sack', '<=', 2)->count();
        $activeCashiers = User::where('role', 'cashier')->count();
        
        $recentTransactions = Transaction::with('cashier')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $lowStockProducts = Product::where('current_stock_sack', '<=', 2)
            ->orderBy('current_stock_sack', 'asc')
            ->take(5)
            ->get();
            
        return view('admin.dashboard', compact(
            'todaySales',
            'todayTransactions',
            'lowStockCount',
            'activeCashiers',
            'recentTransactions',
            'lowStockProducts'
        ));
    }
    
    private function cashierDashboard()
    {
        $today = Carbon::today();
        $cashierId = auth()->id();
        
        $todaySales = Transaction::where('cashier_id', $cashierId)
            ->whereDate('created_at', $today)
            ->sum('total_amount');
            
        $todayTransactions = Transaction::where('cashier_id', $cashierId)
            ->whereDate('created_at', $today)
            ->count();
            
        $preparedOrders = Order::where('status', 'prepared')->count();
        
        $orders = Order::where('status', 'prepared')
            ->with('helper')
            ->get();
            
        $recentTransactions = Transaction::where('cashier_id', $cashierId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        return view('cashier.dashboard', compact(
            'todaySales',
            'todayTransactions',
            'preparedOrders',
            'orders',
            'recentTransactions'
        ));
    }
    
    private function helperDashboard()
    {
        $today = Carbon::today();
        $helperId = auth()->id();
        
        $todayOrders = Order::where('helper_id', $helperId)
            ->whereDate('created_at', $today)
            ->count();
            
        $todayItems = Order::where('helper_id', $helperId)
            ->whereDate('created_at', $today)
            ->withCount('items')
            ->get()
            ->sum('items_count');
            
        $pendingOrders = Order::where('helper_id', $helperId)
            ->where('status', 'prepared')
            ->count();
            
        $recentOrders = Order::where('helper_id', $helperId)
            ->orderBy('created_at', 'desc')
            ->withCount('items')
            ->take(5)
            ->get();
            
        return view('helper.dashboard', compact(
            'todayOrders',
            'todayItems',
            'pendingOrders',
            'recentOrders'
        ));
    }
    
    private function inventoryDashboard()
    {
        $today = Carbon::today();
        
        $totalProducts = Product::count();
        $lowStockCount = Product::where('current_stock_sack', '<=', 2)->count();
        $todayStockIns = StockIn::whereDate('created_at', $today)->count();
        $todayStockOuts = StockOut::whereDate('created_at', $today)->count();
        
        $lowStockProducts = Product::where('current_stock_sack', '<=', 2)
            ->orderBy('current_stock_sack', 'asc')
            ->take(5)
            ->get();
            
        // Get recent stock movements (both in and out)
        $recentStockIns = StockIn::with('product')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
            
        $recentStockOuts = StockOut::with('product')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
            
        $recentMovements = $recentStockIns->concat($recentStockOuts)
            ->sortByDesc('created_at')
            ->take(5);
            
        return view('inventory.dashboard', compact(
            'totalProducts',
            'lowStockCount',
            'todayStockIns',
            'todayStockOuts',
            'lowStockProducts',
            'recentMovements'
        ));
    }
}
