<div class="col-md-2 sidebar p-3">
    <div class="text-center mb-4">
        <img src="{{ asset('images/LOGO.jpg') }}" alt="Logo" class="img-fluid" style="max-width:120px;">
    </div>

    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="#" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            {{-- Sidebar Purchase Order goes to Add Purchase Order --}}
            <a href="{{ route('purchase_order.add') }}" 
               class="nav-link {{ request()->is('purchase_order/add') ? 'active' : '' }}">
                <i class="bi bi-bag me-2"></i> Purchase Order
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link {{ request()->is('stock_out*') ? 'active' : '' }}">
                <i class="bi bi-box-arrow-up me-2"></i> Stock Out
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link {{ request()->is('stock_adjustment*') ? 'active' : '' }}">
                <i class="bi bi-arrow-repeat me-2"></i> Stock Adjustment
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link {{ request()->is('reports*') ? 'active' : '' }}">
                <i class="bi bi-graph-up me-2"></i> Reports
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link {{ request()->is('suppliers*') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i> Suppliers
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link {{ request()->is('orders*') ? 'active' : '' }}">
                <i class="bi bi-cart me-2"></i> Orders
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link {{ request()->is('manage_store*') ? 'active' : '' }}">
                <i class="bi bi-shop me-2"></i> Manage Store
            </a>
        </li>
    </ul>

    <div class="mt-5">
        <hr>
        <a href="#" class="nav-link"><i class="bi bi-gear"></i> Settings</a>
        <br>
        <a href="#" class="nav-link"><i class="bi bi-box-arrow-right"></i> Log Out</a>
    </div>
</div>
