@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Cashier Dashboard</h3>

    <div class="row">
        <div class="col-md-4">
            <div class="card p-3">
                <h6>Total Sales Today</h6>
                <h3>₱{{ number_format($todaySales, 2) }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <h6>Transactions Today</h6>
                <h3>{{ $transactionsCount }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <h6>Actions</h6>
                <a href="{{ route('cashier.transactions.create') }}" class="btn btn-primary btn-sm">Process Prepared Orders</a>
                <a href="{{ route('cashier.transactions.index') }}" class="btn btn-secondary btn-sm">View History</a>
            </div>
        </div>
    </div>
</div>
@endsection
