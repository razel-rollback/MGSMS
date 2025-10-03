@extends('layouts.app')

@section('content')
<div class="col-md-12 p-4 bg-light">

    <!-- Order Details Form -->
    <form action="#" method="POST">
        <div class="form-section mb-3 p-3 bg-white rounded shadow-sm">
            <h5 class="mb-3">Order Details</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="po_number" class="form-label">PO Number</label>
                    <input type="text" name="po_number" id="po_number" class="form-control" placeholder="Enter">
                </div>
                <div class="col-md-3">
                    <label for="supplier_id" class="form-label">Supplier Name</label>
                    <select name="supplier_id" id="supplier_id" class="form-select">
                        <option value="">Select Supplier</option>
                        <option value="1">Supplier 1</option>
                        <option value="2">Supplier 2</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="order_date" class="form-label">Order Date</label>
                    <input type="date" name="order_date" id="order_date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="delivery_date" class="form-label">Delivery Date</label>
                    <input type="date" name="delivery_date" id="delivery_date" class="form-control">
                </div>
            </div>

            <!-- Order Items -->
            <h6 class="mt-4">Order Items</h6>
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="item_id" class="form-label">Item</label>
                    <select name="item_id" id="item_id" class="form-select">
                        <option value="">Select Item</option>
                        <option value="A">Item A</option>
                        <option value="B">Item B</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" placeholder="0">
                </div>
                <div class="col-md-3">
                    <label for="unit_price" class="form-label">Unit Price</label>
                    <input type="number" name="unit_price" id="unit_price" class="form-control" placeholder="0.00" readonly>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary w-100">
                        <i class="bi bi-plus-circle"></i> Add Item
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Products Table -->
    <div class="table-section p-3 bg-white rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Products</h5>
            <div>
                <button class="btn btn-success btn-sm">
                    <i class="bi bi-save"></i> Save
                </button>
                <button class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-funnel"></i> Filters
                </button>
                <button class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-download"></i> Download All
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
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
                <tbody>
                    <?php
                    /*
                      <!--     @foreach ($purchaseOrderItems as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->unit }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td>${{ number_format($item->quantity * $item->price, 2) }}</td>
                        <td>
                            <form action="{{ route('purchase-order.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger p-0 m-0">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach-->
                    */

                    ?>


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
@endsection