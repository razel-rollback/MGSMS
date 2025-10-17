@extends('layouts.production_app')

@section('content')
<div class="container mt-4">

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h5 class="mb-0">My Stock Out Requests</h5>
        <a href="{{ route('production.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Create Request
        </a>
    </div>

    <div class="table-responsive">
        <table id="stockInTable" class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>Items</th>
                    <th>Total Quantity</th>
                    <th>Status</th>
                    <th>Requested At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $request)
                <tr>
                    <td>
                        <div class="scrollable-items border rounded" style="max-height: 120px; overflow-y: auto;">
                            <ul class="list-group list-group-flush">
                                @foreach($request->stockOutItems as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $item->inventoryItem->name ?? 'N/A' }} </strong><br>
                                        <small>Quantity: {{ $item->quantity }}</small><br>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                    </td>
                    <td>{{ $request->stockOutItems->sum('quantity') }}</td>
                    <td>
                        <span class="badge 
                                @if($request->status == 'Approved') bg-success
                                @elseif($request->status == 'Pending') bg-warning text-dark
                                @elseif($request->status == 'Disaaprove') bg-danger
                                @else bg-secondary @endif">
                            {{ ucfirst($request->status) }}
                        </span>
                    </td>
                    <td>{{ $request->created_at?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('production.edit', $request->stock_out_id) }}" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                        <form action="{{ route('production.destroy', $request->stock_out_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this stock request?');" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No requests yet</td>
                </tr>
                @endforelse
            </tbody>
        </table>
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