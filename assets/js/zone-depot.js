const postulerBtn = document.getElementById('postuler-btn');
const zoneDepot = document.getElementById('zone-depot');
const cvInput = document.getElementById('cv');
const lettreInput = document.getElementById('lettre');
const validerBtn = document.getElementById('valider');

const updateValidationState = () => {
    validerBtn.disabled = !(cvInput.files.length && lettreInput.files.length);
};

postulerBtn.addEventListener('click', () => {
    zoneDepot.hidden = !zoneDepot.hidden;
});

cvInput.addEventListener('change', updateValidationState);
lettreInput.addEventListener('change', updateValidationState);
