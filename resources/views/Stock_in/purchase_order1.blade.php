<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Sidebar */
        .sidebar {
            background: linear-gradient(to top, #b2f0f5, #ffffff);
            min-height: 100vh;
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
            padding: 10px 15px;
        }
        /* Cards */
        .card-summary {
            border-radius: 12px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
        }
        .card-summary h6 {
            font-weight: bold;
        }
        .card-summary h4 {
            font-weight: bold;
        }

        /* Purchase Order Table */
        .purchase-card {
            border-radius: 12px;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
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
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar p-3">
            <div class="text-center mb-4">
                <img src="{{ asset('images/LOGO.jpg') }}" alt="Logo" class="img-fluid" style="max-width:120px;">
            </div>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a href="{{ route('purchase_order1.index') }}" class="nav-link active"><i class="bi bi-bag me-2"></i> Purchase Order</a></li>
                <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-box-arrow-up me-2"></i> Stock Out</a></li>
                <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-arrow-repeat me-2"></i> Stock Adjustment</a></li>
                <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-graph-up me-2"></i> Reports</a></li>
                <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-people me-2"></i> Suppliers</a></li>
                <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-cart me-2"></i> Orders</a></li>
                <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-shop me-2"></i> Manage Store</a></li>
            </ul>
            <hr>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <a href="#" class="nav-link"><i class="bi bi-gear"></i> Settings</a>
            <br>
            <a href="#" class="nav-link"><i class="bi bi-box-arrow-right"></i> Log Out</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-4" style="background:#f5f8fa;">

            <!-- Top Bar -->
            <div class="d-flex justify-content-between align-items-center topbar mb-4">
                <!-- Search Form -->
                <form action="{{ route('purchase_order1.index') }}" method="GET" class="d-flex w-50" role="search">
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
                    <img src="{{ asset('images/user.png') }}" class="rounded-circle" width="40" alt="user">
                </div>
            </div>

            <!-- Overall -->
            <div class="card p-3 mb-4 shadow-sm">
                <h5 class="mb-3">Overall Inventory</h5>
                <div class="row text-center">
                            <!-- Categories -->
        <div class="col-md-3">
            <a href="#" class="text-primary fw-bold">Categories</a>
            <h5 class="fw-bold mb-0">14</h5>
            <small class="text-muted">Last 7 days</small>
        </div>

        <!-- Total Products -->
        <div class="col-md-3">
            <span class="text-warning fw-bold">Total Products</span>
            <div class="d-flex justify-content-center gap-4 mt-2">
                <div class="text-center">
                    <h5 class="fw-bold mb-0">868</h5>
                    <small class="text-muted">Last 7 days</small>
                </div>
                <div class="text-center">
                    <h5 class="fw-bold mb-0">$25000</h5>
                    <small class="text-muted">Revenue</small>
                </div>
            </div>
        </div>

        <!-- Top Selling -->
        <div class="col-md-3">
            <span class="text-info fw-bold">Top Selling</span>
            <div class="d-flex justify-content-center gap-3 mt-2">
                <div class="text-center">
                    <h5 class="fw-bold mb-0">5</h5>
                    <small class="text-muted">Last 7 days</small>
                </div>
                <div class="text-center">
                    <h5 class="fw-bold mb-0">$2500</h5>
                    <small class="text-muted">Cost</small>
                </div>
            </div>
        </div>

        <!-- Low Stocks -->
        <div class="col-md-3">
            <span class="text-danger fw-bold">Low Stocks</span>
            <div class="d-flex justify-content-center gap-3 mt-2">
                <div class="text-center">
                    <h5 class="fw-bold mb-0">12</h5>
                    <small class="text-muted">Ordered</small>
                </div>
                <div class="text-center">
                    <h5 class="fw-bold mb-0">2</h5>
                    <small class="text-muted">Not in stock</small>
                </div>
            </div>
        </div>
    </div>
</div>

          <!-- Purchase Orders -->
<div class="card shadow-sm">
    <!-- Header -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Purchase Orders</h5>
        <div>
            <a href="#" class="btn btn-primary btn-sm">Purchase Order</a>
            <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-funnel"></i> Filters</button>
            <button class="btn btn-outline-secondary btn-sm">Download all</button>
        </div>
    </div>

    <!-- Table -->
    <div class="card-body p-3">
        <table class="table table-hover mb-0">
            <thead>
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
                <tr>
                    <td>PO 2025-12121</td>
                    <td>John Dew</td>
                    <td>12-7-2025</td>
                    <td>12-14-2025</td>
                    <td>10,555</td>
                    <td><span class="text-warning">Pending</span></td>
                    <td><i class="bi bi-eye"></i></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    <div class="card-footer d-flex justify-content-between align-items-center">
        <button class="btn btn-outline-secondary btn-sm">Previous</button>
        <small>Page 1 of 10</small>
        <button class="btn btn-outline-secondary btn-sm">Next</button>
    </div>
</div>
