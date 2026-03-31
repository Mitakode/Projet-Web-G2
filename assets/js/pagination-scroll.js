(() => {
    // Session storage key used for dashboard scroll restore
    const storageKey = 'dashboardScrollY';

    // Router may expose the route through ?uri or pathname
    // Normalize both forms to detect the current view
    const isDashboardView = () => {
        const url = new URL(window.location.href);
        const route = (url.searchParams.get('uri') || url.pathname)
            .replace(/^\/+|\/+$/g, '') || '/';
        return route === 'dashboard/admin' || route === 'dashboard/student';
    };

    // Apply this behavior only on dashboard pages
    if (!isDashboardView()) {
        return;
    }

    // Save vertical position right before navigation
    const saveScrollPosition = () => {
        sessionStorage.setItem(storageKey, String(window.scrollY));
    };

    // Persist scroll state when clicking pagination links
    document.addEventListener('click', (event) => {
        const paginationLink = event.target.closest('.pagination a');
        if (!paginationLink) {
            return;
        }
        saveScrollPosition();
    });

    // Restore saved scroll once after next page load
    window.addEventListener('load', () => {
        const savedScroll = sessionStorage.getItem(storageKey);
        if (!savedScroll) {
            return;
        }

        // Prevent repeated restoration on following navigations
        sessionStorage.removeItem(storageKey);
        const scrollY = parseInt(savedScroll, 10);
        if (Number.isNaN(scrollY) || scrollY < 0) {
            return;
        }

        window.scrollTo(0, scrollY);
    });
})();
