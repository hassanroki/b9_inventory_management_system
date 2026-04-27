<div class="modal fade" id="categoryCreateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="#" method="POST" onsubmit="return false;">
                <div class="modal-header">
                    <h5 class="modal-title">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="categoryName">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="categoryName" class="form-control"
                            placeholder="e.g. Electronics" maxlength="255" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="categoryDescription">Description</label>
                        <textarea name="description" id="categoryDescription" class="form-control" rows="3"
                            placeholder="Optional description"></textarea>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="status" value="1"
                            id="categoryStatus" checked>
                        <label class="form-check-label" for="categoryStatus">Active</label>
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
