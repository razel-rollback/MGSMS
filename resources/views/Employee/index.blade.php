@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Employee List</h2>
        <a href="{{ route('employees.create') }}" class="btn btn-primary">Add New Employee</a>
    </div>

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $employee)
            <tr>
                <td>{{ $employee->employee_id }}</td>
                <td>{{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }}</td>
                <td>{{ $employee->email }}</td>
                <td>{{ $employee->phone }}</td>
                <td>{{ $employee->is_active ? 'Active' : 'Inactive' }}</td>
                <td>
                    <a href="{{ route('employees.edit', $employee->employee_id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('employees.destroy', $employee->employee_id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">No employees found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $employees->links() }}
    </div>
</div>
@endsection