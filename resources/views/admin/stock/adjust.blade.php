<div class="modal fade" id="stockAdjustModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="#" method="POST" onsubmit="return false;">
                <div class="modal-header">
                    <h5 class="modal-title">Stock Adjustment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label" for="stockAdjustProductId">Product <span
                                    class="text-danger">*</span></label>
                            <select name="product_id" id="stockAdjustProductId" class="form-select" required>
                                <option value="" selected disabled>Select product</option>
                                <option value="1">iPhone 15 Pro (Electronics)</option>
                                <option value="2">Notebook (A5) (Stationery)</option>
                                <option value="3">Premium Rice (Groceries)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="stockAdjustQuantity">Quantity <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="stockAdjustQuantity" class="form-control"
                                min="1" placeholder="e.g. 2" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="stockAdjustType">Type <span
                                    class="text-danger">*</span></label>
                            <select name="type" id="stockAdjustType" class="form-select" required>
                                <option value="OUT" selected>OUT (decrease stock)</option>
                                <option value="IN">IN (increase stock)</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="stockAdjustNote">Note</label>
                            <textarea name="note" id="stockAdjustNote" class="form-control" rows="3"
                                placeholder="e.g. Damaged items, inventory correction"></textarea>
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
