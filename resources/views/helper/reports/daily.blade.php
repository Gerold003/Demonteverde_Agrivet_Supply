@extends('layouts.app')

@section('title', 'Daily Report')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Daily Report</h1>
        <div class="btn-group">
            <button type="button" class="btn btn-outline-info" onclick="exportReport()">
                <i class="fas fa-download me-2"></i>Export Report
            </button>
            <a href="{{ route('helper.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Date Selection -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <label class="form-label">Select Date</label>
                            <input type="date" id="reportDate" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Quick Select</label>
                            <div class="btn-group w-100">
                                <button type="button" class="btn btn-outline-secondary" onclick="setDate('today')">Today</button>
                                <button type="button" class="btn btn-outline-secondary" onclick="setDate('yesterday')">Yesterday</button>
                                <button type="button" class="btn btn-outline-secondary" onclick="setDate('week')">This Week</button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-primary w-100" onclick="generateReport()">
                                <i class="fas fa-sync-alt me-2"></i>Generate Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Summary -->
    <div class="row mb-4" id="reportSummary">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title mb-0" id="totalOrders">0</h5>
                            <small>Total Orders</small>
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
                            <h5 class="card-title mb-0" id="totalItems">0</h5>
                            <small>Total Items</small>
                        </div>
                        <i class="fas fa-boxes fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title mb-0" id="totalQuantity">0</h5>
                            <small>Total Quantity</small>
                        </div>
                        <i class="fas fa-weight fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title mb-0" id="avgItemsPerOrder">0</h5>
                            <small>Avg Items/Order</small>
                        </div>
                        <i class="fas fa-chart-line fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Orders List -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Orders Prepared</h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleView('table')">
                            <i class="fas fa-table"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleView('cards')">
                            <i class="fas fa-th"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="ordersTableView">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Status</th>
                                        <th>Items</th>
                                        <th>Prepared At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="ordersTableBody">
                                    <!-- Orders will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="ordersCardsView" style="display: none;">
                        <div class="row" id="ordersCardsContainer">
                            <!-- Order cards will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="col-lg-4">
            <!-- Performance Chart -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Performance Metrics</h5>
                </div>
                <div class="card-body">
                    <canvas id="performanceChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Top Products -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Most Prepared Products</h5>
                </div>
                <div class="card-body">
                    <div id="topProductsList">
                        <div class="text-center py-3">
                            <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                            <p class="text-muted mt-2">Loading...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Time Distribution -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Activity Timeline</h5>
                </div>
                <div class="card-body">
                    <div id="activityTimeline">
                        <div class="text-center py-3">
                            <i class="fas fa-clock fa-2x text-muted mb-2"></i>
                            <p class="text-muted">No activity data available</p>
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

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.btn-group .btn {
    margin: 0 2px;
}

.order-card {
    transition: transform 0.2s ease;
}

.order-card:hover {
    transform: translateY(-2px);
}

.performance-metric {
    text-align: center;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 10px;
}

.performance-metric.success {
    background-color: rgba(40, 167, 69, 0.1);
    border-left: 4px solid #28a745;
}

.performance-metric.warning {
    background-color: rgba(255, 193, 7, 0.1);
    border-left: 4px solid #ffc107;
}

.performance-metric.info {
    background-color: rgba(23, 162, 184, 0.1);
    border-left: 4px solid #17a2b8;
}
</style>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let currentView = 'table';
let reportData = null;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize date picker
    document.getElementById('reportDate').addEventListener('change', generateReport);

    // Generate initial report
    generateReport();
});

function setDate(period) {
    const dateInput = document.getElementById('reportDate');
    const today = new Date();

    switch(period) {
        case 'today':
            dateInput.value = today.toISOString().split('T')[0];
            break;
        case 'yesterday':
            const yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);
            dateInput.value = yesterday.toISOString().split('T')[0];
            break;
        case 'week':
            const weekStart = new Date(today);
            weekStart.setDate(weekStart.getDate() - 7);
            dateInput.value = weekStart.toISOString().split('T')[0];
            break;
    }

    generateReport();
}

function generateReport() {
    const date = document.getElementById('reportDate').value;
    const generateBtn = event.target;

    // Show loading state
    generateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generating...';
    generateBtn.disabled = true;

    // Simulate API call (in real app, this would be an AJAX request)
    setTimeout(() => {
        // Mock data - in real app, this would come from the server
        reportData = generateMockReportData(date);

        // Update UI
        updateReportSummary(reportData);
        updateOrdersList(reportData);
        updatePerformanceChart(reportData);
        updateTopProducts(reportData);

        // Reset button
        generateBtn.innerHTML = '<i class="fas fa-sync-alt me-2"></i>Generate Report';
        generateBtn.disabled = false;
    }, 1000);
}

function generateMockReportData(date) {
    // Mock data for demonstration
    return {
        date: date,
        totalOrders: Math.floor(Math.random() * 20) + 5,
        totalItems: Math.floor(Math.random() * 100) + 20,
        totalAmount: Math.floor(Math.random() * 50000) + 10000,
        avgOrderValue: 0,
        orders: [
            {
                id: 1,
                status: 'completed',
                items: Math.floor(Math.random() * 10) + 1,
                amount: Math.floor(Math.random() * 5000) + 500,
                preparedAt: '2024-01-15 09:30:00'
            },
            {
                id: 2,
                status: 'ready_for_pickup',
                items: Math.floor(Math.random() * 10) + 1,
                amount: Math.floor(Math.random() * 5000) + 500,
                preparedAt: '2024-01-15 10:15:00'
            },
            // Add more mock orders...
        ]
    };
}

function updateReportSummary(data) {
    document.getElementById('totalOrders').textContent = data.totalOrders;
    document.getElementById('totalItems').textContent = data.totalItems;
    document.getElementById('totalQuantity').textContent = data.totalItems; // Using totalItems as quantity for now
    document.getElementById('avgItemsPerOrder').textContent = (data.totalItems / data.totalOrders).toFixed(1);
}

function updateOrdersList(data) {
    const tableBody = document.getElementById('ordersTableBody');
    const cardsContainer = document.getElementById('ordersCardsContainer');

    // Clear existing content
    tableBody.innerHTML = '';
    cardsContainer.innerHTML = '';

    data.orders.forEach(order => {
        // Table row
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><strong>#${order.id}</strong></td>
            <td><span class="badge bg-${order.status === 'completed' ? 'success' : 'info'}">${order.status.replace('_', ' ')}</span></td>
            <td>${order.items} items</td>
            <td>${new Date(order.preparedAt).toLocaleString()}</td>
            <td>
                <a href="/helper/orders/${order.id}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye"></i>
                </a>
            </td>
        `;
        tableBody.appendChild(row);

        // Card view
        const card = document.createElement('div');
        card.className = 'col-md-6 mb-3';
        card.innerHTML = `
            <div class="card order-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="card-title mb-0">Order #${order.id}</h6>
                        <span class="badge bg-${order.status === 'completed' ? 'success' : 'info'}">${order.status.replace('_', ' ')}</span>
                    </div>
                    <p class="card-text small text-muted mb-2">${order.items} items</p>
                    <small class="text-muted">${new Date(order.preparedAt).toLocaleString()}</small>
                </div>
            </div>
        `;
        cardsContainer.appendChild(card);
    });
}

function updatePerformanceChart(data) {
    const ctx = document.getElementById('performanceChart').getContext('2d');

    // Destroy existing chart if it exists
    if (window.performanceChart) {
        window.performanceChart.destroy();
    }

    window.performanceChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'Ready for Pickup', 'Prepared'],
            datasets: [{
                data: [65, 25, 10], // Mock data
                backgroundColor: [
                    '#28a745',
                    '#17a2b8',
                    '#ffc107'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
}

function updateTopProducts(data) {
    const container = document.getElementById('topProductsList');

    // Mock top products data
    const topProducts = [
        { name: 'Rice', count: 25, percentage: 35 },
        { name: 'Corn', count: 18, percentage: 25 },
        { name: 'Wheat', count: 15, percentage: 20 },
        { name: 'Soybeans', count: 12, percentage: 15 },
        { name: 'Barley', count: 5, percentage: 5 }
    ];

    container.innerHTML = '';

    topProducts.forEach((product, index) => {
        const productDiv = document.createElement('div');
        productDiv.className = 'mb-3';
        productDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="fw-medium">${index + 1}. ${product.name}</span>
                <small class="text-muted">${product.count} orders</small>
            </div>
            <div class="progress" style="height: 6px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: ${product.percentage}%"></div>
            </div>
        `;
        container.appendChild(productDiv);
    });
}

function toggleView(view) {
    currentView = view;

    const tableView = document.getElementById('ordersTableView');
    const cardsView = document.getElementById('ordersCardsView');

    if (view === 'table') {
        tableView.style.display = 'block';
        cardsView.style.display = 'none';
    } else {
        tableView.style.display = 'none';
        cardsView.style.display = 'block';
    }
}

function exportReport() {
    if (!reportData) {
        alert('Please generate a report first.');
        return;
    }

    // In a real application, this would generate and download a PDF/Excel file
    alert('Export functionality would be implemented here. Report data is ready for export.');
}
</script>
@endpush
