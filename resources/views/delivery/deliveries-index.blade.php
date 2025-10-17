@extends('layouts.app')

@section('content')
<div class="col-md-12 p-4 bg-light">
    <!-- ===================== DELIVERIES HEADER ===================== -->
    <div class="form-section mb-3 p-3 bg-white rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Delivery Management</h5>
                <p class="text-muted mb-0">Manage purchase order deliveries and track delivery status</p>
            </div>
            <div>
                <button class="btn btn-outline-secondary btn-sm me-2">
                    <i class="bi bi-funnel"></i> Filters
                </button>
                <button class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-download"></i> Export
                </button>
            </div>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- ===================== PENDING DELIVERIES ===================== -->
    <div class="form-section mb-4 p-3 bg-white rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">
                <i class="bi bi-clock-history me-2"></i>Pending Deliveries
            </h5>
            <span class="badge bg-warning text-dark">{{ $deliveries->total() }} pending</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>PO Number</th>
                        <th>Supplier</th>
                        <th>Order Date</th>
                        <th>Expected Delivery</th>
                        <th>Status</th>
                        <th>Total Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($deliveries as $delivery)
                    <tr>
                        <td>
                            <strong>{{ $delivery->po_number }}</strong>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="me-2">
                                    <i class="bi bi-building text-primary"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $delivery->supplier->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($delivery->order_date)->format('M d, Y') }}</td>
                        <td>
                            <span class="badge 
                                {{ \Carbon\Carbon::parse($delivery->expected_date)->isPast() ? 'bg-danger' : 
                                   (\Carbon\Carbon::parse($delivery->expected_date)->isToday() ? 'bg-warning text-dark' : 'bg-info') }}">
                                {{ \Carbon\Carbon::parse($delivery->expected_date)->format('M d, Y') }}
                            </span>
                        </td>
                        <td>
                            <span class="badge 
                                {{ $delivery->status == 'Approved' ? 'bg-success' : 
                                   ($delivery->status == 'Pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                {{ $delivery->status }}
                            </span>
                            <br>
                            <small class="text-muted">
                                {{ $delivery->delivery_status ?? 'Not Delivered' }}
                            </small>
                        </td>
                        <td>
                            <span class="fw-bold text-success">₱{{ number_format($delivery->total_amount, 2) }}</span>
                        </td>
                        <td>
                            <div class="btn-group d-flex gap-3" role="group">
                                <button class="btn btn-sm btn-primary view-details-btn"
                                    data-id="{{ $delivery->po_id }}" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <a href="{{ route('delivery.create', ['po_id' => $delivery->po_id]) }}"
                                    class="btn btn-sm btn-success" title="Create Delivery">
                                    <i class="bi bi-plus-circle"></i> Add
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                <p class="mb-0">No pending deliveries found</p>
                                <small>All purchase orders have been delivered</small>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer d-flex justify-content-center">
            @if ($deliveries->hasPages())
            {{ $deliveries->onEachSide(1)->links() }}
            @else
            <nav>
                <ul class="pagination">
                    <li class="page-item disabled"><span class="page-link">1</span></li>
                </ul>
            </nav>
            @endif
        </div>
    </div>

    <!-- ===================== DELIVERED ITEMS ===================== -->
    <div class="form-section p-3 bg-white rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">
                <i class="bi bi-check-circle-fill me-2 text-success"></i>Delivered
            </h5>
            <span class="badge bg-success">{{ $delivered->total() }} delivered</span>
        </div>

        <!-- Search and Filters -->
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="search" class="form-control" placeholder="Search deliveries..." id="searchInput">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Disapproved">Disapproved</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control" id="dateFilter" placeholder="Filter by date">
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" id="clearFilters">
                    <i class="bi bi-x-circle"></i> Clear
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle" id="deliveredTable">
                <thead class="table-light">
                    <tr>
                        <th>Delivery Receipt</th>
                        <th>Supplier</th>
                        <th>PO Reference</th>
                        <th>Delivery Date</th>
                        <th>Status</th>
                        <th>Received By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($delivered as $delivery)
                    <tr>
                        <td>
                            <strong class="text-primary">{{ $delivery->delivery_receipt }}</strong>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="me-2">
                                    <i class="bi bi-building text-secondary"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $delivery->supplier->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $delivery->purchaseOrder->po_number ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-semibold">{{ \Carbon\Carbon::parse($delivery->delivered_date)->format('M d, Y') }}</span>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($delivery->delivered_date)->format('h:i A') }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge 
                                {{ $delivery->status == 'Approved' ? 'bg-success' :
                                   ($delivery->status == 'Disapproved' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                {{ $delivery->status }}
                            </span>
                        </td>
                        <td>
                            @if($delivery->receivedBy)
                            <div class="d-flex align-items-center">
                                <div class="me-2">
                                    <i class="bi bi-person-circle text-primary"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $delivery->receivedBy->first_name }} {{ $delivery->receivedBy->last_name }}</div>
                                    @if($delivery->received_at)
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($delivery->received_at)->format('M d, Y h:i A') }}</small>
                                    @endif
                                </div>
                            </div>
                            @else
                            <span class="text-muted">Not specified</span>
                            @endif
                        </td>
                        <td class="d-flexgap-3">
                            <button class="btn btn-sm btn-primary view-delivered-btn"
                                data-id="{{ $delivery->delivery_id }}" title="View Details">
                                <i class="bi bi-eye"></i>
                            </button>
                            <a href="{{ route('delivery.edit', $delivery->delivery_id) }}"
                                class="btn btn-sm btn-warning" title="Edit Delivery">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="{{ route('delivery.destroy', $delivery->delivery_id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete Delivery">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-truck display-4 d-block mb-2"></i>
                                <p class="mb-0">No delivered items found</p>
                                <small>Create deliveries to see them here</small>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        <div class="card-footer d-flex justify-content-center">
            @if ($delivered->hasPages())
            {{ $delivered->onEachSide(1)->links() }}
            @else
            <nav>
                <ul class="pagination">
                    <li class="page-item disabled"><span class="page-link">1</span></li>
                </ul>
            </nav>
            @endif
        </div>
    </div>
</div>

<!-- ===================== MODALS ===================== -->

<!-- Purchase Order Details Modal -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewDetailsLabel">
                    <i class="bi bi-file-text me-2"></i>Purchase Order Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="modal-content-placeholder">
                    <div class="text-center my-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">Loading details...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delivered Details Modal -->
<div class="modal fade" id="viewDeliveredModal" tabindex="-1" aria-labelledby="viewDeliveredLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="viewDeliveredLabel">
                    <i class="bi bi-truck me-2"></i>Delivery Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="delivered-modal-content">
                <div class="text-center my-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">Loading details...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
    }

    .badge {
        font-size: 0.75em;
    }

    .btn-group .btn {
        border-radius: 0.375rem;
    }

    .btn-group .btn:not(:last-child) {
        margin-right: 2px;
    }

    .form-section {
        border-left: 4px solid #0d6efd;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Purchase Order Details Modal
        const modal = new bootstrap.Modal(document.getElementById('viewDetailsModal'));
        const modalBody = document.getElementById('modal-content-placeholder');

        document.querySelectorAll('.view-details-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;

                modalBody.innerHTML = `
                    <div class="text-center my-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">Loading details...</p>
                    </div>`;

                fetch(`/modal/${id}`)
                    .then(response => response.text())
                    .then(html => {
                        modalBody.innerHTML = html;
                    })
                    .catch(() => {
                        modalBody.innerHTML = `
                            <div class="alert alert-danger text-center">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Failed to load details.
                            </div>`;
                    });

                modal.show();
            });
        });

        // Delivered Details Modal
        const deliveredModal = new bootstrap.Modal(document.getElementById('viewDeliveredModal'));
        const deliveredBody = document.getElementById('delivered-modal-content');

        document.querySelectorAll('.view-delivered-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;

                deliveredBody.innerHTML = `
                    <div class="text-center my-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">Loading details...</p>
                    </div>`;

                fetch(`/delivery/modal/${id}`)
                    .then(response => response.text())
                    .then(html => {
                        deliveredBody.innerHTML = html;
                    })
                    .catch(() => {
                        deliveredBody.innerHTML = `
                            <div class="alert alert-danger text-center">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Failed to load details.
                            </div>`;
                    });

                deliveredModal.show();
            });
        });

        // Search and Filter Functionality
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const dateFilter = document.getElementById('dateFilter');
        const clearFilters = document.getElementById('clearFilters');
        const deliveredTable = document.getElementById('deliveredTable');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value.toLowerCase();
            const dateValue = dateFilter.value;

            const rows = deliveredTable.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const deliveryReceipt = cells[0].textContent.toLowerCase();
                const supplier = cells[1].textContent.toLowerCase();
                const poReference = cells[2].textContent.toLowerCase();
                const deliveryDate = cells[3].textContent.toLowerCase();
                const status = cells[4].textContent.toLowerCase();

                const matchesSearch = deliveryReceipt.includes(searchTerm) ||
                    supplier.includes(searchTerm) ||
                    poReference.includes(searchTerm);

                const matchesStatus = !statusValue || status.includes(statusValue);

                const matchesDate = !dateValue || deliveryDate.includes(dateValue);

                if (matchesSearch && matchesStatus && matchesDate) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        searchInput.addEventListener('input', filterTable);
        statusFilter.addEventListener('change', filterTable);
        dateFilter.addEventListener('input', filterTable);

        clearFilters.addEventListener('click', function() {
            searchInput.value = '';
            statusFilter.value = '';
            dateFilter.value = '';
            filterTable();
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
@endpush