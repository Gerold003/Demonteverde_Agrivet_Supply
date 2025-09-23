@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Weekly Sales Report</h1>
        <div class="btn-group">
            <a href="{{ route('admin.reports.daily') }}" class="btn btn-outline-primary">Daily Report</a>
            <a href="{{ route('admin.reports.inventory') }}" class="btn btn-outline-secondary">Inventory Report</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Weekly Sales ({{ $startOfWeek->format('M d') }} - {{ $endOfWeek->format('M d, Y') }}): ₱{{ number_format($weeklySales, 2) }}</h5>
                    <p class="mb-0">Number of Transactions: {{ $transactionsCount }}</p>
                    <p class="mb-0">Canceled/Refunded Transactions: {{ $canceledRefunded }}</p>
                </div>
                <div class="card-body">
                    @if($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Cashier</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td>#{{ $transaction->id }}</td>
                                            <td>{{ $transaction->cashier->name ?? 'N/A' }}</td>
                                            <td>₱{{ number_format($transaction->total_amount, 2) }}</td>
                                            <td>{{ $transaction->created_at->format('M d, h:i A') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No transactions this week.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Best-Selling Products</h5>
                </div>
                <div class="card-body">
                    @if($bestSelling->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($bestSelling as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ \App\Models\Product::find($item->product_id)->name ?? 'N/A' }} ({{ $item->unit }})
                                    <span class="badge badge-primary badge-pill">{{ $item->total_qty }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No sales data.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Sales Breakdown per Cashier</h5>
                </div>
                <div class="card-body">
                    @if($cashierBreakdown->count() > 0)
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Cashier</th>
                                    <th>Transactions</th>
                                    <th>Total Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cashierBreakdown as $breakdown)
                                    <tr>
                                        <td>{{ $breakdown->cashier->name ?? 'N/A' }}</td>
                                        <td>{{ $breakdown->transaction_count }}</td>
                                        <td>₱{{ number_format($breakdown->total_sales, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">No sales data.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
