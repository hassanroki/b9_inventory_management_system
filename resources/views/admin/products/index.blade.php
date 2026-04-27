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
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
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
                    <tbody>
                        <!-- Static demo data (design only) -->
                        <tr>
                            <td>1</td>
                            <td>
                                <div class="fw-semibold">iPhone 15 Pro</div>
                                <div class="text-muted small">Color: Natural Titanium • Size: 256GB</div>
                            </td>
                            <td class="text-muted">APL-IP15P-256</td>
                            <td class="fw-semibold">Electronics</td>
                            <td class="text-muted">pcs</td>
                            <td class="fw-semibold">$ 1,299.00</td>
                            <td>
                                <span class="badge text-bg-success">42</span>
                            </td>
                            <td><span class="badge text-bg-success">Active</span></td>
                            <td class="text-muted">2026-02-01</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-secondary" disabled>Edit</button>
                                <button type="button" class="btn btn-sm btn-outline-danger" disabled>Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>
                                <div class="fw-semibold">Notebook (A5)</div>
                                <div class="text-muted small">Weight: 0.25kg</div>
                            </td>
                            <td class="text-muted">ST-NB-A5-001</td>
                            <td class="fw-semibold">Stationery</td>
                            <td class="text-muted">pcs</td>
                            <td class="fw-semibold">$ 2.50</td>
                            <td>
                                <span class="badge text-bg-warning">3</span>
                                <div class="text-muted small">Low stock</div>
                            </td>
                            <td><span class="badge text-bg-success">Active</span></td>
                            <td class="text-muted">2026-02-02</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-secondary" disabled>Edit</button>
                                <button type="button" class="btn btn-sm btn-outline-danger" disabled>Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>
                                <div class="fw-semibold">Premium Rice</div>
                                <div class="text-muted small">Weight: 5.00kg</div>
                            </td>
                            <td class="text-muted">GR-RICE-5KG</td>
                            <td class="fw-semibold">Groceries</td>
                            <td class="text-muted">kg</td>
                            <td class="fw-semibold">$ 12.99</td>
                            <td>
                                <span class="badge text-bg-secondary">0</span>
                                <div class="text-muted small">Out of stock</div>
                            </td>
                            <td><span class="badge text-bg-secondary">Inactive</span></td>
                            <td class="text-muted">2026-02-03</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-secondary" disabled>Edit</button>
                                <button type="button" class="btn btn-sm btn-outline-danger" disabled>Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    @include('admin.products.create')
@endsection
