@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Stock Adjustment</h2>

    <form action="{{ route('stock_adjustments.update', $stockAdjustment->adjustment_id) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Item</label>
            <select name="item_id" class="form-control" required>
                @foreach($items as $item)
                    <option value="{{ $item->item_id }}" 
                        {{ $stockAdjustment->item_id == $item->item_id ? 'selected' : '' }}>
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
                        {{ $stockAdjustment->requested_by == $emp->employee_id ? 'selected' : '' }}>
                        {{ $emp->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Adjustment Type</label>
            <select name="adjustment_type" class="form-control" required>
                <option value="increase" {{ $stockAdjustment->adjustment_type == 'increase' ? 'selected' : '' }}>Increase</option>
                <option value="decrease" {{ $stockAdjustment->adjustment_type == 'decrease' ? 'selected' : '' }}>Decrease</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Quantity</label>
            <input type="number" name="quantity" class="form-control" value="{{ $stockAdjustment->quantity }}" required>
        </div>

        <div class="mb-3">
            <label>Reason</label>
            <textarea name="reason" class="form-control" required>{{ $stockAdjustment->reason }}</textarea>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="pending" {{ $stockAdjustment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $stockAdjustment->status == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $stockAdjustment->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update</button