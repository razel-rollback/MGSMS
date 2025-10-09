@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 d-flex justify-content-between align-items-center">
        <span>All Top Selling Items</span>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">Back to Dashboard</a>
    </h3>

    <div class="card shadow-sm border-primary">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Top Selling Stock</h5>

            <table class="table table-borderless align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Sold Quantity</th>
                        <th>Remaining Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topSelling as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td class="text-success fw-semibold">{{ $item->sold ?? 0 }}</td>
                            <td>{{ $item->current_stock ?? 0 }}</td>
                            <td>₱{{ number_format($item->price ?? 0, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No top-selling items found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="mt-3 d-flex justify-content-center">
                {{ $topSelling->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
