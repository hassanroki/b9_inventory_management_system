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

            {{-- Mobile search --}}
            <div class="d-block d-md-none mb-3">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" id="customersMobileSearch" class="form-control"
                        placeholder="Search name, mobile, email...">
                </div>
            </div>

            {{-- Desktop / tablet table view --}}
            <div class="d-none d-md-block table-responsive">
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

            {{-- Mobile card view --}}
            <div id="customersMobileList" class="d-block d-md-none">
                <!-- JS-rendered customer cards -->
            </div>
            <div id="customersMobileEmpty" class="text-center text-muted py-4 d-none">No matching customers.</div>

            <nav class="d-block d-md-none mt-3" aria-label="Customers pagination">
                <ul id="customersMobilePagination" class="pagination pagination-sm justify-content-center mb-0"></ul>
            </nav>

        </div>
    </div>

    @include('admin.customers.create')
    @include('admin.customers.edit')
    @include('admin.customers.delete')


    @push('scripts')
        <script>
            let allCustomers = [];
            let customersMobileSearchTerm = '';
            let customersMobileCurrentPage = 1;
            const CUSTOMERS_MOBILE_PAGE_SIZE = 10; // keep in sync with DataTable's pageLength below
            let customersDataTable = null;

            getCustomers();

            document.getElementById('customersMobileSearch').addEventListener('input', function(e) {
                customersMobileSearchTerm = e.target.value.trim().toLowerCase();
                customersMobileCurrentPage = 1;
                renderCustomersMobileList();
            });

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

                    allCustomers = response.data['data'] || [];

                    if (customersDataTable) {
                        customersDataTable.destroy();
                        customersDataTable = null;
                    }
                    tbody.innerHTML = '';

                    if (allCustomers.length === 0) {
                        tbody.innerHTML = `
        <tr>
            <td colspan="7" class="text-center text-muted py-4">
            No customers found
            </td>
        </tr>
        `;
                        renderCustomersMobileList();
                        return;
                    }

                    allCustomers.forEach((item) => {

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

                    customersDataTable = new DataTable('#customersTable', {
                        pageLength: 10
                    });

                    renderCustomersMobileList();

                } catch (err) {

                    tbody.innerHTML = `
        <tr>
            <td colspan="7" class="text-center text-muted py-4">
            Failed to load customers
            </td>
        </tr>
        `;

                    document.getElementById('customersMobileList').innerHTML = `
        <div class="text-center text-muted py-4">Failed to load customers</div>
        `;

                    showErrorToast(getErrorMessage(err, 'Failed to load customers'));

                }

            }

            // Render mobile cards, filtered by search term and sliced by current page
            function renderCustomersMobileList() {
                let mobileList = document.getElementById('customersMobileList');
                let emptyState = document.getElementById('customersMobileEmpty');
                let paginationEl = document.getElementById('customersMobilePagination');

                let filtered = allCustomers.filter((item) => {
                    if (!customersMobileSearchTerm) return true;
                    let haystack = ((item['name'] || '') + ' ' + (item['mobile'] || '') + ' ' +
                        (item['email'] || '') + ' ' + (item['description'] || '')).toLowerCase();
                    return haystack.includes(customersMobileSearchTerm);
                });

                mobileList.innerHTML = '';
                paginationEl.innerHTML = '';

                if (filtered.length === 0) {
                    emptyState.classList.remove('d-none');
                    return;
                }
                emptyState.classList.add('d-none');

                let totalPages = Math.ceil(filtered.length / CUSTOMERS_MOBILE_PAGE_SIZE);
                if (customersMobileCurrentPage > totalPages) customersMobileCurrentPage = totalPages;
                if (customersMobileCurrentPage < 1) customersMobileCurrentPage = 1;

                let startIdx = (customersMobileCurrentPage - 1) * CUSTOMERS_MOBILE_PAGE_SIZE;
                let pageItems = filtered.slice(startIdx, startIdx + CUSTOMERS_MOBILE_PAGE_SIZE);

                pageItems.forEach((item) => {

                    let created = item['created_at'] ? item['created_at'].substring(0, 10) : '-';

                    mobileList.innerHTML += (`
                <div class="card mb-2">
                    <div class="card-body py-2 px-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-semibold small">${item['name']}</div>
                                <div class="text-muted small">${item['mobile']}</div>
                            </div>
                            <div class="text-muted small">${created}</div>
                        </div>
                        ${item['email'] ? `<div class="text-muted small mt-1">${item['email']}</div>` : ''}
                        ${item['description'] ? `<div class="text-muted small mt-1">${item['description']}</div>` : ''}
                        <div class="d-flex gap-2 mt-2">
                            <button class="btn btn-sm btn-outline-secondary flex-fill"
                                onclick="editCustomer(${item['id']})">
                                Edit
                            </button>
                            <button class="btn btn-sm btn-outline-danger flex-fill"
                                onclick="deleteCustomer(${item['id']})">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            `);

                });

                renderCustomersMobilePagination(paginationEl, totalPages);
            }

            // Build Bootstrap pagination controls for the mobile list
            function renderCustomersMobilePagination(paginationEl, totalPages) {
                if (totalPages <= 1) return;

                let prevDisabled = customersMobileCurrentPage === 1 ? ' disabled' : '';
                paginationEl.innerHTML += `
                <li class="page-item${prevDisabled}">
                    <a class="page-link" href="#" data-page="${customersMobileCurrentPage - 1}">&laquo;</a>
                </li>`;

                for (let i = 1; i <= totalPages; i++) {
                    let active = i === customersMobileCurrentPage ? ' active' : '';
                    paginationEl.innerHTML += `
                <li class="page-item${active}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>`;
                }

                let nextDisabled = customersMobileCurrentPage === totalPages ? ' disabled' : '';
                paginationEl.innerHTML += `
                <li class="page-item${nextDisabled}">
                    <a class="page-link" href="#" data-page="${customersMobileCurrentPage + 1}">&raquo;</a>
                </li>`;

                paginationEl.querySelectorAll('.page-link').forEach((link) => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        let page = parseInt(this.getAttribute('data-page'), 10);
                        if (!page || page < 1 || page > totalPages || page === customersMobileCurrentPage)
                            return;
                        customersMobileCurrentPage = page;
                        renderCustomersMobileList();
                        document.getElementById('customersMobileList').scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest'
                        });
                    });
                });
            }
        </script>
    @endpush

@endsection
