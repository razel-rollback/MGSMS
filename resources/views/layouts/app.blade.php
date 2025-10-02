<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Inventory System')</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* Sidebar */
        .sidebar {
            background: linear-gradient(to top, #b2f0f5, #ffffff);
            min-height: 100vh;
        }
        .sidebar .nav-link {
            color: #333;
            font-weight: 500;
        }
        .sidebar .nav-link.active {
            color: #007bff;
            font-weight: 600;
        }

        /* Top bar */
        .topbar {
            background: #b2f0f5;
            border-radius: 8px;
            padding: 10px 15px;
        }

        /* Cards */
        .card-summary {
            border-radius: 12px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
        }
        .card-summary h6,
        .card-summary h4 {
            font-weight: bold;
        }

        /* Purchase Order Table */
        .purchase-card {
            border-radius: 12px;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
        }
        .table thead {
            background: #f8f9fa;
        }
        .badge-pending {
            background-color: transparent;
            color: #ff9800;
            font-weight: bold;
        }

        /* Buttons */
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-outline-secondary {
            border-color: #bbb;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            {{-- Sidebar --}}
            @include('layouts.sidebar')

            {{-- Page Content --}}
            <div class="col-md-10 p-4">
                <div class="topbar mb-4">
                    <h4>@yield('title')</h4>
                </div>

                @yield('content')
            </div>
        </div>
    </div>

    {{-- Bootstrap Bundle with Popper --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- For page-specific scripts --}}
    @stack('scripts')
</body>
</html>
