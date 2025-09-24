@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Products Management</h1>
        <a href="{{ route('inventory.products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Add New Product
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5>All Products</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Current Stock</th>
                            <th>Critical Level</th>
                            <th>Price per Sack</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    @if($product->description)
                                        <br><small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>{{ $product->brand ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $product->current_stock_sack }} sacks</span>
                                    @if($product->current_stock_kilo > 0)
                                        <br><small class="text-muted">{{ $product->current_stock_kilo }} kg</small>
                                    @endif
                                    @if($product->current_stock_piece > 0)
                                        <br><small class="text-muted">{{ $product->current_stock_piece }} pieces</small>
                                    @endif
                                </td>
                                <td>
                                    @if($product->critical_level_sack > 0)
                                        <span class="badge bg-warning">{{ $product->critical_level_sack }} sacks</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->price_per_sack)
                                        ₱{{ number_format($product->price_per_sack, 2) }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('inventory.products.show', $product) }}"
                                           class="btn btn-sm btn-outline-primary" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('inventory.products.edit', $product) }}"
                                           class="btn btn-sm btn-outline-secondary" title="Edit Product">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('inventory.products.destroy', $product) }}"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this product?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Product">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-box-seam display-4"></i>
                                        <p class="mt-2">No products found.</p>
                                        <a href="{{ route('inventory.products.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-2"></i>Add Your First Product
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
