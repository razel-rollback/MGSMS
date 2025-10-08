@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Stock Adjustments</h2>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-bordered mb-0 align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Adjustment Type</th>
                        <th>Quantity</th>
                        <th>Date</th>
                        <th>Requested By</th>
                        <th>Status</th>
                        <th style="width: 120px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($adjustments as $adjustment)
                        <tr>
                            <td>{{ $adjustment->adjustment_id }}</td>
                            <td>{{ $adjustment->inventoryItem->name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($adjustment->adjustment_type) }}</td>
                            <td>{{ $adjustment->quantity }}</td>
                            <td>{{ $adjustment->created_at->format('d-m-Y') }}</td>
                            <td>{{ $adjustment->requester->name ?? 'N/A' }}</td>
                            <td class="text-center">
                                @if($adjustment->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($adjustment->status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{-- View Button --}}
                                <a href="{{ route('stock_adjustments.show', $adjustment->adjustment_id) }}" 
                                   class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye"></i>
                                </a>

                                {{-- Edit Button (opens modal) --}}
                                <button class="btn btn-sm btn-outline-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editModal{{ $adjustment->adjustment_id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </td>
                        </tr>

                        {{-- EDIT MODAL --}}
                        <div class="modal fade" id="editModal{{ $adjustment->adjustment_id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $adjustment->adjustment_id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="editModalLabel{{ $adjustment->adjustment_id }}">Edit Stock Adjustment</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

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
                                                    @foreach($employees as $emp)
                                                        <option value="{{ $emp->employee_id }}" 
                                                            {{ $adjustment->requested_by == $emp->employee_id ? 'selected' : '' }}>
                                                            {{ $emp->name }}
                                                        </option>
                                                    @endforeach
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

                                            <div class="mb-3">
                                                <label>Status</label>
                                                <select name="status" class="form-control" required>
                                                    <option value="pending" {{ $adjustment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="approved" {{ $adjustment->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                                    <option value="rejected" {{ $adjustment->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No stock adjustments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
