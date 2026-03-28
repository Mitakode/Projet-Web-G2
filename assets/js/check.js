const contactInput = document.querySelector('input[name="contactCompany"]');
const form = contactInput.closest('form');

form.addEventListener('submit', function(event) {
    // 10 chiffres + " - " + format email
    const regex = /^0\d{9} - [a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    if (!regex.test(contactInput.value)) {
        event.preventDefault();
        window.showErrorPopup("Le format doit être : 0145632890 - contact@thepiston.fr")
    }
    else {
        window.showSuccessPopup("Entreprise ajoutée/modifiée avec succès !");
    }
});