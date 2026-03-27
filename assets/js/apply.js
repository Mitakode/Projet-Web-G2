document.getElementById('apply').addEventListener('submit', function (event) {
    // cv
    const inputCv = document.getElementById('cv');
    const cv = inputCv.files[0];

    // LM
    const inputLm = document.getElementById('lettre');
    const letter = inputLm.files[0];

    let counter = 0;

    if (!cv && !letter) {
        window.showErrorPopup("Veuillez sélectionner un CV et une lettre de motivation avant d'envoyer.");
        event.preventDefault();
        return;
    } else if (!cv) {
        window.showErrorPopup("Veuillez sélectionner un CV avant d'envoyer.");
        event.preventDefault();
        return;
    } else if (!letter) {
        window.showErrorPopup("Veuillez sélectionner une lettre de motivation avant d'envoyer.");
        event.preventDefault();
        return;
    } else {
        counter++;
    }


    if (!cv.type === 'application/pdf') {
        window.showErrorPopup("Le CV doit être au format PDF.");
        event.preventDefault();
        return;
    } else if (!letter.type === 'application/pdf') {
        window.showErrorPopup("La lettre de motivation doit être au format PDF.");
        event.preventDefault();
        return;
    } else if (!cv.type === 'application/pdf' && !letter.type === 'application/pdf') {
        window.showErrorPopup("Les fichiers doivent être au format PDF.");
        event.preventDefault();
        return;
    } else {
        counter++;
    }

    const maxSize = 2 * 1024 * 1024; // 2 Mo
    if (cv.size > maxSize) {
        window.showErrorPopup("Le CV est trop lourd (maximum 2 Mo).");
        event.preventDefault();
    } else if (letter.size > maxSize) {
        window.showErrorPopup("La lettre de motivation est trop lourde (maximum 2 Mo).");
        event.preventDefault();
        return;
    } else if (cv.size > maxSize && letter.size > maxSize) {
        window.showErrorPopup("Le CV et la lettre de motivation sont trop lourds (maximum 2 Mo chacun).");
        event.preventDefault();
        return;
    } else {
        counter++;
    }

    if (counter === 3) {
        window.showSuccessPopup("Candidature envoyée avec succès !");
    }
});