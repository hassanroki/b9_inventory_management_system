@extends('layouts.admin')
@section('title', 'Invoices')
@section('content')
    <!-- Summary cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="bi bi-receipt text-primary fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Invoices</div>
                            <div id="totalInvoices" class="fs-4 fw-semibold">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="bi bi-check-circle text-success fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Finalized</div>
                            <div id="finalizedInvoices" class="fs-4 fw-semibold">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-warning bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="bi bi-pencil-square text-warning fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Draft</div>
                            <div id="draftInvoices" class="fs-4 fw-semibold">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-info bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="bi bi-currency-dollar text-info fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Revenue</div>
                            <div id="totalRevenue" class="fs-4 fw-semibold">$ 0.00</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span>Invoice List</span>
            <a href="{{ route('pos') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg me-1"></i> New Invoice (POS)
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
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

            <!-- Pagination placeholder -->
            <div id="paginationWrapper"></div>
        </div>
    </div>
    {{-- View Invoice, Delete, Finalize --}}
    @include('admin.invoices.show')
    @include('admin.invoices.delete')
    @include('admin.invoices.finalize')

    @push('scripts')
        <script>
            let invoicesData = [];
            let currentPage = 1;
            getInvoices();


            async function getInvoices(page = 1) {
                currentPage = page;
                // let URL = '{{ url('/api/v1/invoices') }}';
                // Pagination
                let URL = `{{ url('/api/v1/invoices') }}?page=${page}`;

                let token = localStorage.getItem('token');
                let tbody = document.getElementById('invoicesTableBody');
                try {
                    let response = await axios.get(URL, {
                        headers: {
                            Authorization: 'Bearer ' + token
                        }
                    });
                    // invoicesData = response.data['data'] || [];
                    let apiData = response.data.data;
                    invoicesData = apiData.data || [];

                    tbody.innerHTML = '';
                    if (invoicesData.length === 0) {
                        tbody.innerHTML =
                            '<tr><td colspan="9" class="text-center text-muted py-4">No invoices found.</td></tr>';
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

                    // Pagination
                    renderPagination(apiData);

                    // Summary
                    summary(apiData);

                } catch (err) {
                    tbody.innerHTML =
                        '<tr><td colspan="9" class="text-center text-muted py-4">Failed to load invoices.</td></tr>';
                    showErrorToast(getErrorMessage(err, 'Failed to load invoices.'));
                }
            }

            // Summary
            function summary(meta) {

                let totalInvoices = meta.total;
                let finalizedCount = 0;
                let draftCount = 0;
                let totalRevenue = 0;

                invoicesData.forEach(item => {
                    if (item.status === 'finalized') {
                        finalizedCount++;
                        totalRevenue += parseFloat(item.grand_total || 0);
                    } else if (item.status === 'draft') {
                        draftCount++;
                    }
                });

                document.getElementById('totalInvoices').innerText = totalInvoices;
                document.getElementById('finalizedInvoices').innerText = finalizedCount;
                document.getElementById('draftInvoices').innerText = draftCount;
                document.getElementById('totalRevenue').innerText = '$ ' + totalRevenue.toFixed(2);
            }


            // Pagination
            function renderPagination(meta) {

                let wrapper = document.getElementById('paginationWrapper');

                let html = `<nav class="d-flex justify-content-between align-items-center mt-3">
    <div class="text-muted small">
        Showing ${meta.from} to ${meta.to} of ${meta.total} invoices
    </div>
    <ul class="pagination pagination-sm mb-0">`;

                // Previous button
                html += `
    <li class="page-item ${meta.current_page === 1 ? 'disabled' : ''}">
        <a class="page-link" href="javascript:void(0)" onclick="getInvoices(${meta.current_page - 1})">Previous</a>
    </li>`;

                // Page numbers
                let start = Math.max(1, meta.current_page - 2);
                let end = Math.min(meta.last_page, meta.current_page + 2);

                for (let i = start; i <= end; i++) {
                    html += `
    <li class="page-item ${i === meta.current_page ? 'active' : ''}">
        <a class="page-link" href="javascript:void(0)" onclick="getInvoices(${i})">${i}</a>
    </li>`;
                }

                // Next button
                html += `
    <li class="page-item ${meta.current_page === meta.last_page ? 'disabled' : ''}">
        <a class="page-link" href="javascript:void(0)" onclick="getInvoices(${meta.current_page + 1})">Next</a>
    </li>`;

                html += `</ul></nav>`;

                wrapper.innerHTML = html;
            }
        </script>
    @endpush
@endsection
