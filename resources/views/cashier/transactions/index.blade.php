@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Transactions</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Total</th>
                <th>Items</th>
                <th>Cash Received</th>
                <th>Change</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $t)
            <tr>
                <td>{{ $t->id }}</td>
                <td>{{ $t->created_at->format('M d, Y h:i A') }}</td>
                <td>₱{{ number_format($t->total_amount, 2) }}</td>
                <td>
                    <ul class="mb-0">
                        @foreach($t->items as $it)
                            <li>{{ $it->product->name }} ({{ $it->quantity }} {{ $it->unit }}) — ₱{{ number_format($it->total,2) }}</li>
                        @endforeach
                    </ul>
                </td>
                <td>₱{{ number_format($t->cash_received, 2) }}</td>
                <td>₱{{ number_format($t->change, 2) }}</td>
                <td>
                    <a href="{{ route('cashier.receipt.show', $t->id) }}" class="btn btn-sm btn-outline-primary">Receipt</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $transactions->links() }}
</div>
@endsection
