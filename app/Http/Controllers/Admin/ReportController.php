<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function dailySales()
    {
        $today = Carbon::today();

        $todaySales = Transaction::whereDate('created_at', $today)->where('status', 'completed')->sum('total_amount');
        $transactionsCount = Transaction::whereDate('created_at', $today)->where('status', 'completed')->count();
        $transactions = Transaction::whereDate('created_at', $today)
            ->with('cashier')
            ->orderBy('created_at', 'desc')
            ->get();

        // Best-selling products by unit
        $bestSelling = \DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->whereDate('transactions.created_at', $today)
            ->where('transactions.status', 'completed')
            ->select('transaction_items.product_id', 'transaction_items.unit', \DB::raw('SUM(transaction_items.quantity) as total_qty'), 'products.name as product_name')
            ->groupBy('transaction_items.product_id', 'transaction_items.unit', 'products.name')
            ->orderBy('total_qty', 'desc')
            ->take(10)
            ->get();

        // Canceled/Refunded transactions
        $canceledRefunded = Transaction::whereDate('created_at', $today)
            ->whereIn('status', ['canceled', 'refunded'])
            ->count();

        // Sales breakdown per cashier
        $cashierBreakdown = Transaction::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->select('cashier_id', \DB::raw('COUNT(*) as transaction_count'), \DB::raw('SUM(total_amount) as total_sales'))
            ->groupBy('cashier_id')
            ->get();

        return view('admin.reports.daily', compact('todaySales', 'transactionsCount', 'transactions', 'bestSelling', 'canceledRefunded', 'cashierBreakdown'));
    }

    public function weeklySales()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $weeklySales = Transaction::whereBetween('created_at', [$startOfWeek, $endOfWeek])->where('status', 'completed')->sum('total_amount');
        $transactionsCount = Transaction::whereBetween('created_at', [$startOfWeek, $endOfWeek])->where('status', 'completed')->count();
        $transactions = Transaction::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->with('cashier')
            ->orderBy('created_at', 'desc')
            ->get();

        // Best-selling products by unit
        $bestSelling = \DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->whereBetween('transactions.created_at', [$startOfWeek, $endOfWeek])
            ->where('transactions.status', 'completed')
            ->select('transaction_items.product_id', 'transaction_items.unit', \DB::raw('SUM(transaction_items.quantity) as total_qty'), 'products.name as product_name')
            ->groupBy('transaction_items.product_id', 'transaction_items.unit', 'products.name')
            ->orderBy('total_qty', 'desc')
            ->take(10)
            ->get();

        // Canceled/Refunded transactions
        $canceledRefunded = Transaction::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->whereIn('status', ['canceled', 'refunded'])
            ->count();

        // Sales breakdown per cashier
        $cashierBreakdown = Transaction::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->where('status', 'completed')
            ->select('cashier_id', \DB::raw('COUNT(*) as transaction_count'), \DB::raw('SUM(total_amount) as total_sales'))
            ->groupBy('cashier_id')
            ->get();

        return view('admin.reports.weekly', compact('weeklySales', 'transactionsCount', 'transactions', 'startOfWeek', 'endOfWeek', 'bestSelling', 'canceledRefunded', 'cashierBreakdown'));
    }

    public function inventoryReport()
    {
        $products = Product::with('supplier')->get();
        $lowStockProducts = Product::where('current_stock_sack', '<=', 2)->get();
        $totalProducts = Product::count();

        return view('admin.reports.inventory', compact('products', 'lowStockProducts', 'totalProducts'));
    }
}
