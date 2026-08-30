@extends('layouts.admin')
@section('title', 'Products')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span>Product List</span>
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#productCreateModal">
                <i class="bi bi-plus-lg me-1"></i> Add Product
            </button>
        </div>
        <div class="card-body p-0 p-md-3">

            {{-- ============ MOBILE CARD VIEW (below md) ============ --}}
            <div class="d-md-none">
                {{-- Search box (mobile only) --}}
                <div class="px-3 pt-3 pb-2 border-bottom">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" id="productsMobileSearch" class="form-control"
                            placeholder="Search by name, SKU, category...">
                    </div>
                </div>

                <div id="productsMobile">
                    <div class="text-center text-muted py-4">Loading...</div>
                </div>

                {{-- Pagination controls (mobile only) --}}
                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-top">
                    <span class="small text-muted" id="productsMobilePageInfo">—</span>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary" id="productsMobilePrev">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="productsMobileNext">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ============ DESKTOP TABLE VIEW (md and up) ============ --}}
            <div class="d-none d-md-block table-responsive">
                <table id="productsTable" class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 70px;">#</th>
                            <th>Product</th>
                            <th style="width: 140px;">SKU</th>
                            <th style="width: 160px;">Category</th>
                            <th style="width: 100px;">Unit</th>
                            <th style="width: 120px;">Price</th>
                            <th style="width: 110px;">Stock</th>
                            <th style="width: 110px;">Status</th>
                            <th style="width: 130px;">Created</th>
                            <th class="text-end" style="width: 160px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="productList">
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Add Product Modal -->
    @include('admin.products.create')
    <!-- Edit Product Modal -->
    @include('admin.products.edit')
    <!-- Delete Product Modal -->
    @include('admin.products.delete')

    @push('scripts')
        <script>
            let productsDataTable = null;
            let productsData = [];
            let productsMobileFiltered = [];
            let productsMobilePage = 1;
            const productsMobilePerPage = 5;

            getProducts();
            loadProductCategories();

            // Category load when create and edit category drop down show
            async function loadProductCategories() {
                let URL = '{{ url('/api/v1/categories') }}';
                let token = localStorage.getItem('token');
                try {
                    let response = await axios.get(URL, {
                        headers: {
                            Authorization: 'Bearer ' + token
                        }
                    });
                    let categories = response.data['data'] || [];
                    let createSelect = document.getElementById('productCategoryId');
                    let editSelect = document.getElementById('productEditCategoryId');
                    if (createSelect) {
                        createSelect.innerHTML = '<option value="" selected disabled>Select category</option>';
                        categories.forEach((c) => {
                            createSelect.innerHTML += '<option value="' + c.id + '">' + (c.name || '') +
                                '</option>';
                        });
                    }
                    if (editSelect) {
                        editSelect.innerHTML = '<option value="" selected disabled>Select category</option>';
                        categories.forEach((c) => {
                            editSelect.innerHTML += '<option value="' + c.id + '">' + (c.name || '') + '</option>';
                        });
                    }
                } catch (err) {
                    showErrorToast(getErrorMessage(err, 'Failed to load categories.'));
                }
            }

            // Get Product Data
            async function getProducts() {
                let URL = '{{ url('/api/v1/products') }}';
                let token = localStorage.getItem('token');
                let tbody = document.getElementById('productList');
                let mobileWrap = document.getElementById('productsMobile');

                // আগের DataTable instance destroy — নাহলে "Cannot reinitialise" error আসবে
                if (productsDataTable) {
                    productsDataTable.destroy();
                    productsDataTable = null;
                }

                try {
                    let response = await axios.get(URL, {
                        headers: {
                            Authorization: 'Bearer ' + token
                        }
                    });
                    productsData = response.data['data'] || [];
                    tbody.innerHTML = '';

                    if (productsData.length === 0) {
                        let emptyMsg = 'No products found.';
                        tbody.innerHTML =
                            `<tr><td colspan="10" class="text-center text-muted py-4">${emptyMsg}</td></tr>`;
                        mobileWrap.innerHTML = `<div class="text-center text-muted py-4">${emptyMsg}</div>`;
                        document.getElementById('productsMobilePageInfo').textContent = '';
                        return;
                    }

                    productsData.forEach((item) => {
                        let created = item['created_at'] ? item['created_at'].substring(0, 10) : '-';
                        let statusBadge = item['status'] ? '<span class="badge text-bg-success">Active</span>' :
                            '<span class="badge text-bg-secondary">Inactive</span>';
                        let categoryName = item['category'] && item['category']['name'] ? item['category']['name'] :
                            '-';
                        let price = item['price'] != null ? parseFloat(item['price']).toFixed(2) : '0.00';
                        let stock = item['stock_qty'] != null ? item['stock_qty'] : 0;
                        let stockBadge = stock > 0 ? '<span class="badge text-bg-success">' + stock + '</span>' :
                            '<span class="badge text-bg-secondary">' + stock + '</span>';
                        let subtext = [];
                        if (item['color']) subtext.push('Color: ' + item['color']);
                        if (item['size']) subtext.push('Size: ' + item['size']);
                        if (item['weight']) subtext.push('Weight: ' + item['weight'] + 'kg');
                        let subtextHtml = subtext.length ? '<div class="text-muted small">' + subtext.join(' • ') +
                            '</div>' : '';

                        // ---- Desktop table row ----
                        tbody.innerHTML += (`
                    <tr>
                        <td>${item['id']}</td>
                        <td>
                            <div class="fw-semibold">${item['product_name'] || ''}</div>
                            ${subtextHtml}
                        </td>
                        <td class="text-muted">${item['sku'] || ''}</td>
                        <td class="fw-semibold">${categoryName}</td>
                        <td class="text-muted">${item['unit'] || ''}</td>
                        <td class="fw-semibold">$ ${price}</td>
                        <td>${stockBadge}</td>
                        <td>${statusBadge}</td>
                        <td class="text-muted">${created}</td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="editProduct(${item['id']})">Edit</button>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteProduct(${item['id']})">Delete</button>
                        </td>
                    </tr>
                `);
                    });

                    // Data Tables (শুধু desktop table-এর উপর init হচ্ছে)
                    productsDataTable = new DataTable('#productsTable');

                    // Mobile: search reset করে প্রথম পেজ থেকে render
                    document.getElementById('productsMobileSearch').value = '';
                    productsMobilePage = 1;
                    applyProductsMobileFilter();
                } catch (err) {
                    let errMsg = 'Failed to load products.';
                    tbody.innerHTML = `<tr><td colspan="10" class="text-center text-muted py-4">${errMsg}</td></tr>`;
                    mobileWrap.innerHTML = `<div class="text-center text-muted py-4">${errMsg}</div>`;
                    document.getElementById('productsMobilePageInfo').textContent = '';
                    showErrorToast(getErrorMessage(err, errMsg));
                }
            }

            // ---------- Mobile: search + pagination ----------

            function applyProductsMobileFilter() {
                let query = document.getElementById('productsMobileSearch').value.trim().toLowerCase();

                productsMobileFiltered = !query ? productsData.slice() : productsData.filter((item) => {
                    let name = (item['product_name'] || '').toLowerCase();
                    let sku = (item['sku'] || '').toLowerCase();
                    let categoryName = (item['category'] && item['category']['name'] || '').toLowerCase();
                    return name.includes(query) || sku.includes(query) || categoryName.includes(query);
                });

                productsMobilePage = 1;
                renderProductsMobilePage();
            }

            function renderProductsMobilePage() {
                let mobileWrap = document.getElementById('productsMobile');
                let pageInfo = document.getElementById('productsMobilePageInfo');
                let prevBtn = document.getElementById('productsMobilePrev');
                let nextBtn = document.getElementById('productsMobileNext');

                let total = productsMobileFiltered.length;

                if (total === 0) {
                    mobileWrap.innerHTML = '<div class="text-center text-muted py-4">No matching products.</div>';
                    pageInfo.textContent = '0 results';
                    prevBtn.disabled = true;
                    nextBtn.disabled = true;
                    return;
                }

                let totalPages = Math.ceil(total / productsMobilePerPage);
                if (productsMobilePage > totalPages) productsMobilePage = totalPages;

                let start = (productsMobilePage - 1) * productsMobilePerPage;
                let pageItems = productsMobileFiltered.slice(start, start + productsMobilePerPage);

                mobileWrap.innerHTML = '';
                pageItems.forEach((item) => {
                    let created = item['created_at'] ? item['created_at'].substring(0, 10) : '-';
                    let statusBadge = item['status'] ? '<span class="badge text-bg-success">Active</span>' :
                        '<span class="badge text-bg-secondary">Inactive</span>';
                    let categoryName = item['category'] && item['category']['name'] ? item['category']['name'] :
                        '-';
                    let price = item['price'] != null ? parseFloat(item['price']).toFixed(2) : '0.00';
                    let stock = item['stock_qty'] != null ? item['stock_qty'] : 0;
                    let stockBadge = stock > 0 ? '<span class="badge text-bg-success">' + stock + '</span>' :
                        '<span class="badge text-bg-secondary">' + stock + '</span>';
                    let subtext = [];
                    if (item['color']) subtext.push('Color: ' + item['color']);
                    if (item['size']) subtext.push('Size: ' + item['size']);
                    if (item['weight']) subtext.push('Weight: ' + item['weight'] + 'kg');
                    let subtextHtml = subtext.length ? '<div class="text-muted small">' + subtext.join(' • ') +
                        '</div>' : '';

                    mobileWrap.innerHTML += (`
                <div class="border-bottom px-3 py-3">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <div class="fw-semibold">${item['product_name'] || ''}</div>
                            ${subtextHtml}
                            <div class="text-muted small">SKU: ${item['sku'] || '-'}</div>
                        </div>
                        ${statusBadge}
                    </div>
                    <div class="d-flex flex-wrap gap-3 mt-2 small">
                        <span class="text-muted">Category: <span class="fw-semibold text-dark">${categoryName}</span></span>
                        <span class="text-muted">Unit: <span class="fw-semibold text-dark">${item['unit'] || '-'}</span></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div class="d-flex align-items-center gap-3">
                            <span class="fw-semibold">$ ${price}</span>
                            ${stockBadge}
                        </div>
                        <span class="text-muted small">${created}</span>
                    </div>
                    <div class="d-flex gap-2 mt-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary flex-fill" onclick="editProduct(${item['id']})">Edit</button>
                        <button type="button" class="btn btn-sm btn-outline-danger flex-fill" onclick="deleteProduct(${item['id']})">Delete</button>
                    </div>
                </div>
            `);
                });

                pageInfo.textContent = `Page ${productsMobilePage} of ${totalPages} (${total} results)`;
                prevBtn.disabled = productsMobilePage <= 1;
                nextBtn.disabled = productsMobilePage >= totalPages;
            }

            document.getElementById('productsMobileSearch').addEventListener('input', applyProductsMobileFilter);

            document.getElementById('productsMobilePrev').addEventListener('click', function() {
                if (productsMobilePage > 1) {
                    productsMobilePage--;
                    renderProductsMobilePage();
                }
            });

            document.getElementById('productsMobileNext').addEventListener('click', function() {
                let totalPages = Math.ceil(productsMobileFiltered.length / productsMobilePerPage);
                if (productsMobilePage < totalPages) {
                    productsMobilePage++;
                    renderProductsMobilePage();
                }
            });
        </script>
    @endpush
@endsection
