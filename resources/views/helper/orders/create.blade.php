@extends('layouts.app')

@section('title', 'Prepare New Order')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Prepare New Order</h1>
        <a href="{{ route('helper.orders.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Orders
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    <form id="orderForm" method="POST" action="{{ route('helper.orders.store') }}">
                        @csrf

                        <div id="orderItems"></div>

                        <div id="totalItemsContainer" class="mb-3" style="display: none;">
                            <div class="alert alert-info">
                                Total Items: <span id="itemCount">0</span>
                            </div>
                        </div>

                        <button type="button" id="addItemBtn" class="btn btn-outline-primary mb-3" data-bs-toggle="modal" data-bs-target="#productModal">
                            <i class="fas fa-plus me-2"></i>Add Product
                        </button>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save me-2"></i>Prepare Order
                            </button>
                            <a href="{{ route('helper.orders.create') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-undo me-2"></i>Reset
                            </a>
                        </div>
                    </form>
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
                        @php
                            $iconClass = 'fa-box';
                            if (stripos($product->name, 'hog') !== false) {
                                $iconClass = 'fa-pig-alt';
                            } elseif (stripos($product->name, 'broiler') !== false || stripos($product->name, 'chicken') !== false) {
                                $iconClass = 'fa-dove';
                            } elseif (stripos($product->name, 'feed') !== false || stripos($product->name, 'pellet') !== false) {
                                $iconClass = 'fa-seedling';
                            }

                            $stockKilo = $product->current_stock_kilo ?? 0;
                            $stockSack = $product->current_stock_sack ?? 0;
                            $stockPiece = $product->current_stock_piece ?? 0;
                            $critKilo = $product->critical_level_kilo ?? 0;
                            $critSack = $product->critical_level_sack ?? 0;
                            $critPiece = $product->critical_level_piece ?? 0;
                            $lowestStock = min($stockKilo, $stockSack, $stockPiece);
                            $avgCrit = ($critKilo + $critSack + $critPiece) / 3;
                            $stockColor = 'text-success';
                            if ($lowestStock <= $avgCrit) {
                                $stockColor = 'text-danger';
                            } elseif ($lowestStock <= $avgCrit * 2) {
                                $stockColor = 'text-warning';
                            }
                        @endphp
                        <div class="col-md-6 col-lg-4 product-grid-item" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" data-product-brand="{{ $product->brand }}">
                            <div class="card h-100 product-card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="mb-2">
                                        <i class="fas {{ $iconClass }} fa-2x text-primary mb-2"></i>
                                    </div>
                                    <h6 class="card-title mb-1">{{ $product->name }}</h6>
                                    <p class="card-text text-muted small mb-2">{{ $product->brand }}</p>
                                    <div class="small {{ $stockColor }}">
                                        <div>Stock:</div>
                                        <div>{{ $product->current_stock_kilo }}kg | {{ $product->current_stock_sack }}s | {{ $product->current_stock_piece }}pc</div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary mt-2 quick-add" data-product-id="{{ $product->id }}">
                                        <i class="fas fa-plus me-1"></i>Quick Add
                                    </button>
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
    <div class="order-item card mb-3" style="cursor: move;">
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
                    <div class="input-group">
                        <button type="button" class="btn btn-outline-secondary qty-minus">−</button>
                        <input type="number" class="form-control quantity-input text-center" name="items[INDEX][quantity]" min="0.01" step="0.01" value="1" required>
                        <button type="button" class="btn btn-outline-secondary qty-plus">+</button>
                    </div>
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

<!-- Undo Toast -->
<div id="undoToast" style="display: none; position: fixed; top: 20px; right: 20px; z-index: 1055; min-width: 250px;">
    <div class="alert alert-warning d-flex justify-content-between align-items-center">
        <span id="undoMsg">Item removed.</span>
        <div>
            <button type="button" class="btn btn-sm btn-outline-dark me-1 undo-btn">Undo</button>
            <button type="button" class="btn-close" onclick="document.getElementById('undoToast').style.display='none';"></button>
        </div>
    </div>
</div>

<style>
.product-item {
    transition: all 0.2s ease;
    cursor: pointer;
    border: 1px solid #dee2e6;
    margin-bottom: 0.5rem;
    border-radius: 0.5rem;
    padding: 1rem;
}

.product-item:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.product-item h6 {
    color: #212529;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.add-to-order {
    display: none;
}

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

#productsList {
    max-height: 400px;
    overflow-y: auto;
    padding-right: 0.5rem;
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

.sortable-ghost {
    opacity: 0.4;
    background-color: #f8f9fa !important;
}

.qty-btn {
    border-radius: 0.25rem;
}

.order-item {
    transition: all 0.2s ease;
}

.order-item:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
let itemIndex = 0;
let removedHtml = null;
let removedProductName = '';

document.addEventListener('DOMContentLoaded', function() {
    // Initialize total
    updateTotal();

    // Initialize Sortable for drag & drop
    const orderItems = document.getElementById('orderItems');
    new Sortable(orderItems, {
        animation: 150,
        ghostClass: 'sortable-ghost',
        onEnd: function() {
            renumberItems();
        }
    });

    // Modal product search
    document.getElementById('modalProductSearch').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        document.querySelectorAll('.product-grid-item').forEach(item => {
            const productName = item.dataset.productName.toLowerCase();
            const productBrand = item.dataset.productBrand.toLowerCase();
            const matchesName = productName.includes(searchTerm);
            const matchesBrand = productBrand.includes(searchTerm);
            item.style.display = (matchesName || matchesBrand) ? 'block' : 'none';
        });
    });

    // Add click handlers to product grid items (for whole card click)
    document.querySelectorAll('.product-grid-item').forEach(item => {
        item.addEventListener('click', function(e) {
            if (!e.target.closest('.quick-add')) {
                const productId = this.dataset.productId;
                if (productId) {
                    addOrderItem(productId);
                    // Highlight feedback, but keep modal open
                    this.querySelector('.product-card').style.backgroundColor = '#d4edda';
                    setTimeout(() => {
                        this.querySelector('.product-card').style.backgroundColor = '';
                    }, 1000);
                }
            }
        });
    });

    // Add click handlers to quick-add buttons
    document.querySelectorAll('.quick-add').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const productId = this.dataset.productId;
            addOrderItem(productId);
            // Highlight
            this.closest('.product-card').style.backgroundColor = '#d4edda';
            setTimeout(() => {
                this.closest('.product-card').style.backgroundColor = '';
            }, 1000);
        });
    });

    // Undo toast handler
    const undoBtn = document.querySelector('#undoToast .undo-btn');
    if (undoBtn) {
        undoBtn.addEventListener('click', function() {
            if (removedHtml) {
                const temp = document.createElement('div');
                temp.innerHTML = removedHtml;
                const item = temp.firstElementChild;
                document.getElementById('orderItems').appendChild(item);
                addListeners(item);
                renumberItems();
                updateTotal();
            }
            document.getElementById('undoToast').style.display = 'none';
            clearTimeout(window.undoTimeout);
        });
    }

    // Clear search when modal is shown
    document.getElementById('productModal').addEventListener('show.bs.modal', function() {
        document.getElementById('modalProductSearch').value = '';
        document.querySelectorAll('.product-grid-item').forEach(item => {
            item.style.display = 'block';
        });
    });
});

function addListeners(item) {
    const removeBtn = item.querySelector('.remove-item');
    removeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to remove this item?')) {
            const itemToRemove = this.closest('.order-item');
            const productSelect = itemToRemove.querySelector('.product-select');
            removedProductName = productSelect.options[productSelect.selectedIndex]?.text || 'Unknown Product';
            removedHtml = itemToRemove.outerHTML;

            // Show undo toast
            document.getElementById('undoMsg').textContent = `Removed: ${removedProductName}`;
            const toast = document.getElementById('undoToast');
            toast.style.display = 'block';
            clearTimeout(window.undoTimeout);
            window.undoTimeout = setTimeout(() => {
                toast.style.display = 'none';
            }, 5000);

            // Remove item
            itemToRemove.remove();
            updateTotal();
            renumberItems();
        }
    });

    // Quantity steppers
    const plusBtn = item.querySelector('.qty-plus');
    plusBtn.addEventListener('click', function() {
        const input = this.parentElement.querySelector('.quantity-input');
        let val = parseFloat(input.value) || 0;
        input.value = val + 1;
    });

    const minusBtn = item.querySelector('.qty-minus');
    minusBtn.addEventListener('click', function() {
        const input = this.parentElement.querySelector('.quantity-input');
        let val = parseFloat(input.value) || 0;
        if (val > 0.01) {
            input.value = Math.max(0.01, val - 1);
        }
    });
}

function addOrderItem(productId = null) {
    const template = document.getElementById('orderItemTemplate');
    const orderItems = document.getElementById('orderItems');

    // Clone template
    const newItem = template.firstElementChild.cloneNode(true);
    newItem.innerHTML = newItem.innerHTML.replace(/INDEX/g, itemIndex);

    // Set product if specified
    if (productId) {
        const productSelect = newItem.querySelector('.product-select');
        productSelect.value = productId;
        // Set default unit to 'kilo'
        const unitSelect = newItem.querySelector('.unit-select');
        unitSelect.value = 'kilo';
        // Set default quantity to 1
        const quantityInput = newItem.querySelector('.quantity-input');
        quantityInput.value = 1;
    }

    // Add event listeners
    addListeners(newItem);

    // Append to order items
    orderItems.appendChild(newItem);
    itemIndex++;
    updateTotal();
    renumberItems();
}

function updateTotal() {
    const count = document.querySelectorAll('.order-item').length;
    document.getElementById('itemCount').textContent = count;
    document.getElementById('totalItemsContainer').style.display = count > 0 ? 'block' : 'none';
}

function renumberItems() {
    const items = document.querySelectorAll('.order-item');
    items.forEach((item, index) => {
        const inputs = item.querySelectorAll('input, select');
        inputs.forEach(el => {
            let name = el.getAttribute('name');
            if (name) {
                name = name.replace(/\[\d+\]/, '[' + index + ']');
                el.setAttribute('name', name);
            }
        });
    });
}
</script>
@endpush
