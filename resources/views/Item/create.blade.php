@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-dark">Add New Item</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <form action="{{ route('items.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="id" class="form-label fw-semibold">Item ID</label>
                        <input type="text" id="id" class="form-control" value="Auto-generated" disabled>
                    </div>

                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold">Item Name</label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="Enter item name" required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="price" class="form-label fw-semibold">Price</label>
                        <input type="number" step="0.01" name="price" id="price"
                            class="form-control @error('price') is-invalid @enderror"
                            value="{{ old('price') }}" placeholder="₱0.00" required>
                        @error('price')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="stock" class="form-label fw-semibold">Stock</label>
                        <input type="number" name="stock" id="stock"
                            class="form-control @error('stock') is-invalid @enderror"
                            value="{{ old('stock') }}" placeholder="0" required>
                        @error('stock')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="unit" class="form-label fw-semibold">Unit</label>
                        <input type="text" name="unit" id="unit"
                            class="form-control @error('unit') is-invalid @enderror"
                            value="{{ old('unit', 'pcs') }}" placeholder="e.g. pcs, box, kg" required>
                        @error('unit')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Optional item details">{{ old('description') }}</textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success me-2">
                        <i class="bi bi-check-circle me-1"></i> Add Item
                    </button>
                    <a href="{{ route('items.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection