const postulerBtn = document.getElementById('postuler-btn');
const zoneDepot = document.getElementById('zone-depot');
const cvInput = document.getElementById('file');
const validerBtn = document.getElementById('valider');

const updateValidationState = () => {
    validerBtn.disabled = !(cvInput.files.length && lettreInput.files.length);
};

postulerBtn.addEventListener('click', () => {
    zoneDepot.hidden = !zoneDepot.hidden;
});

cvInput.addEventListener('change', updateValidationState);


const inputFile = document.getElementById('file');
const fileNameSpan = document.querySelector('.file-name');

inputFile.addEventListener('change', function(event) {
  const fileName = event.target.files.length > 0 ? event.target.files[0].name : 'Aucun fichier choisi';
  fileNameSpan.textContent = fileName;
});
