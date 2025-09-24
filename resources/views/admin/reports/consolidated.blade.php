@extends('layouts.app')

@section('content')
<div class="container-fluid" style="max-width: calc(100vw - 300px); margin-left: 280px; padding: 20px;">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div class="flex-grow-1">
            <h1 class="h3 mb-1">Consolidated Report</h1>
            <p class="text-muted mb-0">Comprehensive overview of all system activities and performance metrics.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-outline-primary" onclick="exportReport()">
                <i class="bi bi-download me-1"></i>Export Report
            </button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.reports.consolidated') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Financial Summary -->
    <div class="row mb-4 g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>Financial Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-success mb-1">₱{{ number_format($totalSales, 2) }}</h3>
                                <p class="text-muted mb-0">Total Sales</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-primary mb-1">{{ $totalTransactions }}</h3>
                                <p class="text-muted mb-0">Total Transactions</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-info mb-1">{{ $totalOrders }}</h3>
                                <p class="text-muted mb-0">Total Orders</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-warning mb-1">{{ $activeUsers }}</h3>
                                <p class="text-muted mb-0">Active Users</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Movements -->
    <div class="row mb-4 g-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-arrow-repeat me-2"></i>Stock Movements
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <h4 class="text-success mb-1">₱{{ number_format($stockIns, 2) }}</h4>
                                <p class="text-muted mb-0">Stock In Value</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <h4 class="text-danger mb-1">₱{{ number_format($stockOuts, 2) }}</h4>
                                <p class="text-muted mb-0">Stock Out Value</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-check me-2"></i>Period Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center">
                                <h6 class="mb-1">Start Date</h6>
                                <p class="text-primary mb-0">{{ $startDate->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h6 class="mb-1">End Date</h6>
                                <p class="text-primary mb-0">{{ $endDate->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Breakdown -->
    <div class="row mb-4 g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-bar-chart me-2"></i>Daily Sales Breakdown
                    </h5>
                </div>
                <div class="card-body">
                    @if($dailyBreakdown->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Sales Amount</th>
                                        <th>Transactions</th>
                                        <th>Avg per Transaction</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dailyBreakdown as $day)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($day->date)->format('M d, Y') }}</td>
                                            <td class="text-success fw-bold">₱{{ number_format($day->sales, 2) }}</td>
                                            <td>{{ $day->transactions }}</td>
                                            <td>₱{{ number_format($day->sales / $day->transactions, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-graph-down text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2">No sales data available for the selected period.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- System Performance Metrics -->
    <div class="row mb-4 g-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-speedometer2 me-2"></i>Performance Metrics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                                <h5 class="text-success mb-1">{{ $totalTransactions > 0 ? round(($totalSales / $totalTransactions), 2) : 0 }}</h5>
                                <p class="text-muted mb-0">Avg Transaction Value</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                                <h5 class="text-info mb-1">{{ $dailyBreakdown->count() > 0 ? round($totalTransactions / $dailyBreakdown->count(), 1) : 0 }}</h5>
                                <p class="text-muted mb-0">Avg Daily Transactions</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-trophy me-2"></i>Key Achievements
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Total Revenue Generated</span>
                            <span class="badge bg-success">₱{{ number_format($totalSales, 2) }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Orders Processed</span>
                            <span class="badge bg-primary">{{ $totalOrders }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Active System Users</span>
                            <span class="badge bg-info">{{ $activeUsers }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Stock Value Managed</span>
                            <span class="badge bg-warning">₱{{ number_format($stockIns + $stockOuts, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportReport() {
    // In a real application, this would generate and download a PDF/Excel report
    showNotification('Report export feature would be implemented here', 'info');
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(notification);

    // Auto remove after 4 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 4000);
}
</script>
@endsection
