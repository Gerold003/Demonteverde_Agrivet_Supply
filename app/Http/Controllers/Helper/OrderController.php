<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('helper_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('helper.orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::where(function($query) {
            $query->where('current_stock_kilo', '>', 0)
                ->orWhere('current_stock_sack', '>', 0)
                ->orWhere('current_stock_piece', '>', 0);
        })->get();

        return view('helper.orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit' => 'required|in:kilo,sack,piece',
        ]);

        // Create order
        $order = Order::create([
            'helper_id' => auth()->id(),
            'status' => 'prepared',
        ]);

        // Create order items
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit' => $item['unit'],
                'unit_price' => $this->getUnitPrice($product, $item['unit']),
            ]);
        }

        return redirect()->route('helper.orders.index')
            ->with('success', 'Order prepared successfully.');
    }

    public function show($id)
    {
        $order = Order::with('items.product')->findOrFail($id);

        return view('helper.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:prepared,ready_for_pickup,completed,cancelled',
            'notes' => 'nullable|string|max:500'
        ]);

        $order = Order::where('helper_id', auth()->id())->findOrFail($id);

        $order->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    public function edit($id)
    {
        $order = Order::with('items.product')->where('helper_id', auth()->id())->findOrFail($id);

        if ($order->status !== 'prepared') {
            return redirect()->back()->with('error', 'Only prepared orders can be edited.');
        }

        $products = Product::where(function($query) {
            $query->where('current_stock_kilo', '>', 0)
                ->orWhere('current_stock_sack', '>', 0)
                ->orWhere('current_stock_piece', '>', 0);
        })->get();

        return view('helper.orders.edit', compact('order', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit' => 'required|in:kilo,sack,piece',
        ]);

        $order = Order::where('helper_id', auth()->id())->findOrFail($id);

        if ($order->status !== 'prepared') {
            return redirect()->back()->with('error', 'Only prepared orders can be updated.');
        }

        // Delete existing items
        $order->items()->delete();

        // Create new order items
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit' => $item['unit'],
                'unit_price' => $this->getUnitPrice($product, $item['unit']),
            ]);
        }

        return redirect()->route('helper.orders.show', $order->id)
            ->with('success', 'Order updated successfully.');
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array|min:1',
            'status' => 'required|in:prepared,ready_for_pickup,completed,cancelled',
            'notes' => 'nullable|string|max:500'
        ]);

        $orders = Order::where('helper_id', auth()->id())
            ->whereIn('id', $request->order_ids)
            ->update([
                'status' => $request->status,
                'notes' => $request->notes,
                'updated_at' => now()
            ]);

        $message = count($request->order_ids) . ' order(s) status updated successfully.';

        return redirect()->back()->with('success', $message);
    }

    public function dailyReport()
    {
        $today = now()->startOfDay();
        $endOfDay = now()->endOfDay();

        $orders = Order::where('helper_id', auth()->id())
            ->whereBetween('created_at', [$today, $endOfDay])
            ->with('items.product')
            ->get();

        $totalOrders = $orders->count();
        $totalItems = $orders->sum(function($order) {
            return $order->items->count();
        });

        $statusCounts = $orders->groupBy('status')->map->count();

        return view('helper.reports.daily', compact('orders', 'totalOrders', 'totalItems', 'statusCounts'));
    }

    public function checkAvailability()
    {
        $products = Product::where(function($query) {
            $query->where('current_stock_kilo', '>', 0)
                ->orWhere('current_stock_sack', '>', 0)
                ->orWhere('current_stock_piece', '>', 0);
        })->with('supplier')->get();

        return view('helper.products.availability', compact('products'));
    }

    private function getUnitPrice($product, $unit)
    {
        switch ($unit) {
            case 'kilo':
                return $product->price_per_kilo;
            case 'sack':
                return $product->price_per_sack;
            case 'piece':
                return $product->price_per_piece;
            default:
                return 0;
        }
    }
}
