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
        /* Buttons */
        .btn-primary {
            background-color: #4499f5ff;
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
            <style>
                /* Sidebar */
                .sidebar {
                    background: linear-gradient(to top, #b2f0f5, #ffffff);
                    min-height: 100vh;
                    display: flex;
                    flex-direction: column;
                    /* stack items vertically */
                    justify-content: space-between;
                    /* push last section to bottom */
                    transition: all 0.3s ease-in-out;

                }

                li {
                    list-style: none;
                }

                .font {
                    font-size: 0.85rem;
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
                    padding: 4px 5px;
                }

                /* Cards */
                .card-summary {
                    border-radius: 12px;
                    box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
                }

                .card-summary h6 {
                    font-weight: bold;
                }

                /* Purchase Order Table */
                .purchase-card {
                    border-radius: 12px;
                    box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
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

                .margin {
                    margin-top: 10px;
                    margin-bottom: 10px;
                }

                /* Responsive Sidebar */
                @media (max-width: 768px) {
                    .sidebar {
                        position: fixed;
                        top: 0;
                        left: -250px;
                        width: 250px;
                        height: 100%;
                        z-index: 1050;
                        overflow-y: auto;
                    }

                    .sidebar.show {
                        left: 0;
                    }

                    .content-wrapper {
                        margin-left: 0 !important;
                    }

                }
            </style>

            <!-- Sidebar -->
            <div id="sidebar" class="col-md-2 sidebar p-3 font">
                <div>
                    <div class="text-center mb-4 d-flex justify-content-center align-items-center">
                        <img src="{{ asset('images/LOGO.jpg') }}" alt="Logo" class="img-fluid" style="max-width:120px;">
                        <div>
                            <button class="btn btn-primary d-md-none m-2" onclick="toggleSidebar()">
                                <i class="bi bi-list"></i> Menu
                            </button>
                        </div>
                    </div>

                    <ul class="nav flex-column gap-3">
                        <li class="nav-item">
                            <a href="{{ route('production.dashboard') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link {{ request()->is('orders*') ? 'active' : '' }}">
                                <i class="bi bi-cart me-2"></i> Product Workflow
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('production.index') }}" class="nav-link {{ request()->is('orders*') ? 'active' : '' }}">
                                <i class="bi bi-send me-2"></i> Requests items
                            </a>
                        </li>
                        <div class="mt-3 mb-5 d-flex flex-column gap-3 font">
                            <hr>
                            <li class="nav-item">
                                <a href="#" class="nav-link"><i class="bi bi-gear"></i> Settings</a>
                            </li>

                            <li class="nav-item">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="nav-link btn btn-link text-decoration-none">
                                        <i class="bi bi-box-arrow-right"></i> Log Out
                                    </button>
                                </form>
                            </li>

                        </div>
                    </ul>
                </div>

            </div>
            <div class="col-md-10 p-2 content-wrapper">
                @include('layouts.header')
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