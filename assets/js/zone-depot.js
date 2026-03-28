const postulerBtn = document.getElementById('postuler-btn');
const zoneDepot = document.getElementById('zone-depot');
const cvInput = document.getElementById('cv');
const lettreInput = document.getElementById('lettre');

postulerBtn.addEventListener('click', () => {
    zoneDepot.hidden = !zoneDepot.hidden;
});

const inputFile = document.getElementById('cv');
const fileNameSpan = document.querySelector('.file-name');

inputFile.addEventListener('change', function (event) {
    const fileName = event.target.files.length > 0 ? event.target.files[0].name : 'Aucun fichier choisi';
    fileNameSpan.textContent = fileName;
});

const inputFile2 = document.getElementById('lettre');
const fileNameSpan2 = document.querySelector('.file-name2');

inputFile2.addEventListener('change', function (event) {
    const fileName2 = event.target.files.length > 0 ? event.target.files[0].name : 'Aucun fichier choisi';
    fileNameSpan2.textContent = fileName2;
});