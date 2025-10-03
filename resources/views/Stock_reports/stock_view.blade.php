@extends('layouts.app')

@section('content')>
<div class="col-md-12 p-4 bg-light">

   <!-- Top Bar -->
   <div class="d-flex justify-content-between align-items-center topbar mb-3">
       <!-- Search -->
       <form action="#" method="GET" class="d-flex w-50">
           <input class="form-control me-2" type="search" name="query" placeholder="Search">
           <button class="btn btn-primary" type="submit">
               <i class="bi bi-search"></i>
           </button>
       </form>

       <!-- Notifications + User -->
       <div class="d-flex align-items-center">
           <button class="btn btn-light position-relative me-3">
               <i class="bi bi-bell fs-5"></i>
           </button>
           <img src="{{ asset('images/user.png') }}" class="rounded-circle" width="40" alt="User">
       </div>
   </div>
            <!-- Stock Report Card -->
            <div class="bg-white p-4 rounded shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Stock Report</h5>
                    <div class="d-flex gap-2">
                        <!-- Search inside card -->
                        <form action="#" method="GET" class="d-flex">
                            <div class="input-group input-group-sm">
                                <input type="search" name="query" class="form-control border-primary" placeholder="Search">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>

                        <!-- Filters -->
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-funnel"></i> Filters
                        </button>

                        <!-- Download All -->
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-download"></i> Download All
                        </button>
                    </div>
                </div>

                <!-- Stock Report Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-bold">ID</th>
                                <th class="fw-bold">Item Name</th>
                                <th class="fw-bold">Current Stock Level</th>
                                <th class="fw-bold">Minimum Stock</th>
                                <th class="fw-bold">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Mug</td>
                                <td>100</td>
                                <td>50</td>
                                <td><span class="badge bg-success">In-Stock</span></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Fan</td>
                                <td>40</td>
                                <td>50</td>
                                <td><span class="badge bg-warning text-dark">Low Stock</span></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Pillow</td>
                                <td>50</td>
                                <td>30</td>
                                <td><span class="badge bg-success">In-Stock</span></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>ID</td>
                                <td>1000</td>
                                <td>50</td>
                                <td><span class="badge bg-success">In-Stock</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <button class="btn btn-outline-secondary btn-sm">Previous</button>
                    <small>Page 1 of 10</small>
                    <button class="btn btn-outline-secondary btn-sm">Next</button>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection