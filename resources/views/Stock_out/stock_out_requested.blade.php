@extends('layouts.app')

@section('content')
<div class="col-md-12 p-4 bg-light">

    <!-- Action Buttons + Search -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Stock Out Requests</h5>
        <div class="d-flex align-items-center gap-2">

            <!-- Filters -->
            <button class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-funnel"></i> Filters
            </button>

            <!-- Download All -->
            <button class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-download"></i> Download All
            </button>
        </div>
    </div>

    <!-- Stock Out Request Table -->
    <div class="bg-white p-3 rounded shadow-sm">
        <div class="table-responsive">
            <table id="stockInTable" class="table table-hover table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Stock Out ID</th>
                        <th scope="col">Job Order</th>
                        <th scope="col">Requested By</th>
                        <th scope="col">Requested At</th>
                        <th scope="col">Status</th>
                        <th scope="col">Validated By</th>
                        <th scope="col">Approved By</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stockOutRequests as $request)
                    <tr>
                        <td>{{ $request->stock_out_id }}</td>
                        <td>{{ $request->jobOrder ? $request->jobOrder->job_order_id : 'N/A' }}</td>
                        <td>{{ $request->requester ? $request->requester->first_name : 'N/A' }}</td>
                        <td>{{ $request->requested_at->format('Y-m-d H:i:s') }}</td>
                        <td>
                            @php
                            $status = $request->status;
                            $badgeClass = match($status) {
                            'Pending' => 'bg-warning text-dark',
                            'Validated' => 'bg-info text-dark',
                            'Approved' => 'bg-success',
                            'Disapproved' => 'bg-danger',
                            default => 'bg-secondary'
                            };
                            @endphp

                            <span class="badge {{ $badgeClass }}">
                                {{ $status }}
                            </span>
                        </td>

                        <td>{{ $request->validator ? $request->validator->first_name : 'N/A' }}</td>
                        <td>{{ $request->approver ? $request->approver->first_name : 'N/A' }}</td>
                        <td>
                            <a href="{{ route('stock.out.requests.show', $request->stock_out_id) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">No stock out requests found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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
            pageLength: 10,
            lengthMenu: [5, 10, 25],
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