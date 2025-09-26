@extends('layouts.app')

@section('title', 'Edit Order')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Order #{{ $order->id }}</h1>
        <div class="btn-group">
            <a href="{{ route('helper.orders.show', $order->id) }}" class="btn btn-outline-secondary">
                <i class="fas fa-eye me-2"></i>View Order
            </a>
            <a href="{{ route('helper.orders.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Orders
            </a>
        </div>
    </div>

    @if($order->status !== 'prepared')
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Warning:</strong> Only orders with "prepared" status can be edited.
            Current status: <strong>{{ ucfirst(str_replace('_', ' ', $order->status)) }}</strong>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    @if($order->status === 'prepared')
                        <form id="editOrderForm" method="POST" action="{{ route('helper.orders.update', $order->id) }}">
                            @csrf
                            @method('PUT')

                            <div id="editOrderItems">
                                @foreach($order->items as $index => $item)
                                    <div class="order-item card mb-3" data-existing="true">
                                        <div class="card-body">
                                            <div class="row align-items-end">
                                                <div class="col-md-4">
                                                    <label class="form-label">Product</label>
                                                    <select class="form-select product-select" name="items[{{ $index }}][product_id]" required>
                                                        <option value="">Select Product</option>
                                                        @foreach($products as $product)
                                                            <option value="{{ $product->id }}"
                                                                {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                                {{ $product->name }} - {{ $product->brand }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Unit</label>
                                                    <select class="form-select unit-select" name="items[{{ $index }}][unit]" required>
                                                        <option value="kilo" {{ $item->unit === 'kilo' ? 'selected' : '' }}>Kilo</option>
                                                        <option value="sack" {{ $item->unit === 'sack' ? 'selected' : '' }}>Sack</option>
                                                        <option value="piece" {{ $item->unit === 'piece' ? 'selected' : '' }}>Piece</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Quantity</label>
                                                    <input type="number" class="form-control quantity-input"
                                                           name="items[{{ $index }}][quantity]"
                                                           value="{{ $item->quantity }}"
                                                           min="0.01" step="0.01" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <button type="button" class="btn btn-sm btn-outline-danger remove-item">
                                                        <i class="fas fa-trash"></i> Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" id="addItemBtn" class="btn btn-outline-primary mb-3" data-bs-toggle="modal" data-bs-target="#productModal">
                                <i class="fas fa-plus me-2"></i>Add Product
                            </button>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save me-2"></i>Update Order
                                </button>
                                <a href="{{ route('helper.orders.show', $order->id) }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-lock fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Order Cannot Be Edited</h4>
                            <p class="text-muted">Only orders with "prepared" status can be edited.</p>
                            <a href="{{ route('helper.orders.show', $order->id) }}" class="btn btn-primary">
                                <i class="fas fa-eye me-2"></i>View Order Details
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Selection Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">
                    <i class="fas fa-search me-2"></i>Select Product
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" id="modalProductSearch" class="form-control" placeholder="Search products by name or brand..." autofocus>
                </div>

                <div id="modalProductsGrid" class="row g-3">
                    @foreach($products as $product)
                        <div class="col-md-6 col-lg-4 product-grid-item" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" data-product-brand="{{ $product->brand }}">
                            <div class="card h-100 product-card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="mb-2">
                                        <i class="fas fa-box fa-2x text-primary mb-2"></i>
                                    </div>
                                    <h6 class="card-title mb-1">{{ $product->name }}</h6>
                                    <p class="card-text text-muted small mb-2">{{ $product->brand }}</p>
                                    <div class="small text-muted">
                                        <div>Stock:</div>
                                        <div>{{ $product->current_stock_kilo }}kg | {{ $product->current_stock_sack }}s | {{ $product->current_stock_piece }}pc</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Order Item Template -->
<div id="orderItemTemplate" style="display: none;">
    <div class="order-item card mb-3">
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Product</label>
                    <select class="form-select product-select" name="items[INDEX][product_id]" required>
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->name }} - {{ $product->brand }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Unit</label>
                    <select class="form-select unit-select" name="items[INDEX][unit]" required>
                        <option value="kilo">Kilo</option>
                        <option value="sack">Sack</option>
                        <option value="piece">Piece</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" class="form-control quantity-input" name="items[INDEX][quantity]" min="0.01" step="0.01" required>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-item">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.order-item {
    border-left: 4px solid #28a745;
}

.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: box-shadow 0.2s ease;
    border-radius: 0.75rem;
}

.card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.form-select, .form-control {
    border-radius: 0.5rem;
    border: 1px solid #ced4da;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-select:focus, .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn {
    border-radius: 0.5rem;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.alert {
    border-radius: 12px;
}

.product-card {
    transition: all 0.2s ease;
    cursor: pointer;
}

.product-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
}

.product-grid-item {
    transition: all 0.2s ease;
}

.product-grid-item:hover .product-card {
    border-color: #007bff !important;
}
</style>

@endsection

@push('scripts')
<script>
let itemIndex = {{ $order->items->count() }};

document.addEventListener('DOMContentLoaded', function() {
    // Modal product search
    const modalSearchInput = document.getElementById('modalProductSearch');
    if (modalSearchInput) {
        modalSearchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            document.querySelectorAll('.product-grid-item').forEach(item => {
                const productName = item.dataset.productName.toLowerCase();
                const productBrand = item.dataset.productBrand.toLowerCase();
                const matchesName = productName.includes(searchTerm);
                const matchesBrand = productBrand.includes(searchTerm);
                item.style.display = (matchesName || matchesBrand) ? 'block' : 'none';
            });
        });
    }

    // Add click handlers to product grid items
    document.querySelectorAll('.product-grid-item').forEach(item => {
        item.addEventListener('click', function() {
            const productId = this.dataset.productId;
            if (productId) {
                addOrderItem(productId);
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
                if (modal) {
                    modal.hide();
                }
                // Optional: highlight feedback
                const productCard = this.querySelector('.product-card');
                if (productCard) {
                    productCard.style.backgroundColor = '#d4edda';
                    setTimeout(() => {
                        productCard.style.backgroundColor = '';
                    }, 1000);
                }
            }
        });
    });

    // Add remove listeners for existing items
    document.querySelectorAll('#editOrderItems .remove-item').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove this item?')) {
                this.closest('.order-item').remove();
            }
        });
    });

    // Clear search when modal is shown
    const productModal = document.getElementById('productModal');
    if (productModal) {
        productModal.addEventListener('show.bs.modal', function() {
            const searchInput = document.getElementById('modalProductSearch');
            if (searchInput) {
                searchInput.value = '';
            }
            document.querySelectorAll('.product-grid-item').forEach(item => {
                item.style.display = 'block';
            });
        });
    }
});

function addOrderItem(productId = null) {
    const template = document.getElementById('orderItemTemplate');
    const orderItems = document.getElementById('editOrderItems');

    if (!template || !orderItems) return;

    // Clone template
    const newItem = template.firstElementChild.cloneNode(true);
    newItem.innerHTML = newItem.innerHTML.replace(/INDEX/g, itemIndex);

    // Set product if specified
    if (productId) {
        const productSelect = newItem.querySelector('.product-select');
        if (productSelect) {
            productSelect.value = productId;
        }
        // Set default unit to 'kilo'
        const unitSelect = newItem.querySelector('.unit-select');
        if (unitSelect) {
            unitSelect.value = 'kilo';
        }
        // Set default quantity to 1
        const quantityInput = newItem.querySelector('.quantity-input');
        if (quantityInput) {
            quantityInput.value = 1;
        }
    }

    // Add event listeners
    const removeBtn = newItem.querySelector('.remove-item');
    if (removeBtn) {
        removeBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove this item?')) {
                newItem.remove();
            }
        });
    }

    // Append to order items
    orderItems.appendChild(newItem);
    itemIndex++;
}
</script>
@endpush
