<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-brand">
        <span class="sidebar-logo" aria-hidden="true">
            <i class="bi bi-box-seam"></i>
        </span>
        <span class="sidebar-brand-text">
            <span class="sidebar-brand-name">IMS</span>
            <span class="sidebar-brand-sub">Inventory</span>
        </span>
    </div>

    <nav class="sidebar-nav" aria-label="Main">
        <p class="sidebar-label">Overview</p>
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" title="Dashboard">
            <i class="bi bi-grid-1x2"></i>
            <span>Dashboard</span>
        </a>

        <p class="sidebar-label">Inventory</p>
        <a class="nav-link {{ request()->routeIs('categories') ? 'active' : '' }}" href="{{ route('categories') }}" title="Categories">
            <i class="bi bi-tags"></i>
            <span>Categories</span>
        </a>
        <a class="nav-link {{ request()->routeIs('products') ? 'active' : '' }}" href="{{ route('products') }}" title="Products">
            <i class="bi bi-box-seam"></i>
            <span>Products</span>
        </a>
        <a class="nav-link {{ request()->routeIs('stocks') ? 'active' : '' }}" href="{{ route('stocks') }}" title="Product Stock">
            <i class="bi bi-archive"></i>
            <span>Product Stock</span>
        </a>

        <p class="sidebar-label">Sales</p>
        <a class="nav-link {{ request()->routeIs('customers') ? 'active' : '' }}" href="{{ route('customers') }}" title="Customers">
            <i class="bi bi-people"></i>
            <span>Customers</span>
        </a>
        <a class="nav-link {{ request()->routeIs('pos') ? 'active' : '' }}" href="{{ route('pos') }}" title="POS / Invoice">
            <i class="bi bi-receipt"></i>
            <span>POS / Invoice</span>
        </a>
        <a class="nav-link {{ request()->routeIs('invoices') ? 'active' : '' }}" href="{{ route('invoices') }}" title="Invoices">
            <i class="bi bi-file-earmark-text"></i>
            <span>Invoices</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <button type="button" class="sidebar-logout" id="logoutBtn" title="Log out">
            <i class="bi bi-box-arrow-right"></i>
            <span>Log out</span>
        </button>
    </div>
</aside>
<div class="sidebar-overlay" id="sidebarOverlay" hidden></div>
