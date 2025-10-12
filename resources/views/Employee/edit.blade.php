@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Edit Employee</h2>
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>There were some errors with your input:</strong>
        <ul class="mt-2 mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('employees.update', $employee->employee_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" name="first_name" id="first_name"
                    value="{{ old('first_name', $employee->first_name) }}" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="middle_name" class="form-label">Middle Name</label>
                <input type="text" name="middle_name" id="middle_name"
                    value="{{ old('middle_name', $employee->middle_name) }}" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" name="last_name" id="last_name"
                    value="{{ old('last_name', $employee->last_name) }}" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $employee->phone) }}" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email"
                    value="{{ old('email', $employee->email) }}" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="role_id" class="form-label">Role</label>
                <select name="role_id" id="role_id" class="form-select" required>
                    <option value="">Select Role</option>
                    @foreach ($roles as $role)
                    <option value="{{ $role->role_id }}"
                        {{ $employee->user && $employee->user->role_id == $role->role_id ? 'selected' : '' }}>
                        {{ $role->role_name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-12 form-check mb-4">
                <input type="checkbox" name="is_active" id="is_active" class="form-check-input"
                    {{ $employee->is_active ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection