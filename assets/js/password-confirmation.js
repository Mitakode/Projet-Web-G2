(function () {
    function initAccountForm(form)
    {
        const passwordInput = form.querySelector('[name="password"]');
        const confirmInput = form.querySelector('[name="confirm_password"]');

        if (!passwordInput || !confirmInput) {
            return;
        }

        const syncConfirmState = function () {
            const hasPassword = passwordInput.value.trim() !== '';
            confirmInput.disabled = !hasPassword;

            if (!hasPassword) {
                confirmInput.value = '';
            }
        };

        passwordInput.addEventListener('input', syncConfirmState);
        syncConfirmState();

        const popupError = form.dataset.popupError || '';
        if (popupError && typeof window.showErrorPopup === 'function') {
            window.showErrorPopup(popupError);
        }
    }

    document.querySelectorAll('form[data-account-password-form="true"]').forEach(initAccountForm);
})();
