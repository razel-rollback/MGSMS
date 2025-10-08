@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Manager Dashboard</h2>

    <div class="row">
        <div class="col-md-4">
            <div class="card text-bg-secondary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Pending Adjustments</h5>
                    <p class="fs-3">{{ $pendingCount }}</p>
                    <a href="{{ route('stock_adjustments.pending') }}" class="btn btn-light btn-sm">View Pending</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Approved Adjustments</h5>
                    <p class="fs-3">{{ $approvedCount }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">Rejected Adjustments</h5>
                    <p class="fs-3">{{ $rejectedCount }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection