@extends('layouts.app')

@section('content')
<div class="col-md-10 mx-auto bg-light p-4 rounded shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Stock-In Request Details</h4>
        <a href="{{ route('stock_in.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <form action="{{ route('stock_in.update', $stockIn->stock_in_id) }}" method="POST" id="stockInEditForm" data-row-index="{{ count($stockIn->stockInItems) }}">
        @csrf
        @method('PUT')

        <!-- Optional PO / DR -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Delivery (optional)</label>
                <select name="delivery_id" class="form-control">
                    <option value="">-- None --</option>
                    @foreach($deliveries as $delivery)
                    <option value="{{ $delivery->delivery_id }}" {{ $stockIn->delivery_id == $delivery->delivery_id ? 'selected' : '' }}>
                        {{ $delivery->delivery_receipt }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Notes -->
        <div class="mb-3">
            <label>Note / Remarks</label>
            <textarea name="note" class="form-control" placeholder="Place notes">{{ $stockIn->note }}</textarea>
        </div>

        <!-- Stock In Items Table -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Stock-In Items</span>
                <button type="button" class="btn btn-sm btn-success" id="addItemRow">+ Add Item</button>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0" id="stockItemsTable">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th width="15%">Quantity</th>
                            <th width="20%">Unit Price</th>
                            <th width="5%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stockIn->stockInItems as $index => $item)
                        <tr>
                            <td>
                                <select name="items[{{ $index }}][item_id]" class="form-control" required>
                                    <option value="">-- Select Item --</option>
                                    @foreach($items as $inv)
                                    <option value="{{ $inv->item_id }}" {{ $item->item_id == $inv->item_id ? 'selected' : '' }}>
                                        {{ $inv->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" class="form-control" min="1" required>
                            </td>
                            <td>
                                <input type="number" name="items[{{ $index }}][unit_price]" value="{{ $item->unit_price }}" class="form-control" min="0" step="0.01" required>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm removeRow">✕</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary">Update Stock-In</button>
        </div>
    </form>
</div>

@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
    // Initialize row index from Blade data attribute
    const rowIndexInit = document.getElementById('stockInEditForm').dataset.rowIndex;
    let rowIndex = parseInt(rowIndexInit) || 0;

    // Pre-rendered item options for dynamic rows
    const itemOptions = `{!! str_replace("\n", "", addslashes(
        collect($items)->map(fn($inv) => "<option value='{$inv->item_id}'>{$inv->name}</option>")->implode('')
    )) !!}`;

    // Function to check for duplicate items
    function hasDuplicateItems() {
        const selects = document.querySelectorAll('#stockItemsTable tbody select[name^="items"]');
        const selectedItemIds = new Set();

        for (const select of selects) {
            const selectedValue = select.value;
            if (selectedValue) {
                if (selectedItemIds.has(selectedValue)) {
                    return true; // Duplicate found
                }
                selectedItemIds.add(selectedValue);
            }
        }
        return false; // No duplicates
    }

    // Add new item row
    document.getElementById('addItemRow').addEventListener('click', function() {
        const tableBody = document.querySelector('#stockItemsTable tbody');
        const newRow = document.createElement('tr');

        newRow.innerHTML = `
            <td>
                <select name="items[${rowIndex}][item_id]" class="form-control" required>
                    <option value="">-- Select Item --</option>
                    ${itemOptions}
                </select>
            </td>
            <td>
                <input type="number" name="items[${rowIndex}][quantity]" class="form-control" min="1" required>
            </td>
            <td>
                <input type="number" name="items[${rowIndex}][unit_price]" class="form-control" min="0" step="0.01" required>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm removeRow">✕</button>
            </td>
        `;

        tableBody.appendChild(newRow);

        // Attach event listener to the newly added select element
        const newSelect = newRow.querySelector('select');
        newSelect.addEventListener('change', function() {
            if (hasDuplicateItems()) {
                alert('This item is already added. Please select a different item.');
                this.value = ''; // Reset the selection
            }
        });

        rowIndex++;
    });

    // Remove item row
    document.getElementById('stockItemsTable').addEventListener('click', function(e) {
        if (e.target.classList.contains('removeRow')) {
            e.target.closest('tr').remove();
        }
    });

    // Prevent form submission if no items are present
    document.getElementById('stockInEditForm').addEventListener('submit', function(e) {
        const tableBody = document.querySelector('#stockItemsTable tbody');
        if (tableBody.children.length === 0) {
            e.preventDefault(); // Stop form submission
            alert('Please add at least one item before saving.');
        } else if (hasDuplicateItems()) {
            e.preventDefault(); // Stop form submission
            alert('Duplicate items are not allowed. Please resolve the issue before saving.');
        }
    });

    // Attach change event listeners to pre-existing rows
    document.querySelectorAll('#stockItemsTable tbody select[name^="items"]').forEach(select => {
        select.addEventListener('change', function() {
            if (hasDuplicateItems()) {
                alert('This item is already added. Please select a different item.');
                this.value = ''; // Reset the selection
            }
        });
    });
});
</script>
@endpush