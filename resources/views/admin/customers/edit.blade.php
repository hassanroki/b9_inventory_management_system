<!-- Edit Customer Modal -->
<div class="modal fade" id="customerEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <form id="customerEditForm">

                <input type="hidden" id="editCustomerId">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name *</label>
                            <input type="text" id="editCustomerName" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mobile *</label>
                            <input type="text" id="editCustomerMobile" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" id="editCustomerEmail" class="form-control">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea id="editCustomerDescription" class="form-control" rows="3"></textarea>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary" id="customerUpdateBtn">
                        Update
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@push('scripts')
    <script>
        async function editCustomer(id) {

            let URL = `/api/v1/customers/${id}`;
            let token = localStorage.getItem('token');

            try {

                let response = await axios.get(URL, {
                    headers: {
                        Authorization: 'Bearer ' + token
                    }
                });

                let data = response.data.data;

                document.getElementById('editCustomerId').value = data.id;
                document.getElementById('editCustomerName').value = data.name;
                document.getElementById('editCustomerMobile').value = data.mobile;
                document.getElementById('editCustomerEmail').value = data.email ?? '';
                document.getElementById('editCustomerDescription').value = data.description ?? '';

                new bootstrap.Modal(document.getElementById('customerEditModal')).show();

            } catch (err) {

                showErrorToast('Failed to load customer');

            }

        }


        async function updateCustomer() {

            let id = document.getElementById('editCustomerId').value;

            let obj = {

                name: document.getElementById('editCustomerName').value.trim(),
                mobile: document.getElementById('editCustomerMobile').value.trim(),
                email: document.getElementById('editCustomerEmail').value.trim(),
                description: document.getElementById('editCustomerDescription').value.trim()

            };

            let URL = `/api/v1/customers/${id}`;
            let token = localStorage.getItem('token');

            try {

                let response = await axios.put(URL, obj, {
                    headers: {
                        Authorization: 'Bearer ' + token
                    }
                });

                if (response.data.status) {

                    showSuccessToast('Customer updated');

                    bootstrap.Modal.getInstance(document.getElementById('customerEditModal')).hide();

                    if (typeof getCustomers === 'function') getCustomers();

                }

            } catch (err) {

                showErrorToast('Update failed');

            }

        }


        document.getElementById('customerEditForm').addEventListener('submit', function(e) {

            e.preventDefault();

            updateCustomer();

        });
    </script>
@endpush
