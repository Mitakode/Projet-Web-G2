(function () {
    const burgerBtn = document.querySelector('.burger-btn');
    const nav = document.getElementById('main-nav');

    if (!burgerBtn || !nav) {
        return;
    }

    // Closes the mobile menu and updates accessibility state
    const closeMenu = function () {
        document.body.classList.remove('menu-open');
        burgerBtn.setAttribute('aria-expanded', 'false');
    };

    // Toggles the mobile menu when burger button is clicked
    burgerBtn.addEventListener('click', function () {
        const isOpen = document.body.classList.toggle('menu-open');
        burgerBtn.setAttribute('aria-expanded', String(isOpen));
    });

    // Close menu after navigation link click
    nav.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', closeMenu);
    });

    // Auto close menu when returning to desktop width
    window.addEventListener('resize', function () {
        if (window.innerWidth > 768) {
            closeMenu();
        }
    });
})();
