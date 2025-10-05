@extends('layouts.app')

@section('content')
<div class="col-md-12 p-4 bg-light">

    <form action="{{ route('purchase_order.update', $purchaseOrder->po_id) }}" method="POST" id="purchaseOrderForm" data-existing-items="{{ json_encode($purchaseOrder->purchaseOrderItems) }}">
        @csrf
        @method('PUT')

        <!-- ===================== ORDER DETAILS ===================== -->
        <div class="form-section mb-3 p-3 bg-white rounded shadow-sm">
            <div class="d-flex gap-3">
                <div><a href="{{ route('purchase_order.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-return-left"></i> BACK</a></div>
                <div>
                    <h5 class="mb-3">Edit Purchase Order</h5>
                </div>
            </div>
            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif

            <div></div>
            <div class="row g-3 mt-3">
                <div class="col-md-3">
                    <label for="po_number" class="form-label">PO Number</label>
                    <input type="text" name="po_number" id="po_number" class="form-control" value="{{ $purchaseOrder->po_number }}" readonly>
                </div>

                <div class="col-md-3">
                    <label for="supplier_id" class="form-label">Supplier</label>
                    <select name="supplier_id" id="supplier_id" class="form-select" required>
                        <option value="">Select Supplier</option>
                        @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->supplier_id }}" {{ $purchaseOrder->supplier_id == $supplier->supplier_id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="order_date" class="form-label">Order Date</label>
                    <input type="date" name="order_date" id="order_date" class="form-control" value="{{ $purchaseOrder->order_date->toDateString() }}" readonly>
                </div>

                <div class="col-md-3">
                    <label for="expected_date" class="form-label">Expected Delivery</label>
                    <input type="date" name="expected_date" id="expected_date" class="form-control" value="{{ $purchaseOrder->expected_date->toDateString() }}" required>
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <input type="text" class="form-control" value="{{ ucfirst($purchaseOrder->status) }}" readonly>
                </div>
                <div class="col-md-3">
                    <label for="total_amount" class="form-label">Total Amount</label>
                    <input type="text" class="form-control" value="₱{{ number_format($purchaseOrder->total_amount, 2) }}" readonly>
                </div>
            </div>

        </div>

        <!-- ===================== ADD ITEMS ===================== -->
        <div class="form-section mb-3 p-3 bg-white rounded shadow-sm">

            <h5 class="mt-3">Order Items</h5>
            <div class="row g-3 align-items-end mt-3">
                <div class="col-md-4">
                    <label for="item_id" class="form-label">Item</label>
                    <select id="item_id" class="form-select">
                        <option value="">Select Item</option>
                        @foreach ($items as $item)
                        <option value="{{ $item->item_id }}" data-price="{{ $item->unit_price }}" data-unit="{{ $item->unit }}">
                            {{ $item->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="quantity" class="form-label">Quantity</label>
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

        <!-- ===================== ITEM TABLE ===================== -->
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Order Items</h5>
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="bi bi-save"></i> Update Purchase Order
                </button>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="itemsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Item Name</th>
                                <th>Unit</th>
                                <th>Quantity</th>
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
        const form = document.getElementById('purchaseOrderForm');

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
                    data: 'unit'
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

        // Pre-populate existing items
        const existingItems = JSON.parse(form.dataset.existingItems);
        existingItems.forEach(function(item) {
            const itemName = item.inventory_item.name;
            const unit = item.inventory_item.unit;
            const itemId = item.item_id;
            const quantity = item.quantity;
            const price = parseFloat(item.unit_price);
            const subtotal = parseFloat(item.subtotal);

            total += subtotal;
            addedItems.add(itemId.toString());

            const rowData = {
                name: itemName + `<input type="hidden" name="items[${itemId}][item_id]" value="${itemId}">`,
                unit: unit,
                quantity: quantity + `<input type="hidden" name="items[${itemId}][quantity]" value="${quantity}">`,
                price: '₱' + price.toFixed(2) + `<input type="hidden" name="items[${itemId}][unit_price]" value="${price}">`,
                subtotal: '₱' + subtotal.toFixed(2),
                action: '<button type="button" class="btn btn-link text-danger p-0 remove-item"><i class="bi bi-trash"></i></button>'
            };

            table.row.add(rowData).draw(false);
        });

        totalAmountEl.textContent = '₱' + total.toFixed(2);

        // Autofill price
        itemSelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            unitPriceInput.value = selected.dataset.price || '';
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
            const itemName = selected.text;
            const unit = selected.dataset.unit || '-';
            const price = parseFloat(unitPriceInput.value) || 0;
            const qty = parseInt(qtyInput.value) || 0;

            if (!itemId) return showError('Please select an item.');
            if (qty <= 0) return showError('Please enter a valid quantity.');
            if (price <= 0) return showError('Please enter a valid unit price.');
            if (addedItems.has(itemId)) return showError('This item has already been added.');

            const subtotal = qty * price;
            total += subtotal;

            addedItems.add(itemId);

            const rowData = {
                name: itemName + `<input type="hidden" name="items[${itemId}][item_id]" value="${itemId}">`,
                unit: unit,
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
                if (el.name !== '_token' && el.name !== '_method') el.remove();
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