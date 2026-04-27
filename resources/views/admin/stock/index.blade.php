@extends('layouts.admin')
@section('title', 'Stocks')
@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">Stock In</div>
                <div class="card-body">
                    <p class="text-muted small">Record new stock received. Form will be wired to API later.</p>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#stockInModal">
                        <i class="bi bi-box-arrow-in-down me-1"></i> Stock In
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">Stock Adjustment</div>
                <div class="card-body">
                    <p class="text-muted small">Adjust quantity (corrections / damage). Form will be wired to API later.</p>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#stockAdjustModal">
                        <i class="bi bi-pencil-square me-1"></i> Adjust
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span>Stock Movements</span>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#stockInModal">
                    <i class="bi bi-plus-lg me-1"></i> Stock In
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                    data-bs-target="#stockAdjustModal">
                    <i class="bi bi-sliders me-1"></i> Adjustment
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
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
                    <tbody>
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
                        <tr>
                            <td>2</td>
                            <td class="fw-semibold">Notebook (A5)</td>
                            <td class="text-muted">Stationery</td>
                            <td><span class="badge text-bg-danger">OUT</span></td>
                            <td class="fw-semibold">-2</td>
                            <td class="text-muted">Damage / correction</td>
                            <td class="text-muted">—</td>
                            <td class="text-muted">2026-02-02</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td class="fw-semibold">Premium Rice</td>
                            <td class="text-muted">Groceries</td>
                            <td><span class="badge text-bg-danger">OUT</span></td>
                            <td class="fw-semibold">-5</td>
                            <td class="text-muted">Sold via POS</td>
                            <td>
                                <a href="#" class="text-decoration-none">
                                    <i class="bi bi-receipt me-1"></i>INV-00042
                                </a>
                            </td>
                            <td class="text-muted">2026-02-03</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td class="fw-semibold">iPhone 15 Pro</td>
                            <td class="text-muted">Electronics</td>
                            <td><span class="badge text-bg-danger">OUT</span></td>
                            <td class="fw-semibold">-1</td>
                            <td class="text-muted">Sold via POS</td>
                            <td>
                                <a href="#" class="text-decoration-none">
                                    <i class="bi bi-receipt me-1"></i>INV-00042
                                </a>
                            </td>
                            <td class="text-muted">2026-02-03</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Stock In Modal -->
    @include('admin.stock.stock_in')

    <!-- Stock Adjustment Modal -->
    @include('admin.stock.adjust')
@endsection
