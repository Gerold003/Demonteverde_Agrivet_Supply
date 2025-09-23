@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Prepared Orders</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($preparedOrders->isEmpty())
        <div class="alert alert-info">No prepared orders ready for checkout.</div>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Prepared By</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($preparedOrders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->customer_name ?? 'Walk-in' }}</td>
                        <td>
                            <ul class="mb-0">
                                @foreach($order->items as $it)
                                    <li>{{ $it->product->name }} — {{ $it->quantity }} {{ $it->unit }} (₱{{ number_format($it->unit_price,2) }})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ $order->prepared_by_name ?? 'Helper' }}</td>
                        <td>
                            <a href="{{ route('cashier.transactions.process', $order->id) }}" class="btn btn-primary btn-sm">Process</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <hr>

    <h3>Point of Sale - New Sale</h3>

    <div class="row">
        <div class="col-md-6">
            <h5>Product Selection</h5>
            <input type="text" id="product-search" class="form-control" placeholder="Search product by name...">
            <ul id="product-list" class="list-group mt-2" style="max-height: 300px; overflow-y: auto;"></ul>
        </div>

        <div class="col-md-6">
            <h5>Cart</h5>
            <form method="POST" action="{{ route('cashier.transactions.store') }}" id="pos-form">
                @csrf
                <input type="hidden" name="order_id" value="">

                <table class="table table-bordered" id="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Unit</th>
                            <th style="width: 100px;">Quantity</th>
                            <th style="width: 120px;">Unit Price</th>
                            <th style="width: 120px;">Line Total</th>
                            <th style="width: 50px;">Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Cart items will be added here dynamically -->
                    </tbody>
                </table>

                <div class="mb-3">
                    <label>Subtotal</label>
                    <input type="text" id="subtotal" class="form-control" readonly value="₱0.00">
                </div>

                <div class="mb-3">
                    <label>Cash Received</label>
                    <input type="number" id="cash_received" name="cash_received" class="form-control" step="0.01" min="0" value="0" required>
                </div>

                <div class="mb-3">
                    <label>Change</label>
                    <input type="text" id="change" class="form-control" readonly value="₱0.00">
                </div>

                <button type="submit" class="btn btn-success" id="complete-transaction-btn" disabled>Complete Transaction</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let products = @json($products ?? []);
    let cart = [];

    const productSearchInput = document.getElementById('product-search');
    const productList = document.getElementById('product-list');
    const cartTableBody = document.querySelector('#cart-table tbody');
    const subtotalInput = document.getElementById('subtotal');
    const cashReceivedInput = document.getElementById('cash_received');
    const changeInput = document.getElementById('change');
    const completeTransactionBtn = document.getElementById('complete-transaction-btn');

    function displayProductList(list) {
        productList.innerHTML = '';
        list.forEach(product => {
            const li = document.createElement('li');
            li.className = 'list-group-item list-group-item-action';
            li.textContent = product.name + ' (₱' + (product.price_per_kilo || product.price_per_sack || product.price_per_piece || 0) + ')';
            li.style.cursor = 'pointer';
            li.addEventListener('click', () => addToCart(product));
            productList.appendChild(li);
        });
    }

    displayProductList(products);

    productSearchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase();
        const filtered = products.filter(p => p.name.toLowerCase().includes(query));
        displayProductList(filtered);
    });

    function addToCart(product) {
        // Check if product already in cart
        const existingIndex = cart.findIndex(item => item.product_id === product.id);
        if (existingIndex !== -1) {
            cart[existingIndex].quantity += 1;
        } else {
            // Default unit and price selection logic (kilo, sack, piece)
            let unit = 'piece';
            let unit_price = product.price_per_piece || 0;
            if (product.price_per_kilo) {
                unit = 'kilo';
                unit_price = product.price_per_kilo;
            } else if (product.price_per_sack) {
                unit = 'sack';
                unit_price = product.price_per_sack;
            }
            cart.push({
                product_id: product.id,
                name: product.name,
                unit: unit,
                quantity: 1,
                unit_price: unit_price,
            });
        }
        renderCart();
    }

    function renderCart() {
        cartTableBody.innerHTML = '';
        cart.forEach((item, index) => {
            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td>${item.name}<input type="hidden" name="items[${index}][product_id]" value="${item.product_id}"></td>
                <td>${item.unit}<input type="hidden" name="items[${index}][unit]" value="${item.unit}"></td>
                <td><input type="number" min="0.01" step="0.01" class="form-control qty" data-index="${index}" name="items[${index}][quantity]" value="${item.quantity}"></td>
                <td><input type="number" min="0" step="0.01" class="form-control price" data-index="${index}" name="items[${index}][unit_price]" value="${item.unit_price}"></td>
                <td><input type="text" class="form-control line-total" data-index="${index}" readonly value="₱${(item.quantity * item.unit_price).toFixed(2)}"></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-btn" data-index="${index}">&times;</button></td>
            `;

            cartTableBody.appendChild(tr);
        });

        // Add event listeners for quantity, price changes and remove buttons
        document.querySelectorAll('.qty, .price').forEach(el => el.addEventListener('input', updateCartItem));
        document.querySelectorAll('.remove-btn').forEach(btn => btn.addEventListener('click', removeCartItem));

        calculateTotals();
    }

    function updateCartItem(e) {
        const index = e.target.dataset.index;
        const qty = parseFloat(document.querySelector(`.qty[data-index="${index}"]`).value) || 0;
        const price = parseFloat(document.querySelector(`.price[data-index="${index}"]`).value) || 0;

        cart[index].quantity = qty;
        cart[index].unit_price = price;

        renderCart();
    }

    function removeCartItem(e) {
        const index = e.target.dataset.index;
        cart.splice(index, 1);
        renderCart();
    }

    function calculateTotals() {
        let subtotal = 0;
        cart.forEach(item => {
            subtotal += item.quantity * item.unit_price;
        });
        subtotalInput.value = '₱' + subtotal.toFixed(2);

        const cash = parseFloat(cashReceivedInput.value) || 0;
        const change = cash - subtotal;
        changeInput.value = '₱' + (change >= 0 ? change.toFixed(2) : '0.00');

        completeTransactionBtn.disabled = cart.length === 0 || cash < subtotal;
    }

    cashReceivedInput.addEventListener('input', calculateTotals);

    // Initial render
    renderCart();
});
</script>
@endsection
