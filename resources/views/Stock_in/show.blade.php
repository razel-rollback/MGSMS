@extends('layouts.app')

@section('content')
<div class="col-md-10 mx-auto bg-light p-4 rounded shadow-sm">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Stock-In Request Details</h4>
        <a href="{{ route('stock_in.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <!-- ===================== STOCK IN HEADER ===================== -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-6">
                    <strong>Stock-In ID:</strong> {{ $stockIn->stock_in_id }}
                </div>
                <div class="col-md-6">
                    <strong>Status:</strong>
                    @if ($stockIn->status === 'approved')
                    <span class="badge bg-success">Approved</span>
                    @elseif ($stockIn->status === 'pending')
                    <span class="badge bg-warning text-dark">Pending</span>
                    @else
                    <span class="badge bg-secondary">{{ ucfirst($stockIn->status) }}</span>
                    @endif
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-6">
                    <strong>Purchase Order:</strong>
                    {{ $stockIn->purchaseOrder?->po_number ?? '—' }}
                </div>
                <div class="col-md-6">
                    <strong>Delivery Receipt:</strong>
                    {{ $stockIn->delivery?->delivery_receipt ?? '—' }}
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-6">
                    <strong>Requested By:</strong>
                    {{ $stockIn->requester?->fullname ?? '—' }}
                </div>
                <div class="col-md-6">
                    <strong>Requested At:</strong>
                    {{ $stockIn->requested_at?->format('M d, Y h:i A') ?? '—' }}
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-6">
                    <strong>Approved By:</strong>
                    {{ $stockIn->approver?->fullname ?? '—' }}
                </div>
                <div class="col-md-6">
                    <strong>Approved At:</strong>
                    {{ $stockIn->approved_at?->format('M d, Y h:i A') ?? '—' }}
                </div>
            </div>

            <div class="mb-2">
                <strong>Note:</strong>
                <p class="text-muted mb-0">{{ $stockIn->note ?? 'No remarks provided.' }}</p>
            </div>
        </div>
    </div>

    <!-- ===================== STOCK IN ITEMS ===================== -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Stock-In Items</h6>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stockIn->stockInItems as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->inventoryItem?->name ?? '—' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>₱{{ number_format($item->unit_price, 2) }}</td>
                        <td>₱{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No items found for this stock-in request.</td>
                    </tr>
                    @endforelse
                </tbody>
                @if($stockIn->stockInItems->isNotEmpty())
                <tfoot>
                    <tr class="table-light">
                        <th colspan="4" class="text-end">Grand Total</th>
                        <th>₱{{ number_format($stockIn->stockInItems->sum(fn($i) => $i->quantity * $i->unit_price), 2) }}</th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

</div>
@endsection