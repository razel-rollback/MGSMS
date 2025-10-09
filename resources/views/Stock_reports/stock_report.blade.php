@extends('layouts.app')

@section('content')
<div class="col-md-12 p-4 bg-light">

    <!-- Stock Report Form -->
    <div class="p-5 rounded shadow" style="height: 550px; overflow-y: auto;">
        <h5 class="mb-4">Stock Report</h5>

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('stock_reports_generate') }}">
            @csrf
            <div class="row g-4">
                <!-- Select Report -->
                <div class="col-md-6">
                    <label class="form-label">Select Report</label>
                    <select name="report_type" class="form-select">
                        <option value="product_name" selected>Product Name</option>
                        <option value="adjustments">Adjustments</option>
                        <option value="stock_in">Stock In</option>
                        <option value="stock_out">Stock Out</option>
                    </select>
                </div>

                <!-- Date Range From -->
                <div class="col-md-3">
                    <label class="form-label">From</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                        <input type="date" name="from" class="form-control" required>
                    </div>
                </div>

                <!-- Date Range To -->
                <div class="col-md-3">
                    <label class="form-label">To</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                        <input type="date" name="to" class="form-control" required>
                    </div>
                </div>

                <!-- Filter -->
                <div class="col-md-6">
                    <label class="form-label">Filter</label>
                    <select name="filter" class="form-select">
                        <option value="current_stock" selected>Current Stock Level</option>
                        <option value="low_stock">Low Stock</option>
                        <option value="out_of_stock">Out of Stock</option>
                        <option value="all">All</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-center mt-5 gap-5">
                <button type="submit" class="btn btn-primary" style="width: 150px; height: 45px;">
                    <i class="bi bi-gear"></i> Generate
                </button>

                <button type="button" class="btn btn-success" style="width: 150px; height: 45px;" disabled>
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </button>

                <button type="button" class="btn btn-danger" style="width: 150px; height: 45px;" disabled>
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF
                </button>
            </div>
        </form>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </div>
</div>
@endsection