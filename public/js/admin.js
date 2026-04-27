/**
 * Admin layout - sidebar toggle & collapse
 */
(function () {
    'use strict';

    const sidebar = document.getElementById('adminSidebar');
    const mobileToggle = document.getElementById('sidebarToggle');
    const collapseToggle = document.getElementById('sidebarCollapseToggle');
    const storageKey = 'ims_admin_sidebar_collapsed';

    // Restore collapsed state (desktop)
    try {
        const collapsed = localStorage.getItem(storageKey) === '1';
        if (collapsed) {
            document.body.classList.add('sidebar-collapsed');
        }
    } catch (e) {}

    // Mobile show/hide (offcanvas-like)
    if (mobileToggle && sidebar) {
        mobileToggle.addEventListener('click', function () {
            sidebar.classList.toggle('show');
        });
    }

    // Desktop collapse/expand
    if (collapseToggle) {
        collapseToggle.addEventListener('click', function () {
            document.body.classList.toggle('sidebar-collapsed');
            try {
                localStorage.setItem(
                    storageKey,
                    document.body.classList.contains('sidebar-collapsed') ? '1' : '0'
                );
            } catch (e) {}
        });
    }
})();
