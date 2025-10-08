@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Items</h3>

    <a href="{{ route('items.create') }}" class="btn btn-primary mb-3">Add Item</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Unit</th>
                <th>Reorder Stock</th>
                <th>Current Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->item_id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->unit }}</td>
                    <td>{{ $item->re_order_stock }}</td>
                    <td>{{ $item->current_stock }}</td>
                    <td>
                        <a href="{{ route('items.edit', $item) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('items.destroy', $item) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">No items found.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-3 d-flex justify-content-center">
        {{ $items->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
