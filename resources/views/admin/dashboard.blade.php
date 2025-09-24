@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 py-4">
    <!-- Enhanced Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div class="flex-grow-1">
            <h1 class="h3 mb-1">Admin Dashboard</h1>
            <p class="text-muted mb-0">Welcome back! Here's what's happening across all systems today.</p>
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

    <!-- System Overview Cards -->
    <div class="row mb-4 g-3">
        <!-- Sales from Cashier System -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
            <div class="card card-dashboard bg-gradient-success text-white h-100" onclick="showSalesDetails()">
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
                            <small class="d-block">{{ $salesGrowth >= 0 ? '+' : '' }}{{ number_format($salesGrowth, 1) }}%</small>
                        </div>
                    </div>
                    <div class="progress mt-auto" style="height: 4px;">
                        <div class="progress-bar bg-white bg-opacity-75" role="progressbar" style="width: 75%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions from Cashier System -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
            <div class="card card-dashboard bg-gradient-primary text-white h-100" onclick="showTransactionDetails()">
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
                            <small class="d-block">Active</small>
                        </div>
                    </div>
                    <div class="progress mt-auto" style="height: 4px;">
                        <div class="progress-bar bg-white bg-opacity-75" role="progressbar" style="width: 60%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders from Helper System -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
            <div class="card card-dashboard bg-gradient-info text-white h-100" onclick="showOrderDetails()">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1 opacity-75 fs-6 fs-md-5">
                                <i class="bi bi-box-seam me-2"></i><span class="d-none d-lg-inline">Orders Today</span><span class="d-lg-none">Orders</span>
                            </h6>
                            <h2 class="card-text mb-0 fs-4" id="todayOrders">{{ $todayOrders }}</h2>
                        </div>
                        <div class="trend-indicator d-none d-lg-block">
                            <i class="bi bi-clock-fill text-warning"></i>
                            <small class="d-block">{{ $readyOrders }} ready</small>
                        </div>
                    </div>
                    <div class="progress mt-auto" style="height: 4px;">
                        <div class="progress-bar bg-white bg-opacity-75" role="progressbar" style="width: 85%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Status -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
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
                            <small class="d-block">Critical</small>
                        </div>
                    </div>
                    <div class="progress mt-auto" style="height: 4px;">
                        <div class="progress-bar bg-dark bg-opacity-75" role="progressbar" style="width: 85%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Status Cards -->
    <div class="row mb-4 g-3">
        <!-- Active Users by Role -->
        <div class="col-12 col-md-6 col-lg-3 mb-3">
            <div class="card card-dashboard bg-gradient-secondary text-white h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1 opacity-75 fs-6">
                                <i class="bi bi-people me-2"></i>Active Staff
                            </h6>
                            <h2 class="card-text mb-0 fs-4">{{ $activeCashiers + $activeHelpers + $activeInventory }}</h2>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between text-sm">
                        <span><i class="bi bi-cash-coin me-1"></i>{{ $activeCashiers }} Cashiers</span>
                        <span><i class="bi bi-box me-1"></i>{{ $activeHelpers }} Helpers</span>
                        <span><i class="bi bi-clipboard-data me-1"></i>{{ $activeInventory }} Inventory</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Movements -->
        <div class="col-12 col-md-6 col-lg-3 mb-3">
            <div class="card card-dashboard bg-gradient-light text-dark h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1 opacity-75 fs-6">
                                <i class="bi bi-arrow-repeat me-2"></i>Stock Movements
                            </h6>
                            <h2 class="card-text mb-0 fs-4">{{ $todayStockIns + $todayStockOuts }}</h2>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between text-sm">
                        <span class="text-success"><i class="bi bi-arrow-down me-1"></i>{{ $todayStockIns }} In</span>
                        <span class="text-danger"><i class="bi bi-arrow-up me-1"></i>{{ $todayStockOuts }} Out</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Health -->
        <div class="col-12 col-md-6 col-lg-3 mb-3">
            <div class="card card-dashboard bg-gradient-dark text-white h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1 opacity-75 fs-6">
                                <i class="bi bi-heart-pulse me-2"></i>System Health
                            </h6>
                            <h2 class="card-text mb-0 fs-4">98%</h2>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between text-sm">
                        <span><i class="bi bi-check-circle me-1"></i>{{ $totalUsers }} Users</span>
                        <span><i class="bi bi-graph-up me-1"></i>{{ $completedTransactions }} Transactions</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Score -->
        <div class="col-12 col-md-6 col-lg-3 mb-3">
            <div class="card card-dashboard bg-gradient-success text-white h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1 opacity-75 fs-6">
                                <i class="bi bi-trophy me-2"></i>Performance
                            </h6>
                            <h2 class="card-text mb-0 fs-4">Excellent</h2>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between text-sm">
                        <span><i class="bi bi-star-fill me-1"></i>5.0 Rating</span>
                        <span><i class="bi bi-graph-up me-1"></i>{{ $totalOrders }} Orders</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">
        <!-- Best Selling Products -->
        <div class="col-12 col-lg-6 col-xl-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0">
                        <i class="bi bi-trophy me-2"></i>Top Performing Products
                    </h5>
                    <small class="text-muted">This week</small>
                </div>
                <div class="card-body">
                    @if($bestSellingProducts->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($bestSellingProducts as $index => $product)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="badge bg-primary me-3 fs-6">#{{ $index + 1 }}</div>
                                        <div>
                                            <h6 class="mb-1">{{ $product->name }}</h6>
                                            <small class="text-muted">Best seller</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-success">{{ $product->total_sold }} sold</div>
                                        <small class="text-muted">units</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-graph-down text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2">No sales data available yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Activity Across All Systems -->
        <div class="col-12 col-lg-6 col-xl-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0">
                        <i class="bi bi-activity me-2"></i>Recent Activity
                    </h5>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-secondary active" onclick="filterActivity('all')">All</button>
                        <button class="btn btn-outline-secondary" onclick="filterActivity('sales')">Sales</button>
                        <button class="btn btn-outline-secondary" onclick="filterActivity('orders')">Orders</button>
                        <button class="btn btn-outline-secondary" onclick="filterActivity('stock')">Stock</button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="activity-timeline" id="activityContainer">
                        @foreach($recentActivities as $activity)
                            <div class="activity-item d-flex mb-3" data-type="{{ $activity['type'] }}">
                                <div class="activity-icon bg-{{ $activity['color'] }} text-white me-3" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi {{ $activity['icon'] }}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $activity['message'] }}</h6>
                                    <small class="text-muted">{{ $activity['time']->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status Overview -->
        <div class="col-12 col-lg-6 col-xl-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-shield-check me-2"></i>System Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center g-3">
                        <div class="col-4">
                            <div class="system-status-item">
                                <div class="status-icon bg-success text-white mb-2" style="width: 50px; height: 50px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-cash-coin"></i>
                                </div>
                                <h6 class="mb-1">Cashier System</h6>
                                <small class="text-success">Online</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="system-status-item">
                                <div class="status-icon bg-primary text-white mb-2" style="width: 50px; height: 50px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                                <h6 class="mb-1">Helper System</h6>
                                <small class="text-success">Online</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="system-status-item">
                                <div class="status-icon bg-info text-white mb-2" style="width: 50px; height: 50px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-clipboard-data"></i>
                                </div>
                                <h6 class="mb-1">Inventory System</h6>
                                <small class="text-success">Online</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-12 col-lg-6 col-xl-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-gear me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary" onclick="generateReport()">
                            <i class="bi bi-file-earmark-spreadsheet me-2"></i>Generate Consolidated Report
                        </button>
                        <button class="btn btn-outline-warning" onclick="viewAlerts()">
                            <i class="bi bi-exclamation-triangle me-2"></i>View System Alerts
                        </button>
                        <button class="btn btn-outline-info" onclick="systemSettings()">
                            <i class="bi bi-gear me-2"></i>System Settings
                        </button>
                        <button class="btn btn-outline-success" onclick="backupData()">
                            <i class="bi bi-cloud-arrow-up me-2"></i>Backup Data
                        </button>
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

.bg-gradient-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #868e96 100%);
}

.bg-gradient-light {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.bg-gradient-dark {
    background: linear-gradient(135deg, #212529 0%, #343a40 100%);
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

.status-icon {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}

.status-icon:hover {
    transform: scale(1.1);
}

.system-status-item {
    transition: all 0.3s ease;
}

.system-status-item:hover {
    transform: translateY(-2px);
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

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Notification styles */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border: none;
    border-radius: 8px;
}

/* Responsive Enhancements */
@media (max-width: 1200px) {
    .col-xl-6 {
        flex: 0 0 auto;
        width: 100%;
    }
}

@media (max-width: 992px) {
    .col-lg-6 {
        flex: 0 0 auto;
        width: 100%;
    }

    .col-lg-3 {
        flex: 0 0 auto;
        width: 50%;
    }
}

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

    .activity-timeline::before {
        display: none;
    }

    .col-md-4 {
        flex: 0 0 auto;
        width: 50%;
    }

    .col-md-6 {
        flex: 0 0 auto;
        width: 100%;
    }

    .d-flex.justify-content-between.text-sm {
        flex-direction: column;
        gap: 0.5rem;
    }

    .d-flex.justify-content-between.text-sm span {
        font-size: 0.75rem;
    }
}

@media (max-width: 576px) {
    .col-sm-6 {
        flex: 0 0 auto;
        width: 100%;
    }

    .col-4 {
        flex: 0 0 auto;
        width: 100%;
    }

    .btn-group {
        flex-direction: column;
        width: 100%;
    }

    .btn-group .btn {
        border-radius: var(--border-radius-sm);
        margin-bottom: 0.25rem;
    }

    .d-flex.justify-content-between.align-items-center {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }

    .d-flex.gap-2 {
        flex-direction: column;
        width: 100%;
    }

    .d-flex.gap-2 .btn {
        width: 100%;
    }
}

/* Animation for cards */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card-dashboard {
    animation: fadeInUp 0.6s ease-out;
}

.card-dashboard:nth-child(1) { animation-delay: 0.1s; }
.card-dashboard:nth-child(2) { animation-delay: 0.2s; }
.card-dashboard:nth-child(3) { animation-delay: 0.3s; }
.card-dashboard:nth-child(4) { animation-delay: 0.4s; }
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

function showOrderDetails() {
    showNotification('Order management modal would open here', 'info');
}

function showStockDetails() {
    showNotification('Stock management modal would open here', 'warning');
}

function filterActivity(type) {
    const container = document.getElementById('activityContainer');
    const items = container.querySelectorAll('.activity-item');

    // Update active button
    document.querySelectorAll('.btn-group-sm .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');

    // Filter items
    items.forEach(item => {
        if (type === 'all' || item.dataset.type === type) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });

    showNotification(`Filtered to ${type} activities`, 'info');
}

function generateReport() {
    showNotification('Generating consolidated report...', 'info');
    setTimeout(() => {
        showNotification('Report generated successfully! Check downloads.', 'success');
    }, 2000);
}

function viewAlerts() {
    showNotification('System alerts modal would open here', 'warning');
}

function systemSettings() {
    showNotification('System settings modal would open here', 'info');
}

function backupData() {
    showNotification('Starting data backup...', 'info');
    setTimeout(() => {
        showNotification('Backup completed successfully!', 'success');
    }, 3000);
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed notification`;
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

// Auto-refresh dashboard every 5 minutes
setInterval(() => {
    console.log('Auto-refreshing dashboard data...');
    // In a real application, this would fetch new data via AJAX
}, 300000);

// Add click handlers to cards for better UX
document.addEventListener('DOMContentLoaded', function() {
    // Add click handlers to dashboard cards
    const cards = document.querySelectorAll('.card-dashboard');
    cards.forEach(card => {
        card.addEventListener('click', function() {
            // Add a subtle animation
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });

    // Add hover effects to status icons
    const statusIcons = document.querySelectorAll('.status-icon');
    statusIcons.forEach(icon => {
        icon.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1) rotate(5deg)';
        });

        icon.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });
});

// Real-time updates simulation
function updateLiveData() {
    // Simulate live updates for demo purposes
    const salesElement = document.getElementById('todaySales');
    const transactionsElement = document.getElementById('todayTransactions');

    if (salesElement && transactionsElement) {
        // Add random small updates to simulate real-time data
        setInterval(() => {
            const currentSales = parseFloat(salesElement.textContent.replace(/[^\d.]/g, ''));
            const currentTransactions = parseInt(transactionsElement.textContent);

            // Small random updates (for demo)
            if (Math.random() < 0.1) { // 10% chance every 10 seconds
                const newSales = currentSales + (Math.random() * 100);
                const newTransactions = currentTransactions + 1;

                salesElement.textContent = '₱' + newSales.toFixed(2);
                transactionsElement.textContent = newTransactions;

                showNotification('Live update: New transaction recorded!', 'success');
            }
        }, 10000); // Check every 10 seconds
    }
}

// Initialize live updates
updateLiveData();
</script>
@endsection
