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
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
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
                    <tbody>
                        <!-- Static demo data (design only) -->
                        <tr>
                            <td>1</td>
                            <td class="fw-semibold">Electronics</td>
                            <td class="text-muted">Phones, laptops, accessories</td>
                            <td><span class="badge text-bg-success">Active</span></td>
                            <td class="text-muted">2026-02-01</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-secondary" disabled>Edit</button>
                                <button type="button" class="btn btn-sm btn-outline-danger" disabled>Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td class="fw-semibold">Groceries</td>
                            <td class="text-muted">Daily essentials and food items</td>
                            <td><span class="badge text-bg-success">Active</span></td>
                            <td class="text-muted">2026-02-02</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-secondary" disabled>Edit</button>
                                <button type="button" class="btn btn-sm btn-outline-danger" disabled>Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td class="fw-semibold">Stationery</td>
                            <td class="text-muted">Pens, notebooks, office supplies</td>
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

    <!-- Add Category Modal -->
    @include('admin.categories.create')
@endsection
