@extends('layouts.production_app')

@section('content')
<div class="container mt-4">
    <h4 class="mb-3">Edit Stock Out Request</h4>

    <form method="POST" action="{{ route('production.update', $request->stock_out_id) }}" id="stockOutForm">
        @csrf
        @method('PUT')

        {{-- Items Table --}}
        <table class="table table-bordered" id="itemsTable">
            <thead class="table-light">
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                {{-- Preloaded items --}}
                @foreach ($existingItems as $item)
                <tr>
                    <td>
                        <input type="hidden" name="items[{{ $loop->index }}][item_id]" value="{{ $item['item_id'] }}">
                        {{ $item['name'] }}
                    </td>
                    <td>
                        <input type="number" name="items[{{ $loop->index }}][quantity]" value="{{ $item['quantity'] }}" class="form-control" min="1">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-btn">Remove</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
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

        // Add item to the table
        addItemBtn.addEventListener('click', () => {
            const itemId = itemSelect.value;
            const itemName = itemSelect.options[itemSelect.selectedIndex]?.text;
            const quantity = parseInt(itemQty.value);

            // Validate item and quantity
            if (!itemId || !quantity || quantity <= 0) {
                itemError.textContent = 'Please select a valid item and quantity.';
                return;
            }

            // Check if item is already added
            const existingRow = Array.from(itemsTableBody.querySelectorAll('tr')).find(row => {
                return row.querySelector('input[name="items[]"]').value === itemId;
            });

            if (existingRow) {
                itemError.textContent = 'This item is already added.';
                return;
            }

            itemError.textContent = '';

            // Add new row to the table
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <input type="hidden" name="items[]" value="${itemId}">
                    ${itemName}
                </td>
                <td>
                    <input type="number" name="quantities[]" value="${quantity}" class="form-control" min="1">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-btn">Remove</button>
                </td>
            `;
            itemsTableBody.appendChild(row);

            // Clear the item selection and quantity
            itemSelect.value = '';
            itemQty.value = '';
        });

        // Remove item from the table
        itemsTableBody.addEventListener('click', e => {
            if (e.target.classList.contains('remove-btn')) {
                e.target.closest('tr').remove();
            }
        });
    });
</script>
@endsection