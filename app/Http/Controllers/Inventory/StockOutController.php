<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockOut;
use App\Models\Product;

class StockOutController extends Controller
{
    public function index()
    {
        $stockOuts = StockOut::with('product')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('inventory.stock.out.index', compact('stockOuts'));
    }
    
    public function create()
    {
        $products = Product::all();
        
        return view('inventory.stock.out.create', compact('products'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1',
            'unit' => 'required|in:piece,sack,kilo',
            'reason' => 'required|in:damaged,expired,adjustment,other',
            'notes' => 'nullable|string',
        ]);
        
        // Check if enough stock is available
        $product = Product::find($request->product_id);
        
        if ($request->unit === 'sack' && $product->current_stock_sack < $request->quantity) {
            return back()->withErrors(['quantity' => 'Not enough stock available.']);
        }
        
        if ($request->unit === 'kilo' && $product->current_stock_kilo < $request->quantity) {
            return back()->withErrors(['quantity' => 'Not enough stock available.']);
        }
        
        if ($request->unit === 'piece' && $product->current_stock_piece < $request->quantity) {
            return back()->withErrors(['quantity' => 'Not enough stock available.']);
        }
        
        // Create stock out record
        $stockOut = StockOut::create([
            'product_id' => $request->product_id,
            'inventory_staff_id' => auth()->id(),
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'reason' => $request->reason,
            'notes' => $request->notes,
        ]);
        
        // Update product stock
        if ($request->unit === 'sack') {
            $product->current_stock_sack -= $request->quantity;
        } elseif ($request->unit === 'kilo') {
            $product->current_stock_kilo -= $request->quantity;
        } else {
            $product->current_stock_piece -= $request->quantity;
        }
        
        $product->save();
        
        return redirect()->route('inventory.stock-out.index')
            ->with('success', 'Stock-out recorded successfully.');
    }
}