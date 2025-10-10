@extends('layouts.app')

@section('title', 'Stock Level Overview')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary mb-0">
            <i class="bi bi-box-seam me-2"></i> Inventory Stock Levels
        </h3>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if($inventoryItems->isEmpty())
            <div class="text-center py-4">
                <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                <h5 class="mt-3 text-muted">No stock items found.</h5>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Item Name</th>
                            <th>Unit</th>
                            <th>Current Stock</th>
                            <th>Reorder Level</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inventoryItems as $index => $item)
                        @php
                        // Determine status level
                        if ($item->current_stock <= $item->re_order_stock) {
                            $status = 'Low';
                            $badge = 'danger';
                            $icon = 'bi-arrow-down-circle';
                            } elseif ($item->current_stock <= $item->re_order_stock * 1.5) {
                                $status = 'Medium';
                                $badge = 'warning';
                                $icon = 'bi-dash-circle';
                                } else {
                                $status = 'High';
                                $badge = 'success';
                                $icon = 'bi-arrow-up-circle';
                                }
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ $item->name }}</td>
                                    <td>{{ $item->unit }}</td>
                                    <td>{{ $item->current_stock ?? 0 }}</td>
                                    <td>{{ $item->re_order_stock }}</td>
                                    <td>
                                        <span class="badge bg-{{ $badge }} rounded-pill px-3 py-2">
                                            <i class="bi {{ $icon }} me-1"></i> {{ $status }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3 d-flex justify-content-center">
                {{ $inventoryItems->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection