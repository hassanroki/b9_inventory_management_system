<!-- Delete Customer Modal -->
<div class="modal fade" id="customerDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-danger">Delete Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">

                <input type="hidden" id="deleteCustomerId">

                <p class="mb-0">
                    Are you sure you want to delete this customer?
                </p>

            </div>

            <div class="modal-footer">

                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>

                <button class="btn btn-danger" onclick="confirmDeleteCustomer()">
                    Delete
                </button>

            </div>

        </div>
    </div>
</div>

@push('scripts')
    <script>
        function deleteCustomer(id) {

            document.getElementById('deleteCustomerId').value = id;

            new bootstrap.Modal(document.getElementById('customerDeleteModal')).show();

        }


        async function confirmDeleteCustomer() {

            let id = document.getElementById('deleteCustomerId').value;

            let URL = `/api/v1/customers/${id}`;
            let token = localStorage.getItem('token');

            try {

                let response = await axios.delete(URL, {
                    headers: {
                        Authorization: 'Bearer ' + token
                    }
                });

                if (response.data.status) {

                    showSuccessToast('Customer deleted');

                    bootstrap.Modal.getInstance(document.getElementById('customerDeleteModal')).hide();

                    if (typeof getCustomers === 'function') getCustomers();

                }

            } catch (err) {

                showErrorToast('Delete failed');

            }

        }
    </script>
@endpush
