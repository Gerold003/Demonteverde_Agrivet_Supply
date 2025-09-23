@extends('layouts.app')

@section('content')
<div class="container-fluid" style="max-width: calc(100vw - 300px); margin-left: 280px; padding: 20px;">
    <!-- Enhanced Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div class="flex-grow-1">
            <h1 class="h3 mb-1">Admin Dashboard</h1>
            <p class="text-muted mb-0">Welcome back! Here's what's happening today.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-outline-primary" onclick="refreshDashboard()" title="Refresh Data">
                <i class="bi bi-arrow-clockwise"></i> <span class="d-none d-sm-inline">Refresh</span>
            </button>
            <div class="btn-group flex-wrap">
                <a href="{{ route('admin.reports.daily') }}" class="btn btn-primary">
                    <i class="bi bi-file-earmark-text me-1"></i><span class="d-none d-md-inline">Daily Reports</span><span class="d-md-none">Daily</span>
                </a>
                <a href="{{ route('admin.reports.weekly') }}" class="btn btn-outline-primary">
                    <i class="bi bi-calendar-week me-1"></i><span class="d-none d-md-inline">Weekly Reports</span><span class="d-md-none">Weekly</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Enhanced Dashboard Cards -->
    <div class="row mb-4 g-3">
        <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 mb-3">
            <div class="card card-dashboard bg-gradient-primary text-white h-100" onclick="showSalesDetails()">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1 opacity-75 fs-6 fs-md-5">
                                <i class="bi bi-currency-dollar me-2"></i><span class="d-none d-lg-inline">Total Sales Today</span><span class="d-lg-none">Sales Today</span>
                            </h6>
                            <h2 class="card-text mb-0 fs-4" id="todaySales">₱{{ number_format($todaySales, 2) }}</h2>
                        </div>
                        <div class="trend-indicator d-none d-lg-block">
                            <i class="bi bi-arrow-up-circle-fill text-success"></i>
                            <small class="d-block"></small>
                        </div>
                    </div>
                    <div class="progress mt-auto" style="height: 4px;">
                        <div class="progress-bar bg-white bg-opacity-75" role="progressbar" style="width: 75%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 mb-3">
            <div class="card card-dashboard bg-gradient-success text-white h-100" onclick="showTransactionDetails()">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1 opacity-75 fs-6 fs-md-5">
                                <i class="bi bi-receipt me-2"></i><span class="d-none d-lg-inline">Transactions Today</span><span class="d-lg-none">Transactions</span>
                            </h6>
                            <h2 class="card-text mb-0 fs-4" id="todayTransactions">{{ $todayTransactions }}</h2>
                        </div>
                        <div class="trend-indicator d-none d-lg-block">
                            <i class="bi bi-arrow-up-circle-fill text-success"></i>
                            <small class="d-block"></small>
                        </div>
                    </div>
                    <div class="progress mt-auto" style="height: 4px;">
                        <div class="progress-bar bg-white bg-opacity-75" role="progressbar" style="width: 60%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 mb-3">
            <div class="card card-dashboard bg-gradient-warning text-dark h-100" onclick="showStockDetails()">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1 opacity-75 fs-6 fs-md-5">
                                <i class="bi bi-exclamation-triangle me-2"></i><span class="d-none d-lg-inline">Low Stock Items</span><span class="d-lg-none">Low Stock</span>
                            </h6>
                            <h2 class="card-text mb-0 fs-4" id="lowStockCount">{{ $lowStockCount }}</h2>
                        </div>
                        <div class="trend-indicator d-none d-lg-block">
                            <i class="bi bi-dash-circle-fill text-danger"></i>
                            <small class="d-block"></small>
                        </div>
                    </div>
                    <div class="progress mt-auto" style="height: 4px;">
                        <div class="progress-bar bg-dark bg-opacity-75" role="progressbar" style="width: 85%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 mb-3">
            <div class="card card-dashboard bg-gradient-info text-white h-100" onclick="showCashierDetails()">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1 opacity-75 fs-6 fs-md-5">
                                <i class="bi bi-people me-2"></i><span class="d-none d-lg-inline">Active Cashiers</span><span class="d-lg-none">Cashiers</span>
                            </h6>
                            <h2 class="card-text mb-0 fs-4" id="activeCashiers">{{ $activeCashiers }}</h2>
                        </div>
                        <div class="trend-indicator d-none d-lg-block">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <small class="d-block">All Active</small>
                        </div>
                    </div>
                    <div class="progress mt-auto" style="height: 4px;">
                        <div class="progress-bar bg-white bg-opacity-75" role="progressbar" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Overview -->
    <div class="row mb-4 g-4">
        <div class="col-12 col-lg-8 col-xl-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0">
                        <i class="bi bi-bar-chart-line me-2"></i>Quick Stats Overview
                    </h5>
                    <div class="btn-group btn-group-sm flex-wrap">
                        <button class="btn btn-outline-secondary active" onclick="setPeriod('today')">Today</button>
                        <button class="btn btn-outline-secondary" onclick="setPeriod('week')">This Week</button>
                        <button class="btn btn-outline-secondary" onclick="setPeriod('month')">This Month</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row text-center g-3">
                        <div class="col-6 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                            <div class="stat-item p-2">
                                <h4 class="text-primary mb-1 fs-5">₱24,580</h4>
                                <small class="text-muted d-block">Total Revenue</small>
                                <div class="mini-chart bg-primary mt-2" style="height: 30px; border-radius: 15px;"></div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                            <div class="stat-item p-2">
                                <h4 class="text-success mb-1 fs-5">156</h4>
                                <small class="text-muted d-block">Items Sold</small>
                                <div class="mini-chart bg-success mt-2" style="height: 30px; border-radius: 15px;"></div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                            <div class="stat-item p-2">
                                <h4 class="text-warning mb-1 fs-5">23</h4>
                                <small class="text-muted d-block">Pending Orders</small>
                                <div class="mini-chart bg-warning mt-2" style="height: 30px; border-radius: 15px;"></div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                            <div class="stat-item p-2">
                                <h4 class="text-info mb-1 fs-5">94%</h4>
                                <small class="text-muted d-block">Customer Satisfaction</small>
                                <div class="mini-chart bg-info mt-2" style="height: 30px; border-radius: 15px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-xl-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>Performance
                    </h5>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="text-center">
                        <div class="performance-circle mx-auto mb-3" style="width: 120px; height: 120px; border-radius: 50%; background: conic-gradient(#198754 0deg 270deg, #e9ecef 270deg 360deg); display: flex; align-items: center; justify-content: center; position: relative;">
                            <div style="width: 80px; height: 80px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #198754;">
                                85%
                            </div>
                        </div>
                        <h6 class="mb-1">Overall Performance</h6>
                        <p class="text-muted small mb-0">+5% from last month</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-12 col-lg-8 col-xl-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i>Recent Transactions
                    </h5>
                    <div class="d-flex gap-2 flex-wrap align-items-center">
                        <div class="input-group input-group-sm" style="min-width: 200px;">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" placeholder="Search transactions..." onkeyup="filterTransactions(this.value)">
                        </div>
                        <button class="btn btn-sm btn-outline-primary" onclick="exportTransactions()">
                            <i class="bi bi-download me-1"></i><span class="d-none d-sm-inline">Export</span>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentTransactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="transactionsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Cashier</th>
                                        <th>Amount</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTransactions as $transaction)
                                        <tr class="transaction-row">
                                            <td>
                                                <span class="badge bg-primary">#{{ $transaction->id }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-success text-white me-2" style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                                        {{ substr($transaction->cashier->name, 0, 1) }}
                                                    </div>
                                                    {{ $transaction->cashier->name }}
                                                </div>
                                            </td>
                                            <td class="fw-bold text-success">₱{{ number_format($transaction->total_amount, 2) }}</td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    <i class="bi bi-clock me-1"></i>{{ $transaction->created_at->format('h:i A') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Completed
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-receipt text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">No recent transactions.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12 col-md-12 col-lg-4 col-xl-4 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Low Stock Alert
                    </h5>
                    <div class="d-flex gap-2 flex-wrap">
                        <div class="input-group input-group-sm" style="min-width: 200px;">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" placeholder="Search products..." onkeyup="filterProducts(this.value)">
                        </div>
                        <button class="btn btn-sm btn-outline-warning" onclick="reorderStock()">
                            <i class="bi bi-cart-plus me-1"></i><span class="d-none d-sm-inline">Reorder</span>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($lowStockProducts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="productsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Current Stock</th>
                                        <th>Critical Level</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lowStockProducts as $product)
                                        <tr class="product-row">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-warning text-dark me-2" style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                                        {{ substr($product->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $product->name }}</div>
                                                        <small class="text-muted">SKU: {{ $product->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress me-2" style="width: 60px; height: 6px;">
                                                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ min(100, ($product->current_stock_sack / $product->critical_level_sack) * 100) }}%"></div>
                                                    </div>
                                                    <span class="fw-bold">{{ $product->current_stock_sack }} sacks</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $product->critical_level_sack }} sacks</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-exclamation-triangle me-1"></i>Critical
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">All products are well stocked!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Feed -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-activity me-2"></i>Recent Activity
                    </h5>
                </div>
                <div class="card-body">
                    <div class="activity-timeline">
                        <div class="activity-item d-flex mb-3">
                            <div class="activity-icon bg-primary text-white me-3" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-cash"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">New Sale Recorded</h6>
                                <p class="text-muted mb-1"></p>
                                <small class="text-muted"></small>
                            </div>
                        </div>

                        <div class="activity-item d-flex mb-3">
                            <div class="activity-icon bg-warning text-dark me-3" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Low Stock Alert</h6>
                                <p class="text-muted mb-1"></p>
                                <small class="text-muted"></small>
                            </div>
                        </div>

                        <div class="activity-item d-flex mb-3">
                            <div class="activity-icon bg-success text-white me-3" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-truck"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">New Stock Received</h6>
                                <p class="text-muted mb-1"></p>
                                <small class="text-muted"></small>
                            </div>
                        </div>

                        <div class="activity-item d-flex">
                            <div class="activity-icon bg-info text-white me-3" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-person-check"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Cashier Login</h6>
                                <p class="text-muted mb-1"></p>
                                <small class="text-muted"></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Card Styles */
.bg-gradient-primary {
    background: linear-gradient(135deg, #6f42c1 0%, #8b5fbf 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #198754 0%, #20c997 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #fd7e14 0%, #ff922b 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #0dcaf0 0%, #31d2f2 100%);
}

.card-dashboard {
    border: none;
    border-radius: var(--border-radius);
    transition: var(--transition);
    cursor: pointer;
    box-shadow: var(--shadow);
}

.card-dashboard:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
}

.trend-indicator {
    text-align: center;
    opacity: 0.8;
}

.trend-indicator i {
    font-size: 1.5rem;
    display: block;
    margin-bottom: -5px;
}

.stat-item {
    padding: 1rem;
}

.mini-chart {
    background: linear-gradient(90deg, currentColor 0%, transparent 100%);
    opacity: 0.3;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 0.6; }
}

.activity-icon {
    flex-shrink: 0;
    box-shadow: var(--shadow-sm);
}

.activity-timeline {
    position: relative;
}

.activity-timeline::before {
    content: '';
    position: absolute;
    left: 19px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, var(--primary-color), transparent);
}

.avatar-circle {
    font-weight: 600;
}

/* Enhanced Table Styles */
.table th {
    border-top: none;
    font-weight: 600;
    color: var(--gray-700);
    background-color: var(--gray-100);
}

.table td {
    vertical-align: middle;
    border-color: var(--gray-200);
}

/* Responsive Enhancements */
@media (max-width: 768px) {
    .card-dashboard {
        margin-bottom: 1rem;
    }

    .trend-indicator {
        display: none;
    }

    .progress {
        margin-top: 0.5rem !important;
    }
}

/* Loading Animation */
.loading-spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255,255,255,0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>

<script>
function refreshDashboard() {
    const btn = event.target.closest('button');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="loading-spinner me-2"></span>Refreshing...';
    btn.disabled = true;

    // Simulate refresh
    setTimeout(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;

        // Show success message
        showNotification('Dashboard refreshed successfully!', 'success');
    }, 2000);
}

function showSalesDetails() {
    showNotification('Sales details modal would open here', 'info');
}

function showTransactionDetails() {
    showNotification('Transaction details modal would open here', 'info');
}

function showStockDetails() {
    showNotification('Stock management modal would open here', 'warning');
}

function showCashierDetails() {
    showNotification('Cashier management modal would open here', 'info');
}

function setPeriod(period) {
    // Update active button
    document.querySelectorAll('.btn-group-sm .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');

    showNotification(`Switched to ${period} view`, 'info');
}

function filterTransactions(query) {
    const rows = document.querySelectorAll('.transaction-row');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query.toLowerCase()) ? '' : 'none';
    });
}

function filterProducts(query) {
    const rows = document.querySelectorAll('.product-row');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query.toLowerCase()) ? '' : 'none';
    });
}

function exportTransactions() {
    showNotification('Exporting transactions...', 'info');
    setTimeout(() => {
        showNotification('Transactions exported successfully!', 'success');
    }, 1500);
}

function reorderStock() {
    showNotification('Redirecting to reorder page...', 'info');
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(notification);

    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Auto-refresh dashboard every 5 minutes
setInterval(() => {
    console.log('Auto-refreshing dashboard data...');
    // In a real application, this would fetch new data via AJAX
}, 300000);
</script>
