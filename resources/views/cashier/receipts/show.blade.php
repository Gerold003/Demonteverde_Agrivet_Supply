@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card p-3">
        <div class="d-flex justify-content-between">
            <h5>Receipt #{{ $transaction->id }}</h5>
            <div>
                <small>{{ $transaction->created_at->format('M d, Y h:i A') }}</small>
            </div>
        </div>

        <hr>

        <div>
            <strong>Cashier:</strong> {{ $transaction->cashier->name ?? 'N/A' }}
        </div>

        <table class="table mt-2">
            <thead><tr><th>Product</th><th>Unit</th><th>Qty</th><th>Price</th><th>Line Total</th></tr></thead>
            <tbody>
                @foreach($transaction->items as $it)
                <tr>
                    <td>{{ $it->product->name }}</td>
                    <td>{{ $it->unit }}</td>
                    <td>{{ $it->quantity }}</td>
                    <td>₱{{ number_format($it->unit_price,2) }}</td>
                    <td>₱{{ number_format($it->total,2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-end">
            <p><strong>Total:</strong> ₱{{ number_format($transaction->total_amount,2) }}</p>
            <p><strong>Cash:</strong> ₱{{ number_format($transaction->cash_received,2) }}</p>
            <p><strong>Change:</strong> ₱{{ number_format($transaction->change,2) }}</p>
        </div>

        <div class="mt-3">
            <a href="#" onclick="window.print()" class="btn btn-sm btn-outline-primary">Print</a>
            <a href="{{ route('cashier.transactions.index') }}" class="btn btn-sm btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection
