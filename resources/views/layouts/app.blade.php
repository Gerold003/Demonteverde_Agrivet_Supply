<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Demonteverde Agrivet - @yield('title', 'Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Primary Colors */
            --primary-color: #2c5530;
            --primary-light: #4a7c59;
            --primary-dark: #1e3a21;
            --secondary-color: #8b5a3c;
            --accent-color: #d4a574;

            /* Role-based Colors */
            --admin-color: #6f42c1;
            --admin-light: #8b5fbf;
            --cashier-color: #198754;
            --cashier-light: #20c997;
            --helper-color: #fd7e14;
            --helper-light: #ff922b;
            --inventory-color: #0dcaf0;
            --inventory-light: #31d2f2;

            /* Neutral Colors */
            --light-bg: #f8f9fa;
            --white: #ffffff;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-400: #ced4da;
            --gray-500: #adb5bd;
            --gray-600: #6c757d;
            --gray-700: #495057;
            --gray-800: #343a40;
            --gray-900: #212529;

            /* Shadows */
            --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);

            /* Border Radius */
            --border-radius: 12px;
            --border-radius-sm: 8px;
            --border-radius-lg: 16px;

            /* Transitions */
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-fast: all 0.15s ease-in-out;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: var(--shadow-lg);
        }

        .sidebar .navbar-brand {
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .sidebar .navbar-brand:hover {
            color: var(--accent-color);
        }

        .sidebar hr {
            border-color: rgba(255, 255, 255, 0.2);
            margin: 1rem 0;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            border-radius: var(--border-radius-sm);
            margin: 0.25rem 0;
            transition: var(--transition);
        }

        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: 500;
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }

        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            background-color: var(--light-bg);
        }

        .user-info {
            background: rgba(255, 255, 255, 0.1);
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 1rem;
            backdrop-filter: blur(10px);
        }

        .user-info h6 {
            color: white;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .user-info .badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }

        .role-admin { border-left: 4px solid var(--admin-color); }
        .role-cashier { border-left: 4px solid var(--cashier-color); }
        .role-helper { border-left: 4px solid var(--helper-color); }
        .role-inventory { border-left: 4px solid var(--inventory-color); }

        .card-dashboard {
            border: none;
            border-radius: var(--border-radius);
            transition: var(--transition);
            cursor: pointer;
        }

        .card-dashboard:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .btn {
            border-radius: var(--border-radius-sm);
            font-weight: 500;
            transition: var(--transition);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: var(--gray-700);
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .navbar-toggler {
                display: block;
            }
        }

        .navbar-toggler {
            display: none;
        }

        /* Custom scrollbar for sidebar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="p-4">
                <a class="navbar-brand d-block mb-4" href="#">
                    <i class="bi bi-tree me-2"></i>
                    Demonteverde Agrivet
                </a>

                @if(auth()->check())
                    <div class="user-info">
                        <h6 class="mb-1">{{ auth()->user()->name }}</h6>
                        <span class="badge bg-white text-dark">{{ ucfirst(auth()->user()->role) }}</span>
                    </div>
                @endif

                <hr>

                <ul class="nav flex-column">
                    @if(auth()->check())
                        @if(auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.daily') }}">
                                    <i class="bi bi-graph-up me-2"></i>Reports
                                </a>
                            </li>
                        @elseif(auth()->user()->role === 'cashier')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('cashier.dashboard') ? 'active' : '' }}" href="{{ route('cashier.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('cashier.transactions.*') ? 'active' : '' }}" href="{{ route('cashier.transactions.index') }}">
                                    <i class="bi bi-receipt me-2"></i>Transactions
                                </a>
                            </li>
                        @elseif(auth()->user()->role === 'helper')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('helper.dashboard') ? 'active' : '' }}" href="{{ route('helper.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('helper.orders.*') ? 'active' : '' }}" href="{{ route('helper.orders.index') }}">
                                    <i class="bi bi-box-seam me-2"></i>Orders
                                </a>
                            </li>
                        @elseif(auth()->user()->role === 'inventory')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('inventory.dashboard') ? 'active' : '' }}" href="{{ route('inventory.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('inventory.products.*') ? 'active' : '' }}" href="{{ route('inventory.products.index') }}">
                                    <i class="bi bi-boxes me-2"></i>Products
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('inventory.stock-in.*') ? 'active' : '' }}" href="{{ route('inventory.stock-in.index') }}">
                                    <i class="bi bi-arrow-down-circle me-2"></i>Stock In
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('inventory.stock-out.*') ? 'active' : '' }}" href="{{ route('inventory.stock-out.index') }}">
                                    <i class="bi bi-arrow-up-circle me-2"></i>Stock Out
                                </a>
                            </li>
                        @endif

                        <li class="nav-item mt-3">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link bg-transparent border-0 w-100 text-start" style="color: rgba(255, 255, 255, 0.8);">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <div class="container-fluid p-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')
</body>
</html>
