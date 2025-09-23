<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;

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

        return view('cashier.dashboard', compact('todaySales', 'transactionsCount'));
    }
}
