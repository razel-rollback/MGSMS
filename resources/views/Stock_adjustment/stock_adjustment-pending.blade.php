@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Pending Stock Adjustments</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Item</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Reason</th>
                <th>Requested By</th>
                <th>Requested At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($adjustments as $adj)
                <tr>
                    <td>{{ $adj->adjustment_id }}</td>
                    <td>{{ $adj->inventoryItem->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($adj->adjustment_type) }}</td>
                    <td>{{ $adj->quantity }}</td>
                    <td>{{ $adj->reason }}</td>
                    <td>{{ $adj->requester->name ?? 'N/A' }}</td>
                    <td>{{ $adj->requested_at?->format('Y-m-d H:i') }}</td>
                    <td>
                        <form action="{{ route('stock_adjustments.approve', $adj->adjustment_id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                        </form>
                        <form action="{{ route('stock_adjustments.reject', $adj->adjustment_id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center">No pending adjustments.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection