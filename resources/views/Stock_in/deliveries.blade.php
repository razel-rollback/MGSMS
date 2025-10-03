@extends('layouts.app')

@section('content')
<div class="col-md-12 p-4 bg-light">

    <!-- Top Bar -->
    <div class="d-flex justify-content-between align-items-center topbar mb-3">
        <!-- Search -->
        <form action="#" method="GET" class="d-flex w-50">
            <input class="form-control me-2" type="search" name="query" placeholder="Search">
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>

        <!-- Notifications + User -->
        <div class="d-flex align-items-center">
            <button class="btn btn-light position-relative me-3">
                <i class="bi bi-bell fs-5"></i>
            </button>
            <img src="{{ asset('images/user.png') }}" class="rounded-circle" width="40" alt="User">
        </div>
    </div>

    <!-- Deliveries Section -->
    <div class="table-section p-3 bg-white rounded shadow-sm mb-4">
        <h5 class="mb-3">Deliveries</h5>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
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
                    <tr>
                        <td>1</td>
                        <td>Mark</td>
                        <td>09/11/25</td>
                        <td>09/12/25</td>
                        <td>20,0424</td>
                        <td><span class="badge bg-success">Received</span></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Jacob</td>
                        <td>09/13/25</td>
                        <td>09/14/25</td>
                        <td>23,2213</td>
                        <td><span class="badge bg-success">Received</span></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Larry</td>
                        <td>09/13/25</td>
                        <td>09/14/25</td>
                        <td>21,3232</td>
                        <td><span class="badge bg-success">Received</span></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Delivered Section -->
    <div class="table-section p-3 bg-white rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Delivered</h5>
            <div>
                <button class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-funnel"></i> Filters
                </button>
                <button class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-download"></i> Download All
                </button>
            </div>
        </div>

        <!-- Search -->
        <div class="mb-3">
            <input type="search" class="form-control w-25" placeholder="Search">
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Delivery Receipt</th>
                        <th>Supplier</th>
                        <th>PO Reference</th>
                        <th>Delivery Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>DR-23@#</td>
                        <td>Noah</td>
                        <td>PO-2025-12345</td>
                        <td>09/21/25</td>
                        <td><span class="badge bg-primary">Approved</span></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
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
