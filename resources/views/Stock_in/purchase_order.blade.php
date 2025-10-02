@extends('layouts.app')

@section('content')
<div class="col-md-12 p-4 bg-light">

    <!-- Top Bar -->
    <div class="d-flex justify-content-between align-items-center topbar mb-4">
        <!-- Search Form -->
        <form action="{{ route('purchase_order.index') }}" method="GET" class="d-flex w-50" role="search">
            <input class="form-control me-2" type="search" name="search" placeholder="Search..." aria-label="Search">
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>

        <!-- Notification + User -->
        <div class="d-flex align-items-center">
            <button class="btn btn-light position-relative me-3">
                <i class="bi bi-bell fs-5"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"></span>
            </button>
            <img src="{{ asset('images/user.png') }}" class="rounded-circle" width="40" alt="User">
        </div>
    </div>

    <!-- Purchase Orders -->
    <div class="card shadow-sm">
        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Purchase Orders</h5>
            <div>
                <a href="{{ route('purchase_order.add') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Purchase Order
                </a>
                <button class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-funnel"></i> Filters
                </button>
                <button class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-download"></i> Download All
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="card-body p-3">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Purchase Number</th>
                        <th>Supplier</th>
                        <th>Order Date</th>
                        <th>Delivery Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example Row -->
                    <tr>
                        <td>PO-2025-12121</td>
                        <td>John Dew</td>
                        <td>12-07-2025</td>
                        <td>12-14-2025</td>
                        <td>$10,555</td>
                        <td><span class="badge bg-warning text-dark">Pending</span></td>
                        <td>
                            <a href="#" class="text-primary me-2"><i class="bi bi-eye"></i></a>
                            <a href="#" class="text-success me-2"><i class="bi bi-pencil"></i></a>
                            <a href="#" class="text-danger"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="card-footer d-flex justify-content-between align-items-center">
            <button class="btn btn-outline-secondary btn-sm">Previous</button>
            <small>Page 1 of 10</small>
            <button class="btn btn-outline-secondary btn-sm">Next</button>
        </div>
    </div>

</div>
@endsection
