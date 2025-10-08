<div class="p-3" style="max-height: 400px; overflow-y: auto;">
    <h6><strong>Delivery Receipt:</strong> {{ $delivery->delivery_receipt }}</h6>
    <p><strong>Supplier:</strong> {{ $delivery->supplier->name ?? 'N/A' }}</p>
    <p><strong>PO Reference:</strong> {{ $delivery->purchaseOrder->po_number ?? 'N/A' }}</p>
    <p><strong>Delivered Date:</strong> {{ \Carbon\Carbon::parse($delivery->delivered_date)->format('M d, Y') }}</p>
    <p><strong>Status:</strong> {{ $delivery->status }}</p>

    <hr>
    <h6><strong>Delivered Items:</strong></h6>
    <ul class="list-group list-group-flush">
        @forelse ($delivery->deliveryItems as $item)
        <li class="list-group-item d-flex justify-content-between align-items-start">
            <div>
                <strong>{{ $item->inventoryItem->name ?? 'N/A' }}</strong><br>
                Qty: {{ $item->quantity }}<br>
                Unit: ₱{{ number_format($item->unit_price, 2) }}
            </div>
            <span class="fw-semibold">
                ₱{{ number_format($item->quantity * $item->unit_price, 2) }}
            </span>
        </li>
        @empty
        <li class="list-group-item text-muted text-center">No items found.</li>
        @endforelse
    </ul>
</div>