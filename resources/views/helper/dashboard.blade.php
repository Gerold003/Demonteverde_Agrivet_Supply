@extends('layouts.app')

@section('title', 'Helper Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Helper Dashboard</h1>
        <div class="btn-group">
            <span class="text-muted me-3">Welcome back, {{ auth()->user()->name }}!</span>
            <a href="{{ route('helper.orders.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Prepare New Order
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title mb-0">{{ $todayOrders }}</h5>
                            <small>Today's Orders</small>
                        </div>
                        <i class="fas fa-clipboard-list fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title mb-0">{{ $todayItems }}</h5>
                            <small>Items Prepared</small>
                        </div>
                        <i class="fas fa-boxes fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title mb-0">{{ $pendingOrders }}</h5>
                            <small>Pending Orders</small>
                        </div>
                        <i class="fas fa-clock fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title mb-0">{{ $recentOrders->count() }}</h5>
                            <small>Recent Orders</small>
                        </div>
                        <i class="fas fa-history fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Orders -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Orders</h5>
                    <a href="{{ route('helper.orders.index') }}" class="btn btn-sm btn-outline-primary">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body">
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Status</th>
                                    <th>Items</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td>
                                                <strong>#{{ $order->id }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $order->status === 'prepared' ? 'warning' :
                                                    ($order->status === 'ready_for_pickup' ? 'info' :
                                                    ($order->status === 'completed' ? 'success' : 'danger')) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                </span>
                                            </td>
                                            <td>{{ $order->items->count() }} items</td>
                                            <td>
                                                <small>{{ $order->created_at->format('M j, h:i A') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('helper.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($order->status === 'prepared')
                                                        <button type="button" class="btn btn-sm btn-outline-info" onclick="updateOrderStatus({{ $order->id }}, 'ready_for_pickup')" title="Mark as Ready">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No Recent Orders</h4>
                            <p class="text-muted">You haven't prepared any orders yet today.</p>
                            <a href="{{ route('helper.orders.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Prepare Your First Order
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions & Status Overview -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('helper.orders.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Prepare New Order
                        </a>
                        <a href="{{ route('helper.orders.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i>View All Orders
                        </a>
                        <a href="{{ route('helper.products.availability') }}" class="btn btn-outline-info">
                            <i class="fas fa-boxes me-2"></i>Check Product Availability
                        </a>
                        <a href="{{ route('helper.reports.daily') }}" class="btn btn-outline-success">
                            <i class="fas fa-chart-bar me-2"></i>Daily Report
                        </a>
                    </div>
                </div>
            </div>

            <!-- Today's Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Today's Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-0">{{ $todayOrders }}</h4>
                                <small class="text-muted">Orders</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-0">{{ $todayItems }}</h4>
                            <small class="text-muted">Items</small>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Pending Orders:</span>
                        <span class="badge bg-warning">{{ $pendingOrders }}</span>
                    </div>
                </div>
            </div>

            <!-- Status Overview -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Status Overview</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Prepared</span>
                            <span class="badge bg-warning">{{ $preparedCount }}</span>
                        </div>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $totalOrders > 0 ? ($preparedCount / $totalOrders * 100) : 0 }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Ready for Pickup</span>
                            <span class="badge bg-info">{{ $readyCount }}</span>
                        </div>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $totalOrders > 0 ? ($readyCount / $totalOrders * 100) : 0 }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Completed</span>
                            <span class="badge bg-success">{{ $completedCount }}</span>
                        </div>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $totalOrders > 0 ? ($completedCount / $totalOrders * 100) : 0 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Cancelled</span>
                            <span class="badge bg-danger">{{ $cancelledCount }}</span>
                        </div>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $totalOrders > 0 ? ($cancelledCount / $totalOrders * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: box-shadow 0.2s ease;
}

.card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    background-color: #f8f9fa;
}

.table td {
    vertical-align: middle;
}

.btn-group .btn {
    margin: 0 2px;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.progress {
    border-radius: 3px;
}

.text-primary {
    color: #007bff !important;
}

.text-success {
    color: #28a745 !important;
}

.text-warning {
    color: #ffc107 !important;
}

.text-info {
    color: #17a2b8 !important;
}

.text-danger {
    color: #dc3545 !important;
}
</style>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // You can add JavaScript functionality here
    // For example, real-time updates, notifications, etc.
});

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
</script>
@endpush
