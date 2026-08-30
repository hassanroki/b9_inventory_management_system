/**
 * Admin layout - sidebar toggle & collapse
 */
(function () {
    "use strict";

    const sidebar = document.getElementById("adminSidebar");
    const overlay = document.getElementById("sidebarOverlay");
    const mobileToggle = document.getElementById("sidebarToggle");
    const collapseToggle = document.getElementById("sidebarCollapseToggle");
    const storageKey = "ims_admin_sidebar_collapsed";

    function setSidebarOpen(open) {
        if (!sidebar) return;
        sidebar.classList.toggle("show", open);
        if (overlay) {
            overlay.classList.toggle("is-visible", open);
            overlay.hidden = !open;
        }
        document.body.style.overflow = open && window.innerWidth < 992 ? "hidden" : "";
    }

    try {
        const collapsed = localStorage.getItem(storageKey) === "1";
        if (collapsed) {
            document.body.classList.add("sidebar-collapsed");
        }
    } catch (e) {}

    if (mobileToggle && sidebar) {
        mobileToggle.addEventListener("click", function () {
            setSidebarOpen(!sidebar.classList.contains("show"));
        });
    }

    if (overlay) {
        overlay.addEventListener("click", function () {
            setSidebarOpen(false);
        });
    }

    if (collapseToggle) {
        collapseToggle.addEventListener("click", function () {
            document.body.classList.toggle("sidebar-collapsed");
            try {
                localStorage.setItem(
                    storageKey,
                    document.body.classList.contains("sidebar-collapsed")
                        ? "1"
                        : "0",
                );
            } catch (e) {}
        });
    }

    const dateEl = document.getElementById("headerClock");
    const dateIcon = document.getElementById("headerMetaIcon");
    if (dateEl) {
        let showTime = false;
        const dateOptions = {
            weekday: "short",
            month: "short",
            day: "numeric",
            year: "numeric",
        };
        const timeOptions = {
            hour: "2-digit",
            minute: "2-digit",
            second: "2-digit",
            hour12: true,
        };

        function renderClock() {
            const now = new Date();
            dateEl.dateTime = now.toISOString();
            if (showTime) {
                dateEl.textContent = now.toLocaleTimeString(undefined, timeOptions);
                if (dateIcon) dateIcon.className = "bi bi-clock";
            } else {
                dateEl.textContent = now.toLocaleDateString(undefined, dateOptions);
                if (dateIcon) dateIcon.className = "bi bi-calendar3";
            }
        }

        renderClock();
        setInterval(renderClock, 1000);
        setInterval(function () {
            showTime = !showTime;
            if (dateEl.classList) {
                dateEl.classList.add("header-clock-swap");
                window.setTimeout(function () {
                    dateEl.classList.remove("header-clock-swap");
                }, 280);
            }
            renderClock();
        }, 4000);
    }

    try {
        const raw = localStorage.getItem("user");
        const user = raw ? JSON.parse(raw) : {};
        const name = user.name || user.full_name || user.email || "Admin";
        const email = user.email || "administrator";
        const nameEl = document.getElementById("headerUserName");
        const avatarEl = document.getElementById("headerAvatar");
        const menuName = document.getElementById("headerMenuUserName");
        const menuEmail = document.getElementById("headerMenuUserEmail");
        if (nameEl) nameEl.textContent = name;
        if (menuName) menuName.textContent = name;
        if (menuEmail) menuEmail.textContent = email;
        if (avatarEl) {
            avatarEl.textContent = String(name).trim().charAt(0).toUpperCase();
        }
    } catch (e) {}

    const logoutBtn = document.getElementById("logoutBtn");
    const headerLogoutBtn = document.getElementById("headerLogoutBtn");
    if (logoutBtn) {
        logoutBtn.addEventListener("click", async function (e) {
            e.preventDefault();
            await doLogout();
        });
    }
    if (headerLogoutBtn) {
        headerLogoutBtn.addEventListener("click", async function (e) {
            e.preventDefault();
            await doLogout();
        });
    }

    async function doLogout() {
        const token = localStorage.getItem("token");
        const url = "/api/v1/logout";

        try {
            if (token) {
                const response = await axios.post(
                    url,
                    {},
                    {
                        headers: {
                            Authorization: "Bearer " + token,
                        },
                    },
                );

                if (response.status !== 200) {
                    showErrorToast("Logout failed. Please try again.");
                }
            }
        } catch (error) {
            showErrorToast(
                getErrorMessage(error, "Logout failed. Please try again"),
            );
        } finally {
            localStorage.removeItem("token");
            localStorage.removeItem("user");
            document.cookie = "api_token=; path=/; max-age=0; SameSite=Lax";
            window.location.href = "/login";
        }
    }
})();
