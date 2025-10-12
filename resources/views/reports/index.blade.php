@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3>Stock Report Generator</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('reports.generate') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="report_type" class="form-label">Report Type</label>
                            <select class="form-select" id="report_type" name="report_type" required>
                                <option value="">Select Report Type</option>
                                <option value="current_stock">Current Stock Levels</option>
                                <option value="low_stock">Low Stock Alert</option>
                                <option value="stock_movement">Stock Movement History</option>
                                <option value="purchase_orders">Purchase Order Status</option>
                            </select>
                        </div>

                        <div id="date_range" class="row mb-3" style="display: none;">
                            <div class="col-md-6">
                                <label for="date_from" class="form-label">From Date</label>
                                <input type="date" class="form-control" id="date_from" name="date_from">
                            </div>
                            <div class="col-md-6">
                                <label for="date_to" class="form-label">To Date</label>
                                <input type="date" class="form-control" id="date_to" name="date_to">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Generate Report</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('report_type').addEventListener('change', function() {
        const dateRange = document.getElementById('date_range');
        if (this.value === 'stock_movement') {
            dateRange.style.display = 'flex';
        } else {
            dateRange.style.display = 'none';
        }
    });
</script>
@endpush