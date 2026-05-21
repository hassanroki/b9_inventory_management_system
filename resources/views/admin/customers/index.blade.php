@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span>Customer List</span>

            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#customerCreateModal">
                <i class="bi bi-plus-lg me-1"></i> Add Customer
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="customersTable" class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:70px">#</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Description</th>
                            <th style="width:130px">Created</th>
                            <th class="text-end" style="width:160px">Actions</th>
                        </tr>
                    </thead>

                    <tbody id="customersTableBody">
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    @include('admin.customers.create')
    @include('admin.customers.edit')
    @include('admin.customers.delete')


    @push('scripts')
        <script>
            getCustomers();

            async function getCustomers() {

                let URL = '{{ url('/api/v1/customers') }}';
                let token = localStorage.getItem('token');
                let tbody = document.getElementById('customersTableBody');

                try {

                    let response = await axios.get(URL, {
                        headers: {
                            Authorization: 'Bearer ' + token
                        }
                    });

                    let customers = response.data['data'] || [];

                    tbody.innerHTML = '';

                    customers.forEach((item) => {

                        let created = item['created_at'] ? item['created_at'].substring(0, 10) : '-';

                        tbody.innerHTML += (`

            <tr>
                <td>${item['id']}</td>

                <td class="fw-semibold">${item['name']}</td>

                <td>${item['mobile']}</td>

                <td class="text-muted">${item['email'] ?? '-'}</td>

                <td class="text-muted">${item['description'] ?? '-'}</td>

                <td class="text-muted">${created}</td>

                <td class="text-end">

                    <button class="btn btn-sm btn-outline-secondary"
                    onclick="editCustomer(${item['id']})">
                    Edit
                    </button>

                    <button class="btn btn-sm btn-outline-danger"
                    onclick="deleteCustomer(${item['id']})">
                    Delete
                    </button>

                </td>
            </tr>

            `);

                    });

                    new DataTable('#customersTable');

                } catch (err) {

                    tbody.innerHTML = `
        <tr>
            <td colspan="7" class="text-center text-muted py-4">
            Failed to load customers
            </td>
        </tr>
        `;

                    showErrorToast(getErrorMessage(err, 'Failed to load customers'));

                }

            }
        </script>
    @endpush

@endsection
