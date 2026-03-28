(function () {
    var initialized = false;
    var queryPopupShown = false;

    function showAlert(title, message)
    {
        const text = message || '';
        alert(title + '\n\n' + text);
    }

    function initApplyForm()
    {
        var applyForm = document.getElementById('apply');
        if (!applyForm) {
            return;
        }

        applyForm.addEventListener('submit', function (event) {
            var inputCv = document.getElementById('cv');
            var cv = inputCv && inputCv.files ? inputCv.files[0] : null;

            var inputLm = document.getElementById('lettre');
            var letter = inputLm && inputLm.files ? inputLm.files[0] : null;

            var errorMessages = "";

            if (!cv && !letter) {
                errorMessages += "Veuillez sélectionner un CV et une lettre de motivation avant d'envoyer.\n";
            } else if (!cv) {
                errorMessages += "Veuillez sélectionner un CV avant d'envoyer.\n";
            } else if (!letter) {
                errorMessages += "Veuillez sélectionner une lettre de motivation avant d'envoyer.\n";
            }

            if (cv && letter && cv.type !== 'application/pdf' && letter.type !== 'application/pdf') {
                errorMessages += "Le CV et la lettre de motivation doivent être au format PDF.\n";
            } else if (cv && cv.type !== 'application/pdf') {
                errorMessages += "Le CV doit être au format PDF.\n";
            } else if (letter && letter.type !== 'application/pdf') {
                errorMessages += "La lettre de motivation doit être au format PDF.\n";
            }

            var maxSize = 4 * 1024 * 1024;
            if (cv && letter && cv.size > maxSize && letter.size > maxSize) {
                errorMessages += "Le CV et la lettre de motivation sont trop lourds (maximum 4 Mo chacun).\n";
            } else if (cv && cv.size > maxSize) {
                errorMessages += "Le CV est trop lourd (maximum 4 Mo).\n";
            } else if (letter && letter.size > maxSize) {
                errorMessages += "La lettre de motivation est trop lourde (maximum 4 Mo).\n";
            }

            if (errorMessages) {
                event.preventDefault();
                window.showErrorPopup(errorMessages);
            }
        });
    }

    function initCompanyForm()
    {
        var contactInput = document.querySelector('input[name="contactCompany"]');
        if (!contactInput) {
            return;
        }

        var form = contactInput.closest('form');
        if (!form) {
            return;
        }

        form.addEventListener('submit', function (event) {
            var regex = /^0\d{9} - [a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            if (!regex.test(contactInput.value)) {
                event.preventDefault();
                window.showErrorPopup("Le format doit être : 0145632890 - contact@thepiston.fr");
            }
        });
    }

    function initCompanyRating()
    {
        window.ouvrirPopupNote = function (idEntreprise, nomEntreprise) {
            var message = 'Notez "' + nomEntreprise + '"\n\nChoisissez une note de 1 à 10 :\n1 = Mauvais\n5 = Moyen\n10 = Excellent';
            var promptValue = window.prompt(message, '');

            if (promptValue === null) {
                return;
            }

            var note = parseInt(promptValue, 10);

            if (isNaN(note) || note < 1 || note > 10) {
                window.showErrorPopup('Veuillez entrer un nombre entre 1 et 10');
                return;
            }

            if (typeof window.saveScrollState === 'function') {
                window.saveScrollState();
            }

            var currentUrl = new URL(window.location.href);
            var params = new URLSearchParams();
            params.set('uri', 'companies/rate');
            params.set('id', String(idEntreprise));
            params.set('rating', String(note));

            var recherche = currentUrl.searchParams.get('recherche') || '';
            var page = currentUrl.searchParams.get('page') || '1';

            params.set('recherche', recherche);
            params.set('page', page);

            window.location.href = 'index.php?' + params.toString();
        };
    }

    function showPopupFromQuery()
    {
        if (queryPopupShown) {
            return;
        }

        const params = new URLSearchParams(window.location.search);
        const popup = params.get('popup');

        if (!popup) {
            return;
        }

        const successMessages = {
            student_created: "Étudiant créé avec succès !",
            student_updated: "Étudiant modifié avec succès !",
            student_deleted: "Étudiant supprimé avec succès !",
            pilot_created: "Pilote créé avec succès !",
            pilot_updated: "Pilote modifié avec succès !",
            pilot_deleted: "Pilote supprimé avec succès !",
            company_created: "Entreprise créée avec succès !",
            company_updated: "Entreprise modifiée avec succès !",
            company_deleted: "Entreprise supprimée avec succès !",
            offer_deleted: "Offre supprimée avec succès !",
            offer_updated: "Offre modifiée avec succès !",
            company_rated: "Note enregistrée avec succès !",
            success: "Candidature envoyée avec succès !"
        };

        const errorMessages = {
            error: "Une erreur est survenue. Veuillez réessayer.",
            already_applied: "Vous avez déjà candidaté à cette offre.",
            student_delete_blocked: "Impossible de supprimer cet étudiant car des données y sont liées.",
            student_delete_error: "Erreur lors de la suppression de l'étudiant.",
            pilot_delete_blocked: "Impossible de supprimer ce pilote car des étudiants lui sont associés.",
            pilot_delete_error: "Erreur lors de la suppression du pilote.",
            company_delete_blocked: "Impossible de supprimer cette entreprise car des évaluations ou offres y sont liées.",
            company_delete_error: "Erreur lors de la suppression de l'entreprise.",
            offer_delete_blocked: "Impossible de supprimer cette offre car des candidatures y sont liées.",
            offer_delete_error: "Erreur lors de la suppression de l'offre."
        };

        if (successMessages[popup]) {
            queryPopupShown = true;
            window.showSuccessPopup(successMessages[popup]);

            params.delete('popup');
            const cleanQuery = params.toString();
            const cleanUrl = window.location.pathname + (cleanQuery ? '?' + cleanQuery : '');
            window.history.replaceState({}, '', cleanUrl);
            return;
        }

        if (errorMessages[popup]) {
            queryPopupShown = true;
            window.showErrorPopup(errorMessages[popup]);

            params.delete('popup');
            const cleanQuery = params.toString();
            const cleanUrl = window.location.pathname + (cleanQuery ? '?' + cleanQuery : '');
            window.history.replaceState({}, '', cleanUrl);
        }
    }

    window.showSuccessPopup = function (message, duration) {
        showAlert('✅ Succès', message || '✅ Opération effectuée avec succès.');
    };

    window.showErrorPopup = function (message, duration) {
        showAlert('❌ Erreur', message || '❌ Une erreur est survenue.');
    };

    function initPopups()
    {
        if (initialized) {
            return;
        }

        initialized = true;
        initApplyForm();
        initCompanyForm();
        initCompanyRating();

        // Les popups liées à la redirection doivent s'afficher après chargement complet.
        if (document.readyState === 'complete') {
            showPopupFromQuery();
        } else {
            window.addEventListener('load', showPopupFromQuery, { once: true });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPopups);
    } else {
        initPopups();
    }
})();
