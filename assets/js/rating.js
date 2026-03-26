function ouvrirPopupNote(idEntreprise, nomEntreprise) {
    const message = `Notez "${nomEntreprise}"\n\nChoisissez une note de 1 à 10 :\n1 = Mauvais\n5 = Moyen\n10 = Excellent`;
    const prompt = window.prompt(message, "");
    
    if (prompt !== null) {
        const note = parseInt(prompt);
        
        if (isNaN(note) || note < 1 || note > 10) {
            alert("Veuillez entrer un nombre entre 1 et 10");
            return;
        }

        if (typeof window.saveScrollState === 'function') {
            window.saveScrollState();
        }

        const currentUrl = new URL(window.location.href);
        const params = new URLSearchParams();
        params.set('uri', 'companies/rate');
        params.set('id', idEntreprise);
        params.set('rating', note);

        const recherche = currentUrl.searchParams.get('recherche') || '';
        const page = currentUrl.searchParams.get('page') || '1';

        params.set('recherche', recherche);
        params.set('page', page);
        
        window.location.href = `index.php?${params.toString()}`;
    }
}
