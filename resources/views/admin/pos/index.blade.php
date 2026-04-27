@extends('layouts.admin')
@section('title', 'POST')
@section('content')
    <div class="row g-4">
        <!-- Left: Products -->
        <div class="col-lg-8">
            <!-- Search -->
            <div class="card mb-3">
                <div class="card-body py-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search product by name or SKU..."
                            id="posSearch">
                    </div>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-grid me-2"></i>Products</span>
                    <span class="text-muted small">Click to add to cart</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Product 1: In stock -->
                        <div class="col-6 col-md-4 col-xl-3">
                            <div class="card pos-product-card h-100">
                                <div class="product-image">
                                    <i class="bi bi-phone"></i>
                                </div>
                                <div class="card-body p-2">
                                    <div class="fw-semibold small text-truncate" title="iPhone 15 Pro">iPhone 15 Pro</div>
                                    <div class="text-muted small">APL-IP15P-256</div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span class="fw-bold text-primary">$ 1,299.00</span>
                                        <span class="badge text-bg-success">42</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 2: In stock -->
                        <div class="col-6 col-md-4 col-xl-3">
                            <div class="card pos-product-card h-100">
                                <div class="product-image">
                                    <i class="bi bi-laptop"></i>
                                </div>
                                <div class="card-body p-2">
                                    <div class="fw-semibold small text-truncate" title="MacBook Pro 14">MacBook Pro 14"
                                    </div>
                                    <div class="text-muted small">APL-MBP14-512</div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span class="fw-bold text-primary">$ 1,999.00</span>
                                        <span class="badge text-bg-success">15</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 3: Low stock -->
                        <div class="col-6 col-md-4 col-xl-3">
                            <div class="card pos-product-card h-100">
                                <div class="product-image">
                                    <i class="bi bi-journal-text"></i>
                                </div>
                                <div class="card-body p-2">
                                    <div class="fw-semibold small text-truncate" title="Notebook (A5)">Notebook (A5)</div>
                                    <div class="text-muted small">ST-NB-A5-001</div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span class="fw-bold text-primary">$ 2.50</span>
                                        <span class="badge text-bg-warning">3</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 4: Out of stock -->
                        <div class="col-6 col-md-4 col-xl-3">
                            <div class="card pos-product-card h-100 out-of-stock">
                                <div class="product-image">
                                    <i class="bi bi-basket3"></i>
                                </div>
                                <div class="card-body p-2">
                                    <div class="fw-semibold small text-truncate" title="Premium Rice">Premium Rice (5kg)
                                    </div>
                                    <div class="text-muted small">GR-RICE-5KG</div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span class="fw-bold text-primary">$ 12.99</span>
                                        <span class="badge text-bg-secondary">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 5 -->
                        <div class="col-6 col-md-4 col-xl-3">
                            <div class="card pos-product-card h-100">
                                <div class="product-image">
                                    <i class="bi bi-headphones"></i>
                                </div>
                                <div class="card-body p-2">
                                    <div class="fw-semibold small text-truncate" title="AirPods Pro">AirPods Pro</div>
                                    <div class="text-muted small">APL-APP-002</div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span class="fw-bold text-primary">$ 249.00</span>
                                        <span class="badge text-bg-success">28</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 6 -->
                        <div class="col-6 col-md-4 col-xl-3">
                            <div class="card pos-product-card h-100">
                                <div class="product-image">
                                    <i class="bi bi-pen"></i>
                                </div>
                                <div class="card-body p-2">
                                    <div class="fw-semibold small text-truncate" title="Ball Pen (Pack of 10)">Ball Pen
                                        (Pack of 10)</div>
                                    <div class="text-muted small">ST-PEN-10PK</div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span class="fw-bold text-primary">$ 5.00</span>
                                        <span class="badge text-bg-success">120</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 7 -->
                        <div class="col-6 col-md-4 col-xl-3">
                            <div class="card pos-product-card h-100">
                                <div class="product-image">
                                    <i class="bi bi-cup-hot"></i>
                                </div>
                                <div class="card-body p-2">
                                    <div class="fw-semibold small text-truncate" title="Green Tea (Box)">Green Tea (Box)
                                    </div>
                                    <div class="text-muted small">GR-TEA-GRN</div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span class="fw-bold text-primary">$ 8.50</span>
                                        <span class="badge text-bg-success">45</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 8 -->
                        <div class="col-6 col-md-4 col-xl-3">
                            <div class="card pos-product-card h-100">
                                <div class="product-image">
                                    <i class="bi bi-mouse"></i>
                                </div>
                                <div class="card-body p-2">
                                    <div class="fw-semibold small text-truncate" title="Wireless Mouse">Wireless Mouse
                                    </div>
                                    <div class="text-muted small">EL-MOUSE-WL</div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span class="fw-bold text-primary">$ 29.99</span>
                                        <span class="badge text-bg-success">67</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Cart -->
        <div class="col-lg-4">
            <div class="card cart-sticky">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                    <span><i class="bi bi-cart3 me-2"></i>Cart</span>
                    <span class="badge bg-white text-primary">3 items</span>
                </div>
                <div class="card-body p-0">
                    <!-- Cart Items -->
                    <div class="p-3" style="max-height: 320px; overflow-y: auto;">
                        <!-- Cart item 1 -->
                        <div class="pos-cart-item">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="flex-grow-1 me-2">
                                    <div class="fw-semibold">iPhone 15 Pro</div>
                                    <div class="text-muted small">$ 1,299.00 × 1</div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger p-1 lh-1">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div class="input-group input-group-sm" style="width: 100px;">
                                    <button class="btn btn-outline-secondary" type="button">−</button>
                                    <input type="number" class="form-control text-center px-1" value="1"
                                        min="1">
                                    <button class="btn btn-outline-secondary" type="button">+</button>
                                </div>
                                <div class="flex-grow-1 text-end fw-semibold">$ 1,299.00</div>
                            </div>
                        </div>

                        <!-- Cart item 2 with item discount -->
                        <div class="pos-cart-item">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="flex-grow-1 me-2">
                                    <div class="fw-semibold">AirPods Pro</div>
                                    <div class="text-muted small">$ 249.00 × 2</div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger p-1 lh-1">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="input-group input-group-sm" style="width: 100px;">
                                    <button class="btn btn-outline-secondary" type="button">−</button>
                                    <input type="number" class="form-control text-center px-1" value="2"
                                        min="1">
                                    <button class="btn btn-outline-secondary" type="button">+</button>
                                </div>
                                <div class="flex-grow-1 text-end fw-semibold">$ 498.00</div>
                            </div>
                            <!-- Item discount -->
                            <div class="d-flex align-items-center gap-2 bg-light rounded p-2">
                                <span class="small text-muted">Discount:</span>
                                <select class="form-select form-select-sm" style="width: 80px;">
                                    <option value="">None</option>
                                    <option value="fixed">$</option>
                                    <option value="percent" selected>%</option>
                                </select>
                                <input type="number" class="form-control form-control-sm" style="width: 60px;"
                                    value="10" min="0">
                                <span class="small text-danger">-$ 49.80</span>
                            </div>
                        </div>

                        <!-- Cart item 3 -->
                        <div class="pos-cart-item">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="flex-grow-1 me-2">
                                    <div class="fw-semibold">Notebook (A5)</div>
                                    <div class="text-muted small">$ 2.50 × 3</div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger p-1 lh-1">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div class="input-group input-group-sm" style="width: 100px;">
                                    <button class="btn btn-outline-secondary" type="button">−</button>
                                    <input type="number" class="form-control text-center px-1" value="3"
                                        min="1">
                                    <button class="btn btn-outline-secondary" type="button">+</button>
                                </div>
                                <div class="flex-grow-1 text-end fw-semibold">$ 7.50</div>
                            </div>
                        </div>
                    </div>

                    <!-- Totals -->
                    <div class="border-top p-3 bg-light">
                        <!-- Subtotal -->
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span>$ 1,804.50</span>
                        </div>

                        <!-- Item discounts total -->
                        <div class="d-flex justify-content-between mb-2 text-danger">
                            <span>Item Discounts</span>
                            <span>- $ 49.80</span>
                        </div>

                        <!-- Invoice discount -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Invoice Discount</span>
                            <div class="d-flex align-items-center gap-1">
                                <select class="form-select form-select-sm" style="width: 65px;">
                                    <option value="">None</option>
                                    <option value="fixed" selected>$</option>
                                    <option value="percent">%</option>
                                </select>
                                <input type="number" class="form-control form-control-sm" style="width: 70px;"
                                    value="50" min="0">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mb-2 text-danger">
                            <span></span>
                            <span>- $ 50.00</span>
                        </div>

                        <hr class="my-2">

                        <!-- Grand Total -->
                        <div class="d-flex justify-content-between fs-5 fw-bold">
                            <span>Grand Total</span>
                            <span class="text-success">$ 1,704.70</span>
                        </div>
                    </div>

                    <!-- Invoice Info & Actions -->
                    <div class="border-top p-3">
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small text-muted mb-1">Invoice No</label>
                                <input type="text" class="form-control form-control-sm" value="INV-202602-0043"
                                    readonly>
                            </div>
                            <div class="col-6">
                                <label class="form-label small text-muted mb-1">Date</label>
                                <input type="date" class="form-control form-control-sm" value="2026-02-04">
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Finalize Invoice
                            </button>
                            <div class="row g-2">
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-save me-1"></i>Save Draft
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-secondary w-100">
                                        <i class="bi bi-x-lg me-1"></i>Clear
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
