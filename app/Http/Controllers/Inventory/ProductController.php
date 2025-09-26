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

    public function create()
    {
        return view('inventory.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price_per_kilo' => 'nullable|numeric|min:0',
            'price_per_sack' => 'nullable|numeric|min:0',
            'price_per_piece' => 'nullable|numeric|min:0',
            'current_stock_kilo' => 'nullable|numeric|min:0',
            'current_stock_sack' => 'nullable|numeric|min:0',
            'current_stock_piece' => 'nullable|numeric|min:0',
            'critical_level_kilo' => 'nullable|numeric|min:0',
            'critical_level_sack' => 'nullable|numeric|min:0',
            'critical_level_piece' => 'nullable|numeric|min:0',
        ]);

        // Set default values for empty fields
        $validated['price_per_kilo'] = $validated['price_per_kilo'] ?: 0;
        $validated['price_per_sack'] = $validated['price_per_sack'] ?: 0;
        $validated['price_per_piece'] = $validated['price_per_piece'] ?: 0;
        $validated['current_stock_kilo'] = $validated['current_stock_kilo'] ?: 0;
        $validated['current_stock_sack'] = $validated['current_stock_sack'] ?: 0;
        $validated['current_stock_piece'] = $validated['current_stock_piece'] ?: 0;
        $validated['critical_level_kilo'] = $validated['critical_level_kilo'] ?: 0;
        $validated['critical_level_sack'] = $validated['critical_level_sack'] ?: 0;
        $validated['critical_level_piece'] = $validated['critical_level_piece'] ?: 0;

        Product::create($validated);

        return redirect()->route('inventory.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return view('inventory.products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price_per_kilo' => 'nullable|numeric|min:0',
            'price_per_sack' => 'nullable|numeric|min:0',
            'price_per_piece' => 'nullable|numeric|min:0',
            'current_stock_kilo' => 'nullable|numeric|min:0',
            'current_stock_sack' => 'nullable|numeric|min:0',
            'current_stock_piece' => 'nullable|numeric|min:0',
            'critical_level_kilo' => 'nullable|numeric|min:0',
            'critical_level_sack' => 'nullable|numeric|min:0',
            'critical_level_piece' => 'nullable|numeric|min:0',
        ]);

        // Set default values for empty fields
        $validated['price_per_kilo'] = $validated['price_per_kilo'] ?: 0;
        $validated['price_per_sack'] = $validated['price_per_sack'] ?: 0;
        $validated['price_per_piece'] = $validated['price_per_piece'] ?: 0;
        $validated['current_stock_kilo'] = $validated['current_stock_kilo'] ?: 0;
        $validated['current_stock_sack'] = $validated['current_stock_sack'] ?: 0;
        $validated['current_stock_piece'] = $validated['current_stock_piece'] ?: 0;
        $validated['critical_level_kilo'] = $validated['critical_level_kilo'] ?: 0;
        $validated['critical_level_sack'] = $validated['critical_level_sack'] ?: 0;
        $validated['critical_level_piece'] = $validated['critical_level_piece'] ?: 0;

        $product = Product::findOrFail($id);
        $product->update($validated);

        return redirect()->route('inventory.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('inventory.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
