<!-- Add Customer Modal -->
<div class="modal fade" id="customerCreateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <form id="customerCreateForm">

                <div class="modal-header">
                    <h5 class="modal-title">Add Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" id="customerName" class="form-control" placeholder="Customer name"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mobile <span class="text-danger">*</span></label>
                            <input type="text" id="customerMobile" class="form-control" placeholder="017XXXXXXXX"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" id="customerEmail" class="form-control"
                                placeholder="example@email.com">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea id="customerDescription" class="form-control" rows="3" placeholder="Optional description"></textarea>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary" id="customerSaveBtn">
                        <i class="bi bi-check2-circle me-1"></i> Save
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@push('scripts')
    <script>
        async function doCreateCustomer() {

            let nameValue = document.getElementById('customerName').value.trim();
            let mobileValue = document.getElementById('customerMobile').value.trim();
            let emailValue = document.getElementById('customerEmail').value.trim();
            let descriptionValue = document.getElementById('customerDescription').value.trim();

            let saveBtn = document.getElementById('customerSaveBtn');

            let obj = {
                name: nameValue,
                mobile: mobileValue,
                email: emailValue || null,
                description: descriptionValue || null
            }

            let URL = '{{ url('/api/v1/customers') }}';
            let token = localStorage.getItem('token');

            saveBtn.disabled = true;

            try {

                let response = await axios.post(URL, obj, {
                    headers: {
                        Authorization: 'Bearer ' + token
                    }
                });

                if (response.data && response.data.status) {

                    showSuccessToast(response.data.message || 'Customer created successfully');

                    let modalEl = document.getElementById('customerCreateModal');
                    let modal = bootstrap.Modal.getInstance(modalEl);

                    if (modal) modal.hide();

                    document.getElementById('customerCreateForm').reset();

                    if (typeof getCustomers === 'function') getCustomers();

                } else {

                    showErrorToast('Failed to create customer');

                }

            } catch (err) {

                showErrorToast(getErrorMessage(err, 'Failed to create customer'));

            } finally {

                saveBtn.disabled = false;

            }

        }

        document.getElementById('customerCreateForm').addEventListener('submit', async function(e) {

            e.preventDefault();

            await doCreateCustomer();

        });
    </script>
@endpush
