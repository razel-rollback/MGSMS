@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Add Item</h3>
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    <form action="{{ route('items.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Unit</label>
            <input type="text" name="unit" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Reorder Stock</label>
            <input type="number" name="re_order_stock" class="form-control" min="0" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Current Stock</label>
            <input type="number" name="current_stock" class="form-control" min="0" required>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('items.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection