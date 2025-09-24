@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Product Details</h1>
        <div class="btn-group">
            <a href="{{ route('inventory.products.edit', $product) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-2"></i>Edit Product
            </a>
            <a href="{{ route('inventory.products.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Products
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Product Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Basic Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th>Name:</th>
                                    <td><strong>{{ $product->name }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Brand:</th>
                                    <td>{{ $product->brand ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Description:</th>
                                    <td>{{ $product->description ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Stock Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th>Current Stock (Sacks):</th>
                                    <td>
                                        <span class="badge bg-info fs-6">{{ $product->current_stock_sack }}</span>
                                        @if($product->current_stock_sack <= $product->critical_level_sack)
                                            <span class="badge bg-danger ms-2">Low Stock</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Current Stock (Kilos):</th>
                                    <td>{{ $product->current_stock_kilo ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <th>Current Stock (Pieces):</th>
                                    <td>{{ $product->current_stock_piece ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <th>Critical Level (Sacks):</th>
                                    <td>{{ $product->critical_level_sack ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h6>Pricing Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th>Price per Sack:</th>
                                    <td>
                                        @if($product->price_per_sack)
                                            ₱{{ number_format($product->price_per_sack, 2) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Price per Kilo:</th>
                                    <td>
                                        @if($product->price_per_kilo)
                                            ₱{{ number_format($product->price_per_kilo, 2) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Price per Piece:</th>
                                    <td>
                                        @if($product->price_per_piece)
                                            ₱{{ number_format($product->price_per_piece, 2) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('inventory.stock-in.create', ['product' => $product->id]) }}"
                           class="btn btn-success">
                            <i class="bi bi-plus-circle me-2"></i>Record Stock In
                        </a>
                        <a href="{{ route('inventory.stock-out.create', ['product' => $product->id]) }}"
                           class="btn btn-warning">
                            <i class="bi bi-dash-circle me-2"></i>Record Stock Out
                        </a>
                        <a href="{{ route('inventory.products.edit', $product) }}"
                           class="btn btn-primary">
                            <i class="bi bi-pencil me-2"></i>Edit Product
                        </a>
                        <form method="POST" action="{{ route('inventory.products.destroy', $product) }}"
                              class="d-inline"
                              onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-trash me-2"></i>Delete Product
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5>Stock Status</h5>
                </div>
                <div class="card-body">
                    @if($product->current_stock_sack <= $product->critical_level_sack)
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Critical Stock Level!</strong>
                            <br>This product is below the critical stock level.
                        </div>
                    @elseif($product->current_stock_sack <= ($product->critical_level_sack * 1.5))
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            <strong>Low Stock Warning</strong>
                            <br>Consider restocking this product soon.
                        </div>
                    @else
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>
                            <strong>Stock Level OK</strong>
                            <br>Product has sufficient stock.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Stock In</h5>
                </div>
                <div class="card-body">
                    @if($product->stockIns->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Quantity</th>
                                        <th>Supplier</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->stockIns->take(5) as $stockIn)
                                        <tr>
                                            <td>{{ $stockIn->created_at->format('M j, Y') }}</td>
                                            <td>{{ $stockIn->quantity }} {{ $stockIn->unit }}</td>
                                            <td>{{ $stockIn->supplier->name ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No stock in records found.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Stock Out</h5>
                </div>
                <div class="card-body">
                    @if($product->stockOuts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Quantity</th>
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->stockOuts->take(5) as $stockOut)
                                        <tr>
                                            <td>{{ $stockOut->created_at->format('M j, Y') }}</td>
                                            <td>{{ $stockOut->quantity }} {{ $stockOut->unit }}</td>
                                            <td>{{ ucfirst($stockOut->reason) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No stock out records found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
