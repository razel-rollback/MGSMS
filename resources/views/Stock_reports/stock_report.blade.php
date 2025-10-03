@extends('layouts.app')

@section('content')
<div class="col-md-12 p-4 bg-light">

   <!-- Top Bar -->
   <div class="d-flex justify-content-between align-items-center topbar mb-3">
       <!-- Search -->
       <form action="#" method="GET" class="d-flex w-50">
           <input class="form-control me-2" type="search" name="query" placeholder="Search product, supplier, order">
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

   <!-- Stock Report Form -->
  <div class="p-5 rounded shadow" style="height: 550px; overflow-y: auto;">
       <h5 class="mb-4">Stock Report</h5>
       <form>
           <div class="row g-4">
               <!-- Select Report -->
               <div class="col-md-6">
                   <label class="form-label">Select Report</label>
                   <select class="form-select">
                       <option selected>Product Name</option>
                       <option value="adjustments">Adjustments</option>
                       <option value="stock_in">Stock In</option>
                       <option value="stock_out">Stock Out</option>
                   </select>
               </div>

               <!-- Date Range From -->
               <div class="col-md-3">
                   <label class="form-label">From</label>
                   <div class="input-group">
                       <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                       <input type="date" class="form-control">
                   </div>
               </div>

               <!-- Date Range To -->
               <div class="col-md-3">
                   <label class="form-label">To</label>
                   <div class="input-group">
                       <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                       <input type="date" class="form-control">
                   </div>
               </div>

               <!-- Filter -->
               <div class="col-md-6">
                   <label class="form-label">Filter</label>
                   <select class="form-select">
                       <option selected>Current Stock Level</option>
                       <option value="low_stock">Low Stock</option>
                       <option value="out_of_stock">Out of Stock</option>
                       <option value="all">All</option>
                   </select>
               </div>
           </div>

    <!-- Action Buttons -->
<div class="d-flex justify-content-center mt-5 gap-5">
    <button type="button" class="btn btn-primary" style="width: 150px; height: 45px;">
        <i class="bi bi-gear"></i> Generate
    </button>

    <button type="button" class="btn btn-primary" style="width: 150px; height: 45px;">
        <i class="bi bi-file-earmark-excel"></i> Export Excel
    </button>

    <button type="button" class="btn btn-primary" style="width: 150px; height: 45px;">
        <i class="bi bi-file-earmark-pdf"></i> Export PDF
    </button>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection