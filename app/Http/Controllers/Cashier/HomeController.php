<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Order;

class HomeController extends Controller
{
    public function index()
    {
        $todaySales = Transaction::where('cashier_id', auth()->id())
            ->whereDate('created_at', today())
            ->sum('total_amount');

        $transactionsCount = Transaction::where('cashier_id', auth()->id())
            ->whereDate('created_at', today())
            ->count();

        // Get orders ready for processing
        $readyOrders = Order::where('status', 'ready_for_pickup')
            ->with(['items.product', 'helper'])
            ->orderBy('created_at', 'desc')
            ->get();

        $readyOrdersCount = $readyOrders->count();

        // Get recent transactions
        $recentTransactions = Transaction::where('cashier_id', auth()->id())
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('cashier.dashboard', compact(
            'todaySales',
            'transactionsCount',
            'readyOrders',
            'readyOrdersCount',
            'recentTransactions'
        ));
    }
}
