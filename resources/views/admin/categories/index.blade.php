@extends('layouts.admin')
@section('title', 'Categories')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span>Category List</span>
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#categoryCreateModal">
                <i class="bi bi-plus-lg me-1"></i> Add Category
            </button>
        </div>
        <div class="card-body p-0 p-md-3">

            {{-- ============ MOBILE CARD VIEW (below md) ============ --}}
            <div class="d-md-none">
                {{-- Search box (mobile only — desktop টেবিলের DataTable এর নিজস্ব search আছে) --}}
                <div class="px-3 pt-3 pb-2 border-bottom">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" id="categoriesMobileSearch" class="form-control"
                            placeholder="Search by name or description...">
                    </div>
                </div>

                <div id="categoriesMobile">
                    <div class="text-center text-muted py-4">Loading...</div>
                </div>

                {{-- Pagination controls (mobile only) --}}
                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-top">
                    <span class="small text-muted" id="categoriesMobilePageInfo">—</span>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary" id="categoriesMobilePrev">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="categoriesMobileNext">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ============ DESKTOP TABLE VIEW (md and up) ============ --}}
            <div class="d-none d-md-block table-responsive">
                <table id="categoriesTable" class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 70px;">#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th style="width: 110px;">Status</th>
                            <th style="width: 130px;">Created</th>
                            <th class="text-end" style="width: 160px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="categoriesTableBody">
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Add Category Modal -->
    @include('admin.categories.create')
    <!-- Edit Category Modal -->
    @include('admin.categories.edit')
    <!-- Delete Category Modal -->
    @include('admin.categories.delete')

    @push('scripts')
        <script>
            let categoriesDataTable = null;
            let categoriesData = [];      // API থেকে আসা সব ক্যাটাগরি (raw)
            let categoriesMobileFiltered = []; // search-এর পর filtered list
            let categoriesMobilePage = 1;
            const categoriesMobilePerPage = 5;

            getCategories();

            async function getCategories() {
                let URL = '{{ url('/api/v1/categories') }}';
                let token = localStorage.getItem('token');
                let tbody = document.getElementById('categoriesTableBody');
                let mobileWrap = document.getElementById('categoriesMobile');

                // আগের DataTable instance থাকলে destroy — নাহলে "Cannot reinitialise" error আসবে
                if (categoriesDataTable) {
                    categoriesDataTable.destroy();
                    categoriesDataTable = null;
                }

                try {
                    let response = await axios.get(URL, {
                        headers: {
                            Authorization: 'Bearer ' + token
                        }
                    });

                    categoriesData = response.data['data'] || [];
                    tbody.innerHTML = '';

                    if (categoriesData.length === 0) {
                        let emptyMsg = 'No categories found.';
                        tbody.innerHTML =
                            `<tr><td colspan="6" class="text-center text-muted py-4">${emptyMsg}</td></tr>`;
                        mobileWrap.innerHTML =
                            `<div class="text-center text-muted py-4">${emptyMsg}</div>`;
                        document.getElementById('categoriesMobilePageInfo').textContent = '';
                        return;
                    }

                    categoriesData.forEach((item) => {
                        let created = item['created_at'] ? item['created_at'].substring(0, 10) : '-';
                        let statusBadge = item['status'] ? '<span class="badge text-bg-success">Active</span>' :
                            '<span class="badge text-bg-secondary">Inactive</span>';

                        // ---- Desktop table row ----
                        tbody.innerHTML += (
                            `<tr>
                                <td>${item['id']}</td>
                                <td class="fw-semibold">${item['name']}</td>
                                <td class="text-muted">${item['description']}</td>
                                <td>${statusBadge}</td>
                                <td class="text-muted">${created}</td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="editCategory(${item['id']})">Edit</button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteCategory(${item['id']})">Delete</button>
                                </td>
                            </tr>`
                        );
                    });

                    // Data Tables (শুধু desktop table-এর উপর init হচ্ছে)
                    categoriesDataTable = new DataTable('#categoriesTable', {
                        perPage: 5,
                        perPageSelect: [5, 10, 50, 100],
                    });

                    // Mobile: search reset করে প্রথম পেজ থেকে render
                    document.getElementById('categoriesMobileSearch').value = '';
                    categoriesMobilePage = 1;
                    applyCategoriesMobileFilter();
                } catch (error) {
                    let errMsg = 'Failed to load categories.';
                    tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-4">${errMsg}</td></tr>`;
                    mobileWrap.innerHTML = `<div class="text-center text-muted py-4">${errMsg}</div>`;
                    document.getElementById('categoriesMobilePageInfo').textContent = '';
                    showErrorToast(getErrorMessage(error, errMsg));
                }
            }

            // ---------- Mobile: search + pagination ----------

            function applyCategoriesMobileFilter() {
                let query = document.getElementById('categoriesMobileSearch').value.trim().toLowerCase();

                categoriesMobileFiltered = !query ? categoriesData.slice() : categoriesData.filter((item) => {
                    let name = (item['name'] || '').toLowerCase();
                    let desc = (item['description'] || '').toLowerCase();
                    return name.includes(query) || desc.includes(query);
                });

                categoriesMobilePage = 1;
                renderCategoriesMobilePage();
            }

            function renderCategoriesMobilePage() {
                let mobileWrap = document.getElementById('categoriesMobile');
                let pageInfo = document.getElementById('categoriesMobilePageInfo');
                let prevBtn = document.getElementById('categoriesMobilePrev');
                let nextBtn = document.getElementById('categoriesMobileNext');

                let total = categoriesMobileFiltered.length;

                if (total === 0) {
                    mobileWrap.innerHTML = '<div class="text-center text-muted py-4">No matching categories.</div>';
                    pageInfo.textContent = '0 results';
                    prevBtn.disabled = true;
                    nextBtn.disabled = true;
                    return;
                }

                let totalPages = Math.ceil(total / categoriesMobilePerPage);
                if (categoriesMobilePage > totalPages) categoriesMobilePage = totalPages;

                let start = (categoriesMobilePage - 1) * categoriesMobilePerPage;
                let pageItems = categoriesMobileFiltered.slice(start, start + categoriesMobilePerPage);

                mobileWrap.innerHTML = '';
                pageItems.forEach((item) => {
                    let created = item['created_at'] ? item['created_at'].substring(0, 10) : '-';
                    let statusBadge = item['status'] ? '<span class="badge text-bg-success">Active</span>' :
                        '<span class="badge text-bg-secondary">Inactive</span>';

                    mobileWrap.innerHTML += (
                        `<div class="border-bottom px-3 py-3">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <div class="fw-semibold">${item['name']}</div>
                                    <div class="text-muted small">ID: ${item['id']}</div>
                                </div>
                                ${statusBadge}
                            </div>
                            <p class="text-muted small mb-2 mt-2">${item['description'] || '-'}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">Created: ${created}</span>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="editCategory(${item['id']})">Edit</button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteCategory(${item['id']})">Delete</button>
                                </div>
                            </div>
                        </div>`
                    );
                });

                pageInfo.textContent = `Page ${categoriesMobilePage} of ${totalPages} (${total} results)`;
                prevBtn.disabled = categoriesMobilePage <= 1;
                nextBtn.disabled = categoriesMobilePage >= totalPages;
            }

            document.getElementById('categoriesMobileSearch').addEventListener('input', applyCategoriesMobileFilter);

            document.getElementById('categoriesMobilePrev').addEventListener('click', function () {
                if (categoriesMobilePage > 1) {
                    categoriesMobilePage--;
                    renderCategoriesMobilePage();
                }
            });

            document.getElementById('categoriesMobileNext').addEventListener('click', function () {
                let totalPages = Math.ceil(categoriesMobileFiltered.length / categoriesMobilePerPage);
                if (categoriesMobilePage < totalPages) {
                    categoriesMobilePage++;
                    renderCategoriesMobilePage();
                }
            });
        </script>
    @endpush
@endsection
