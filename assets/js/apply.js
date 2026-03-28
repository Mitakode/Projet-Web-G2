document.getElementById('apply').addEventListener('submit', function (event) {
    console.log('Form submitted');
    // cv
    const inputCv = document.getElementById('cv');
    const cv = inputCv.files[0];

    // LM
    const inputLm = document.getElementById('lettre');
    const letter = inputLm.files[0];

    let errorMessages = "";

    // Vérifier la sélection des fichiers
    if (!cv && !letter) {
        errorMessages += "Veuillez sélectionner un CV et une lettre de motivation avant d'envoyer.\n";
    } else if (!cv) {
        errorMessages += "Veuillez sélectionner un CV avant d'envoyer.\n";
        console.log('CV manquant');
    } else if (!letter) {
        errorMessages += "Veuillez sélectionner une lettre de motivation avant d'envoyer.\n";
        console.log('Lettre manquante');
    }

    // Vérifier le type PDF (uniquement si les fichiers sont sélectionnés)
    if (cv && letter && cv.type !== 'application/pdf' && letter.type !== 'application/pdf') {
        errorMessages += "Le CV et la lettre de motivation doivent être au format PDF.\n";
    } else if (cv && cv.type !== 'application/pdf') {
        errorMessages += "Le CV doit être au format PDF.\n";
    } else if (letter && letter.type !== 'application/pdf') {
        errorMessages += "La lettre de motivation doit être au format PDF.\n";
    }

    // Vérifier la taille (uniquement si les fichiers sont sélectionnés)
    const maxSize = 4 * 1024 * 1024; // 4 Mo
    if (cv && letter && cv.size > maxSize && letter.size > maxSize) {
        errorMessages += "Le CV et la lettre de motivation sont trop lourds (maximum 4 Mo chacun).\n";
    } else if (cv && cv.size > maxSize) {
        errorMessages += "Le CV est trop lourd (maximum 4 Mo).\n";
    } else if (letter && letter.size > maxSize) {
        errorMessages += "La lettre de motivation est trop lourde (maximum 4 Mo).\n";
    }

    // Afficher l'erreur ou soumettre
    if (errorMessages) {
        event.preventDefault();
        console.log('Erreurs:', errorMessages);
        window.showErrorPopup(errorMessages);
    } else {
        window.showSuccessPopup("Candidature envoyée avec succès !");
    }
});