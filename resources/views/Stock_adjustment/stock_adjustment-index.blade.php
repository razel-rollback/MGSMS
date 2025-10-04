@extends('layouts.app')

@section('content')
<div class="col-md-12 p-4 bg-light">

    <!-- Action Buttons + Search -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Stock Adjustment</h5>
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

            <!-- Add Adjustment -->
            <a href="{{  route('stock_adjustment.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Add Adjustment
            </a>

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

    <!-- Stock Adjustment Table -->
    <div class="bg-white p-3 rounded shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th class="fw-bold">ID</th>
                        <th class="fw-bold">Name</th>
                        <th class="fw-bold">Adjustment Type</th>
                        <th class="fw-bold">Current Stock Level</th>
                        <th class="fw-bold">Date</th>
                        <th class="fw-bold">Added By</th>
                        <th class="fw-bold">Status</th>
                        <th class="fw-bold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Mug</td>
                        <td>Deduction</td>
                        <td>50</td>
                        <td>2025-12-03</td>
                        <td>Mario Maro</td>
                        <td><span class="badge bg-success">Approved</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Fan</td>
                        <td>Deduction</td>
                        <td>50</td>
                        <td>2025-12-03</td>
                        <td>Mario Maro</td>
                        <td><span class="badge bg-success">Approved</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Pillow</td>
                        <td>Deduction</td>
                        <td>50</td>
                        <td>2025-12-03</td>
                        <td>Mario Maro</td>
                        <td><span class="badge bg-success">Approved</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>ID</td>
                        <td>Deduction</td>
                        <td>50</td>
                        <td>2025-12-03</td>
                        <td>Mario Maro</td>
                        <td><span class="badge bg-danger">Rejected</span></td>
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
            <small>Page 1 of 10</small>
            <button class="btn btn-outline-secondary btn-sm">Next</button>
        </div>
    </div>

</div>
@endsection