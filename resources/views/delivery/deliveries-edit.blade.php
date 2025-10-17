@extends('layouts.app')

@section('content')
<div class="col-md-12 p-4 bg-light">

    <form action="{{ route('delivery.update', $delivery->delivery_id) }}"
        method="POST"
        id="deliveryEditForm"
        data-existing-items='@json($existingItems)'>
        @csrf
        @method('PUT')



        @if($errors->any())
        @foreach ($errors->all() as $error)
        <div>{{ $error }}</div>
        @endforeach
        @endif


        <!-- ===================== PURCHASE ORDER SUMMARY ===================== -->
        <div class="form-section mb-3 p-3 bg-white rounded shadow-sm">
            <div class="d-flex gap-3">
                <div><a href="{{ route('delivery.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-return-left"></i> BACK</a></div>
                <div>
                    <h5 class="mb-3">Delivery Details</h5>
                </div>
            </div>
            @session('error')
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endsession
            @session('success')
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endsession
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
                            <input type="text" name="delivery_receipt" id="delivery_receipt"
                                class="form-control" value="{{ $delivery->delivery_receipt }}" readonly>

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
                                <option value="Not Delivered" {{ $delivery->purchaseOrder->delivery_status == 'Not Delivered' ? 'selected' : '' }}>Not Delivered</option>
                                <option value="Partially Delivered" {{ $delivery->purchaseOrder->delivery_status == 'Partially Delivered' ? 'selected' : '' }}>Partially Delivered</option>
                                <option value="Fully Delivered" {{ $delivery->purchaseOrder->delivery_status == 'Fully Delivered' ? 'selected' : '' }}>Fully Delivered</option>
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
    $(document).ready(function() {
        let existingItems = $('#deliveryEditForm').data('existing-items');
        const tableBody = $('#itemsTable tbody');
        const totalAmountEl = $('#totalAmount');

        // ========================
        // LOAD EXISTING ITEMS
        // ========================
        if (existingItems && existingItems.length > 0) {
            existingItems.forEach(item => {
                let row = `
            <tr data-id="${item.item_id}" data-di-id="${item.di_id ?? ''}">
                <td>${item.item_name}</td>
                <td>${item.ordered_quantity ?? ''}</td>
                <td>${item.quantity}</td>
                <td>₱${parseFloat(item.unit_price).toFixed(2)}</td>
                <td>₱${(item.quantity * item.unit_price).toFixed(2)}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-item" 
                        data-di-id="${item.di_id ?? ''}">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
                <input type="hidden" name="items[${item.item_id}][item_id]" value="${item.item_id}">
                <input type="hidden" name="items[${item.item_id}][quantity]" value="${item.quantity}">
                <input type="hidden" name="items[${item.item_id}][unit_price]" value="${item.unit_price}">
            </tr>`;
                tableBody.append(row);
            });
            updateTotal();
        }

        // ========================
        // ADD ITEM TO TABLE
        // ========================
        $('#addItemBtn').on('click', function() {
            const itemSelect = $('#item_id');
            const itemId = itemSelect.val();
            const itemName = itemSelect.find('option:selected').text().split('(Ordered')[0].trim();
            const orderedQty = itemSelect.find('option:selected').data('quantity');
            const quantity = parseFloat($('#quantity').val());
            const unitPrice = parseFloat($('#unit_price').val());
            const errorDiv = $('#itemError');

            // Validate inputs
            if (!itemId || !quantity || !unitPrice) {
                errorDiv.text('Please select an item, enter quantity, and unit price.').show();
                return;
            }

            // Prevent duplicate items
            if (tableBody.find(`tr[data-id="${itemId}"]`).length > 0) {
                errorDiv.text('This item is already added.').show();
                return;
            }

            errorDiv.hide();

            const subtotal = quantity * unitPrice;
            const newRow = `
        <tr data-id="${itemId}">
            <td>${itemName}</td>
            <td>${orderedQty.toString()}</td>
            <td>${quantity}</td>
            <td>₱${unitPrice.toFixed(2)}</td>
            <td>₱${subtotal.toFixed(2)}</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-item">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
            <input type="hidden" name="items[${itemId}][item_id]" value="${itemId}">
            <input type="hidden" name="items[${itemId}][quantity]" value="${quantity}">
            <input type="hidden" name="items[${itemId}][unit_price]" value="${unitPrice}">
        </tr>`;

            tableBody.append(newRow);
            updateTotal();

            // Reset fields
            itemSelect.val('');
            $('#quantity').val('1');
            $('#unit_price').val('');
        });

        // ========================
        // DELETE ITEM
        // ========================
        $(document).on('click', '.remove-item', function() {
            const row = $(this).closest('tr');
            const diId = $(this).data('di-id');

            if (!diId) {
                row.remove();
                updateTotal();
                return;
            }

            if (confirm('Are you sure you want to delete this delivery item?')) {
                $.ajax({
                    url: `/delivery-item/${diId}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            row.remove();
                            updateTotal();
                        }
                    },
                    error: function() {
                        alert('Error deleting item. Please try again.');
                    }
                });
            }
        });

        // ========================
        // UPDATE TOTAL
        // ========================
        function updateTotal() {
            let total = 0;
            $('#itemsTable tbody tr').each(function() {
                const subtotalText = $(this).find('td:nth-child(5)').text().replace(/[₱,]/g, '');
                total += parseFloat(subtotalText) || 0;
            });
            totalAmountEl.text(`₱${total.toFixed(2)}`);
        }
    });
</script>
@endpush