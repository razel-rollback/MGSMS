@extends('layouts.app')

@section('content')
<div class="col-md-12 p-4 bg-light">

<<<<<<< HEAD
=======
    <!-- Top Bar -->
    <div class="d-flex justify-content-between align-items-center topbar mb-3">
        <!-- Search -->
        <form action="#" method="GET" class="d-flex w-50">
            <input class="form-control me-2" type="search" name="query" placeholder="Search">
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>

        <!-- Notifications + User -->
        <div class="d-flex align-items-center">
            <button class="btn btn-light position-relative me-3">
                <i class="bi bi-bell fs-5"></i>
            </button>
            <img src="{{ asset('images/user.png') }}" class="rounded-circle" width="40" alt="User">
        </div>
    </div>

>>>>>>> jasmine
    <!-- Stock Out Form -->
    <div class="form-section mb-3 p-3 bg-white rounded shadow-sm">
        <h5 class="mb-3">Stock Out Details</h5>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">JO Number</label>
                <input type="text" name="jo_number" class="form-control" value="JO 28694-7965" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label">Customer Name</label>
                <select class="form-select" name="customer_name">
                    <option selected>Andrew Kho</option>
                    <option>Maria Koh</option>
                    <option>James Lee</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Due Date</label>
                <input type="date" name="due_date" class="form-control" value="2025-12-03">
            </div>
            <div class="col-md-3">
                <label class="form-label">Requested By</label>
                <select class="form-select" name="requested_by">
                    <option selected>Maria Koh</option>
                    <option>David Chan</option>
                </select>
            </div>
        </div>

        <!-- Request Item -->
        <h6 class="mt-4">Request Item</h6>
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <select name="item_name" class="form-select">
                    <option value="Mug">Mug</option>
                    <option value="Umbrella">Umbrella</option>
                    <option value="Bag">Bag</option>
                    <option value="Pillow">Pillow</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="quantity" class="form-control" placeholder="Enter Quantity">
            </div>
            <div class="col-md-3">
                <input type="text" name="remarks" class="form-control" placeholder="Souvenir Gift">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-primary w-100">
                    <i class="bi bi-plus-circle"></i> Add
                </button>
            </div>
        </div>
    </div>

    <!-- Request Items Table -->
    <div class="table-section p-3 bg-white rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Request Item</h5>
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
            <!-- Added text-center for centered content -->
            <table class="table table-hover table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th class="fw-bold">Item Name</th>
                        <th class="fw-bold">Quantity</th>
                        <th class="fw-bold">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Mug</td>
                        <td>50</td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>Umbrella</td>
                        <td>40</td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>Bag</td>
                        <td>70</td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>Pillow</td>
                        <td>70</td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
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