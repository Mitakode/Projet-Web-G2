function ouvrirPopupNote(idEntreprise, nomEntreprise) {
    const message = `Notez "${nomEntreprise}"\n\nChoisissez une note de 1 à 10 :\n1 = Mauvais\n5 = Moyen\n10 = Excellent`;
    const prompt = window.prompt(message, "");
    
    if (prompt !== null) {
        const note = parseInt(prompt);
        
        if (isNaN(note) || note < 1 || note > 10) {
            alert("Veuillez entrer un nombre entre 1 et 10");
            return;
        }
        
        window.location.href = `index.php?uri=companies/rate&id=${idEntreprise}&rating=${note}`;
    }
}
