<style>
    /* Sidebar */
    .sidebar {
        background: linear-gradient(to top, #b2f0f5, #ffffff);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.3s ease-in-out;
    }

    li {
        list-style: none;
    }

    .font {
        font-size: 0.85rem;
    }

    .sidebar .nav-link {
        color: #333;
        font-weight: 500;
    }

    .sidebar .nav-link.active {
        color: #007bff;
        font-weight: 600;
    }

    /* Top bar */
    .topbar {
        background: #b2f0f5;
        border-radius: 8px;
        padding: 4px 5px;
    }

    /* Cards */
    .card-summary {
        border-radius: 12px;
        box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
    }

    .card-summary h6 {
        font-weight: bold;
    }

    /* Purchase Order Table */
    .purchase-card {
        border-radius: 12px;
        box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
    }

    .table thead {
        background: #f8f9fa;
    }

    .badge-pending {
        background-color: transparent;
        color: #ff9800;
        font-weight: bold;
    }

    /* Buttons */
    .btn-primary {
        background-color: #007bff;
        border: none;
    }

    .btn-outline-secondary {
        border-color: #bbb;
        color: #333;
    }

    .margin {
        margin-top: 10px;
        margin-bottom: 10px;
    }

    /* Responsive Sidebar */
    @media (max-width: 768px) {
        .sidebar {
            position: fixed;
            top: 0;
            left: -250px;
            width: 250px;
            height: 100%;
            z-index: 1050;
            overflow-y: auto;
        }

        .sidebar.show {
            left: 0;
        }

        .content-wrapper {
            margin-left: 0 !important;
        }
    }
</style>

<!-- Sidebar -->
<div id="sidebar" class="col-md-2 sidebar p-3 font">
    <div>
        <div class="text-center mb-4 d-flex justify-content-center align-items-center">
            <img src="{{ asset('images/LOGO.jpg') }}" alt="Logo" class="img-fluid" style="max-width:120px;">
            <div>
                <button class="btn btn-primary d-md-none m-2" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i> Menu
                </button>
            </div>
        </div>

        <ul class="nav flex-column gap-3">
            @if (auth()->check() && auth()->user()->role && auth()->user()->role->role_name === 'Admin')
            <li class="nav-item">

                <a href="{{ route('pending.requests') }}" class="nav-link {{ request()->is('pending-requests') ? 'active' : '' }}">
                    <i class="bi-check-circle"></i> Stock Requests
                </a>

            </li>
            @endif
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('Dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('purchase_order.index') }}" class="nav-link {{ request()->is('purchase_order*') ? 'active' : '' }}">
                    <i class="bi bi-bag me-2"></i> Purchase Order
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('delivery.index') }}" class="nav-link {{ request()->is('delivery*') ? 'active' : '' }}">
                    <i class="bi bi-truck me-2"></i> Deliveries
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('stock_in.index') }}" class="nav-link {{ request()->is('stock_in*') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle me-2"></i> Stock In
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('stock-out.index') }}" class="nav-link {{ request()->is('stock-out-requests*') ? 'active' : '' }}">
                    <i class="bi bi-box-arrow-up-right me-2"></i> Stock Out
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('stock_adjustments.index') }}" class="nav-link {{ request()->is('stock_adjustments*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-repeat me-2"></i> Stock Adjustment
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('stock_reports_index') }}" class="nav-link {{ request()->is('Stock-Report*') ? 'active' : '' }}">
                    <i class="bi bi-graph-up me-2"></i> Reports
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('items.index') }}" class="nav-link {{ request()->is('items*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam me-2"></i> Items
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->is('suppliers*') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i> Suppliers
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('employees.index') }}" class="nav-link {{ request()->is('employees*') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i> Employees
                </a>
            </li>
            <!-- Placeholder routes (to be implemented) -->
            <li class="nav-item">
                <a href="#" class="nav-link {{ request()->is('customers*') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i> Customers
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link {{ request()->is('orders*') ? 'active' : '' }}">
                    <i class="bi bi-journal-text me-2"></i> Orders
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link {{ request()->is('product_workflow*') ? 'active' : '' }}">
                    <i class="bi bi-activity me-2"></i> Product Workflow
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link {{ request()->is('services*') ? 'active' : '' }}">
                    <i class="bi bi-tools me-2"></i> Service
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link {{ request()->is('manage_store*') ? 'active' : '' }}">
                    <i class="bi bi-shop me-2"></i> Manage Store
                </a>
            </li>
            <div class="mt-3 mb-5 d-flex flex-column gap-3 font">
                <hr>
                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->is('settings*') ? 'active' : '' }}">
                        <i class="bi bi-gear"></i> Settings
                    </a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link text-decoration-none">
                            <i class="bi bi-box-arrow-right"></i> Log Out
                        </button>
                    </form>
                </li>
            </div>
        </ul>
    </div>
</div>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('show');
    }
</script>