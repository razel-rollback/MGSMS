@extends('layouts.app')

@section('content')
<div class="container mt-4">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h4 class="mb-3">Request Stock Out</h4>

    <form method="POST" action="{{ route('production.requestStock') }}">
        @csrf
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Select Item</label>
                <select name="item_id" class="form-select" required>
                    <option value="" disabled selected>Choose item</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" min="1" required>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Request</button>
            </div>
        </div>
    </form>

    <h5 class="mt-4">My Stock Out Requests</h5>
    <table class="table table-bordered mt-2">
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Requested At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requests as $request)
                <tr>
                    <td>{{ $request->item->name ?? 'N/A' }}</td>
                    <td>{{ $request->quantity }}</td>
                    <td>{{ ucfirst($request->status) }}</td>
                    <td>{{ $request->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No requests yet</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection