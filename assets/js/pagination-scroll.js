(() => {
    // Clé de session dédiée au scroll des dashboards
    const storageKey = 'dashboardScrollY';

    // Le routeur peut fournir la route via ?uri=... ou via le pathname
    // On normalise les deux cas pour reconnaître la vue courante
    const isDashboardView = () => {
        const url = new URL(window.location.href);
        const route = (url.searchParams.get('uri') || url.pathname)
            .replace(/^\/+|\/+$/g, '') || '/';
        return route === 'dashboard/admin' || route === 'dashboard/student';
    };

    // Ce script ne s'applique qu'aux dashboards
    if (!isDashboardView()) {
        return;
    }

    // Mémorise la position verticale juste avant la navigation
    const saveScrollPosition = () => {
        sessionStorage.setItem(storageKey, String(window.scrollY));
    };

    // Sauvegarde le scroll au clic sur un lien de pagination
    document.addEventListener('click', (event) => {
        const paginationLink = event.target.closest('.pagination a');
        if (!paginationLink) {
            return;
        }
        saveScrollPosition();
    });

    // Au chargement de la page suivante, on restaure une seule fois
    window.addEventListener('load', () => {
        const savedScroll = sessionStorage.getItem(storageKey);
        if (!savedScroll) {
            return;
        }

        // Empêche une restauration en boucle sur les navigations suivantes
        sessionStorage.removeItem(storageKey);
        const scrollY = parseInt(savedScroll, 10);
        if (Number.isNaN(scrollY) || scrollY < 0) {
            return;
        }

        window.scrollTo(0, scrollY);
    });
})();
