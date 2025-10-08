<div>
    <div class="row">
        <div class="col">
            <h6><strong>Purchase Number:</strong> {{ $purchaseOrder->po_number }}</h6>
            <p><strong>Supplier:</strong> {{ $purchaseOrder->supplier->name ?? 'N/A' }}</p>
            <p><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($purchaseOrder->order_date)->format('M d, Y') }}</p>
        </div>
        <div class="col">
            <p><strong>Expected Delivery:</strong> {{ \Carbon\Carbon::parse($purchaseOrder->expected_date)->format('M d, Y') }}</p>
            <p><strong>Status:</strong><span class="badge 
                                    {{ $purchaseOrder->status == 'Approved' ? 'bg-success' : 
                                       ($purchaseOrder->status == 'Pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                    {{ $purchaseOrder->status }}
                </span> <span class="badge 
                               {{ $purchaseOrder->delivery_status == 'Fully Delivered' ? 'bg-success' : 
                                  ($purchaseOrder->delivery_status == 'Partially Delivered' ? 'bg-info text-dark' : 'bg-secondary') }}">
                    {{ $purchaseOrder->delivery_status }}
                </span></p>
            <p><strong>Total:</strong> ₱{{ number_format($purchaseOrder->total_amount, 2) }}</p>
        </div>
    </div>

    <hr>
    <h6>Order Items:</h6>
    <table class="table table-sm table-bordered">
        <thead class="table-light">
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Cost</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchaseOrder->purchaseOrderItems as $item)
            <tr>
                <td>{{ $item->inventoryItem->name ?? 'N/A' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>₱{{ number_format($item->unit_price, 2) }}</td>
                <td>₱{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>