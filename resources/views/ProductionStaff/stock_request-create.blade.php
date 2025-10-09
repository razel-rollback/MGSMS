@extends('layouts.production_app')

@section('content')
<div class="container mt-4">
    <h4 class="mb-3">Request Stock Out</h4>

    <form method="POST" action="{{ route('production.store') }}" id="stockOutForm">
        @csrf

        <!-- Item input row -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Select Item</label>
                <!-- NOTE: default option is NOT disabled to avoid browser selection quirks -->
                <select id="itemSelect" class="form-select">
                    <option value="">Choose item</option>
                    @foreach($items as $item)
                    <option value="{{ $item->item_id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Quantity</label>
                <input type="number" id="itemQty" class="form-control" min="1" value="1">
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <button type="button" id="addItemBtn" class="btn btn-success w-100">Add Item</button>
            </div>
        </div>

        <div id="itemError" class="text-danger mb-2" style="display:none;"></div>

        <!-- Dynamic table -->
        <div class="table-responsive">
            <table class="table table-bordered mt-3" id="itemsTable">
                <thead class="table-light">
                    <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="emptyRow">
                        <td colspan="3" class="text-center text-muted">No items added yet</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Submit -->
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Submit Request</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('stockOutForm');
        const addItemBtn = document.getElementById('addItemBtn');
        const itemSelect = document.getElementById('itemSelect');
        const itemQty = document.getElementById('itemQty');
        const itemsTableBody = document.querySelector('#itemsTable tbody');
        const itemError = document.getElementById('itemError');

        // internal array of items: { item_id, name, quantity }
        let addedItems = [];

        // sanitize text for DOM
        function escapeHtml(unsafe) {
            return String(unsafe)
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        function showError(msg) {
            itemError.textContent = msg;
            itemError.style.display = 'block';
        }

        function hideError() {
            itemError.textContent = '';
            itemError.style.display = 'none';
        }

        function renderTable() {
            itemsTableBody.innerHTML = '';

            if (addedItems.length === 0) {
                itemsTableBody.innerHTML = `
                <tr id="emptyRow">
                    <td colspan="3" class="text-center text-muted">No items added yet</td>
                </tr>
            `;
                return;
            }

            addedItems.forEach((it, idx) => {
                const tr = document.createElement('tr');
                tr.dataset.index = idx;

                tr.innerHTML = `
                <td>
                    ${escapeHtml(it.name)}
                    <input type="hidden" name="items[${idx}][item_id]" value="${escapeHtml(it.item_id)}">
                </td>
                <td>
                    ${escapeHtml(it.quantity)}
                    <input type="hidden" name="items[${idx}][quantity]" value="${escapeHtml(it.quantity)}">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm removeBtn" data-index="${idx}">
                        Remove
                    </button>
                </td>
            `;

                itemsTableBody.appendChild(tr);
            });
        }

        addItemBtn.addEventListener('click', function() {
            hideError();

            const itemId = itemSelect.value;
            const qtyRaw = itemQty.value;
            const qty = qtyRaw ? Number(qtyRaw) : 0;

            if (!itemId) {
                showError('Please select an item.');
                return;
            }
            if (!qty || qty <= 0 || !Number.isFinite(qty)) {
                showError('Please enter a valid quantity.');
                return;
            }

            // prevent duplicate by item_id
            if (addedItems.some(i => String(i.item_id) === String(itemId))) {
                showError('This item is already added.');
                return;
            }

            const itemName = itemSelect.options[itemSelect.selectedIndex]?.text || 'N/A';

            // push and re-render
            addedItems.push({
                item_id: itemId,
                name: itemName,
                quantity: qty
            });

            renderTable();

            // reset inputs (select back to default option, qty to 1)
            itemSelect.value = '';
            itemQty.value = 1;
        });

        // delegate remove buttons
        itemsTableBody.addEventListener('click', function(e) {
            const btn = e.target.closest('.removeBtn');
            if (!btn) return;
            const idx = Number(btn.dataset.index);
            if (!Number.isInteger(idx)) return;

            // remove item and re-render (indexes will be regenerated)
            addedItems.splice(idx, 1);
            renderTable();
        });

        // prevent submit if no items
        form.addEventListener('submit', function(e) {
            if (addedItems.length === 0) {
                e.preventDefault();
                showError('Please add at least one item before submitting.');
                return false;
            }
            // hidden inputs are already generated by renderTable(), so submission will send them
        });

        // initial render (show placeholder)
        renderTable();
    });
</script>
@endpush