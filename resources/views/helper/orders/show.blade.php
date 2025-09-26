@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-primary">Order Details #{{ $order->id }}</h1>
        <div class="btn-group">
            <a href="{{ route('helper.orders.index') }}" class="btn btn-outline-secondary rounded-pill">
                <i class="fas fa-arrow-left me-2"></i>Back to Orders
            </a>
            @if($order->status === 'prepared')
                <a href="{{ route('helper.orders.edit', $order->id) }}" class="btn btn-warning rounded-pill">
                    <i class="fas fa-edit me-2"></i>Edit Order
                </a>
                <button type="button" class="btn btn-info rounded-pill" onclick="updateStatus('ready_for_pickup')">
                    <i class="fas fa-check me-2"></i>Mark as Ready
                </button>
            @elseif($order->status === 'ready_for_pickup')
                <button type="button" class="btn btn-success rounded-pill" onclick="updateStatus('completed')">
                    <i class="fas fa-check-double me-2"></i>Mark as Completed
                </button>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Order Information -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-primary text-white rounded-top">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Order Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted fw-medium">Order ID:</td>
                                    <td><strong class="text-primary">#{{ $order->id }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-medium">Status:</td>
                                    <td>
                                        <span class="badge rounded-pill px-3 py-2 fs-6 bg-{{ $order->status === 'prepared' ? 'warning' :
                                            ($order->status === 'ready_for_pickup' ? 'info' :
                                            ($order->status === 'completed' ? 'success' : 'danger')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-medium">Prepared By:</td>
                                    <td><span class="fw-bold">{{ $order->helper->name }}</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-medium">Prepared At:</td>
                                    <td><span class="fw-medium">{{ $order->created_at->format('M j, Y \a\t h:i A') }}</span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted fw-medium">Last Updated:</td>
                                    <td><span class="fw-medium">{{ $order->updated_at->format('M j, Y \a\t h:i A') }}</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-medium">Total Items:</td>
                                    <td><strong class="text-success">{{ $order->items->count() }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-medium">Total Quantity:</td>
                                    <td><strong class="text-info">{{ $order->items->sum('quantity') }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light rounded-top">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Order Items ({{ $order->items->count() }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Unit</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr class="table-row-hover">
                                        <td>
                                            <div>
                                                <strong class="text-dark">{{ $item->product->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $item->product->brand }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary rounded-pill px-3 py-2">{{ ucfirst($item->unit) }}</span>
                                        </td>
                                        <td><span class="fw-bold text-primary">{{ number_format($item->quantity, 2) }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: box-shadow 0.3s ease;
}

.card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.table th {
    font-weight: 600;
    color: #495057;
    background-color: #f8f9fa;
    border-top: none;
}

.table td {
    vertical-align: middle;
    border-color: #f8f9fa;
}

.table-row-hover:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
    transition: all 0.2s ease;
}

.badge {
    font-size: 0.85rem;
    font-weight: 500;
}

.btn {
    border-radius: 0.75rem;
    transition: all 0.2s ease;
    font-weight: 500;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.text-primary {
    color: #0d6efd !important;
}

.text-success {
    color: #198754 !important;
}

.text-info {
    color: #0dcaf0 !important;
}

.table-borderless td {
    padding: 0.75rem 0;
    border: none;
}

.fw-medium {
    font-weight: 500;
}
</style>

@endsection

@push('scripts')
<script>
function updateStatus(newStatus) {
    if (confirm(`Are you sure you want to mark this order as "${newStatus.replace('_', ' ')}"?`)) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/helper/orders/{{ $order->id }}/status`;

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = document.querySelector('meta[name="csrf-token"]').content;

        const status = document.createElement('input');
        status.type = 'hidden';
        status.name = 'status';
        status.value = newStatus;

        form.appendChild(csrf);
        form.appendChild(status);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
