<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name')->get();
        
        $criticalProducts = Product::where('current_stock_sack', '<=', 2)->get();
        
        return view('inventory.products.index', compact('products', 'criticalProducts'));
    }
    
    public function show($id)
    {
        $product = Product::with(['stockIns', 'stockOuts'])->findOrFail($id);
        
        return view('inventory.products.show', compact('product'));
    }
}