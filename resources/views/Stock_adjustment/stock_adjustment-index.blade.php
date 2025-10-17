@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Stock Adjustment</h2>
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Top Controls --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        {{-- Action Buttons --}}
        <div class="d-flex gap-2">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#stockAdjustmentAddModal">
                <i class="bi bi-plus-circle"></i> Add New Adjustment
            </button>

            <button class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-funnel"></i> Filters
            </button>
            <button class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-download"></i> Download All
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="stockadjTable" class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Product Name</th>
                            <th>Adjustment Type</th>
                            <th>Quantity
                            <th>Current Stock Level</th>
                            <th>Date</th>
                            <th>Requested By</th>
                            <th>Status</th>
                            <th style="width: 100px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($adjustments as $adjustment)
                        <tr>
                            <td>{{ $adjustment->adjustment_id }}</td>
                            <td>{{ $adjustment->inventoryItem->name }}</td>
                            <td>{{ ucfirst($adjustment->adjustment_type) }}</td>
                            <td>{{ ucfirst($adjustment->quantity) }}</td>
                            <td>{{ $adjustment->inventoryItem->current_stock }}</td>
                            <td>{{ $adjustment->created_at->format('d-m-Y') }}</td>
                            <td>{{ $adjustment->requester->fullname }}</td>
                            <td>
                                @if($adjustment->status === 'Approved')
                                <span class="badge bg-success">Approved</span>
                                @elseif($adjustment->status === 'Disapproved')
                                <span class="badge bg-danger">Disapproved</span>
                                @else
                                <span class="badge bg-warning text-dark">{{ $adjustment->status }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <!-- View button -->
                                <a href="{{ route('stock_adjustments.show', $adjustment->adjustment_id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <!-- Edit button triggers modal -->
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $adjustment->adjustment_id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('stock_adjustments.destroy', $adjustment->adjustment_id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this adjustment?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>

                            <!-- Modal -->
                            <div class="modal fade" id="editModal{{ $adjustment->adjustment_id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $adjustment->adjustment_id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">

                                        <div class="modal-header text-dark" style="background-color: #b2f0f5;">
                                            <h5 class="modal-title" id="editModalLabel{{ $adjustment->adjustment_id }}">Edit Stock Adjustment</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <!-- Pass correct ID to update route -->
                                        <form action="{{ route('stock_adjustments.update', $adjustment->adjustment_id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">

                                                <div class="mb-3">
                                                    <label>Item</label>
                                                    <select name="item_id" class="form-control" required>
                                                        @foreach($items as $item)
                                                        <option value="{{ $item->item_id }}"
                                                            {{ $adjustment->item_id == $item->item_id ? 'selected' : '' }}>
                                                            {{ $item->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Requested By</label>
                                                    <select name="requested_by" class="form-control" required>
                                                        @php
                                                        $uniqueEmployees = $employees->unique('fullname');
                                                        @endphp
                                                        @forelse($uniqueEmployees as $emp)
                                                        <option value="{{ $emp->employee_id }}"
                                                            {{ optional($adjustment)->requested_by == $emp->employee_id ? 'selected' : '' }}>
                                                            {{ $emp->fullname }}
                                                        </option>
                                                        @empty
                                                        <option disabled>No employees found</option>
                                                        @endforelse
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Adjustment Type</label>
                                                    <select name="adjustment_type" class="form-control" required>
                                                        <option value="increase" {{ $adjustment->adjustment_type == 'increase' ? 'selected' : '' }}>Increase</option>
                                                        <option value="decrease" {{ $adjustment->adjustment_type == 'decrease' ? 'selected' : '' }}>Decrease</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Quantity</label>
                                                    <input type="number" name="quantity" class="form-control" value="{{ $adjustment->quantity }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Reason</label>
                                                    <textarea name="reason" class="form-control" required>{{ $adjustment->reason }}</textarea>
                                                </div>


                                            </div>

                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">Update</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                        <tr>
                            <td colspan="9" class="text-center">No stock adjustments found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Add Stock Adjustment Modal --}}
    <div class="modal fade" id="stockAdjustmentAddModal" tabindex="-1" aria-labelledby="stockAdjustmentAddModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="stockAdjustmentAddModalLabel">Add Stock Adjustment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('stock_adjustments.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Item</label>
                            <select name="item_id" class="form-control" required>
                                <option value="">-- Select Item --</option>
                                @foreach($items as $item)
                                <option value="{{ $item->item_id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Requested By</label>
                            <select name="requested_by" class="form-control" required>
                                <option value="">-- Select Employee --</option>
                                @foreach($employees as $emp)
                                <option value="{{ $emp->employee_id }}">{{ $emp->fullname }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Adjustment Type</label>
                            <select name="adjustment_type" class="form-control" required>
                                <option value="increase">Increase</option>
                                <option value="decrease">Decrease</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="quantity" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Reason</label>
                            <textarea name="reason" class="form-control" required></textarea>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>

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
            $('#stockadjTable').DataTable({ // Corrected selector
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