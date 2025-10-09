<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="createForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Stock Out Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Form fields -->
                    <input type="text" name="job_order_id" class="form-control mb-2" placeholder="Job Order ID" required>
                    <input type="text" name="requested_by" class="form-control mb-2" placeholder="Requested By" required>
                    <input type="text" name="customer_name" class="form-control mb-2" placeholder="Customer Name" required>
                    <input type="text" name="purpose" class="form-control mb-2" placeholder="Purpose" required>
                    <input type="date" name="due_date" class="form-control mb-2" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>