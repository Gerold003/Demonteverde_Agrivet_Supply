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
                <h6>Orders Ready for Processing</h6>
                <h3 class="text-warning">{{ $readyOrdersCount }}</h3>
                @if($readyOrdersCount > 0)
                    <small class="text-muted">Orders waiting for cashier</small>
                @endif
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Ready Orders</h5>
                </div>
                <div class="card-body">
                    @if($readyOrders->count() > 0)
                        <div class="list-group">
                            @foreach($readyOrders->take(5) as $order)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Order #{{ $order->id }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $order->items->count() }} items |
                                            {{ $order->helper->name ?? 'Unknown Helper' }}
                                        </small>
                                    </div>
                                    <div>
                                        <a href="{{ route('cashier.transactions.process', $order->id) }}"
                                           class="btn btn-sm btn-success">
                                            Process
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($readyOrders->count() > 5)
                            <div class="text-center mt-2">
                                <a href="{{ route('cashier.transactions.create') }}" class="btn btn-outline-primary btn-sm">
                                    View All Ready Orders
                                </a>
                            </div>
                        @endif
                    @else
                        <p class="text-muted">No orders ready for processing.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Transactions</h5>
                </div>
                <div class="card-body">
                    @if($recentTransactions->count() > 0)
                        <div class="list-group">
                            @foreach($recentTransactions->take(5) as $transaction)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Transaction #{{ $transaction->id }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                ₱{{ number_format($transaction->total_amount, 2) }} |
                                                {{ $transaction->created_at->format('M j, g:i A') }}
                                            </small>
                                        </div>
                                        <div>
                                            <a href="{{ route('cashier.receipt.show', $transaction->id) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                Receipt
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-2">
                            <a href="{{ route('cashier.transactions.index') }}" class="btn btn-outline-secondary btn-sm">
                                View All Transactions
                            </a>
                        </div>
                    @else
                        <p class="text-muted">No recent transactions.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('cashier.transactions.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Process Prepared Orders
                        </a>
                        <a href="{{ route('cashier.transactions.index') }}" class="btn btn-secondary">
                            <i class="bi bi-list-ul me-2"></i>View Transaction History
                        </a>
                        @if($readyOrdersCount > 0)
                            <a href="{{ route('cashier.transactions.create') }}" class="btn btn-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>Process Ready Orders ({{ $readyOrdersCount }})
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
