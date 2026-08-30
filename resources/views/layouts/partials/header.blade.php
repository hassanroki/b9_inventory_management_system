<header class="admin-header">
    <div class="header-left">
        <button class="header-icon-btn d-none d-lg-inline-flex" type="button" id="sidebarCollapseToggle"
            aria-label="Collapse sidebar">
            <i class="bi bi-layout-sidebar-inset"></i>
        </button>
        <button class="header-icon-btn d-lg-none" type="button" id="sidebarToggle" aria-label="Toggle sidebar">
            <i class="bi bi-list"></i>
        </button>
        <div class="header-title-wrap">
            <h1 class="header-title">@yield('title', 'Dashboard')</h1>
        </div>
    </div>

    <div class="header-right">
        <div class="header-meta" aria-live="polite">
            <i class="bi bi-calendar3" id="headerMetaIcon"></i>
            <time id="headerClock" datetime=""></time>
        </div>

        <div class="dropdown header-profile">
            <button class="header-user" type="button" id="headerProfileToggle" data-bs-toggle="dropdown"
                data-bs-offset="0,10" aria-expanded="false" aria-haspopup="true" aria-label="Open profile menu">
                <span class="header-avatar" id="headerAvatar">A</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end header-profile-menu" aria-labelledby="headerProfileToggle">
                <li class="header-profile-summary px-3 py-2">
                    <span class="d-block fw-semibold text-dark" id="headerMenuUserName">Admin</span>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a class="dropdown-item {{ request()->routeIs('settings') ? 'active' : '' }}"
                        href="{{ route('settings') }}">
                        <i class="bi bi-gear me-2"></i> Settings
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <button type="button" class="dropdown-item text-danger" id="headerLogoutBtn">
                        <i class="bi bi-box-arrow-right me-2"></i> Log out
                    </button>
                </li>
            </ul>
        </div>
    </div>
</header>
