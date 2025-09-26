@extends('layouts.app')

@section('title', 'Order Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Order Management</h1>
        <div class="btn-group">
            <a href="{{ route('helper.orders.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Prepare New Order
            </a>
            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#bulkStatusModal">
                <i class="fas fa-tasks me-2"></i>Update Status
            </button>
        </div>
    </div>

    <!-- Status Filter -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <form method="GET" action="{{ route('helper.orders.index') }}" id="statusFilterForm">
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="status" id="all" value="all" {{ $status == 'all' ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="btn btn-outline-secondary rounded-start" for="all">All Orders</label>

                            <input type="radio" class="btn-check" name="status" id="prepared" value="prepared" {{ $status == 'prepared' ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="btn btn-outline-warning" for="prepared">Prepared</label>

                            <input type="radio" class="btn-check" name="status" id="ready_for_pickup" value="ready_for_pickup" {{ $status == 'ready_for_pickup' ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="btn btn-outline-info" for="ready_for_pickup">Ready for Pickup</label>

                            <input type="radio" class="btn-check" name="status" id="completed" value="completed" {{ $status == 'completed' ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="btn btn-outline-success" for="completed">Completed</label>

                            <input type="radio" class="btn-check" name="status" id="cancelled" value="cancelled" {{ $status == 'cancelled' ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="btn btn-outline-danger rounded-end" for="cancelled">Cancelled</label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0 pb-0">
            <h5 class="mb-3">Orders</h5>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="ordersTable">
                    <thead class="table-light">
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>Order ID</th>
                            <th>Status</th>
                            <th>Items</th>
                            <th>Prepared At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr data-status="{{ $order->status }}" class="table-row-hover">
                                <td>
                                    <input type="checkbox" class="form-check-input order-checkbox" value="{{ $order->id }}">
                                </td>
                                <td>
                                    <strong class="text-primary">#{{ $order->id }}</strong>
                                    <br>
                                    <small class="text-muted">by {{ $order->helper->name }}</small>
                                </td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-2 fs-6 bg-{{ $order->status === 'prepared' ? 'warning' :
                                        ($order->status === 'ready_for_pickup' ? 'info' :
                                        ($order->status === 'completed' ? 'success' : 'danger')) }}">

                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $order->items->count() }} items</div>
                                    <small class="text-muted">
                                        @foreach($order->items->take(2) as $item)
                                            {{ $item->product->name }} ({{ $item->quantity }} {{ $item->unit }})
                                            @if(!$loop->last && $loop->index < 1), @endif
                                        @endforeach
                                        @if($order->items->count() > 2)
                                            <span class="text-muted">...</span>
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $order->created_at->format('M j, Y') }}</div>
                                    <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('helper.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($order->status === 'prepared')
                                            <a href="{{ route('helper.orders.edit', $order->id) }}" class="btn btn-sm btn-outline-warning rounded-pill" title="Edit Order">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-info rounded-pill" onclick="updateOrderStatus({{ $order->id }}, 'ready_for_pickup')" title="Mark as Ready">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @elseif($order->status === 'ready_for_pickup')
                                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill" onclick="updateOrderStatus({{ $order->id }}, 'completed')" title="Mark as Completed">
                                                <i class="fas fa-check-double"></i>
                                            </button>
                                        @endif
                                        @if(in_array($order->status, ['prepared', 'ready_for_pickup']))
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" onclick="cancelOrder({{ $order->id }})" title="Cancel Order">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- Bulk Status Update Modal -->
<div class="modal fade" id="bulkStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Update Order Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="bulkStatusForm" method="POST" action="{{ route('helper.orders.bulk-status') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">New Status</label>
                        <select class="form-select" name="status" required>
                            <option value="">Select Status</option>
                            <option value="prepared">Prepared</option>
                            <option value="ready_for_pickup">Ready for Pickup</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" name="notes" rows="3" placeholder="Add notes about this status change..."></textarea>
                    </div>
                    <div id="selectedOrdersCount">No orders selected</div>
                    <input type="hidden" name="order_ids" id="selectedOrderIds">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    background-color: #f8f9fa;
    border-radius: 0.5rem 0.5rem 0 0;
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

.btn-group .btn {
    margin: 0 1px;
    border-radius: 0.5rem;
}

.card {
    border-radius: 1rem;
    overflow: hidden;
}

.badge {
    font-size: 0.8rem;
    padding: 0.5rem 1rem;
    font-weight: 500;
}

.btn-group-vertical > .btn,
.btn-group > .btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* Hide rows based on filter */
.hidden {
    display: none;
}

.btn-check:checked + .btn-outline-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

.btn-check:checked + .btn-outline-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #212529;
}

.btn-check:checked + .btn-outline-info {
    background-color: #0dcaf0;
    border-color: #0dcaf0;
    color: #000;
}

.btn-check:checked + .btn-outline-success {
    background-color: #198754;
    border-color: #198754;
    color: white;
}

.btn-check:checked + .btn-outline-danger {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}
</style>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.order-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateSelectedCount();
    });

    // Individual checkbox change
    document.querySelectorAll('.order-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            updateSelectedCount();
        });
    });

    // Bulk status modal
    document.getElementById('bulkStatusModal').addEventListener('show.bs.modal', function() {
        updateSelectedCount();
    });
});

function updateSelectedCount() {
    const selected = document.querySelectorAll('.order-checkbox:checked');
    const count = selected.length;
    const ids = Array.from(selected).map(cb => cb.value);

    document.getElementById('selectedOrdersCount').textContent =
        count > 0 ? `${count} order(s) selected` : 'No orders selected';

    document.getElementById('selectedOrderIds').value = ids.join(',');

    // Enable/disable bulk update button
    const bulkBtn = document.querySelector('#bulkStatusModal .btn-primary');
    bulkBtn.disabled = count === 0;
}

function updateOrderStatus(orderId, newStatus) {
    if (confirm(`Are you sure you want to mark order #${orderId} as "${newStatus.replace('_', ' ')}"?`)) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/helper/orders/${orderId}/status`;

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

function cancelOrder(orderId) {
    if (confirm(`Are you sure you want to cancel order #${orderId}? This action cannot be undone.`)) {
        updateOrderStatus(orderId, 'cancelled');
    }
}
</script>
@endpush
