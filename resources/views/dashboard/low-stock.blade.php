@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 d-flex justify-content-between align-items-center">
        <span>All Low Stock Items</span>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">Back to Dashboard</a>
    </h3>

    <div class="card shadow-sm border-primary">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Low Quantity Stock</h5>

            @forelse($lowStock as $item)
                <div class="d-flex align-items-center justify-content-between border-bottom py-2">
                    <div>
                        <h6 class="mb-0 fw-semibold">{{ $item->name }}</h6>
                        <small class="text-muted">
                            Remaining Quantity: {{ $item->current_stock ?? 0 }} Packet
                        </small>
                    </div>
                    <span class="badge bg-danger rounded-pill px-3 py-2">Low</span>
                </div>
            @empty
                <p class="text-muted text-center my-3">No low-stock items found.</p>
            @endforelse

            <!-- Pagination -->
            <div class="mt-3 d-flex justify-content-center">
                {{ $lowStock->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
