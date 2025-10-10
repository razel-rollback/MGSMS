@extends('layouts.app')

@section('content')
<div class="col-md-12 p-4 bg-light">

    <form action="{{ route('delivery.store') }}" method="POST" id="deliveryForm">
        @csrf
        <!-- ===================== DELIVERY DETAILS ===================== -->
        <div class="form-section mb-3 p-3 bg-white rounded shadow-sm">
            <div class="d-flex gap-3">
                <div><a href="{{ route('delivery.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-return-left"></i> BACK</a></div>
                <div>
                    <h5 class="mb-3">Delivery Details</h5>
                </div>
                @if($errors->any())
                @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
                @endforeach
                @endif
            </div>
            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            <!-- ===================== PURCHASE ORDER SUMMARY ===================== -->

            <div class="row g-3 mt-3">
                <div class="col-4">
                    <!-- ===================== PURCHASE ORDER SUMMARY ===================== -->
                    @if(isset($purchaseOrder))
                    <div class="form-section mb-3 p-3 bg-white rounded shadow-sm">
                        <h5 class="mb-3">Purchase Order Summary</h5>

                        <ul class="row list-unstyled mb-3">
                            <div class="col">
                                <li><strong>PO Number:</strong> {{ $purchaseOrder->po_number }}</li>
                                <li><strong>Supplier:</strong> {{ $purchaseOrder->supplier->name ?? 'N/A' }}</li>
                                <li><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($purchaseOrder->order_date)->format('F d, Y') }}</li>
                            </div>
                            <div class="col">
                                <li><strong>Expected Delivery:</strong> {{ \Carbon\Carbon::parse($purchaseOrder->expected_date)->format('F d, Y') }}</li>
                                <li><strong>Status:</strong>
                                    <span class="badge 
                {{ $purchaseOrder->status == 'Approved' ? 'bg-success' : 
                   ($purchaseOrder->status == 'Pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                        {{ $purchaseOrder->status }}
                                    </span>
                                </li>
                                <li><strong>Total Amount:</strong> ₱{{ number_format($purchaseOrder->total_amount, 2) }}</li>
                            </div>
                        </ul>

                        <h6 class="fw-bold mt-3">Order Items:</h6>
                        <div class="scrollable-items border rounded" style="max-height: 150px; overflow-y: auto;">
                            <ul class="list-group list-group-flush">
                                @foreach ($purchaseOrder->purchaseOrderItems as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $item->inventoryItem->name ?? 'N/A' }}</strong><br>
                                        <small>Quantity: {{ $item->quantity }}</small><br>
                                        <small>Unit Price: ₱{{ number_format($item->unit_price, 2) }}</small>
                                    </div>
                                    <div class="text-end fw-semibold">
                                        ₱{{ number_format($item->quantity * $item->unit_price, 2) }}
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif

                </div>
                <div class="col-8">
                    <div class="row">
                        <div class="col">
                            <label for="delivery_receipt" class="form-label">Delivery Receipt</label>
                            <input type="text" name="delivery_receipt" id="delivery_receipt" class="form-control" placeholder="Enter delivery receipt number" required>
                        </div>

                        <div class="col">
                            <label for="po_id" class="form-label">PO Reference</label>
                            <input type="text" id="po_reference" class="form-control" value="{{ $purchaseOrder->po_number ?? '' }}" readonly>
                            <input type="hidden" name="po_id" value="{{ $purchaseOrder->po_id ?? '' }}">
                        </div>

                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="delivered_date" class="form-label">Delivery Date</label>
                            <input type="date" name="delivered_date" id="delivered_date" class="form-control" value="{{ now()->toDateString() }}" readonly>
                        </div>

                        <div class="col">
                            <label for="delivery_status" class="form-label">Delivery Status</label>
                            <select name="delivery_status" id="delivery_status" class="form-select" required>
                                <option value="">Select Status</option>
                                <option value="Not Delivered">Not Delivered</option>
                                <option value="Partially Delivered">Partially Delivered</option>
                                <option value="Fully Delivered">Fully Delivered</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-section mb-3 p-3 bg-white rounded shadow-sm">
                            <h5 class="mt-3">Delivery Items</h5>
                            <div class="row g-3 align-items-end mt-3">
                                <div class="col-md-4">
                                    <label for="item_id" class="form-label">Item</label>
                                    <select id="item_id" class="form-select">
                                        <option value="">Select Item</option>
                                        @if(isset($purchaseOrder) && $purchaseOrder->purchaseOrderItems)
                                        @foreach ($purchaseOrder->purchaseOrderItems as $item)
                                        <option value="{{ $item->item_id }}" data-quantity="{{ $item->quantity }}">
                                            {{ $item->inventoryItem->name }} (Ordered: {{ $item->quantity }})
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label for="quantity" class="form-label">Delivered Quantity</label>
                                    <input type="number" id="quantity" class="form-control" min="1" value="1">
                                </div>

                                <div class="col-md-2">
                                    <label for="unit_price" class="form-label">Unit Price</label>
                                    <input type="number" id="unit_price" class="form-control" step="0.01">
                                </div>

                                <div class="col-md-2">
                                    <button type="button" id="addItemBtn" class="btn btn-primary w-100">
                                        <i class="bi bi-plus-circle"></i> Add
                                    </button>
                                </div>
                            </div>
                            <div id="itemError" class="text-danger mt-2" style="display: none;"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ===================== ADD ITEMS ===================== -->


        <!-- ===================== ITEM TABLE ===================== -->
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Delivery Items</h5>
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="bi bi-save"></i> Save Delivery
                </button>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="itemsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Item Name</th>
                                <th>Ordered Qty</th>
                                <th>Delivered Qty</th>
                                <th>Unit Price</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr class="fw-bold">
                                <td colspan="4" class="text-end">Total:</td>
                                <td id="totalAmount">₱0.00</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('deliveryForm');

        const table = $('#itemsTable').DataTable({
            paging: true,
            searching: false,
            ordering: false,
            pageLength: 5,
            lengthChange: false,
            autoWidth: false,
            columns: [{
                    data: 'name'
                },
                {
                    data: 'ordered_qty'
                },
                {
                    data: 'quantity'
                },
                {
                    data: 'price'
                },
                {
                    data: 'subtotal'
                },
                {
                    data: 'action'
                }
            ]
        });

        const itemSelect = document.getElementById('item_id');
        const unitPriceInput = document.getElementById('unit_price');
        const qtyInput = document.getElementById('quantity');
        const addItemBtn = document.getElementById('addItemBtn');
        const totalAmountEl = document.getElementById('totalAmount');
        const itemError = document.getElementById('itemError');

        let total = 0;
        let addedItems = new Set();

        // Autofill price and set max quantity
        itemSelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const orderedQty = parseInt(selected.dataset.quantity) || 0;
            unitPriceInput.value = ''; // You might want to get this from the purchase order
            qtyInput.max = orderedQty;
            qtyInput.value = Math.min(1, orderedQty);
            hideError();
        });

        qtyInput.addEventListener('input', hideError);

        function showError(msg) {
            itemError.textContent = msg;
            itemError.style.display = 'block';
        }

        function hideError() {
            itemError.style.display = 'none';
        }

        // Add item
        addItemBtn.addEventListener('click', function() {
            const selected = itemSelect.options[itemSelect.selectedIndex];
            const itemId = selected.value;
            const itemName = selected.text.split(' (Ordered:')[0]; // Remove the ordered quantity part
            const orderedQty = parseInt(selected.dataset.quantity) || 0;
            const price = parseFloat(unitPriceInput.value) || 0;
            const qty = parseInt(qtyInput.value) || 0;

            if (!itemId) return showError('Please select an item.');
            if (qty <= 0) return showError('Please enter a valid quantity.');
            if (qty > orderedQty) return showError('Delivered quantity cannot exceed ordered quantity.');
            if (addedItems.has(itemId)) return showError('This item has already been added.');

            const subtotal = qty * price;
            total += subtotal;

            addedItems.add(itemId);

            const rowData = {
                name: itemName + `<input type="hidden" name="items[${itemId}][item_id]" value="${itemId}">`,
                ordered_qty: orderedQty,
                quantity: qty + `<input type="hidden" name="items[${itemId}][quantity]" value="${qty}">`,
                price: '₱' + price.toFixed(2) + `<input type="hidden" name="items[${itemId}][unit_price]" value="${price}">`,
                subtotal: '₱' + subtotal.toFixed(2),
                action: '<button type="button" class="btn btn-link text-danger p-0 remove-item"><i class="bi bi-trash"></i></button>'
            };

            table.row.add(rowData).draw(false);
            totalAmountEl.textContent = '₱' + total.toFixed(2);

            // Reset inputs
            itemSelect.value = '';
            unitPriceInput.value = '';
            qtyInput.value = '1';
            qtyInput.max = '';
            hideError();
        });

        // Remove item
        $('#itemsTable tbody').on('click', '.remove-item', function() {
            const row = $(this).closest('tr');
            const rowData = table.row(row).data();

            if (rowData) {
                const parser = new DOMParser();
                const nameInput = parser.parseFromString(rowData.name, 'text/html').querySelector('input');
                const itemId = nameInput?.value;

                addedItems.delete(itemId);

                const subtotalText = $(row).find('td:eq(4)').text().replace('₱', '');
                total -= parseFloat(subtotalText) || 0;
                totalAmountEl.textContent = '₱' + total.toFixed(2);

                table.row(row).remove().draw();
            }
        });

        // Handle form submission
        form.addEventListener('submit', function(e) {
            const allData = table.rows().data().toArray();

            if (allData.length === 0) {
                e.preventDefault();
                alert('Please add at least one item.');
                return false;
            }

            // Clear existing hidden inputs
            form.querySelectorAll('input[type="hidden"]').forEach(el => {
                if (el.name !== '_token' && !el.name.includes('po_id')) el.remove();
            });

            // Append all hidden inputs from every DataTable row
            allData.forEach(row => {
                const parser = new DOMParser();
                const nameInput = parser.parseFromString(row.name, 'text/html').querySelector('input');
                const qtyInput = parser.parseFromString(row.quantity, 'text/html').querySelector('input');
                const priceInput = parser.parseFromString(row.price, 'text/html').querySelector('input');

                if (nameInput) form.appendChild(nameInput);
                if (qtyInput) form.appendChild(qtyInput);
                if (priceInput) form.appendChild(priceInput);
            });
        });
    });
</script>

@endpush