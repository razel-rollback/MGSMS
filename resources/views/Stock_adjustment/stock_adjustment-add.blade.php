@extends('layouts.app')

@section('content')
<div class="col-md-12 p-4 bg-light">



    <!-- Stock Adjustment Form -->
    <div class="bg-white p-4 rounded shadow-sm">
        <h5 class="mb-4">Stock Adjustment Information</h5>
        <form>
            <div class="row g-3">
                <!-- Product Name -->
                <div class="col-md-6">
                    <label class="form-label">Product Name</label>
                    <select class="form-select">
                        <option selected disabled>Select Product</option>
                        <option value="Mug">Mug</option>
                        <option value="Fan">Fan</option>
                        <option value="Pillow">Pillow</option>
                        <option value="ID">ID</option>
                    </select>
                </div>

                <!-- Adjustment Type -->
                <div class="col-md-6">
                    <label class="form-label">Adjustment Type</label>
                    <select class="form-select">
                        <option selected disabled>Select Type</option>
                        <option value="Addition">Addition</option>
                        <option value="Deduction">Deduction</option>
                        <option value="Correction">Correction</option>
                    </select>
                </div>

                <!-- Current Stock Level -->
                <div class="col-md-6">
                    <label class="form-label">Current Stock Level</label>
                    <input type="number" class="form-control" value="50" readonly>
                </div>

                <!-- Adjustment Quantity -->
                <div class="col-md-6">
                    <label class="form-label">Adjustment Quantity</label>
                    <input type="number" class="form-control" placeholder="Enter quantity">
                </div>

                <!-- Requested By -->
                <div class="col-md-6">
                    <label class="form-label">Requested By</label>
                    <input type="text" class="form-control" value="Marie Koh" readonly>
                </div>

                <!-- Requested Date -->
                <div class="col-md-6">
                    <label class="form-label">Requested Date</label>
                    <input type="date" class="form-control" value="2025-03-23" readonly>
                </div>

                <!-- Status (Dropdown) -->
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select class="form-select">
                        <option value="Pending" selected>Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>

                <!-- Reason -->
                <div class="col-12">
                    <label class="form-label">Reason</label>
                    <textarea class="form-control" rows="3" placeholder=""></textarea>
                </div>
            </div>

            <!-- Submit + Cancel Buttons -->
            <div class="d-flex justify-content-end mt-4 gap-2">
                <a href="{{ route('stock_adjustment.index') }}" class="btn btn-outline-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    Submit Stock Adjustment
                </button>
            </div>
        </form>
    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection