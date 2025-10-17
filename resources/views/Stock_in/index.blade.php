@extends('layouts.app')

@section('content')
<div class="col-md-12 p-4 bg-light">

    <!-- ===================== HEADER ===================== -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Stock In Management</h5>
        <a href="{{ route('stock_in.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> New Stock In
        </a>
    </div>

    @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- ===================== TABLE CARD ===================== -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table id="stockInTable" class="table table-bordered table-striped align-middle w-100">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>PO No.</th>
                            <th>Delivery Receipt</th>
                            <th>Supplier</th>
                            <th>Requested At</th>
                            <th>Requested By</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stockIns as $stockIn)
                        <tr>
                            <td>{{ $loop->iteration }}</td> <!-- Better for DataTables -->
                            <td>{{ $stockIn->delivery->purchaseOrder?->po_number ?? '—' }}</td>
                            <td>{{ $stockIn->delivery?->delivery_receipt ?? '—' }}</td>
                            <td>{{ $stockIn->delivery->supplier->name ?? '-'}}</td>
                            <td>{{ $stockIn->requested_at?->format('M d, Y h:i A') ?? '—' }}</td>
                            <td>
                                {{ optional($stockIn->requester)->first_name ? optional($stockIn->requester)->first_name . ' ' . optional($stockIn->requester)->last_name : '—' }}
                            </td>

                            <td>
                                @if ($stockIn->status === 'Approved')
                                <span class="badge bg-success">Approved</span>
                                @elseif ($stockIn->status === 'Pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                <span class="badge bg-danger">{{ ucfirst($stockIn->status) }}</span>
                                @endif
                            </td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('stock_in.show', $stockIn->stock_in_id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('stock_in.edit', $stockIn->stock_in_id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('stock_in.destroy', $stockIn->stock_in_id) }}" method="POST" class="d-inline"> <!-- Fixed route name -->
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No stock-in records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

<script>
    $(document).ready(function() {
        $('#stockInTable').DataTable({ // Corrected selector
            pageLength: 5,
            order: [],
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
            }
        });
    });
</script>
@endpush