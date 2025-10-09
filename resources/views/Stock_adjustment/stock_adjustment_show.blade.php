@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Stock Adjustment Details</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>ID</th>
                    <td>{{ $adjustment->adjustment_id }}</td>
                </tr>
                <tr>
                    <th>Product Name</th>
                    <td>{{ $adjustment->inventoryItem->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Adjustment Type</th>
                    <td>{{ ucfirst($adjustment->adjustment_type) }}</td>
                </tr>
                <tr>
                    <th>Quantity</th>
                    <td>{{ $adjustment->quantity }}</td>
                </tr>
                <tr>
                    <th>Requested By</th>
                    <td>{{ $adjustment->requester->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Reason</th>
                    <td>{{ $adjustment->reason }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if($adjustment->status === 'approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($adjustment->status === 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td>{{ $adjustment->created_at->format('d-m-Y') }}</td>
                </tr>
            </table>
            <!-- Back Button -->
                <a href="{{ route('stock_adjustments.index') }}" class="btn btn-outline-secondary mb-3">
                    <i class="bi bi-arrow-left"></i> Back
                </a>    
        </div>
    </div>
</div>
@endsection