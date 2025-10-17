@extends('layouts.app')

@push('styles')
<style>
    .table-responsive {
        max-height: 600px;
    }

    .low-stock {
        background-color: #ffcccc;
    }

    .high-urgency {
        background-color: #ff6666;
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container mt-4">
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Stock Report - {{ ucfirst(str_replace('_', ' ', $reportType)) }}</h2>
        </div>
        <div class="mt-3">
            <a href="{{ route('reports.stock') }}" class="btn btn-primary">Back to Reports</a>
            <span class="ms-3 text-muted">Generated on: {{ now()->format('Y-m-d H:i:s') }}</span>
        </div>
    </div>



    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    @foreach($columns as $key => $label)
                    <th>{{ $label }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                <tr class="{{ 
                        isset($row['status']) && $row['status'] == 'Low Stock' ? 'low-stock' : 
                        (isset($row['urgency']) && $row['urgency'] == 'High' ? 'high-urgency' : '') 
                    }}">
                    @foreach($columns as $key => $label)
                    <td>{{ $row[$key] ?? 'N/A' }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection

@push('scripts')
<!-- jQuery (required by DataTables) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables JS + CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<!-- DataTables Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<!-- Buttons HTML5 export -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<!-- PDF export -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<!-- JSZip for Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script>
    $(document).ready(function() {
        $('table').DataTable({
            pageLength: 10, // show 10 rows per page
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            lengthMenu: [5, 10, 25, 50, 100],
            ordering: true, // enable column sorting
            searching: true, // enable search box
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries per page",
                info: "Showing _START_ to _END_ of _TOTAL_ records",
                paginate: {
                    previous: "&laquo;",
                    next: "&raquo;"
                }
            }
        });
    });
</script>
@endpush