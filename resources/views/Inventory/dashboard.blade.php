@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Inventory Staff Dashboard</h1>
        <div class="btn-group">
            <a href="{{ route('inventory.stock-in.create') }}" class="btn btn-primary">Record Stock In</a>
            <a href="{{ route('inventory.stock-out.create') }}" class="btn btn-outline-primary">Record Stock Out</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card card-dashboard bg-primary text-white mb-4">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <h2 class="card-text">{{ $totalProducts }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-dashboard bg-warning text-dark mb-4">
                <div class="card-body">
                    <h5 class="card-title">Low Stock Items</h5>
                    <h2 class="card-text">{{ $lowStockCount }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-dashboard bg-info text-white mb-4">
                <div class="card-body">
                    <h5 class="card-title">Stock Ins Today</h5>
                    <h2 class="card-text">{{ $todayStockIns }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-dashboard bg-success text-white mb-4">
                <div class="card-body">
                    <h5 class="card-title">Stock Outs Today</h5>
                    <h2 class="card-text">{{ $todayStockOuts }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Low Stock Alert</h5>
                </div>
                <div class="card-body">
                    @if($lowStockProducts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Current Stock</th>
                                        <th>Critical Level</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lowStockProducts as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->current_stock_sack }} sacks</td>
                                            <td>{{ $product->critical_level_sack }} sacks</td>
                                            <td>
                                                <span class="badge bg-danger">Critical</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No products are low in stock.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Recent Stock Movements</h5>
                </div>
                <div class="card-body">
                    @if($recentMovements->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Type</th>
                                        <th>Quantity</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentMovements as $movement)
                                        <tr>
                                            <td>{{ $movement->product->name }}</td>
                                            <td>
                                                <span class="badge bg-{{ $movement instanceof App\Models\StockIn ? 'success' : 'warning' }}">
                                                    {{ $movement instanceof App\Models\StockIn ? 'IN' : 'OUT' }}
                                                </span>
                                            </td>
                                            <td>{{ $movement->quantity }} {{ $movement->unit }}</td>
                                            <td>{{ $movement->created_at->format('M j, h:i A') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No recent stock movements.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection