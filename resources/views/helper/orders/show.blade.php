@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Order Details #{{ $order->id }}</h1>
        <div class="btn-group">
            <a href="{{ route('helper.orders.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Orders
            </a>
            @if($order->status === 'prepared')
                <a href="{{ route('helper.orders.edit', $order->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Edit Order
                </a>
                <button type="button" class="btn btn-info" onclick="updateStatus('ready_for_pickup')">
                    <i class="fas fa-check me-2"></i>Mark as Ready
                </button>
            @elseif($order->status === 'ready_for_pickup')
                <button type="button" class="btn btn-success" onclick="updateStatus('completed')">
                    <i class="fas fa-check-double me-2"></i>Mark as Completed
                </button>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Order Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Order Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted">Order ID:</td>
                                    <td><strong>#{{ $order->id }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status:</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status === 'prepared' ? 'warning' :
                                            ($order->status === 'ready_for_pickup' ? 'info' :
                                            ($order->status === 'completed' ? 'success' : 'danger')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Prepared By:</td>
                                    <td>{{ $order->helper->name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Prepared At:</td>
                                    <td>{{ $order->created_at->format('M j, Y \a\t h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted">Last Updated:</td>
                                    <td>{{ $order->updated_at->format('M j, Y \a\t h:i A') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Total Items:</td>
                                    <td><strong>{{ $order->items->count() }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Total Quantity:</td>
                                    <td><strong>{{ $order->items->sum('quantity') }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Total Amount:</td>
                                    <td><strong class="text-success">₱{{ number_format($order->items->sum(function($item) {
                                        return $item->quantity * $item->unit_price;
                                    }), 2) }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Unit</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $item->product->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $item->product->brand }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ ucfirst($item->unit) }}</span>
                                        </td>
                                        <td>{{ number_format($item->quantity, 2) }}</td>
                                        <td>₱{{ number_format($item->unit_price, 2) }}</td>
