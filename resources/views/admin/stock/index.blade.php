@extends('layouts.admin')

@section('title', 'Stocks')

@section('content')
    <div class="row g-2 g-md-4 mb-4 d-none d-md-flex">
        <div class="col-6 col-md-6">
            <div class="card h-100">
                <div class="card-header small small-md-normal">Stock In</div>
                <div class="card-body p-2 p-md-3">
                    <p class="text-muted small d-none d-md-block">Record new stock received. Form will be wired to API later.
                    </p>
                    <button type="button" class="btn btn-primary btn-sm w-100 w-md-auto" data-bs-toggle="modal"
                        data-bs-target="#stockInModal">
                        <i class="bi bi-box-arrow-in-down me-1"></i> Stock In
                    </button>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-6">
            <div class="card h-100">
                <div class="card-header small small-md-normal">Stock Adjustment</div>
                <div class="card-body p-2 p-md-3">
                    <p class="text-muted small d-none d-md-block">Adjust quantity (corrections / damage). Form will be wired
                        to API later.</p>
                    <button type="button" class="btn btn-outline-secondary btn-sm w-100 w-md-auto" data-bs-toggle="modal"
                        data-bs-target="#stockAdjustModal">
                        <i class="bi bi-pencil-square me-1"></i> Adjust
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
            <span>Stock Movements</span>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-primary flex-fill flex-md-grow-0" data-bs-toggle="modal"
                    data-bs-target="#stockInModal">
                    <i class="bi bi-plus-lg me-1"></i> Stock In
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary flex-fill flex-md-grow-0"
                    data-bs-toggle="modal" data-bs-target="#stockAdjustModal">
                    <i class="bi bi-sliders me-1"></i> Adjustment
                </button>
            </div>
        </div>
        <div class="card-body">

            {{-- Mobile search --}}
            <div class="d-block d-md-none mb-3">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" id="stocksMobileSearch" class="form-control"
                        placeholder="Search product, category, note...">
                </div>
            </div>

            {{-- Desktop / tablet table view --}}
            <div class="d-none d-md-block table-responsive">
                <table id="stocksProductsTable" class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 70px;">#</th>
                            <th>Product</th>
                            <th style="width: 140px;">Category</th>
                            <th style="width: 110px;">Type</th>
                            <th style="width: 100px;">Quantity</th>
                            <th>Note</th>
                            <th style="width: 120px;">Invoice</th>
                            <th style="width: 130px;">Date</th>
                        </tr>
                    </thead>
                    <tbody id="stocksTableBody">
                        <!-- Static demo data (design only) -->
                        <tr>
                            <td>1</td>
                            <td class="fw-semibold">iPhone 15 Pro</td>
                            <td class="text-muted">Electronics</td>
                            <td><span class="badge text-bg-success">IN</span></td>
                            <td class="fw-semibold">+20</td>
                            <td class="text-muted">New shipment received</td>
                            <td class="text-muted">—</td>
                            <td class="text-muted">2026-02-01</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Mobile card view --}}
            <div id="stocksMobileList" class="d-block d-md-none">
                <!-- JS-rendered stock cards -->
            </div>
            <div id="stocksMobileEmpty" class="text-center text-muted py-4 d-none">No matching stock movements.</div>

            <nav class="d-block d-md-none mt-3" aria-label="Stock movements pagination">
                <ul id="stocksMobilePagination" class="pagination pagination-sm justify-content-center mb-0"></ul>
            </nav>

        </div>
    </div>

    @include('admin.stock.stock_in')
    @include('admin.stock.adjust')

    @push('scripts')
        <script>
            let allStocks = [];
            let mobileSearchTerm = '';
            let mobileCurrentPage = 1;
            const MOBILE_PAGE_SIZE = 10; // keep in sync with DataTable's pageLength below
            let stocksDataTable = null;

            getStocks();
            loadProductsForStock();

            document.getElementById('stocksMobileSearch').addEventListener('input', function(e) {
                mobileSearchTerm = e.target.value.trim().toLowerCase();
                mobileCurrentPage = 1;
                renderMobileList();
            });

            // Get Stock
            async function getStocks() {
                let URL = '{{ url('/api/v1/stocks') }}';
                let token = localStorage.getItem('token');
                let tbody = document.getElementById('stocksTableBody');
                try {
                    let response = await axios.get(URL, {
                        headers: {
                            Authorization: 'Bearer ' + token
                        }
                    });
                    allStocks = response.data['data'] || [];

                    if (stocksDataTable) {
                        stocksDataTable.destroy();
                        stocksDataTable = null;
                    }
                    tbody.innerHTML = '';

                    if (allStocks.length === 0) {
                        tbody.innerHTML =
                            '<tr><td colspan="8" class="text-center text-muted py-4">No stock movements found.</td></tr>';
                        renderMobileList();
                        return;
                    }

                    allStocks.forEach((item) => {
                        let created = item['created_at'] ? item['created_at'].substring(0, 10) : '-';
                        let productName = item['product'] && item['product']['product_name'] ? item['product'][
                            'product_name'
                        ] : '-';
                        let categoryName = item['product'] && item['product']['category'] && item['product'][
                            'category'
                        ]['name'] ? item['product']['category']['name'] : '-';
                        let typeBadge = item['type'] === 'IN' ? '<span class="badge text-bg-success">IN</span>' :
                            '<span class="badge text-bg-danger">OUT</span>';
                        let qty = item['quantity'] || 0;
                        let qtyDisplay = item['type'] === 'IN' ? '+' + qty : '-' + qty;
                        let invoiceDisplay = item['invoice_id'] ? '<span class="text-muted">INV-' + item[
                            'invoice_id'] + '</span>' : '<span class="text-muted">—</span>';
                        tbody.innerHTML += (`
                    <tr>
                        <td>${item['id']}</td>
                        <td class="fw-semibold">${productName}</td>
                        <td class="text-muted">${categoryName}</td>
                        <td>${typeBadge}</td>
                        <td class="fw-semibold">${qtyDisplay}</td>
                        <td class="text-muted">${item['note'] || '—'}</td>
                        <td>${invoiceDisplay}</td>
                        <td class="text-muted">${created}</td>
                    </tr>
                `);
                    });

                    // Data table
                    stocksDataTable = new DataTable('#stocksProductsTable', {
                        pageLength: 10
                    });

                    renderMobileList();
                } catch (err) {
                    tbody.innerHTML =
                        '<tr><td colspan="8" class="text-center text-muted py-4">Failed to load stock movements.</td></tr>';
                    document.getElementById('stocksMobileList').innerHTML =
                        '<div class="text-center text-muted py-4">Failed to load stock movements.</div>';
                    showErrorToast(getErrorMessage(err, 'Failed to load stock movements.'));
                }
            }
            // Get Stock End

            // Render mobile cards, filtered by search term and sliced by current page
            function renderMobileList() {
                let mobileList = document.getElementById('stocksMobileList');
                let emptyState = document.getElementById('stocksMobileEmpty');
                let paginationEl = document.getElementById('stocksMobilePagination');

                let filtered = allStocks.filter((item) => {
                    if (!mobileSearchTerm) return true;
                    let productName = item['product'] && item['product']['product_name'] ? item['product'][
                        'product_name'
                    ] : '';
                    let categoryName = item['product'] && item['product']['category'] && item['product'][
                        'category'
                    ]['name'] ? item['product']['category']['name'] : '';
                    let note = item['note'] || '';
                    let invoice = item['invoice_id'] ? ('INV-' + item['invoice_id']) : '';
                    let haystack = (productName + ' ' + categoryName + ' ' + note + ' ' + invoice)
                        .toLowerCase();
                    return haystack.includes(mobileSearchTerm);
                });

                mobileList.innerHTML = '';
                paginationEl.innerHTML = '';

                if (filtered.length === 0) {
                    emptyState.classList.remove('d-none');
                    return;
                }
                emptyState.classList.add('d-none');

                let totalPages = Math.ceil(filtered.length / MOBILE_PAGE_SIZE);
                if (mobileCurrentPage > totalPages) mobileCurrentPage = totalPages;
                if (mobileCurrentPage < 1) mobileCurrentPage = 1;

                let startIdx = (mobileCurrentPage - 1) * MOBILE_PAGE_SIZE;
                let pageItems = filtered.slice(startIdx, startIdx + MOBILE_PAGE_SIZE);

                pageItems.forEach((item) => {
                    let created = item['created_at'] ? item['created_at'].substring(0, 10) : '-';
                    let productName = item['product'] && item['product']['product_name'] ? item['product'][
                        'product_name'
                    ] : '-';
                    let categoryName = item['product'] && item['product']['category'] && item['product'][
                        'category'
                    ]['name'] ? item['product']['category']['name'] : '-';
                    let isIn = item['type'] === 'IN';
                    let typeBadge = isIn ? '<span class="badge text-bg-success">IN</span>' :
                        '<span class="badge text-bg-danger">OUT</span>';
                    let qty = item['quantity'] || 0;
                    let qtyDisplay = isIn ? '+' + qty : '-' + qty;
                    let qtyTextClass = isIn ? 'text-success' : 'text-danger';
                    let invoiceDisplay = item['invoice_id'] ? 'INV-' + item['invoice_id'] : '—';

                    mobileList.innerHTML += (`
                <div class="card mb-2">
                    <div class="card-body py-2 px-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-semibold small">${productName}</div>
                                <div class="text-muted small">${categoryName} &middot; ${typeBadge}</div>
                            </div>
                            <div class="fw-bold ${qtyTextClass}">${qtyDisplay}</div>
                        </div>
                        ${item['note'] ? `<div class="text-muted small mt-1">${item['note']}</div>` : ''}
                        <div class="d-flex justify-content-between text-muted small mt-2">
                            <span>${invoiceDisplay}</span>
                            <span>${created}</span>
                        </div>
                    </div>
                </div>
            `);
                });

                renderMobilePagination(paginationEl, totalPages);
            }
            // Render mobile list end

            // Build Bootstrap pagination controls for the mobile list
            function renderMobilePagination(paginationEl, totalPages) {
                if (totalPages <= 1) return;

                let prevDisabled = mobileCurrentPage === 1 ? ' disabled' : '';
                paginationEl.innerHTML += `
                <li class="page-item${prevDisabled}">
                    <a class="page-link" href="#" data-page="${mobileCurrentPage - 1}">&laquo;</a>
                </li>`;

                for (let i = 1; i <= totalPages; i++) {
                    let active = i === mobileCurrentPage ? ' active' : '';
                    paginationEl.innerHTML += `
                <li class="page-item${active}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>`;
                }

                let nextDisabled = mobileCurrentPage === totalPages ? ' disabled' : '';
                paginationEl.innerHTML += `
                <li class="page-item${nextDisabled}">
                    <a class="page-link" href="#" data-page="${mobileCurrentPage + 1}">&raquo;</a>
                </li>`;

                paginationEl.querySelectorAll('.page-link').forEach((link) => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        let page = parseInt(this.getAttribute('data-page'), 10);
                        if (!page || page < 1 || page > totalPages || page === mobileCurrentPage) return;
                        mobileCurrentPage = page;
                        renderMobileList();
                        document.getElementById('stocksMobileList').scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest'
                        });
                    });
                });
            }
            // Render mobile pagination end

            // Product Load when selected
            async function loadProductsForStock() {
                let URL = '{{ url('/api/v1/products') }}';
                let token = localStorage.getItem('token');
                try {
                    let response = await axios.get(URL, {
                        headers: {
                            Authorization: 'Bearer ' + token
                        }
                    });
                    let products = response.data['data'] || [];
                    let stockInSelect = document.getElementById('stockInProductId');
                    let stockAdjustSelect = document.getElementById('stockAdjustProductId');
                    if (stockInSelect) {
                        stockInSelect.innerHTML = '<option value="" selected disabled>Select product</option>';
                        products.forEach((p) => {
                            let cat = p.category && p.category.name ? p.category.name : '';
                            let stock = p.stock_qty != null ? p.stock_qty : 0;
                            stockInSelect.innerHTML += '<option value="' + p.id + '">' + (p.product_name || '') +
                                ' (' + cat + ') -> Stock: ' + stock + '</option>';
                        });
                    }
                    if (stockAdjustSelect) {
                        stockAdjustSelect.innerHTML = '<option value="" selected disabled>Select product</option>';
                        products.forEach((p) => {
                            let cat = p.category && p.category.name ? p.category.name : '';
                            let stock = p.stock_qty != null ? p.stock_qty : 0;
                            stockAdjustSelect.innerHTML += '<option value="' + p.id + '">' + (p.product_name ||
                                '') + ' (' + cat + ') -> Stock: ' + stock + '</option>';
                        });
                    }
                } catch (err) {
                    showErrorToast(getErrorMessage(err, 'Failed to load products.'));
                }
            }
            // Product load when selected end
        </script>
    @endpush
@endsection
