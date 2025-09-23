@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Inventory Report</h1>
        <div class="btn-group">
            <a href="{{ route('admin.reports.daily') }}" class="btn btn-outline-primary">Daily Report</a>
            <a href="{{ route('admin.reports.weekly') }}" class="btn btn-outline-primary">Weekly Report</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Total Products: {{ $totalProducts }}</h5>
                </div>
                <div class="card-body">
                    @if($products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Product Name</th>
                                        <th>Current Stock</th>
                                        <th>Supplier</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td>{{ $product->id }}</td>
                                            <td>{{ $product->name ?? 'N/A' }}</td>
                                            <td>{{ $product->current_stock_sack }} sacks</td>
                                            <td>{{ $product->supplier->name ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No products found.</p>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Low Stock Alert (≤2 sacks)</h5>
                </div>
                <div class="card-body">
                    @if($lowStockProducts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Product Name</th>
                                        <th>Current Stock</th>
                                        <th>Supplier</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lowStockProducts as $product)
                                        <tr class="table-warning">
                                            <td>{{ $product->id }}</td>
                                            <td>{{ $product->name ?? 'N/A' }}</td>
                                            <td>{{ $product->current_stock_sack }} sacks</td>
                                            <td>{{ $product->supplier->name ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No low stock products.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
