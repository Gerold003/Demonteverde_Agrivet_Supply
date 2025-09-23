@extends('layouts.app')

@section('title', 'Product Availability')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Product Availability</h1>
        <div class="btn-group">
            <button type="button" class="btn btn-outline-info" onclick="refreshAvailability()">
                <i class="fas fa-sync-alt me-2"></i>Refresh
            </button>
            <a href="{{ route('helper.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" id="productSearch" class="form-control" placeholder="Search products...">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select id="stockFilter" class="form-select">
                                <option value="all">All Products</option>
                                <option value="in_stock">In Stock</option>
                                <option value="low_stock">Low Stock</option>
                                <option value="out_of_stock">Out of Stock</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="unitFilter" class="form-select">
                                <option value="all">All Units</option>
                                <option value="kilo">Kilo</option>
                                <option value="sack">Sack</option>
                                <option value="piece">Piece</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="showLowStockOnly">
                                <label class="form-check-label" for="showLowStockOnly">
                                    Low Stock Alert
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title mb-0" id="totalProducts">{{ $products->count() }}</h5>
                            <small>Total Products</small>
                        </div>
                        <i class="fas fa-boxes fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title mb-0" id="inStockCount">0</h5>
                            <small>In Stock</small>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title mb-0" id="lowStockCount">0</h5>
                            <small>Low Stock</small>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title mb-0" id="outOfStockCount">0</h5>
                            <small>Out of Stock</small>
                        </div>
                        <i class="fas fa-times-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Available Products</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="productsTable">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Brand</th>
                            <th>Kilo Stock</th>
                            <th>Sack Stock</th>
                            <th>Piece Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr data-product-name="{{ strtolower($product->name) }}"
                                data-in-stock="{{ $product->current_stock_kilo > 0 || $product->current_stock_sack > 0 || $product->current_stock_piece > 0 ? '1' : '0' }}"
                                data-low-stock="{{ $product->isLowStock() ? '1' : '0' }}"
                                data-out-of-stock="{{ $product->current_stock_kilo <= 0 && $product->current_stock_sack <= 0 && $product->current_stock_piece <= 0 ? '1' : '0' }}">
                                <td>
                                    <div>
                                        <strong>{{ $product->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $product->description }}</small>
                                    </div>
                                </td>
                                <td>{{ $product->brand }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-success me-2">{{ $product->current_stock_kilo }}</span>
                                        <span class="text-muted small">kg</span>
                                        @if($product->current_stock_kilo <= $product->critical_level_kilo)
                                            <i class="fas fa-exclamation-triangle text-warning ms-2" title="Low stock"></i>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-primary me-2">{{ $product->current_stock_sack }}</span>
                                        <span class="text-muted small">sack</span>
                                        @if($product->current_stock_sack <= $product->critical_level_sack)
                                            <i class="fas fa-exclamation-triangle text-warning ms-2" title="Low stock"></i>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-warning me-2">{{ $product->current_stock_piece }}</span>
                                        <span class="text-muted small">pc</span>
                                        @if($product->current_stock_piece <= $product->critical_level_piece)
                                            <i class="fas fa-exclamation-triangle text-warning ms-2" title="Low stock"></i>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($product->current_stock_kilo <= 0 && $product->current_stock_sack <= 0 && $product->current_stock_piece <= 0)
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @elseif($product->isLowStock())
                                        <span class="badge bg-warning">Low Stock</span>
                                    @else
                                        <span class="badge bg-success">In Stock</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewProductDetails({{ $product->id }})">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Product Details Modal -->
<div class="modal fade" id="productDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Product Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="productDetailsContent">
                <!-- Product details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: box-shadow 0.2s ease;
}

.card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    background-color: #f8f9fa;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.btn-group .btn {
    margin: 0 2px;
}

/* Hide rows based on filter */
.hidden {
    display: none;
}

/* Low stock alert styling */
.text-warning {
    color: #ffc107 !important;
}

.fa-exclamation-triangle {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
    100% {
        opacity: 1;
    }
}
</style>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize counters
    updateCounters();

    // Search functionality
    document.getElementById('productSearch').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        filterProducts();
    });

    // Stock filter
    document.getElementById('stockFilter').addEventListener('change', filterProducts);

    // Unit filter
    document.getElementById('unitFilter').addEventListener('change', filterProducts);

    // Low stock only checkbox
    document.getElementById('showLowStockOnly').addEventListener('change', filterProducts);
});

function filterProducts() {
    const searchTerm = document.getElementById('productSearch').value.toLowerCase();
    const stockFilter = document.getElementById('stockFilter').value;
    const unitFilter = document.getElementById('unitFilter').value;
    const showLowStockOnly = document.getElementById('showLowStockOnly').checked;

    const rows = document.querySelectorAll('#productsTable tbody tr');

    rows.forEach(row => {
        const productName = row.dataset.productName;
        const inStock = row.dataset.inStock === '1';
        const lowStock = row.dataset.lowStock === '1';
        const outOfStock = row.dataset.outOfStock === '1';

        let show = true;

        // Search filter
        if (searchTerm && !productName.includes(searchTerm)) {
            show = false;
        }

        // Stock filter
        if (stockFilter === 'in_stock' && !inStock) {
            show = false;
        } else if (stockFilter === 'low_stock' && !lowStock) {
            show = false;
        } else if (stockFilter === 'out_of_stock' && !outOfStock) {
            show = false;
        }

        // Unit filter (simplified - could be enhanced)
        if (unitFilter !== 'all') {
            // This would need more complex logic based on which units have stock
        }

        // Low stock only filter
        if (showLowStockOnly && !lowStock) {
            show = false;
        }

        row.classList.toggle('hidden', !show);
    });

    updateCounters();
}

function updateCounters() {
    const visibleRows = document.querySelectorAll('#productsTable tbody tr:not(.hidden)');

    let inStockCount = 0;
    let lowStockCount = 0;
    let outOfStockCount = 0;

    visibleRows.forEach(row => {
        if (row.dataset.inStock === '1') {
            inStockCount++;
        }
        if (row.dataset.lowStock === '1') {
            lowStockCount++;
        }
        if (row.dataset.outOfStock === '1') {
            outOfStockCount++;
        }
    });

    document.getElementById('inStockCount').textContent = inStockCount;
    document.getElementById('lowStockCount').textContent = lowStockCount;
    document.getElementById('outOfStockCount').textContent = outOfStockCount;
}

function refreshAvailability() {
    // Show loading state
    const refreshBtn = event.target.closest('button');
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Refreshing...';
    refreshBtn.disabled = true;

    // Simulate refresh (in real app, this would make an AJAX call)
    setTimeout(() => {
        refreshBtn.innerHTML = originalText;
        refreshBtn.disabled = false;
        // You could reload the page or update data via AJAX here
        location.reload();
    }, 1000);
}

function viewProductDetails(productId) {
    // In a real application, this would make an AJAX call to get product details
    // For now, we'll show a placeholder
    const modal = new bootstrap.Modal(document.getElementById('productDetailsModal'));
    const content = document.getElementById('productDetailsContent');

    content.innerHTML = `
        <div class="text-center">
            <i class="fas fa-box fa-3x text-muted mb-3"></i>
            <h4>Product Details</h4>
            <p class="text-muted">Product ID: ${productId}</p>
            <p class="text-muted">Detailed product information would be loaded here.</p>
            <button class="btn btn-primary" onclick="bootstrap.Modal.getInstance(document.getElementById('productDetailsModal')).hide()">
                Close
            </button>
        </div>
    `;

    modal.show();
}
</script>
@endpush
