(function () {
    function showAlert(title, message) {
        const text = message || '';
        alert(title + '\n\n' + text);
    }

    window.showSuccessPopup = function (message, duration) {
        showAlert('✅ Valide', message || '✅ Operation effectuee avec succes.');
    };

    window.showErrorPopup = function (message, duration) {
        showAlert('❌ Erreur', message || '❌ Une erreur est survenue.');
    };
})();
