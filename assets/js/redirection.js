(function () {
    var container = document.querySelector('.access-page');
    if (!container) {
        return;
    }

    var redirectUrl = container.getAttribute('data-redirect-url') || '/login';
    var redirectDelay = Number(container.getAttribute('data-redirect-delay') || '5000');
    var countdownElement = document.getElementById('redirect-countdown');
    var secondsLeft = Math.max(1, Math.ceil(redirectDelay / 1000));

    if (countdownElement) {
        countdownElement.textContent = String(secondsLeft);

        var countdownTimer = setInterval(function () {
            secondsLeft -= 1;
            if (secondsLeft >= 0) {
                countdownElement.textContent = String(secondsLeft);
            }

            if (secondsLeft <= 0) {
                clearInterval(countdownTimer);
            }
        }, 1000);
    }

    setTimeout(function () {
        window.location.href = redirectUrl;
    }, redirectDelay);
})();
