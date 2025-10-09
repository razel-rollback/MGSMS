@extends('layouts.app')

@section('content')
<div class="col-md-12 p-4 bg-light">

    <!-- Deliveries Section -->
    <h2>Pending Deliveries</h2>
    <table class="table table-bordered">
        <thead>
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
                <td>{{ $delivery->receivedBy->first_name . ' ' . $delivery->receivedBy->last_name ?? 'N/A' }}</td>

                <td>{{ $delivery->status }}</td>
                <td>
                    <form action="{{ route('delivery.approve', $delivery->delivery_id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                    </form>
                    <form action="{{ route('delivery.disapprove', $delivery->delivery_id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger btn-sm">Disapprove</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Purchase Orders Section -->
    <h2>Pending Purchase Orders</h2>
    <table class="table table-bordered">
        <thead>
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
                <td>{{ number_format($po->total_amount, 2) }}</td>
                <td>{{ $po->status }}</td>
                <td>
                    <form action="{{ route('purchase-order.approve', $po->po_id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                    </form>
                    <form action="{{ route('purchase-order.disapprove', $po->po_id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger btn-sm">Disapprove</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Stock In Requests Section -->
    <h2>Pending Stock In Requests</h2>
    <table class="table table-bordered">
        <thead>
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
            @foreach ($stockInRequests as $request)
            <tr>
                <td>{{ $request->purchaseOrder->po_number ?? 'N/A' }}</td>
                <td>{{ $request->delivery->delivery_receipt ?? 'N/A' }}</td>
                <td>{{ $request->requester->first_name.' '.$request->requester->last_name  ?? 'N/A' }}</td>
                <td>{{ $request->requested_at?->format('Y-m-d H:i') }}</td>
                <td>{{ $request->status }}</td>
                <td>
                    <form action="{{ route('stock-in.approve', $request->stock_in_id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                    </form>
                    <form action="{{ route('stock-in.disapprove', $request->stock_in_id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger btn-sm">Disapprove</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Stock Out Requests Section -->
    <h2>Pending Stock Out Requests</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Job Order</th>
                <th>Requested By</th>
                <th>Requested At</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stockOutRequests as $request)
            <tr>
                <td>{{ $request->jobOrder->job_order_number ?? 'N/A' }}</td>
                <td>{{ $request->requester->first_name. ' '.$request->requester->last_name ?? 'N/A' }}</td>
                <td>{{ $request->requested_at?->format('Y-m-d H:i') }}</td>
                <td>{{ $request->status }}</td>
                <td>
                    <form action="{{ route('stock-out.approve', $request->stock_out_id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                    </form>
                    <form action="{{ route('stock-out.disapprove', $request->stock_out_id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger btn-sm">Disapprove</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Stock Adjustments Section -->
    <h2>Pending Stock Adjustments</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Item</th>
                <th>Requested By</th>
                <th>Requested At</th>
                <th>Adjustment Type</th>
                <th>Quantity</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stockAdjustments as $adjustment)
            <tr>
                <td>{{ $adjustment->inventoryItem->name ?? 'N/A' }}</td>
                <td>{{ $adjustment->requester->first_name.' '. $adjustment->requester->last_name ?? 'N/A' }}</td>
                <td>{{ $adjustment->requested_at?->format('Y-m-d H:i') }}</td>
                <td>{{ $adjustment->adjustment_type }}</td>
                <td>{{ $adjustment->quantity }}</td>
                <td>{{ $adjustment->reason }}</td>
                <td>{{ $adjustment->status }}</td>
                <td>
                    <form action="{{ route('stock-adjustment.approve', $adjustment->adjustment_id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                    </form>
                    <form action="{{ route('stock-adjustment.disapprove', $adjustment->adjustment_id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger btn-sm">Disapprove</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection