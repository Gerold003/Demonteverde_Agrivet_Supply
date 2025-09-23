<?php

// app/Http/Controllers/Cashier/ReceiptController.php
namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class ReceiptController extends Controller
{
    public function show($id)
    {
        $transaction = Transaction::with('items.product', 'cashier')->findOrFail($id);
        return view('cashier.receipts.show', compact('transaction'));
    }
}
