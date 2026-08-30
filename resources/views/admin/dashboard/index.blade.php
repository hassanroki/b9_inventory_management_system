@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
    {{-- Summary Cards --}}
    <div class="row g-3 g-md-4 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-2 gap-md-3">
                    <div class="rounded-3 bg-primary bg-opacity-10 p-2 p-md-3">
                        <i class="bi bi-tags text-primary fs-5 fs-md-3"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Categories</p>
                        <h5 class="mb-0 fw-semibold" id="totalCategories">—</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-2 gap-md-3">
                    <div class="rounded-3 bg-success bg-opacity-10 p-2 p-md-3">
                        <i class="bi bi-box text-success fs-5 fs-md-3"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Products</p>
                        <h5 class="mb-0 fw-semibold" id="totalProducts">—</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-2 gap-md-3">
                    <div class="rounded-3 bg-info bg-opacity-10 p-2 p-md-3">
                        <i class="bi bi-receipt text-info fs-5 fs-md-3"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Invoices</p>
                        <h5 class="mb-0 fw-semibold" id="totalInvoices">—</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-2 gap-md-3">
                    <div class="rounded-3 bg-warning bg-opacity-10 p-2 p-md-3">
                        <i class="bi bi-currency-dollar text-warning fs-5 fs-md-3"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Total Revenue</p>
                        <h5 class="mb-0 fw-semibold" id="totalRevenue">—</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Stock Alerts --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-exclamation-triangle me-1"></i> Stock Alerts</span>
                    <span class="badge text-bg-danger" id="stockAlertCount">0</span>
                </div>
                <div class="card-body p-0 p-md-3">

                    {{-- ============ MOBILE CARD VIEW (below md) ============ --}}
                    <div class="d-md-none" id="stockAlertsMobile">
                        <div class="text-center text-muted py-4">Loading...</div>
                    </div>

                    {{-- ============ DESKTOP TABLE VIEW (md and up) ============ --}}
                    <div class="d-none d-md-block table-responsive">
                        <table id="stockAlertsTable" class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 70px;">#</th>
                                    <th>Product</th>
                                    <th style="width: 160px;">Category</th>
                                    <th style="width: 120px;">Stock Qty</th>
                                    <th style="width: 140px;">Threshold</th>
                                    <th style="width: 120px;">Status</th>
                                </tr>
                            </thead>
                            <tbody id="stockAlertsTableBody">
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">Quick Actions</div>
                <div class="card-body">
                    <div class="row row-cols-2 row-cols-lg-1 g-2">
                        <div class="col">
                            <a href="{{ route('pos') }}"
                                class="btn btn-primary w-100 h-100 d-flex align-items-center justify-content-center text-center">
                                <i class="bi bi-receipt me-2"></i> New Invoice (POS)
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('products') }}"
                                class="btn btn-outline-primary w-100 h-100 d-flex align-items-center justify-content-center text-center">
                                <i class="bi bi-box me-2"></i> Manage Products
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('stocks') }}"
                                class="btn btn-outline-secondary w-100 h-100 d-flex align-items-center justify-content-center text-center">
                                <i class="bi bi-archive me-2"></i> Stock In
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('categories') }}"
                                class="btn btn-outline-secondary w-100 h-100 d-flex align-items-center justify-content-center text-center">
                                <i class="bi bi-tags me-2"></i> Manage Categories
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('invoices') }}"
                                class="btn btn-outline-secondary w-100 h-100 d-flex align-items-center justify-content-center text-center">
                                <i class="bi bi-journal-text me-2"></i> View Invoices
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Get Dashboard & Stock Alert Data
            loadDashboard();
            async function loadDashboard() {
                let URL = '{{ url('/api/v1/dashboard/summary') }}';
                let token = localStorage.getItem('token');
                let tbody = document.getElementById('stockAlertsTableBody');
                let mobileWrap = document.getElementById('stockAlertsMobile');

                try {
                    let response = await axios.get(URL, {
                        headers: {
                            Authorization: 'Bearer ' + token
                        }
                    });
                    let data = response.data['data'] || {};

                    document.getElementById('totalCategories').textContent = data['total_categories'] ?? '0';
                    document.getElementById('totalProducts').textContent = data['total_products'] ?? '0';
                    document.getElementById('totalInvoices').textContent = data['total_invoices'] ?? '0';
                    document.getElementById('totalRevenue').textContent = '$ ' + parseFloat(data['total_revenue'] || 0)
                        .toFixed(2);

                    // Low Stock Alert
                    let alerts = data['stock_alerts'] || [];
                    document.getElementById('stockAlertCount').textContent = alerts.length;

                    tbody.innerHTML = '';
                    mobileWrap.innerHTML = '';

                    if (alerts.length === 0) {
                        let emptyMsg = 'No stock alerts. All products are well-stocked.';
                        tbody.innerHTML =
                            `<tr><td colspan="6" class="text-center text-muted py-4">${emptyMsg}</td></tr>`;
                        mobileWrap.innerHTML =
                            `<div class="text-center text-muted py-4">${emptyMsg}</div>`;
                        return;
                    }

                    alerts.forEach((item) => {
                        let categoryName = item['category'] && item['category']['name'] ? item['category']['name'] :
                            '-';
                        let stock = item['stock_qty'] != null ? item['stock_qty'] : 0;
                        let threshold = item['low_stock_threshold'] != null ? item['low_stock_threshold'] : 0;
                        let isOut = stock === 0;
                        let statusBadge = isOut ?
                            '<span class="badge text-bg-danger">Out of Stock</span>' :
                            '<span class="badge text-bg-warning">Low Stock</span>';

                        // ---- Desktop table row ----
                        tbody.innerHTML += (`
                    <tr>
                        <td>${item['id']}</td>
                        <td class="fw-semibold">${item['product_name'] || ''}</td>
                        <td class="text-muted">${categoryName}</td>
                        <td><span class="badge text-bg-danger">${stock}</span></td>
                        <td class="text-muted">${threshold}</td>
                        <td>${statusBadge}</td>
                    </tr>
                `);

                        // ---- Mobile card ----
                        mobileWrap.innerHTML += (`
                    <div class="border-bottom px-3 py-3">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div>
                                <div class="fw-semibold">${item['product_name'] || ''}</div>
                                <div class="text-muted small">${categoryName}</div>
                            </div>
                            ${statusBadge}
                        </div>
                        <div class="d-flex gap-3 mt-2 small">
                            <span class="text-muted">Stock:
                                <span class="badge text-bg-danger">${stock}</span>
                            </span>
                            <span class="text-muted">Threshold: <span class="fw-semibold text-dark">${threshold}</span></span>
                        </div>
                    </div>
                `);
                    });
                    // Low Stock Alert end

                    // Data table (শুধু desktop table-এর উপর init হচ্ছে)
                    let table = new DataTable('#stockAlertsTable');

                } catch (err) {
                    let errMsg = 'Failed to load dashboard data.';
                    tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-4">${errMsg}</td></tr>`;
                    mobileWrap.innerHTML = `<div class="text-center text-muted py-4">${errMsg}</div>`;
                    showErrorToast(getErrorMessage(err, errMsg));
                }
            }
            // Get Dashboard & Stock Alert Data end
        </script>
    @endpush
@endsection
