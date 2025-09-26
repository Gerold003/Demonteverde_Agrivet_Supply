<?php

// app/Http/Controllers/Cashier/TransactionController.php
namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\Order;
use App\Models\StockOut;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('cashier_id', auth()->id())
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Add this line
        $transactionsCount = Transaction::where('cashier_id', auth()->id())->count();

        return view('cashier.transactions.index', compact('transactions', 'transactionsCount'));
    }

    public function create()
    {
        // Orders that are sent to cashier by helper/takal
        $preparedOrders = Order::sentToCashier()
            ->with('items.product')
            ->get();

        return view('cashier.transactions.create', compact('preparedOrders'));
    }

    public function processOrder(Request $request, $orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);

        return view('cashier.transactions.process', compact('order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'nullable|exists:orders,id',
            'cash_received' => 'required|numeric|min:0',
        ]);

        // If order_id provided, process the prepared order
        if ($request->order_id) {
            $order = Order::sentToCashier()->with('items.product')->findOrFail($request->order_id);

            $totalAmount = 0;
            $transactionItems = [];

            foreach ($order->items as $item) {
                $unitPrice = $item->product->getPriceForUnit($item->unit);
                $itemTotal = $item->quantity * $unitPrice;
                $totalAmount += $itemTotal;

                $transactionItems[] = [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $unitPrice,
                    'unit' => $item->unit,
                    'total' => $itemTotal,
                ];
            }
        } else {
            // POS mode - validate items
            $request->validate([
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|numeric|min:0.01',
                'items.*.unit_price' => 'required|numeric|min:0',
                'items.*.unit' => 'required|string',
            ]);

            $totalAmount = 0;
            $transactionItems = $request->items;
            foreach ($transactionItems as &$it) {
                $totalAmount += $it['quantity'] * $it['unit_price'];
                $it['total'] = $it['quantity'] * $it['unit_price'];
            }
        }

        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'order_id' => $request->order_id ?: null,
                'cashier_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'cash_received' => $request->cash_received,
                'change' => max(0, $request->cash_received - $totalAmount),
                'status' => 'completed',
            ]);

            foreach ($transactionItems as $it) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $it['product_id'],
                    'quantity' => $it['quantity'],
                    'unit_price' => $it['unit_price'],
                    'unit' => $it['unit'],
                    'total' => $it['total'],
                ]);

                // Deduct stock
                $product = Product::find($it['product_id']);
                if ($product) {
                    $product->deductStock($it['quantity'], $it['unit']);

                    // Create automatic stock-out record
                    StockOut::create([
                        'product_id' => $it['product_id'],
                        'quantity' => $it['quantity'],
                        'unit' => $it['unit'],
                        'reason' => 'sale',
                        'inventory_staff_id' => Auth::id(),
                        'notes' => "Automatic stock-out from transaction #{$transaction->id}",
                    ]);
                }
            }

            // Mark order completed if order_id is provided
            if ($request->order_id) {
                $order->update(['status' => 'completed']);
            }

            DB::commit();

            return redirect()->route('cashier.receipt.show', $transaction->id)
                ->with('success', 'Transaction completed successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Failed to complete transaction: ' . $e->getMessage());
        }
    }




}
