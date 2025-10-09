@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h5 class="mb-4 fw-bold">Stock Out Details</h5>

    <form action="{{ route('production.stockRequest.store') }}" method="POST">
        @csrf

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label">JO Number</label>
                <input type="text" name="jo_number" class="form-control" value="JO {{ rand(10000,99999) }}-{{ rand(1000,9999) }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Customer Name</label>
                <input type="text" name="customer_name" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Due Date</label>
                <input type="date" name="due_date" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Requested By</label>
                <input type="text" name="requested_by" class="form-control" value="{{ Auth::user()->name }}" readonly>
            </div>
        </div>

        <h6 class="fw-bold mb-3">Request Item</h6>
        <div class="row g-2 align-items-end mb-3">
            <div class="col-md-4">
                <label class="form-label">Item</label>
                <select class="form-select" name="item_id">
                    @foreach($items as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" min="1" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Souvenir Gift</label>
                <input type="text" name="gift" class="form-control">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-plus-circle"></i> Add
                </button>
            </div>
        </div>
    </form>

    <div class="bg-white p-3 rounded shadow-sm mt-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0 fw-bold">Request Item Table</h6>
            <input type="search" class="form-control form-control-sm w-25" placeholder="Search">
        </div>

        <table class="table table-bordered table-hover text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Mug</td>
                    <td>50</td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>Umbrella</td>
                    <td>80</td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>Bag</td>
                    <td>70</td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>Pillow</td>
                    <td>40</td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="d-flex justify-content-end gap-2 mt-3">
            <button class="btn btn-success btn-sm"><i class="bi bi-save"></i> Save</button>
            <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-funnel"></i> Filters</button>
            <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-download"></i> Download All</button>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <button class="btn btn-outline-secondary btn-sm">Previous</button>
            <small>Page 1 of 10</small>
            <button class="btn btn-outline-secondary btn-sm">Next</button>
        </div>
    </div>
</div>
@endsection