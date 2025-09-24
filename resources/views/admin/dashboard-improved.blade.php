@extends('layouts.app')

@section('content')
<div class="admin-dashboard-container">
    <!-- Enhanced Header Section -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-text">
                <h1 class="dashboard-title">Admin Dashboard</h1>
                <p class="dashboard-subtitle">Welcome back! Here's what's happening across all systems today.</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-refresh" onclick="refreshDashboard()" title="Refresh Data">
                    <i class="bi bi-arrow-clockwise"></i>
                    <span>Refresh</span>
                </button>
                <div class="btn-group">
                    <a href="{{ route('admin.reports.daily') }}" class="btn btn-primary">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Daily Reports</span>
                    </a>
                    <a href="{{ route('admin.reports.weekly') }}" class="btn btn-outline-primary">
                        <i class="bi bi-calendar-week"></i>
                        <span>Weekly Reports</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- System Overview Cards -->
    <div class="metrics-grid">
        <!-- Sales from Cashier System -->
        <div class="metric-card sales-card">
            <div class="card-content" onclick="showSalesDetails()">
                <div class="card-header">
                    <div class="card-title">
                        <i class="bi bi-currency-dollar"></i>
                        <span>Total Sales Today</span>
                    </div>
                    <div class="trend-indicator">
                        <i class="bi bi-arrow-up-circle-fill"></i>
                        <span>{{ $salesGrowth >= 0 ? '+' : '' }}{{ number_format($salesGrowth, 1) }}%</span>
                    </div>
                </div>
                <div class="card-value">
                    <span class="value">₱{{ number_format($todaySales, 2) }}</span>
                </div>
                <div class="card-progress">
                    <div class="progress-bar"></div>
                </div>
            </div>
        </div>

        <!-- Transactions from Cashier System -->
        <div class="metric-card transactions-card">
            <div class="card-content" onclick="showTransactionDetails()">
                <div class="card-header">
                    <div class="card-title">
                        <i class="bi bi-receipt"></i>
                        <span>Transactions Today</span>
                    </div>
                    <div class="trend-indicator">
                        <i class="bi bi-arrow-up-circle-fill"></i>
                        <span>Active</span>
                    </div>
                </div>
                <div class="card-value">
                    <span class="value">{{ $todayTransactions }}</span>
                </div>
                <div class="card-progress">
                    <div class="progress-bar"></div>
                </div>
            </div>
        </div>

        <!-- Orders from Helper System -->
        <div class="metric-card orders-card">
            <div class="card-content" onclick="showOrderDetails()">
                <div class="card-header">
                    <div class="card-title">
                        <i class="bi bi-box-seam"></i>
                        <span>Orders Today</span>
                    </div>
                    <div class="trend-indicator">
                        <i class="bi bi-clock-fill"></i>
                        <span>{{ $readyOrders }} ready</span>
                    </div>
                </div>
                <div class="card-value">
                    <span class="value">{{ $todayOrders }}</span>
                </div>
                <div class="card-progress">
                    <div class="progress-bar"></div>
                </div>
            </div>
        </div>

        <!-- Inventory Status -->
        <div class="metric-card inventory-card">
            <div class="card-content" onclick="showStockDetails()">
                <div class="card-header">
                    <div class="card-title">
                        <i class="bi bi-exclamation-triangle"></i>
                        <span>Low Stock Items</span>
                    </div>
                    <div class="trend-indicator">
                        <i class="bi bi-dash-circle-fill"></i>
                        <span>Critical</span>
                    </div>
                </div>
                <div class="card-value">
                    <span class="value">{{ $lowStockCount }}</span>
                </div>
                <div class="card-progress">
                    <div class="progress-bar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Status Cards -->
    <div class="status-grid">
        <!-- Active Users by Role -->
        <div class="status-card staff-card">
            <div class="card-content">
                <div class="card-header">
                    <div class="card-title">
                        <i class="bi bi-people"></i>
                        <span>Active Staff</span>
                    </div>
                </div>
                <div class="card-value">
                    <span class="value">{{ $activeCashiers + $activeHelpers + $activeInventory }}</span>
                </div>
                <div class="card-details">
                    <div class="detail-item">
                        <i class="bi bi-cash-coin"></i>
                        <span>{{ $activeCashiers }} Cashiers</span>
                    </div>
                    <div class="detail-item">
                        <i class="bi bi-box"></i>
                        <span>{{ $activeHelpers }} Helpers</span>
                    </div>
                    <div class="detail-item">
                        <i class="bi bi-clipboard-data"></i>
                        <span>{{ $activeInventory }} Inventory</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Movements -->
        <div class="status-card stock-card">
            <div class="card-content">
                <div class="card-header">
                    <div class="card-title">
                        <i class="bi bi-arrow-repeat"></i>
                        <span>Stock Movements</span>
                    </div>
                </div>
                <div class="card-value">
                    <span class="value">{{ $todayStockIns + $todayStockOuts }}</span>
                </div>
                <div class="card-details">
                    <div class="detail-item success">
                        <i class="bi bi-arrow-down"></i>
                        <span>{{ $todayStockIns }} In</span>
                    </div>
                    <div class="detail-item danger">
                        <i class="bi bi-arrow-up"></i>
                        <span>{{ $todayStockOuts }} Out</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Health -->
        <div class="status-card health-card">
            <div class="card-content">
                <div class="card-header">
                    <div class="card-title">
                        <i class="bi bi-heart-pulse"></i>
                        <span>System Health</span>
                    </div>
                </div>
                <div class="card-value">
                    <span class="value">98%</span>
                </div>
                <div class="card-details">
                    <div class="detail-item">
                        <i class="bi bi-check-circle"></i>
                        <span>{{ $totalUsers }} Users</span>
                    </div>
                    <div class="detail-item">
                        <i class="bi bi-graph-up"></i>
                        <span>{{ $completedTransactions }} Transactions</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Score -->
        <div class="status-card performance-card">
            <div class="card-content">
                <div class="card-header">
                    <div class="card-title">
                        <i class="bi bi-trophy"></i>
                        <span>Performance</span>
                    </div>
                </div>
                <div class="card-value">
                    <span class="value">Excellent</span>
                </div>
                <div class="card-details">
                    <div class="detail-item">
                        <i class="bi bi-star-fill"></i>
                        <span>5.0 Rating</span>
                    </div>
                    <div class="detail-item">
                        <i class="bi bi-graph-up"></i>
                        <span>{{ $totalOrders }} Orders</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Best Selling Products -->
        <div class="content-card products-card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="bi bi-trophy"></i>
                    Top Performing Products
                </h5>
                <span class="card-subtitle">This week</span>
            </div>
            <div class="card-body">
                @if($bestSellingProducts->count() > 0)
                    <div class="products-list">
                        @foreach($bestSellingProducts as $index => $product)
                            <div class="product-item">
                                <div class="product-rank">#{{ $index + 1 }}</div>
                                <div class="product-info">
                                    <h6 class="product-name">{{ $product->name }}</h6>
                                    <span class="product-label">Best seller</span>
                                </div>
                                <div class="product-stats">
                                    <div class="product-sold">{{ $product->total_sold }} sold</div>
                                    <span class="product-unit">units</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="bi bi-graph-down"></i>
                        <p>No sales data available yet.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Activity Across All Systems -->
        <div class="content-card activity-card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="bi bi-activity"></i>
                    Recent Activity
                </h5>
                <div class="activity-filters">
                    <button class="filter-btn active" onclick="filterActivity('all')">All</button>
                    <button class="filter-btn" onclick="filterActivity('sales')">Sales</button>
                    <button class="filter-btn" onclick="filterActivity('orders')">Orders</button>
                    <button class="filter-btn" onclick="filterActivity('stock')">Stock</button>
                </div>
            </div>
            <div class="card-body">
                <div class="activity-timeline" id="activityContainer">
                    @foreach($recentActivities as $activity)
                        <div class="activity-item" data-type="{{ $activity['type'] }}">
                            <div class="activity-icon">
                                <i class="bi bi-{{ $activity['icon'] }}"></i>
                            </div>
                            <div class="activity-content">
                                <h6 class="activity-message">{{ $activity['message'] }}</h6>
                                <span class="activity-time">{{ $activity['time']->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- System Status Overview -->
        <div class="content-card status-overview-card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="bi bi-shield-check"></i>
                    System Status
                </h5>
            </div>
            <div class="card-body">
                <div class="system-status-grid">
                    <div class="system-item">
                        <div class="system-icon cashier-icon">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <h6 class="system-name">Cashier System</h6>
                        <span class="system-status online">Online</span>
                    </div>
                    <div class="system-item">
                        <div class="system-icon helper-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h6 class="system-name">Helper System</h6>
                        <span class="system-status online">Online</span>
                    </div>
                    <div class="system-item">
                        <div class="system-icon inventory-icon">
                            <i class="bi bi-clipboard-data"></i>
                        </div>
                        <h6 class="system-name">Inventory System</h6>
                        <span class="system-status online">Online</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="content-card actions-card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="bi bi-gear"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="actions-grid">
                    <button class="action-btn primary" onclick="generateReport()">
                        <i class="bi bi-file-earmark-spreadsheet"></i>
                        <span>Generate Consolidated Report</span>
                    </button>
                    <button class="action-btn warning" onclick="viewAlerts()">
                        <i class="bi bi-exclamation-triangle"></i>
                        <span>View System Alerts</span>
                    </button>
                    <button class="action-btn info" onclick="systemSettings()">
                        <i class="bi bi-gear"></i>
                        <span>System Settings</span>
                    </button>
                    <button class="action-btn success" onclick="backupData()">
                        <i class="bi bi-cloud-arrow-up"></i>
                        <span>Backup Data</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
