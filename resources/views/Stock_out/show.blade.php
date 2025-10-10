@extends('layouts.app')

@section('content')
<div class="col-md-12 p-4 bg-light">

    <!-- Header and Back Button -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Stock Out Request #{{ $stockOutRequest->stock_out_id }}</h5>


        @if ($stockOutRequest->status !== 'Validated' && Auth::check())
        <form action="{{ route('stock.out.requests.validate', $stockOutRequest->stock_out_id) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-success btn-sm">
                <i class="bi bi-check-circle"></i> Validate Request
            </button>
        </form>
        @endif
        <a href="{{ route('stock-out.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>
    <div>
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if (session('insufficient_items'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Insufficient Inventory:</strong>
            <ul class="mt-2 mb-0">
                @foreach (session('insufficient_items') as $item)
                <li>
                    {{ $item['item_name'] }} —
                    Requested: {{ $item['requested_quantity'] }},
                    Available: {{ $item['available_quantity'] }},
                    Shortfall: {{ $item['shortfall'] }}
                </li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

    </div>

    <!-- Stock Out Request Details -->
    <div class="bg-white p-3 rounded shadow-sm mb-4">
        <h6 class="border-bottom pb-2 mb-3">Request Details</h6>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Job Order:</strong> {{ $stockOutRequest->jobOrder ? $stockOutRequest->jobOrder->job_order_id : 'N/A' }}</p>
                <p><strong>Requested By:</strong> {{ $stockOutRequest->requester ? $stockOutRequest->requester->fullname : 'N/A' }}</p>
                <p><strong>Requested At:</strong> {{ $stockOutRequest->requested_at->format('Y-m-d H:i:s') }}</p>
                <p><strong>Status:</strong> {{ $stockOutRequest->status }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Validated By:</strong> {{ $stockOutRequest->validator ? $stockOutRequest->validator->first_name : 'N/A' }}</p>
                <p><strong>Validated At:</strong> {{ $stockOutRequest->validated_at ? $stockOutRequest->validated_at->format('Y-m-d H:i:s') : 'N/A' }}</p>
                <p><strong>Approved By:</strong> {{ $stockOutRequest->approver ? $stockOutRequest->approver->first_name : 'N/A' }}</p>
                <p><strong>Approved At:</strong> {{ $stockOutRequest->approved_at ? $stockOutRequest->approved_at->format('Y-m-d H:i:s') : 'N/A' }}</p>
            </div>
        </div>
        <p><strong>Note:</strong> {{ $stockOutRequest->note ?? 'N/A' }}</p>
    </div>

    <!-- Stock Out Items Table -->
    <div class="bg-white p-3 rounded shadow-sm">
        <h6 class="border-bottom pb-2 mb-3">Stock Out Items</h6>
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Item ID</th>
                        <th scope="col">Item Name</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stockOutRequest->stockOutItems as $item)
                    <tr>
                        <td>{{ $item->item_id }}</td>
                        <td>{{ $item->inventoryItem ? $item->inventoryItem->name : 'N/A' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->status }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">No stock out items found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection