@extends('layouts.app')

@section('content')
<div class="col-md- mx-auto bg-light p-4 rounded shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Stock-In Request Details</h4>
        <a href="{{ route('stock_in.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <form action="{{ route('stock_in.store') }}" method="POST" id="stockInForm">
        @csrf

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Optional PO / DR -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Delivery (optional)</label>
                <select name="delivery_id" class="form-control">
                    <option value="">-- None --</option>
                    @foreach($deliveries as $delivery)
                    <option value="{{ $delivery->delivery_id }}">{{ $delivery->delivery_receipt }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Notes -->
        <div class="mb-3">
            <label>Note / Remarks</label>
            <textarea name="note" class="form-control" placeholder="Place notes"></textarea>
        </div>

        <!-- Item Input Fields -->
        <div class="row mb-3 g-2 align-items-end">
            <div class="col-md-6">
                <label>Item</label>
                <select id="itemSelect" class="form-control">
                    <option value="">-- Select Item --</option>
                    @foreach($items as $item)
                    <option value="{{ $item->item_id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Quantity</label>
                <input type="number" id="itemQty" class="form-control" min="1" value="1">
            </div>
            <div class="col-md-2">
                <label>Unit Price</label>
                <input type="number" id="itemPrice" class="form-control" min="0" step="0.01">
            </div>
            <div class="col-md-2 text-end">
                <button type="button" class="btn btn-success w-100" id="addItemRow">+ Add Item</button>
            </div>
            <!-- Error Container -->
            <div id="errorDiv" class="alert alert-danger d-none" role="alert">
    <!-- Error message will be dynamically inserted here -->
            </div>
        </div>

        <!-- Stock In Items Table -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Stock-In Items</span>
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
                        <!-- Dynamic rows go here -->
                    </tbody>
                </table>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary">Save Stock-In</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    let rowIndex = 0;
    const tableBody = document.querySelector('#stockItemsTable tbody');
    const errorDiv = document.getElementById('errorDiv'); // Reference to the error div

    // Function to clear error messages
    const clearError = () => {
        errorDiv.textContent = ''; // Clear the error message
        errorDiv.classList.add('d-none'); // Hide the error div
    };

    // Add item to table
    document.getElementById('addItemRow').addEventListener('click', function() {
        const itemSelect = document.getElementById('itemSelect');
        const itemId = itemSelect.value;
        const itemName = itemSelect.options[itemSelect.selectedIndex]?.text || '';
        const quantity = document.getElementById('itemQty').value;
        const price = document.getElementById('itemPrice').value;

        // Clear any previous errors
        clearError();

        if (!itemId || !quantity || !price) {
            errorDiv.textContent = 'Please fill out all item fields before adding.';
            errorDiv.classList.remove('d-none'); // Show the error div
            return;
        }

        if (quantity <= 0 || price < 0) {
            errorDiv.textContent = 'Quantity must be greater than 0 and Price cannot be negative.';
            errorDiv.classList.remove('d-none'); // Show the error div
            return;
        }

        // Prevent duplicate items
        const existingItems = Array.from(tableBody.querySelectorAll('input[name^="items"]'))
            .filter(input => input.name.includes('[item_id]'))
            .map(input => input.value);

        if (existingItems.includes(itemId)) {
            errorDiv.textContent = `"${itemName}" has already been added. You can remove it and add it again if needed.`;
            errorDiv.classList.remove('d-none'); // Show the error div
            return;
        }

        // Create a new row
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>
                ${itemName}
                <input type="hidden" name="items[${rowIndex}][item_id]" value="${itemId}">
            </td>
            <td>
                ${quantity}
                <input type="hidden" name="items[${rowIndex}][quantity]" value="${quantity}">
            </td>
            <td>
                ₱${parseFloat(price).toFixed(2)}
                <input type="hidden" name="items[${rowIndex}][unit_price]" value="${price}">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm removeRow">✕</button>
            </td>
        `;

        tableBody.appendChild(newRow);
        rowIndex++;

        // Clear inputs
        itemSelect.value = '';
        document.getElementById('itemQty').value = '1'; // Reset to default value
        document.getElementById('itemPrice').value = '';
    });

    // Remove row
    document.getElementById('stockItemsTable').addEventListener('click', function(e) {
        if (e.target.classList.contains('removeRow')) {
            e.target.closest('tr').remove();
        }
    });

    // Prevent form submission if no items are added
    document.getElementById('stockInForm').addEventListener('submit', function(e) {
        const tableBody = document.querySelector('#stockItemsTable tbody');
        if (tableBody.children.length === 0) {
            e.preventDefault(); // Stop form submission
            errorDiv.textContent = 'Please add at least one item before saving.';
            errorDiv.classList.remove('d-none'); // Show the error div
        }
    });
});
</script>
@endsection