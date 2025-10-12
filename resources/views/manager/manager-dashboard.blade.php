@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 bg-light">

    <h3 class="mb-4 fw-bold">Manager Dashboard</h3>

    <div class="row g-4">

        {{-- Card Component --}}
        @php
        $sections = [
        ['title' => 'Pending Deliveries', 'id' => 'deliveries', 'data' => $deliveries],
        ['title' => 'Pending Purchase Orders', 'id' => 'purchaseOrders', 'data' => $purchaseOrders],
        ['title' => 'Pending Stock In Requests', 'id' => 'stockInRequests', 'data' => $stockInRequests],
        ['title' => 'Pending Stock Out Requests', 'id' => 'stockOutRequests', 'data' => $stockOutRequests],
        ['title' => 'Pending Stock Adjustments', 'id' => 'stockAdjustments', 'data' => $stockAdjustments],
        ];
        @endphp

        @foreach ($sections as $section)
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $section['title'] }}</h5>
                    <button class="btn btn-light btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $section['id'] }}Table">
                        Toggle
                    </button>
                </div>
                <div class="collapse show" id="{{ $section['id'] }}Table">
                    <div class="card-body">
                        <div class="table-responsive">
                            @switch($section['id'])
                            @case('deliveries')
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>Delivery Receipt</th>
                                        <th>Purchase Order</th>
                                        <th>Supplier</th>
                                        <th>Delivered Date</th>
                                        <th>Received By</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($deliveries as $delivery)
                                    <tr>
                                        <td>{{ $delivery->delivery_receipt }}</td>
                                        <td>{{ $delivery->purchaseOrder->po_number ?? 'N/A' }}</td>
                                        <td>{{ $delivery->supplier->name ?? 'N/A' }}</td>
                                        <td>{{ $delivery->delivered_date?->format('Y-m-d H:i') }}</td>
                                        <td>{{ $delivery->receivedBy->first_name ?? '' }} {{ $delivery->receivedBy->last_name ?? '' }}</td>
                                        <td><span class="badge bg-warning text-dark">{{ $delivery->status }}</span></td>
                                        <td>
                                            <form action="{{ route('delivery.approve', $delivery->delivery_id) }}" method="POST" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-success btn-sm">Approve</button>
                                            </form>
                                            <form action="{{ route('delivery.disapprove', $delivery->delivery_id) }}" method="POST" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-danger btn-sm">Disapprove</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @break

                            @case('purchaseOrders')
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>PO Number</th>
                                        <th>Supplier</th>
                                        <th>Order Date</th>
                                        <th>Expected Date</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchaseOrders as $po)
                                    <tr>
                                        <td>{{ $po->po_number }}</td>
                                        <td>{{ $po->supplier->name ?? 'N/A' }}</td>
                                        <td>{{ $po->order_date?->format('Y-m-d') }}</td>
                                        <td>{{ $po->expected_date?->format('Y-m-d') }}</td>
                                        <td>₱{{ number_format($po->total_amount, 2) }}</td>
                                        <td><span class="badge bg-warning text-dark">{{ $po->status }}</span></td>
                                        <td>
                                            <form action="{{ route('purchase-order.approve', $po->po_id) }}" method="POST" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-success btn-sm">Approve</button>
                                            </form>
                                            <form action="{{ route('purchase-order.disapprove', $po->po_id) }}" method="POST" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-danger btn-sm">Disapprove</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @break

                            @case('stockInRequests')
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>Purchase Order</th>
                                        <th>Delivery</th>
                                        <th>Requested By</th>
                                        <th>Requested At</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stockInRequests as $req)
                                    <tr>
                                        <td>{{ $req->purchaseOrder->po_number ?? 'N/A' }}</td>
                                        <td>{{ $req->delivery->delivery_receipt ?? 'N/A' }}</td>
                                        <td>{{ $req->requester->first_name ?? '' }} {{ $req->requester->last_name ?? '' }}</td>
                                        <td>{{ $req->requested_at?->format('Y-m-d H:i') }}</td>
                                        <td><span class="badge bg-warning text-dark">{{ $req->status }}</span></td>
                                        <td>
                                            <form action="{{ route('stock-in.approve', $req->stock_in_id) }}" method="POST" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-success btn-sm">Approve</button>
                                            </form>
                                            <form action="{{ route('stock-in.disapprove', $req->stock_in_id) }}" method="POST" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-danger btn-sm">Disapprove</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @break

                            @case('stockOutRequests')
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>Job Order</th>
                                        <th>Requested By</th>
                                        <th>Requested At</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stockOutRequests as $req)
                                    <tr>
                                        <td>{{ $req->jobOrder->job_order_number ?? 'N/A' }}</td>
                                        <td>{{ $req->requester->first_name ?? '' }} {{ $req->requester->last_name ?? '' }}</td>
                                        <td>{{ $req->requested_at?->format('Y-m-d H:i') }}</td>
                                        <td><span class="badge bg-warning text-dark">{{ $req->status }}</span></td>
                                        <td>
                                            <form action="{{ route('stock-out.approve', $req->stock_out_id) }}" method="POST" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-success btn-sm">Approve</button>
                                            </form>
                                            <form action="{{ route('stock-out.disapprove', $req->stock_out_id) }}" method="POST" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-danger btn-sm">Disapprove</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @break

                            @case('stockAdjustments')
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>Item</th>
                                        <th>Requested By</th>
                                        <th>Requested At</th>
                                        <th>Type</th>
                                        <th>Quantity</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stockAdjustments as $adj)
                                    <tr>
                                        <td>{{ $adj->inventoryItem->name ?? 'N/A' }}</td>
                                        <td>{{ $adj->requester->first_name ?? '' }} {{ $adj->requester->last_name ?? '' }}</td>
                                        <td>{{ $adj->requested_at?->format('Y-m-d H:i') }}</td>
                                        <td>{{ ucfirst($adj->adjustment_type) }}</td>
                                        <td>{{ $adj->quantity }}</td>
                                        <td>{{ $adj->reason }}</td>
                                        <td><span class="badge bg-warning text-dark">{{ $adj->status }}</span></td>
                                        <td>
                                            <form action="{{ route('stock-adjustment.approve', $adj->adjustment_id) }}" method="POST" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-success btn-sm">Approve</button>
                                            </form>
                                            <form action="{{ route('stock-adjustment.disapprove', $adj->adjustment_id) }}" method="POST" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-danger btn-sm">Disapprove</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @break
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

<script>
    $(document).ready(function() {
        $('table').DataTable({
            pageLength: 5,
            lengthMenu: [5, 10, 25],
            order: [],
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
            }
        });
    });
</script>
@endpush