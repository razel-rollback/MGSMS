@extends('layouts.app')

@section('content')
<div class="col-md-12 p-4 bg-light">

    <!-- Top Bar -->
    <div class="d-flex justify-content-between align-items-center topbar mb-3">
        <!-- Search -->
        <form action="#" method="GET" class="d-flex w-50">
            <input class="form-control me-2" type="search" name="query" placeholder="Search product, supplier, order">
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

    <!-- Stock Out Request Table -->
    <div class="table-section p-3 bg-white rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Stock Out Request</h5>
            <div>
                <a href="{{ route('stockout.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Request Stock Out
                </a>
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
                        <th>Job Order ID</th>
                        <th>Requested By</th>
                        <th>Customer Name</th>
                        <th>Purpose</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>JO-001</td>
                        <td>Alice</td>
                        <td>Acme Corp</td>
                        <td>Installation</td>
                        <td>2025-10-05</td>
                        <td><span class="badge bg-success">Approved</span></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            <a href="#" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>JO-002</td>
                        <td>Bob</td>
                        <td>Beta Ltd</td>
                        <td>Repair</td>
                        <td>2025-10-07</td>
                        <td><span class="badge bg-danger">Rejected</span></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            <a href="#" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>JO-003</td>
                        <td>Carol</td>
                        <td>Gamma Inc</td>
                        <td>Upgrade</td>
                        <td>2025-10-10</td>
                        <td><span class="badge bg-warning text-dark">Pending</span></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            <a href="#" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <button class="btn btn-outline-secondary btn-sm">Previous</button>
            <small>Page 1 of 3</small>
            <button class="btn btn-outline-secondary btn-sm">Next</button>
        </div>
    </div>

</div>
@endsection