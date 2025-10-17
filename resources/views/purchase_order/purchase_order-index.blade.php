@extends('layouts.app')

@section('content')
<div class="col-md-12 p-4 bg-light">

    <!-- Purchase Orders -->
    @session('success')
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endsession
    @session('error')
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endsession

    <div class="card shadow-sm">
        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">Purchase Orders</h5>
            <div class="d-flex flex-wrap gap-2">
                <!-- Search Form -->
                <form method="GET" action="{{ route('purchase_order.index') }}" class="d-flex align-items-center">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search PO or Supplier..."
                        class="form-control form-control-sm me-2" style="min-width: 200px;">
                    <button class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-search"></i>
                    </button>
                </form>

                <!-- Buttons -->
                <a href="{{ route('purchase_order.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Purchase Order
                </a>
                <button class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-funnel"></i> Filters
                </button>
                <button class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-download"></i> Download All
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="card-body p-3 ">
            <div class="table-responsive" style=" min-height: 300px;">
                <table class=" table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Purchase Number</th>
                            <th>Supplier</th>
                            <th>Order Date</th>
                            <th>Delivery Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($purchaseOrders as $po)
                        <tr>
                            <td>{{ $po->po_number }}</td>
                            <td>{{ $po->supplier->name ?? 'N/A' }}</td>
                            <td>{{ $po->order_date ? $po->order_date->format('m-d-Y') : '—' }}</td>
                            <td>{{ $po->expected_date ? \Carbon\Carbon::parse($po->expected_date)->format('m-d-Y') : '—' }}</td>
                            <td>₱{{ number_format($po->total_amount, 2) }}</td>
                            <td>
                                @php
                                $badgeClass = match($po->status) {
                                'Approved' => 'bg-success',
                                'Pending' => 'bg-warning text-dark',
                                'Disapproved' => 'bg-danger',
                                default => 'bg-secondary'
                                };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($po->status) }}</span>
                            </td>
                            <td class="d-flex gap-2">
                                <div>
                                    <a href="javascript:void(0)" class="viewOrderBtn btn btn-sm btn-primary" data-id="{{ $po->po_id }}" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                                <div>
                                    <form action="{{ route('purchase_order.destroy', $po->po_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">No purchase orders found.</td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
                <div class="card-footer d-flex justify-content-center">
                    @if ($purchaseOrders->hasPages())
                    {{ $purchaseOrders->onEachSide(1)->links() }}
                    @else
                    <nav>
                        <ul class="pagination">
                            <li class="page-item disabled"><span class="page-link">1</span></li>
                        </ul>
                    </nav>
                    @endif
                </div>
                <!-- Purchase Order Details Modal -->
            </div>
            <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content shadow-sm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="orderDetailsLabel">Order Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <!-- Order Info -->
                            <div id="orderInfo" class=" row mb-3">

                                <div class="col">
                                    <p><strong>Purchase Order Number:</strong> <span id="po_number"></span></p>
                                    <p><strong>Supplier:</strong> <span id="supplier_name"></span></p>
                                    <p><strong>Order Date:</strong> <span id="order_date"></span></p>
                                </div>
                                <div class="col">
                                    <p><strong>Expected Date:</strong> <span id="expected_date"></span></p>
                                    <p><strong>Total Amount:</strong> ₱<span id="total_amount"></span></p>
                                    <p><strong>Status:</strong> <span id="status" class="badge bg-warning text-dark"></span></p>
                                </div>
                            </div>

                            <hr>

                            <!-- Order Items -->
                            <h6>Order Items:</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Unit</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody id="orderItemsTableBody"></tbody>
                                </table>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <form id="editForm" method="GET" class="my-0 py-0 px-0 mx-0">
                                <input type="hidden" id="po_id_input" name="po_id">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const orderModal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));

        document.querySelectorAll('.viewOrderBtn').forEach(button => {
            button.addEventListener('click', function() {
                const poId = this.dataset.id;

                fetch(`/purchase_order/${poId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Basic Info
                        document.getElementById('editForm').action = `/purchase_order/${data.po_id}/edit`;
                        document.getElementById('po_id_input').value = data.po_id;
                        document.getElementById('supplier_name').textContent = data.supplier?.name ?? 'N/A';
                        document.getElementById('order_date').textContent = new Date(data.order_date).toLocaleDateString();
                        document.getElementById('expected_date').textContent = new Date(data.expected_date).toLocaleDateString();
                        document.getElementById('total_amount').textContent = parseFloat(data.total_amount).toLocaleString();
                        document.getElementById('status').textContent = data.status;

                        // Items
                        const tbody = document.getElementById('orderItemsTableBody');
                        tbody.innerHTML = '';

                        data.purchase_order_items.forEach(item => {
                            const inv = item.inventory_item;
                            const row = `
              <tr>
                <td>${inv?.name ?? 'N/A'}</td>
                <td>${inv?.unit ?? 'pcs'}</td>
                <td>${item.quantity}</td>
                <td>${item.unit_price}</td>
                <td>${item.subtotal}</td>
              </tr>
            `;
                            tbody.insertAdjacentHTML('beforeend', row);
                        });

                        // Show modal
                        orderModal.show();
                    });
            });
        });
    });
</script>
@endpush