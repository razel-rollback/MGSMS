@extends('layouts.production_app')

@section('content')
<div class="container mt-4">

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h5 class="mb-0">My Stock Out Requests</h5>
        <a href="{{ route('production.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Create Request
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>Items</th>
                    <th>Total Quantity</th>
                    <th>Status</th>
                    <th>Requested At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $request)
                <tr>
                    <td>
                        @foreach($request->stockOutItems as $item)
                        <div>- {{ $item->inventoryItem->name ?? 'N/A' }} ({{ $item->quantity }})</div>
                        @endforeach
                    </td>
                    <td>{{ $request->stockOutItems->sum('quantity') }}</td>
                    <td>
                        <span class="badge 
                                @if($request->status == 'Approved') bg-success
                                @elseif($request->status == 'Pending') bg-warning text-dark
                                @elseif($request->status == 'Disaaprove') bg-danger
                                @else bg-secondary @endif">
                            {{ ucfirst($request->status) }}
                        </span>
                    </td>
                    <td>{{ $request->created_at?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('production.edit', $request->stock_out_id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No requests yet</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $requests->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection