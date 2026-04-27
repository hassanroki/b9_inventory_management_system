<div class="modal fade" id="stockInModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="#" method="POST" onsubmit="return false;">
                <div class="modal-header">
                    <h5 class="modal-title">Stock In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label" for="stockInProductId">Product <span
                                    class="text-danger">*</span></label>
                            <select name="product_id" id="stockInProductId" class="form-select" required>
                                <option value="" selected disabled>Select product</option>
                                <option value="1">iPhone 15 Pro (Electronics)</option>
                                <option value="2">Notebook (A5) (Stationery)</option>
                                <option value="3">Premium Rice (Groceries)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="stockInQuantity">Quantity <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="stockInQuantity" class="form-control"
                                min="1" placeholder="e.g. 10" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="stockInNote">Note</label>
                            <textarea name="note" id="stockInNote" class="form-control" rows="3" placeholder="e.g. New shipment received"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                        <i class="bi bi-check2-circle me-1"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
