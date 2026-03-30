(() => {
    const storageKey = 'dashboardScrollY';

    const isDashboardView = () => {
        const url = new URL(window.location.href);
        const route = (url.searchParams.get('uri') || url.pathname)
            .replace(/^\/+|\/+$/g, '') || '/';
        return route === 'dashboard/admin' || route === 'dashboard/student';
    };

    if (!isDashboardView()) {
        return;
    }

    const saveScrollPosition = () => {
        sessionStorage.setItem(storageKey, String(window.scrollY));
    };

    document.addEventListener('click', (event) => {
        const paginationLink = event.target.closest('.pagination a');
        if (!paginationLink) {
            return;
        }
        saveScrollPosition();
    });

    window.addEventListener('load', () => {
        const savedScroll = sessionStorage.getItem(storageKey);
        if (!savedScroll) {
            return;
        }

        sessionStorage.removeItem(storageKey);
        const scrollY = parseInt(savedScroll, 10);
        if (Number.isNaN(scrollY) || scrollY < 0) {
            return;
        }

        window.scrollTo(0, scrollY);
    });
})();
