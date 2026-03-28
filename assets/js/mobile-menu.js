(function () {
    const burgerBtn = document.querySelector('.burger-btn');
    const nav = document.getElementById('main-nav');

    if (!burgerBtn || !nav) {
        return;
    }

    const closeMenu = function () {
        document.body.classList.remove('menu-open');
        burgerBtn.setAttribute('aria-expanded', 'false');
    };

    burgerBtn.addEventListener('click', function () {
        const isOpen = document.body.classList.toggle('menu-open');
        burgerBtn.setAttribute('aria-expanded', String(isOpen));
    });

    nav.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', closeMenu);
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth > 768) {
            closeMenu();
        }
    });
})();
