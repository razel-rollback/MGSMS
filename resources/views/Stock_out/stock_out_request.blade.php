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

    <!-- Action Buttons -->
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary me-2">
            <i class="bi bi-plus-circle"></i> Request Stock Out
        </button>
        <button class="btn btn-outline-secondary me-2">
            <i class="bi bi-funnel"></i> Filters
        </button>
        <button class="btn btn-outline-secondary">
            <i class="bi bi-download"></i> Download All
        </button>
    </div>

    <!-- Stock Out Request Table -->
    <div class="bg-white p-3 rounded shadow-sm">
        <h5 class="mb-3">Stock Out Request</h5>
        <div class="table-responsive">
            <!-- Added text-center to center all table content -->
            <table class="table table-hover table-bordered align-middle text-center">
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
                            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
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
                            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
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
                            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
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