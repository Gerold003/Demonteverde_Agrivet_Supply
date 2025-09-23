<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
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
}
