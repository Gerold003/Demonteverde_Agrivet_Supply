<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockIn;
use App\Models\Product;
use App\Models\Supplier;

class StockInController extends Controller
{
    public function index()
    {
        $stockIns = StockIn::with('product', 'supplier')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('inventory.stock.in.index', compact('stockIns'));
    }

    public function create()
    {
        $products = Product::all();
        $suppliers = Supplier::all();

        return view('inventory.stock.in.create', compact('products', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:1',
            'unit' => 'required|in:piece,sack,kilo',
            'notes' => 'nullable|string',
        ]);

        // Find or create supplier
        $supplier = Supplier::firstOrCreate(
            ['name' => $request->supplier_name],
            ['name' => $request->supplier_name]
        );

        // Create stock in record
        $stockIn = StockIn::create([
            'product_id' => $request->product_id,
            'supplier_id' => $supplier->id,
            'inventory_staff_id' => auth()->id(),
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'notes' => $request->notes,
        ]);

        // Update product stock
        $product = Product::find($request->product_id);

        if ($request->unit === 'sack') {
            $product->current_stock_sack += $request->quantity;
        } elseif ($request->unit === 'kilo') {
            $product->current_stock_kilo += $request->quantity;
        } else {
            $product->current_stock_piece += $request->quantity;
        }

        $product->save();

        return redirect()->route('inventory.stock-in.index')
            ->with('success', 'Stock-in recorded successfully.');
    }
}
