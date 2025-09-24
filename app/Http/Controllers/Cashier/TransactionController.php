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
        // Orders that are already prepared by helper/takal
        $preparedOrders = Order::where('status', 'prepared')
            ->with('items.product')
            ->get();

        // Products for POS
        $products = Product::all();

        return view('cashier.transactions.create', compact('preparedOrders', 'products'));
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
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.unit' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // Calculate total
            $totalAmount = 0;
            foreach ($request->items as $it) {
                $totalAmount += $it['quantity'] * $it['unit_price'];
            }

            $transaction = Transaction::create([
                'order_id' => $request->order_id ?: null,
                'cashier_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'cash_received' => $request->cash_received,
                'change' => max(0, $request->cash_received - $totalAmount),
                'status' => 'completed',
            ]);

            foreach ($request->items as $it) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $it['product_id'],
                    'quantity' => $it['quantity'],
                    'unit_price' => $it['unit_price'],
                    'unit' => $it['unit'],
                    'total' => $it['quantity'] * $it['unit_price'],
                ]);

                // Update product stocks (ensure those columns exist; fallback to 'stock' if not)
                $product = Product::find($it['product_id']);
                if ($product) {
                    if ($it['unit'] === 'kilo' && isset($product->current_stock_kilo)) {
                        $product->decrement('current_stock_kilo', $it['quantity']);
                    } elseif ($it['unit'] === 'sack' && isset($product->current_stock_sack)) {
                        $product->decrement('current_stock_sack', $it['quantity']);
                    } elseif (isset($product->current_stock_piece)) {
                        $product->decrement('current_stock_piece', $it['quantity']);
                    } elseif (isset($product->stock)) {
                        $product->decrement('stock', $it['quantity']);
                    }

                    // Create automatic stock-out record for inventory tracking
                    StockOut::create([
                        'product_id' => $it['product_id'],
                        'quantity' => $it['quantity'],
                        'unit' => $it['unit'],
                        'reason' => 'sale',
                        'inventory_staff_id' => Auth::id(), // Cashier acts as inventory staff for this transaction
                        'notes' => "Automatic stock-out from transaction #{$transaction->id}",
                    ]);
                }
            }

            // Mark order completed if order_id is provided
            if ($request->order_id) {
                $order = Order::find($request->order_id);
                if ($order) {
                    $order->status = 'completed';
                    $order->save();
                }
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
