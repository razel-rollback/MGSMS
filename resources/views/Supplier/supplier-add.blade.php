@extends('layouts.app')

@section('title', isset($supplier) ? 'Edit Supplier' : 'Add Supplier')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark mb-0">
            {{ isset($supplier) ? 'Edit Supplier' : 'Add New Supplier' }}
        </h2>
        @if(!isset($supplier))
            <a href="{{ route('suppliers.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left-circle me-1"></i> Back to List
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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
        <div class="card-header bg-white border-bottom-0">
            <h5 class="mb-0 fw-semibold">
                {{ isset($supplier) ? 'Update Supplier Details' : 'Enter Supplier Information' }}
            </h5>
        </div>
        <div class="card-body">
            <form 
                action="{{ isset($supplier) ? route('suppliers.update', $supplier->supplier_id) : route('suppliers.store') }}" 
                method="POST"
            >
                @csrf
                @if(isset($supplier))
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold">Supplier Name</label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $supplier->name ?? '') }}" 
                            placeholder="Enter supplier name"
                            required
                        >
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $supplier->email ?? '') }}" 
                            placeholder="Enter supplier email"
                            required
                        >
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label fw-semibold">Phone</label>
                        <input 
                            type="text" 
                            name="phone" 
                            id="phone"
                            class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', $supplier->phone ?? '') }}" 
                            placeholder="Enter supplier phone number"
                            required
                        >
                        @error('phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="address" class="form-label fw-semibold">Address</label>
                        <textarea 
                            name="address" 
                            id="address"
                            class="form-control @error('address') is-invalid @enderror"
                            rows="3" 
                            placeholder="Enter supplier address"
                            required
                        >{{ old('address', $supplier->address ?? '') }}</textarea>
                        @error('address')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success me-2">
                        <i class="bi bi-check-circle me-1"></i>
                        {{ isset($supplier) ? 'Update Supplier' : 'Add Supplier' }}
                    </button>
                    <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection