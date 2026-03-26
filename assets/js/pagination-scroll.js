(() => {
    const storageKey = 'uiScrollState';

    const getLogicalView = (url) => {
        const uriParam = url.searchParams.get('uri');
        if (uriParam) {
            return uriParam;
        }

        const pathname = url.pathname.replace(/^\/+|\/+$/g, '');
        return pathname || '/';
    };

    const saveScrollState = () => {
        const currentUrl = new URL(window.location.href);

        const scrollState = {
            view: getLogicalView(currentUrl),
            scrollY: window.scrollY
        };

        sessionStorage.setItem(storageKey, JSON.stringify(scrollState));
    };

    window.saveScrollState = saveScrollState;

    document.addEventListener('click', (event) => {
        const actionLink = event.target.closest(
            '.pagination a, a[href*="uri=offers/addWishlist"], a[href*="uri=offers/deleteWishlist"]'
        );

        if (!actionLink) {
            return;
        }

        const currentUrl = new URL(window.location.href);
        const targetUrl = new URL(actionLink.href, window.location.origin);

        const currentView = getLogicalView(currentUrl);
        const targetUri = targetUrl.searchParams.get('uri') || '';

        const isSameView =
            currentView === getLogicalView(targetUrl) ||
            (currentView === 'offers' && (targetUri === 'offers/addWishlist' || targetUri === 'offers/deleteWishlist'));

        if (!isSameView) {
            return;
        }

        saveScrollState();
    });

    window.addEventListener('load', () => {
        const rawState = sessionStorage.getItem(storageKey);
        if (!rawState) {
            return;
        }

        sessionStorage.removeItem(storageKey);

        let parsedState;
        try {
            parsedState = JSON.parse(rawState);
        } catch (error) {
            return;
        }

        const currentUrl = new URL(window.location.href);
        const isSameView = (parsedState.view || '') === getLogicalView(currentUrl);

        if (!isSameView) {
            return;
        }

        const scrollY = Number(parsedState.scrollY);
        if (Number.isNaN(scrollY) || scrollY < 0) {
            return;
        }

        window.scrollTo(0, scrollY);
    });
})();
