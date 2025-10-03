@extends('layouts.app')

@section('content')
<div class="col-md-12 p-4 bg-light">


    <!-- Delivery Details -->
    <div class="form-section mb-4 p-3 bg-white rounded shadow-sm">
        <h5 class="mb-3">Delivery Details</h5>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Delivery Receipts</label>
                <input type="text" id="delivery_receipts" class="form-control" placeholder="Enter">
            </div>
            <div class="col-md-3">
                <label class="form-label">Delivery Date</label>
                <input type="date" id="delivery_date" class="form-control" value="2025-12-14">
            </div>
            <div class="col-md-3">
                <label class="form-label">PO Reference</label>
                <input type="text" id="po_reference" class="form-control" value="PO-232424">
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <input type="text" id="status" class="form-control" placeholder="Status">
            </div>
        </div>

        <!-- Order Items -->
        <h6 class="mt-4">Order Items</h6>
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <select id="item" class="form-select">
                    <option value="">Select Item</option>
                    <option value="Mug">Mug</option>
                    <option value="Plate">Plate</option>
                    <option value="Spoon">Spoon</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" id="quantity" class="form-control" placeholder="Quantity">
            </div>
            <div class="col-md-3">
                <input type="number" id="unit_price" class="form-control" placeholder="Unit Price">
            </div>
            <div class="col-md-2">
                <button type="button" id="addItem" class="btn btn-primary w-100">Add Item</button>
            </div>
        </div>
    </div>

    <!-- Request Item Table -->
    <div class="table-section p-3 bg-white rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Request Item</h5>
            <div class="d-flex gap-2">
                <form class="d-flex" role="search">
                    <input class="form-control form-control-sm me-2" type="search" placeholder="Search">
                    <button class="btn btn-light btn-sm" type="submit"><i class="bi bi-search"></i></button>
                </form>
                <button class="btn btn-success btn-sm">Save</button>
                <button class="btn btn-outline-secondary btn-sm">Filters</button>
                <button class="btn btn-outline-secondary btn-sm">Download all</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle" id="requestTable">
                <thead class="table-light">
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dynamic rows go here -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <button class="btn btn-outline-secondary btn-sm">Previous</button>
            <small>Page 1 of 10</small>
            <button class="btn btn-outline-secondary btn-sm">Next</button>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    document.getElementById("addItem").addEventListener("click", function() {
        let item = document.getElementById("item").value;
        let qty = document.getElementById("quantity").value;
        let table = document.getElementById("requestTable").getElementsByTagName("tbody")[0];

        if (item && qty > 0) {
            let newRow = `
                <tr>
                    <td>${item}</td>
                    <td>${qty}</td>
                    <td>
                        <a href="#" class="text-danger" onclick="this.closest('tr').remove()">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
            `;
            table.insertAdjacentHTML("beforeend", newRow);

            // Clear inputs
            document.getElementById("item").value = "";
            document.getElementById("quantity").value = "";
            document.getElementById("unit_price").value = "";
        } else {
            alert("Please select an item and enter a valid quantity.");
        }
    });

    // Dummy button alerts
    document.querySelector(".btn-success").addEventListener("click", () => alert("✅ Saved successfully! (Demo only)"));
    document.querySelector(".btn-outline-secondary:nth-of-type(1)").addEventListener("click", () => alert("🔍 Filters coming soon..."));
    document.querySelector(".btn-outline-secondary:nth-of-type(2)").addEventListener("click", () => alert("⬇️ Download feature coming soon..."));
</script>
@endsection