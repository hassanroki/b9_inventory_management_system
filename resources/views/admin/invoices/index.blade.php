@extends('layouts.admin')

@section('title', 'Invoices')

@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span>Invoice List</span>
            <a href="{{ route('pos') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg me-1"></i> New Invoice (POS)
            </a>
        </div>
        <div class="card-body">

            {{-- Mobile search --}}
            <div class="d-block d-md-none mb-3">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" id="invoicesMobileSearch" class="form-control"
                        placeholder="Search invoice no, status...">
                </div>
            </div>

            {{-- Desktop / tablet table view --}}
            <div class="d-none d-md-block table-responsive">
                <table id="invoicesTable" class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 70px;">#</th>
                            <th style="width: 160px;">Invoice No</th>
                            <th style="width: 120px;">Date</th>
                            <th style="width: 80px;">Items</th>
                            <th style="width: 120px;">Subtotal</th>
                            <th style="width: 120px;">Discount</th>
                            <th style="width: 130px;">Grand Total</th>
                            <th style="width: 110px;">Status</th>
                            <th class="text-end" style="width: 180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="invoicesTableBody">


                    </tbody>
                </table>
            </div>

            {{-- Mobile card view --}}
            <div id="invoicesMobileList" class="d-block d-md-none">
                <!-- JS-rendered invoice cards -->
            </div>
            <div id="invoicesMobileEmpty" class="text-center text-muted py-4 d-none">No matching invoices.</div>

            <nav class="d-block d-md-none mt-3" aria-label="Invoices pagination">
                <ul id="invoicesMobilePagination" class="pagination pagination-sm justify-content-center mb-0"></ul>
            </nav>

        </div>
    </div>

    @include('admin.invoices.show')
    @include('admin.invoices.delete')
    @include('admin.invoices.finalize')

    @push('scripts')
        <script>
            let invoicesData = [];
            let invoicesMobileSearchTerm = '';
            let invoicesMobileCurrentPage = 1;
            const INVOICES_MOBILE_PAGE_SIZE = 10; // keep in sync with DataTable's pageLength below
            let invoicesDataTable = null;

            getInvoices();

            document.getElementById('invoicesMobileSearch').addEventListener('input', function(e) {
                invoicesMobileSearchTerm = e.target.value.trim().toLowerCase();
                invoicesMobileCurrentPage = 1;
                renderInvoicesMobileList();
            });

            async function getInvoices() {
                let URL = '{{ url('/api/v1/invoices') }}';
                let token = localStorage.getItem('token');
                let tbody = document.getElementById('invoicesTableBody');
                try {
                    let response = await axios.get(URL, {
                        headers: {
                            Authorization: 'Bearer ' + token
                        }
                    });
                    invoicesData = response.data['data'] || [];

                    if (invoicesDataTable) {
                        invoicesDataTable.destroy();
                        invoicesDataTable = null;
                    }
                    tbody.innerHTML = '';

                    if (invoicesData.length === 0) {
                        tbody.innerHTML =
                            '<tr><td colspan="9" class="text-center text-muted py-4">No invoices found.</td></tr>';
                        renderInvoicesMobileList();
                        return;
                    }
                    invoicesData.forEach((item) => {
                        let invoiceDate = item['invoice_date'] ? item['invoice_date'].substring(0, 10) : '-';
                        let itemsCount = item['items'] ? item['items'].length : 0;
                        let subtotal = parseFloat(item['subtotal'] || 0).toFixed(2);
                        let discountAmount = parseFloat(item['discount_amount'] || 0);
                        let grandTotal = parseFloat(item['grand_total'] || 0).toFixed(2);
                        let status = item['status'] || 'draft';

                        let discountHtml = '—';
                        if (discountAmount > 0) {
                            let discountLabel = '';
                            if (item['discount_type'] === 'percent') {
                                discountLabel = parseFloat(item['discount_value'] || 0) + '%';
                            } else if (item['discount_type'] === 'fixed') {
                                discountLabel = 'Fixed';
                            }
                            discountHtml = '<span class="text-danger">- $ ' + discountAmount.toFixed(2) + '</span>';
                            if (discountLabel) {
                                discountHtml += '<div class="text-muted small">' + discountLabel + '</div>';
                            }
                        }

                        let statusBadge = '';
                        let grandTotalClass = 'fw-semibold';
                        let rowClass = '';
                        let isCancelled = false;
                        let isFinalized = false;

                        if (status === 'finalized') {
                            statusBadge =
                                '<span class="badge text-bg-success"><i class="bi bi-check-circle me-1"></i>Finalized</span>';
                            grandTotalClass = 'fw-semibold text-success';
                            isFinalized = true;
                        } else if (status === 'cancelled') {
                            statusBadge =
                                '<span class="badge text-bg-secondary"><i class="bi bi-x-circle me-1"></i>Cancelled</span>';
                            rowClass = 'class="table-light"';
                            isCancelled = true;
                        } else {
                            statusBadge =
                                '<span class="badge text-bg-warning"><i class="bi bi-pencil-square me-1"></i>Draft</span>';
                        }

                        let invoiceNoHtml = isCancelled ?
                            '<span class="fw-semibold text-muted text-decoration-line-through">' + (item[
                                'invoice_no'] || '') + '</span>' :
                            '<span class="fw-semibold text-primary">' + (item['invoice_no'] || '') + '</span>';

                        let actionsHtml = `
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewInvoice(${item['id']})" title="View">
                            <i class="bi bi-eye"></i>
                        </button>`;

                        if (status === 'draft') {
                            actionsHtml += `
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="finalizeInvoice(${item['id']})" title="Finalize">
                            <i class="bi bi-check-lg"></i> Finalize
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteInvoice(${item['id']})" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>`;
                        } else if (isFinalized) {
                            actionsHtml += `
                        <button type="button" class="btn btn-sm btn-outline-danger" disabled title="Cannot delete finalized">
                            <i class="bi bi-trash"></i>
                        </button>`;
                        } else {
                            actionsHtml += `
                        <button type="button" class="btn btn-sm btn-outline-danger" disabled>
                            <i class="bi bi-trash"></i>
                        </button>`;
                        }

                        tbody.innerHTML += `
                    <tr ${rowClass}>
                        <td${isCancelled ? ' class="text-muted"' : ''}>${item['id']}</td>
                        <td>${invoiceNoHtml}</td>
                        <td class="text-muted">${invoiceDate}</td>
                        <td><span class="badge bg-secondary rounded-pill">${itemsCount}</span></td>
                        <td${isCancelled ? ' class="text-muted"' : ''}>$ ${subtotal}</td>
                        <td${isCancelled ? ' class="text-muted"' : ''}>${discountHtml}</td>
                        <td class="${grandTotalClass}${isCancelled ? ' text-muted' : ''}">$ ${grandTotal}</td>
                        <td>${statusBadge}</td>
                        <td class="text-end">${actionsHtml}</td>
                    </tr>`;
                    });

                    // Data table
                    invoicesDataTable = new DataTable('#invoicesTable', {
                        pageLength: 10
                    });

                    renderInvoicesMobileList();

                } catch (err) {
                    tbody.innerHTML =
                        '<tr><td colspan="9" class="text-center text-muted py-4">Failed to load invoices.</td></tr>';
                    document.getElementById('invoicesMobileList').innerHTML =
                        '<div class="text-center text-muted py-4">Failed to load invoices.</div>';
                    showErrorToast(getErrorMessage(err, 'Failed to load invoices.'));
                }
            }

            // Render mobile cards, filtered by search term and sliced by current page
            function renderInvoicesMobileList() {
                let mobileList = document.getElementById('invoicesMobileList');
                let emptyState = document.getElementById('invoicesMobileEmpty');
                let paginationEl = document.getElementById('invoicesMobilePagination');

                let filtered = invoicesData.filter((item) => {
                    if (!invoicesMobileSearchTerm) return true;
                    let haystack = ((item['invoice_no'] || '') + ' ' + (item['status'] || '')).toLowerCase();
                    return haystack.includes(invoicesMobileSearchTerm);
                });

                mobileList.innerHTML = '';
                paginationEl.innerHTML = '';

                if (filtered.length === 0) {
                    emptyState.classList.remove('d-none');
                    return;
                }
                emptyState.classList.add('d-none');

                let totalPages = Math.ceil(filtered.length / INVOICES_MOBILE_PAGE_SIZE);
                if (invoicesMobileCurrentPage > totalPages) invoicesMobileCurrentPage = totalPages;
                if (invoicesMobileCurrentPage < 1) invoicesMobileCurrentPage = 1;

                let startIdx = (invoicesMobileCurrentPage - 1) * INVOICES_MOBILE_PAGE_SIZE;
                let pageItems = filtered.slice(startIdx, startIdx + INVOICES_MOBILE_PAGE_SIZE);

                pageItems.forEach((item) => {
                    let invoiceDate = item['invoice_date'] ? item['invoice_date'].substring(0, 10) : '-';
                    let itemsCount = item['items'] ? item['items'].length : 0;
                    let subtotal = parseFloat(item['subtotal'] || 0).toFixed(2);
                    let discountAmount = parseFloat(item['discount_amount'] || 0);
                    let grandTotal = parseFloat(item['grand_total'] || 0).toFixed(2);
                    let status = item['status'] || 'draft';

                    let discountHtml = '—';
                    if (discountAmount > 0) {
                        let discountLabel = '';
                        if (item['discount_type'] === 'percent') {
                            discountLabel = parseFloat(item['discount_value'] || 0) + '%';
                        } else if (item['discount_type'] === 'fixed') {
                            discountLabel = 'Fixed';
                        }
                        discountHtml = '<span class="text-danger">- $ ' + discountAmount.toFixed(2) + '</span>';
                        if (discountLabel) {
                            discountHtml += ' <span class="text-muted">(' + discountLabel + ')</span>';
                        }
                    }

                    let statusBadge = '';
                    let grandTotalClass = 'fw-bold';
                    let isCancelled = false;
                    let isFinalized = false;

                    if (status === 'finalized') {
                        statusBadge =
                            '<span class="badge text-bg-success"><i class="bi bi-check-circle me-1"></i>Finalized</span>';
                        grandTotalClass = 'fw-bold text-success';
                        isFinalized = true;
                    } else if (status === 'cancelled') {
                        statusBadge =
                            '<span class="badge text-bg-secondary"><i class="bi bi-x-circle me-1"></i>Cancelled</span>';
                        isCancelled = true;
                    } else {
                        statusBadge =
                            '<span class="badge text-bg-warning"><i class="bi bi-pencil-square me-1"></i>Draft</span>';
                    }

                    let invoiceNoHtml = isCancelled ?
                        '<span class="fw-semibold text-muted text-decoration-line-through">' + (item[
                            'invoice_no'] || '') + '</span>' :
                        '<span class="fw-semibold text-primary">' + (item['invoice_no'] || '') + '</span>';

                    let actionButtons = `
                    <button type="button" class="btn btn-sm btn-outline-primary flex-fill" onclick="viewInvoice(${item['id']})">
                        <i class="bi bi-eye me-1"></i>View
                    </button>`;

                    if (status === 'draft') {
                        actionButtons += `
                    <button type="button" class="btn btn-sm btn-outline-success flex-fill" onclick="finalizeInvoice(${item['id']})">
                        <i class="bi bi-check-lg me-1"></i>Finalize
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteInvoice(${item['id']})">
                        <i class="bi bi-trash"></i>
                    </button>`;
                    } else {
                        actionButtons += `
                    <button type="button" class="btn btn-sm btn-outline-danger" disabled title="${isFinalized ? 'Cannot delete finalized' : ''}">
                        <i class="bi bi-trash"></i>
                    </button>`;
                    }

                    mobileList.innerHTML += (`
                <div class="card mb-2 ${isCancelled ? 'bg-light' : ''}">
                    <div class="card-body py-2 px-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="small">${invoiceNoHtml}</div>
                                <div class="text-muted small">${invoiceDate} &middot; <span class="badge bg-secondary rounded-pill">${itemsCount} items</span></div>
                            </div>
                            ${statusBadge}
                        </div>
                        <div class="d-flex justify-content-between text-muted small mt-2">
                            <span>Subtotal: $ ${subtotal}</span>
                            <span>Discount: ${discountHtml}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <span class="text-muted small">Grand Total</span>
                            <span class="${grandTotalClass}">$ ${grandTotal}</span>
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            ${actionButtons}
                        </div>
                    </div>
                </div>
            `);
                });

                renderInvoicesMobilePagination(paginationEl, totalPages);
            }

            // Build Bootstrap pagination controls for the mobile list
            function renderInvoicesMobilePagination(paginationEl, totalPages) {
                if (totalPages <= 1) return;

                let prevDisabled = invoicesMobileCurrentPage === 1 ? ' disabled' : '';
                paginationEl.innerHTML += `
                <li class="page-item${prevDisabled}">
                    <a class="page-link" href="#" data-page="${invoicesMobileCurrentPage - 1}">&laquo;</a>
                </li>`;

                for (let i = 1; i <= totalPages; i++) {
                    let active = i === invoicesMobileCurrentPage ? ' active' : '';
                    paginationEl.innerHTML += `
                <li class="page-item${active}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>`;
                }

                let nextDisabled = invoicesMobileCurrentPage === totalPages ? ' disabled' : '';
                paginationEl.innerHTML += `
                <li class="page-item${nextDisabled}">
                    <a class="page-link" href="#" data-page="${invoicesMobileCurrentPage + 1}">&raquo;</a>
                </li>`;

                paginationEl.querySelectorAll('.page-link').forEach((link) => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        let page = parseInt(this.getAttribute('data-page'), 10);
                        if (!page || page < 1 || page > totalPages || page === invoicesMobileCurrentPage)
                    return;
                        invoicesMobileCurrentPage = page;
                        renderInvoicesMobileList();
                        document.getElementById('invoicesMobileList').scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest'
                        });
                    });
                });
            }
        </script>
    @endpush
@endsection
