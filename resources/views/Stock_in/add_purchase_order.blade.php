@extends('layouts.app')

@section('title', 'Add Purchase Order')

@section('content')
<div class="col-md-12 p-4 bg-light">

    <!-- Top Bar -->
    <div class="d-flex justify-content-between align-items-center topbar mb-4">
        <!-- Search -->
        <form action="" method="GET" class="d-flex w-50">
            <input class="form-control me-2" type="search" placeholder="Search product, supplier, order">
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>

        <!-- Notifications + User -->
        <div class="d-flex align-items-center">
            <button class="btn btn-light position-relative me-3">
                <i class="bi bi-bell fs-5"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">!</span>
            </button>
            <img src="{{ asset('images/user.png') }}" class="rounded-circle" width="40" alt="User">
        </div>
    </div>

    <!-- Order Details -->
    <div class="form-section mb-4 p-3 bg-white rounded shadow-sm">
        <h5 class="mb-3">Order Details</h5>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">PO Number</label>
                <input type="text" class="form-control" placeholder="Enter PO Number">
            </div>
            <div class="col-md-3">
                <label class="form-label">Supplier Name</label>
                <select class="form-select">
                    <option>Select Supplier</option>
                    <option>Supplier 1</option>
                    <option>Supplier 2</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Order Date</label>
                <input type="date" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Delivery Date</label>
                <input type="date" class="form-control">
            </div>
        </div>

        <!-- Order Items -->
        <h6 class="mt-4">Order Items</h6>
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Item</label>
                <select class="form-select">
                    <option>Select Item</option>
                    <option>Item A</option>
                    <option>Item B</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Quantity</label>
                <input type="number" class="form-control" placeholder="0">
            </div>
            <div class="col-md-3">
                <label class="form-label">Unit Price</label>
                <input type="number" class="form-control" placeholder="0.00">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">
                    <i class="bi bi-plus-circle"></i> Add Item
                </button>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="table-section p-3 bg-white rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Products</h5>
            <div>
                <button class="btn btn-success btn-sm">
                    <i class="bi bi-save"></i> Save
                </button>
                <button class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-funnel"></i> Filters
                </button>
                <button class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-download"></i> Download All
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Item Name</th>
                        <th>Unit</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example Row -->
                    <tr>
                        <td>Sample Item</td>
                        <td>pcs</td>
                        <td>10</td>
                        <td>$100</td>
                        <td>$1,000</td>
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
        <div class="d-flex justify-content-between align-items-center mt-3">
            <button class="btn btn-outline-secondary btn-sm">Previous</button>
            <small>Page 1 of 10</small>
            <button class="btn btn-outline-secondary btn-sm">Next</button>
        </div>
    </div>
</div>
@endsection
