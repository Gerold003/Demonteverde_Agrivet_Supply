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
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Products</h5>
                            <h2 class="card-text">{{ $totalProducts }}</h2>
                        </div>
                        <i class="bi bi-boxes fs-1 opacity-75"></i>
                    </div>
                    <a href="{{ route('inventory.products.index') }}" class="text-white text-decoration-none">
                        <small>View all products <i class="bi bi-arrow-right"></i></small>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-dashboard bg-warning text-dark mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Low Stock Items</h5>
                            <h2 class="card-text">{{ $lowStockCount }}</h2>
                        </div>
                        <i class="bi bi-exclamation-triangle fs-1 opacity-75"></i>
                    </div>
                    @if($lowStockCount > 0)
                        <a href="{{ route('inventory.products.index') }}?filter=low_stock" class="text-dark text-decoration-none">
                            <small>View low stock <i class="bi bi-arrow-right"></i></small>
                        </a>
                    @else
                        <small class="text-muted">All items in stock</small>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-dashboard bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Stock Ins Today</h5>
                            <h2 class="card-text">{{ $todayStockIns }}</h2>
                        </div>
                        <i class="bi bi-arrow-down-circle fs-1 opacity-75"></i>
                    </div>
                    <a href="{{ route('inventory.stock-in.index') }}" class="text-white text-decoration-none">
                        <small>View stock ins <i class="bi bi-arrow-right"></i></small>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-dashboard bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Stock Outs Today</h5>
                            <h2 class="card-text">{{ $todayStockOuts }}</h2>
                        </div>
                        <i class="bi bi-arrow-up-circle fs-1 opacity-75"></i>
                    </div>
                    <a href="{{ route('inventory.stock-out.index') }}" class="text-white text-decoration-none">
                        <small>View stock outs <i class="bi bi-arrow-right"></i></small>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Monitoring Section -->
    <div class="row">
        <div class="col-md-6">
            <div class="card card-dashboard bg-danger text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Pending Transactions</h5>
                            <h2 class="card-text">{{ $pendingTransactionsCount }}</h2>
                        </div>
                        <i class="bi bi-clock-history fs-1 opacity-75"></i>
                    </div>
                    @if($pendingTransactionsCount > 0)
                        <a href="#pending-transactions" class="text-white text-decoration-none">
                            <small>View pending transactions <i class="bi bi-arrow-right"></i></small>
                        </a>
                    @else
                        <small class="text-muted">No pending transactions</small>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-dashboard bg-secondary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Today's Sales Impact</h5>
                            <h2 class="card-text">{{ $todaySalesTransactions }}</h2>
                        </div>
                        <i class="bi bi-graph-up fs-1 opacity-75"></i>
                    </div>
                    <small class="text-muted">Transactions affecting inventory today</small>
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

    <!-- Pending Transactions Section -->
    <div class="row" id="pending-transactions">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Pending Transactions</h5>
                </div>
                <div class="card-body">
                    @if($pendingTransactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Order ID</th>
                                        <th>Cashier</th>
                                        <th>Items</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingTransactions as $transaction)
                                        <tr>
                                            <td>#{{ $transaction->id }}</td>
                                            <td>
                                                @if($transaction->order)
                                                    #{{ $transaction->order->id }}
                                                @else
                                                    <span class="text-muted">Direct Sale</span>
                                                @endif
                                            </td>
                                            <td>{{ $transaction->cashier->name ?? 'Unknown' }}</td>
                                            <td>{{ $transaction->items->count() }} items</td>
                                            <td>₱{{ number_format($transaction->total_amount, 2) }}</td>
                                            <td>
                                                <span class="badge bg-warning">Pending</span>
                                            </td>
                                            <td>{{ $transaction->created_at->format('M j, h:i A') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No pending transactions.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
