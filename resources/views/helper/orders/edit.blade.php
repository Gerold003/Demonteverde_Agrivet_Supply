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
        <div class="col-lg-8">
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
                                                                data-price-kilo="{{ $product->price_per_kilo }}"
                                                                data-price-sack="{{ $product->price_per_sack }}"
                                                                data-price-piece="{{ $product->price_per_piece }}"
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
                                                <div class="col-md-2">
                                                    <label class="form-label">Quantity</label>
                                                    <input type="number" class="form-control quantity-input"
                                                           name="items[{{ $index }}][quantity]"
                                                           value="{{ $item->quantity }}"
                                                           min="0.01" step="0.01" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Unit Price</label>
                                                    <input type="number" class="form-control price-input"
                                                           name="items[{{ $index }}][unit_price]"
                                                           value="{{ $item->unit_price }}"
                                                           min="0" step="0.01" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Line Total</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">₱</span>
                                                        <input type="text" class="form-control line-total"
                                                               value="{{ number_format($item->quantity * $item->unit_price, 2) }}" readonly>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-outline-danger mt-2 remove-item">
                                                        <i class="fas fa-trash"></i> Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" id="addItemBtn" class="btn btn-outline-primary">
                                <i class="fas fa-plus me-2"></i>Add Product
                            </button>

                            <div class="mt-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h5 class="card-title">Order Summary</h5>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Total Items:</span>
                                                    <span id="totalItems">{{ $order->items->count() }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Total Quantity:</span>
                                                    <span id="totalQuantity">{{ $order->items->sum('quantity') }}</span>
                                                </div>
                                                <hr>
                                                <div class="d-flex justify-content-between">
                                                    <strong>Total Amount:</strong>
                                                    <strong id="totalAmount">₱{{ number_format($order->items->sum(function($item) {
                                                        return $item->quantity * $item->unit_price;
                                                    }), 2) }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

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

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Available Products</h5>
                </div>
                <div class="card-body">
                    <div class="input-group mb-3">
                        <input type="text" id="productSearch" class="form-control" placeholder="Search products...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>

                    <div id="productsList">
                        @foreach($products as $product)
                            <div class="product-item mb-2 p-2 border rounded" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $product->name }}</h6>
                                        <small class="text-muted">{{ $product->brand }}</small>
                                        <div class="mt-1">
                                            <small class="badge bg-success me-1">Kilo: ₱{{ number_format($product->price_per_kilo, 2) }}</small>
                                            <small class="badge bg-primary me-1">Sack: ₱{{ number_format($product->price_per_sack, 2) }}</small>
                                            <small class="badge bg-warning">Piece: ₱{{ number_format($product->price_per_piece, 2) }}</small>
                                        </div>
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary add-to-order" data-product-id="{{ $product->id }}">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        Stock: {{ $product->current_stock_kilo }}kg, {{ $product->current_stock_sack }}s, {{ $product->current_stock_piece }}pc
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
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
                            <option value="{{ $product->id }}" data-price-kilo="{{ $product->price_per_kilo }}" data-price-sack="{{ $product->price_per_sack }}" data-price-piece="{{ $product->price_per_piece }}">
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
                <div class="col-md-2">
                    <label class="form-label">Quantity</label>
                    <input type="number" class="form-control quantity-input" name="items[INDEX][quantity]" min="0.01" step="0.01" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Unit Price</label>
                    <input type="number" class="form-control price-input" name="items[INDEX][unit_price]" min="0" step="0.01" readonly>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Line Total</label>
                    <div class="input-group">
                        <span class="input-group-text">₱</span>
                        <input type="text" class="form-control line-total" readonly>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger mt-2 remove-item">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.product-item {
    transition: all 0.2s ease;
    cursor: pointer;
}

.product-item:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
}

.add-to-order {
    transition: all 0.2s ease;
}

.add-to-order:hover {
    transform: scale(1.1);
}

.order-item {
    border-left: 4px solid #007bff;
}

.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: box-shadow 0.2s ease;
}

.card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.alert {
    border-radius: 12px;
}
</style>

@endsection

@push('scripts')
<script>
let itemIndex = {{ $order->items->count() }};

document.addEventListener('DOMContentLoaded', function() {
    // Add item button
    document.getElementById('addItemBtn').addEventListener('click', addOrderItem);

    // Add product to order buttons
    document.querySelectorAll('.add-to-order').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            addOrderItem(productId);
        });
    });

    // Product search
    document.getElementById('productSearch').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        document.querySelectorAll('.product-item').forEach(item => {
            const productName = item.dataset.productName.toLowerCase();
            item.style.display = productName.includes(searchTerm) ? 'block' : 'none';
        });
    });

    // Initialize calculations for existing items
    updateOrderSummary();
});

function addOrderItem(productId = null) {
    const template = document.getElementById('orderItemTemplate');
    const orderItems = document.getElementById('editOrderItems');

    // Clone template
    const newItem = template.firstElementChild.cloneNode(true);
    newItem.innerHTML = newItem.innerHTML.replace(/INDEX/g, itemIndex);

    // Set product if specified
    if (productId) {
        const productSelect = newItem.querySelector('.product-select');
        productSelect.value = productId;
        updateUnitPrice(newItem);
    }

    // Add event listeners
    const productSelect = newItem.querySelector('.product-select');
    const unitSelect = newItem.querySelector('.unit-select');
    const quantityInput = newItem.querySelector('.quantity-input');
    const removeBtn = newItem.querySelector('.remove-item');

    productSelect.addEventListener('change', function() {
        updateUnitPrice(newItem);
    });

    unitSelect.addEventListener('change', function() {
        updateUnitPrice(newItem);
    });

    quantityInput.addEventListener('input', function() {
        calculateLineTotal(newItem);
        updateOrderSummary();
    });

    removeBtn.addEventListener('click', function() {
        if (confirm('Are you sure you want to remove this item?')) {
            newItem.remove();
            updateOrderSummary();
        }
    });

    // Append to order items
    orderItems.appendChild(newItem);
    itemIndex++;

    updateOrderSummary();
}

function updateUnitPrice(itemElement) {
    const productSelect = itemElement.querySelector('.product-select');
    const unitSelect = itemElement.querySelector('.unit-select');
    const priceInput = itemElement.querySelector('.price-input');

    const selectedOption = productSelect.options[productSelect.selectedIndex];
    if (!selectedOption.value) return;

    let price = 0;
    switch (unitSelect.value) {
        case 'kilo':
            price = selectedOption.dataset.priceKilo;
            break;
        case 'sack':
            price = selectedOption.dataset.priceSack;
            break;
        case 'piece':
            price = selectedOption.dataset.pricePiece;
            break;
    }

    priceInput.value = price;
    calculateLineTotal(itemElement);
    updateOrderSummary();
}

function calculateLineTotal(itemElement) {
    const quantityInput = itemElement.querySelector('.quantity-input');
    const priceInput = itemElement.querySelector('.price-input');
    const lineTotalInput = itemElement.querySelector('.line-total');

    const quantity = parseFloat(quantityInput.value) || 0;
    const price = parseFloat(priceInput.value) || 0;
    const lineTotal = quantity * price;

    lineTotalInput.value = lineTotal.toFixed(2);
}

function updateOrderSummary() {
    let totalItems = 0;
    let totalQuantity = 0;
    let totalAmount = 0;

    document.querySelectorAll('.order-item').forEach(item => {
        totalItems++;
        const quantity = parseFloat(item.querySelector('.quantity-input').value) || 0;
        const lineTotal = parseFloat(item.querySelector('.line-total').value) || 0;

        totalQuantity += quantity;
        totalAmount += lineTotal;
    });

    document.getElementById('totalItems').textContent = totalItems;
    document.getElementById('totalQuantity').textContent = totalQuantity.toFixed(2);
    document.getElementById('totalAmount').textContent = '₱' + totalAmount.toFixed(2);
}
</script>
@endpush
