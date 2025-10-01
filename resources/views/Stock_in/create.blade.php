<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Purchase Order</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f8f9fa; }
    .content { margin-left: 250px; padding: 20px; }
    .card { border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
    .btn-primary { background-color: #007bff; border: none; }
    .table thead th { background: #f1f3f5; }
    .table td, .table th { vertical-align: middle; }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar position-fixed bg-light" style="width:250px; height:100vh; padding:15px;">
    <h5 class="text-center">LOGO</h5>
    <ul class="nav flex-column mt-4">
      <li class="nav-item"><a class="nav-link" href="#">Dashboard</a></li>
      <li class="nav-item"><a class="nav-link active text-primary" href="#">Purchase Order</a></li>
      <li class="nav-item"><a class="nav-link" href="#">Stock Out</a></li>
      <li class="nav-item"><a class="nav-link" href="#">Stock Adjustment</a></li>
      <li class="nav-item"><a class="nav-link" href="#">Reports</a></li>
      <li class="nav-item"><a class="nav-link" href="#">Suppliers</a></li>
      <li class="nav-item"><a class="nav-link" href="#">Orders</a></li>
      <li class="nav-item"><a class="nav-link" href="#">Manage Store</a></li>
    </ul>
    <div class="mt-auto">
      <a href="#" class="nav-link">⚙ Settings</a>
      <a href="#" class="nav-link">🚪 Log Out</a>
    </div>
  </div>

  <!-- Content -->
  <div class="content">
    <form action="{{ route('purchase_order1.store') }}" method="POST">
      @csrf

      <!-- Order Details -->
      <div class="card p-4 mb-4">
        <h5 class="mb-3">Order Details</h5>
        <div class="row mb-3">
          <div class="col-md-3">
            <label class="form-label">PO Number</label>
            <input type="text" class="form-control" name="po_number" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Supplier Name</label>
            <input type="text" class="form-control" name="supplier_name" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Order Date</label>
            <input type="date" class="form-control" name="order_date" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Delivery Date</label>
            <input type="date" class="form-control" name="delivery_date" required>
          </div>
        </div>
      </div>

      <!-- Order Items -->
      <div class="card p-4 mb-4">
        <h5 class="mb-3">Order Items</h5>
        <div class="row g-3 align-items-end">
          <div class="col-md-3">
            <input type="text" class="form-control" placeholder="Item Name" id="item_name">
          </div>
          <div class="col-md-2">
            <input type="text" class="form-control" placeholder="Unit" id="item_unit">
          </div>
          <div class="col-md-2">
            <input type="number" class="form-control" placeholder="Quantity" id="item_qty">
          </div>
          <div class="col-md-2">
            <input type="number" class="form-control" placeholder="Unit Price" id="item_price">
          </div>
          <div class="col-md-3">
            <button type="button" class="btn btn-primary w-100" onclick="addItem()">Add Item</button>
          </div>
        </div>
      </div>

      <!-- Products Table -->
      <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5>Products</h5>
          <div>
            <button type="submit" class="btn btn-success">Save</button>
            <button type="reset" class="btn btn-outline-secondary">Reset</button>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table align-middle" id="itemsTable">
            <thead>
              <tr>
                <th>Item Name</th>
                <th>Unit</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr><td colspan="6" class="text-center text-muted">No products added yet</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </form>
  </div>

  <script>
    function addItem() {
      let name = document.getElementById('item_name').value;
      let unit = document.getElementById('item_unit').value;
      let qty = document.getElementById('item_qty').value;
      let price = document.getElementById('item_price').value;

      if (!name || !qty || !price) {
        alert("Please fill in all fields");
        return;
      }

      let subtotal = qty * price;

      let table = document.getElementById('itemsTable').getElementsByTagName('tbody')[0];
      if (table.rows[0].cells[0].innerText === "No products added yet") {
        table.deleteRow(0);
      }

      let row = table.insertRow();
      row.innerHTML = `
        <td><input type="hidden" name="items[][name]" value="${name}">${name}</td>
        <td><input type="hidden" name="items[][unit]" value="${unit}">${unit}</td>
        <td><input type="hidden" name="items[][quantity]" value="${qty}">${qty}</td>
        <td><input type="hidden" name="items[][unit_price]" value="${price}">${price}</td>
        <td>${subtotal}</td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">Remove</button></td>
      `;
      document.getElementById('item_name').value = "";
      document.getElementById('item_unit').value = "";
      document.getElementById('item_qty').value = "";
      document.getElementById('item_price').value = "";
    }

    function removeRow(btn) {
      let row = btn.parentNode.parentNode;
      row.parentNode.removeChild(row);

      let table = document.getElementById('itemsTable').getElementsByTagName('tbody')[0];
      if (table.rows.length === 0) {
        let row = table.insertRow();
        row.innerHTML = `<td colspan="6" class="text-center text-muted">No products added yet</td>`;
      }
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
