@extends('layouts.production_app')

@section('content')
<div class="container mt-4">
    <h4 class="mb-3">Edit Stock Out Request</h4>

    <form method="POST" action="{{ route('production.update', $request->stock_out_id) }}" id="stockOutForm">
        @csrf
        @method('PUT')

        {{-- Item selection --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Select Item</label>
                <select id="itemSelect" class="form-select">
                    <option value="">-- Select Item --</option>
                    @foreach ($items as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Quantity</label>
                <input type="number" id="itemQty" class="form-control" min="1">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" id="addItemBtn" class="btn btn-success w-100">Add</button>
            </div>
        </div>

        <p id="itemError" class="text-danger small"></p>

        {{-- Items Table --}}
        <table class="table table-bordered" id="itemsTable">
            <thead class="table-light">
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Update Request</button>
            <a href="{{ route('production.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const itemSelect = document.getElementById('itemSelect');
        const itemQty = document.getElementById('itemQty');
        const addItemBtn = document.getElementById('addItemBtn');
        const itemsTableBody = document.querySelector('#itemsTable tbody');
        const itemError = document.getElementById('itemError');

        // ✅ Laravel data (preloaded safely)
        const addedItems = @json($existingItems ?? []);

        // Render preloaded items
        addedItems.forEach(item => addRow(item));

        function addRow(item) {
            const row = document.createElement('tr');
            row.innerHTML = `
            <td>
                <input type="hidden" name="items[]" value="${item.item_id}">
                ${item.name}
            </td>
            <td>
                <input type="number" name="quantities[]" value="${item.quantity}" class="form-control" min="1">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-btn">Remove</button>
            </td>
        `;
            itemsTableBody.appendChild(row);
        }

        addItemBtn.addEventListener('click', () => {
            const itemId = itemSelect.value;
            const itemName = itemSelect.options[itemSelect.selectedIndex].text;
            const quantity = parseInt(itemQty.value);

            if (!itemId || !quantity || quantity <= 0) {
                itemError.textContent = 'Please select a valid item and quantity.';
                return;
            }

            itemError.textContent = '';
            const newItem = {
                item_id: itemId,
                name: itemName,
                quantity
            };
            addRow(newItem);
            addedItems.push(newItem);

            itemSelect.value = '';
            itemQty.value = '';
        });

        itemsTableBody.addEventListener('click', e => {
            if (e.target.classList.contains('remove-btn')) {
                e.target.closest('tr').remove();
            }
        });
    });
</script>
@endsection