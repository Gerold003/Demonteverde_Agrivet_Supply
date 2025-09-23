@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Process Order #{{ $order->id }}</h3>

    <form method="POST" action="{{ route('cashier.transactions.store') }}">
        @csrf
        <input type="hidden" name="order_id" value="{{ $order->id }}">

        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Unit</th>
                    <th style="width:120px">Quantity</th>
                    <th style="width:150px">Unit Price</th>
                    <th style="width:150px">Line Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->unit }}</td>
                        <td>
                            <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $item->product_id }}">
                            <input type="hidden" name="items[{{ $index }}][unit]" value="{{ $item->unit }}">
                            <input class="form-control qty" data-index="{{ $index }}" type="number" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" min="0.01" step="0.01">
                        </td>
                        <td>
                            <input class="form-control price" data-index="{{ $index }}" type="number" name="items[{{ $index }}][unit_price]" value="{{ $item->unit_price }}" min="0" step="0.01">
                        </td>
                        <td>
                            <input class="form-control line-total" data-index="{{ $index }}" type="text" readonly value="₱{{ number_format($item->quantity * $item->unit_price, 2) }}">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="row mb-3">
            <div class="col-md-4">
                <label>Subtotal</label>
                <input id="subtotal" class="form-control" readonly value="₱0.00">
            </div>
            <div class="col-md-4">
                <label>Cash Received</label>
                <input id="cash_received" name="cash_received" class="form-control" type="number" step="0.01" value="0" min="0" required>
            </div>
            <div class="col-md-4">
                <label>Change</label>
                <input id="change" class="form-control" readonly value="₱0.00">
            </div>
        </div>

        <button class="btn btn-success" type="submit">Complete Transaction</button>
        <a href="{{ route('cashier.transactions.create') }}" class="btn btn-secondary">Back</a>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function calculate() {
        let subtotal = 0;
        document.querySelectorAll('.qty').forEach(function(qEl) {
            const idx = qEl.dataset.index;
            const qty = parseFloat(qEl.value) || 0;
            const price = parseFloat(document.querySelector('.price[data-index="'+idx+'"]').value) || 0;
            const line = qty * price;
            subtotal += line;
            document.querySelector('.line-total[data-index="'+idx+'"]').value = '₱' + line.toFixed(2);
        });

        document.getElementById('subtotal').value = '₱' + subtotal.toFixed(2);

        const cash = parseFloat(document.getElementById('cash_received').value) || 0;
        const change = cash - subtotal;
        document.getElementById('change').value = '₱' + (change >= 0 ? change.toFixed(2) : '0.00');
    }

    document.querySelectorAll('.qty, .price').forEach(el => el.addEventListener('input', calculate));
    document.getElementById('cash_received').addEventListener('input', calculate);

    // initial calculation
    calculate();
});
</script>
@endsection
