@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-primary bg-opacity-10 p-3">
                        <i class="bi bi-tags text-primary fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Categories</p>
                        <h4 class="mb-0">3</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-success bg-opacity-10 p-3">
                        <i class="bi bi-box text-success fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Products</p>
                        <h4 class="mb-0">8</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-info bg-opacity-10 p-3">
                        <i class="bi bi-archive text-info fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Stock Items</p>
                        <h4 class="mb-0">320</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-warning bg-opacity-10 p-3">
                        <i class="bi bi-receipt text-warning fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Invoices</p>
                        <h4 class="mb-0">42</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">Recent Activity</div>
                <div class="card-body">
                    <p class="text-muted mb-0">Activity summary will appear here once connected to APIs.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">Quick Actions</div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="pos.html" class="btn btn-primary">
                            <i class="bi bi-receipt me-2"></i> New Invoice (POS)
                        </a>
                        <a href="products.html" class="btn btn-outline-primary">
                            <i class="bi bi-box me-2"></i> Add Product
                        </a>
                        <a href="stock.html" class="btn btn-outline-secondary">
                            <i class="bi bi-archive me-2"></i> Stock In
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
