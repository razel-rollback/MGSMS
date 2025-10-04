@extends('layouts.app')

@section('content')
<div class="col-md-12 p-4 bg-light">



    <!-- Action Buttons + Search -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Stock Out Requests</h5>
        <div class="d-flex align-items-center gap-2">
            <!-- Search Form -->
            <form action="#" method="GET" class="d-flex">
                <div class="input-group input-group-sm">
                    <input type="search" name="query" class="form-control border-primary" placeholder="Search">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            <!-- Request Stock Out -->
            <button class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Request Stock Out
            </button>

            <!-- Filters -->
            <button class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-funnel"></i> Filters
            </button>

            <!-- Download All -->
            <button class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-download"></i> Download All
            </button>
        </div>
    </div>

    <!-- Stock Out Request Table -->
    <div class="bg-white p-3 rounded shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th class="fw-bold">Job Order ID</th>
                        <th class="fw-bold">Requested By</th>
                        <th class="fw-bold">Customer Name</th>
                        <th class="fw-bold">Purpose</th>
                        <th class="fw-bold">Due Date</th>
                        <th class="fw-bold">Status</th>
                        <th class="fw-bold">Actions</th>
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