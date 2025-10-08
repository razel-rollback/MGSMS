@extends('layouts.app')

@section('content')
<style>
    /* === Dashboard Custom Styles === */
    .card-summary {
        border-radius: 12px;
        background: #fff;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s;
    }
    .card-summary:hover {
        transform: scale(1.03);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* Border color themes */
    .border-sales { border: 2px solid #007bff; }
    .border-revenue { border: 2px solid #28a745; }
    .border-profit { border: 2px solid #17a2b8; }
    .border-cost { border: 2px solid #dc3545; }
    .border-supplies { border: 2px solid #ffc107; }
    .border-categories { border: 2px solid #6c757d; }

    /* Table and card styling */
    .table th, .table td {
        vertical-align: middle;
        text-align: center;
    }
    .low-stock {
        color: red;
        font-weight: bold;
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>

<div class="container-fluid">
    <h2 class="mb-4">Dashboard</h2>

    {{-- Sales Overview --}}
    <h4 class="mb-3">Sales Overview</h4>
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card text-center card-summary border-sales">
                <div class="card-body">
                    <i class="bi bi-cart fs-3 text-primary"></i>
                    <h6 class="mt-2">Sales</h6>
                    <p class="fs-5 fw-bold">{{ $sales ?? 832 }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card text-center card-summary border-revenue">
                <div class="card-body">
                    <i class="bi bi-currency-exchange fs-3 text-success"></i>
                    <h6 class="mt-2">Revenue</h6>
                    <p class="fs-5 fw-bold">₱{{ number_format($revenue ?? 21300) }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card text-center card-summary border-profit">
                <div class="card-body">
                    <i class="bi bi-graph-up-arrow fs-3 text-info"></i>
                    <h6 class="mt-2">Profit</h6>
                    <p class="fs-5 fw-bold">{{ $profit ?? '88%' }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card text-center card-summary border-cost">
                <div class="card-body">
                    <i class="bi bi-cash-coin fs-3 text-danger"></i>
                    <h6 class="mt-2">Cost</h6>
                    <p class="fs-5 fw-bold">₱{{ number_format($cost ?? 17632) }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card text-center card-summary border-supplies">
                <div class="card-body">
                    <i class="bi bi-box-seam fs-3 text-warning"></i>
                    <h6 class="mt-2">Supplies</h6>
                    <p class="fs-5 fw-bold">{{ $supplies ?? 31 }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card text-center card-summary border-categories">
                <div class="card-body">
                    <i class="bi bi-tags fs-3 text-secondary"></i>
                    <h6 class="mt-2">Categories</h6>
                    <p class="fs-5 fw-bold">{{ $categories ?? 6 }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Purchase Overview --}}
    <h4>Purchase Overview</h4>
    <div class="row mb-4">
        <div class="col-md-3"><div class="card border-primary"><div class="card-body fw-bold">Purchase: ₱{{ number_format($purchase ?? 25000) }}</div></div></div>
        <div class="col-md-3"><div class="card border-success"><div class="card-body fw-bold">Cost: ₱{{ number_format($purchaseCost ?? 17432) }}</div></div></div>
        <div class="col-md-3"><div class="card border-danger"><div class="card-body fw-bold">Cancel: ₱{{ number_format($cancel ?? 1000) }}</div></div></div>
        <div class="col-md-3"><div class="card border-warning"><div class="card-body fw-bold">Orders: {{ $orders ?? 51 }}</div></div></div>
    </div>

    {{-- Inventory + Product Summary --}}
    <div class="row mb-4">

        {{-- Inventory Summary --}}
        <div class="col-md-6">
            <h4 class="mb-3">Inventory Summary</h4>
            <div class="d-flex flex-wrap gap-3">
                <div class="card text-center border-primary" style="flex:1; min-width:90px;">
                    <div class="card-body">
                        <h6 class="fw-bold">Quantity in Hand</h6>
                        <p class="fs-4 fw-bold mb-0">{{ $quantityInHand ?? 868 }}</p>
                    </div>
                </div>
                <div class="card text-center border-danger" style="flex:1; min-width:90px;">
                    <div class="card-body">
                        <h6 class="fw-bold">To Reorder</h6>
                        <p class="fs-4 fw-bold mb-0">{{ $toReorder ?? 200 }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Product Summary --}}
        <div class="col-md-6">
            <h4 class="mb-3">Product Summary</h4>
            <div class="d-flex flex-wrap gap-3">
                <div class="card text-center border-success" style="flex:1; min-width:90px;">
                    <div class="card-body">
                        <h6 class="fw-bold">Total Products</h6>
                        <p class="fs-4 fw-bold mb-0">{{ $totalProducts ?? 120 }}</p>
                    </div>
                </div>
                <div class="card text-center border-warning" style="flex:1; min-width:90px;">
                    <div class="card-body">
                        <h6 class="fw-bold">Categories</h6>
                        <p class="fs-4 fw-bold mb-0">{{ $productCategories ?? 15 }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Top Selling + Low Quantity Stock --}}
    <div class="row mb-4">

        {{-- Top Selling --}}
        <div class="col-md-6">
            <h4 class="mb-3 d-flex justify-content-between align-items-center">
                <span>Top Selling Stock</span>
                <a href="{{ route('dashboard.topSelling') }}" class="btn btn-link btn-sm">View All</a>
            </h4>

            <div class="card border-success shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Sold</th>
                                <th>Remaining</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topSelling ?? [] as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td><span class="badge bg-success">{{ $item->sold }}</span></td>
                                    <td>{{ $item->current_stock }}</td>
                                    <td>₱{{ number_format($item->price, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center">No data available</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Low Stock --}}
        <div class="col-md-6">
            <h4 class="mb-3 d-flex justify-content-between align-items-center">
                <span>Low Quantity Stock</span>
                <a href="{{ route('dashboard.lowStock') }}" class="btn btn-link btn-sm">View All</a>
            </h4>

            <div class="card border-danger shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Remaining Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStock ?? [] as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td class="{{ $item->current_stock < 10 ? 'low-stock' : '' }}">
                                        {{ $item->current_stock }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center">No low stock items</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
