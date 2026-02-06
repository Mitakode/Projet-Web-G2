<!DOCTYPE html>
<html lang="fr">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/icon.png">
    <link rel="stylesheet" href="style.css">
    <title>Accueil - ThePiston</title>
</head>

<body>
    <?php include 'header.php'; ?>
    
<main>
    <section class="search-section">
        <h1>Les offres disponibles</h1>

        <form class="search-form"  action="offres.php" method="GET">
        <div class="search-bar-wrapper">
            <input class="search-input" type="text" name="recherche" placeholder="Rechercher...">
            <input type="submit" class="search-btn" value="🔎">
        </div>

            <div class="filters-container">
                <select class="filter-item" name="competences">
                    <option value="">Mes compétences</option>
                    <option value="competence1">Compétence 1</option>
                    <option value="competence2">Compétence 2</option>
                    <option value="competence3">Compétence 3</option>
                </select>

                <select class="filter-item" name="type">
                    <option value="">Type d'offre</option>
                    <option value="stage">Stage</option>
                    <option value="Alternance">Alternance</option>
                </select>

                <input type="text" class="filter-item" name="loc" placeholder="Localisation...">
                
                <input type="text" class="filter-item" name="ent" placeholder="Entreprise...">
            </div>
           
        </form>
    </section>

    

    <div class="info-grid">
        <section class="info-box">
            <h2>Qui sommes-nous ?</h2>
            <p>Nous sommes nous mêmes 4 étudiants pour qui trouver un stage fut laborieux, très laborieux. Le monde du travail est une bêtre sauvage pour les étudiants. C'est pourquoi nous avons eu l'idée de créer ThePiston, le site regroupant un pannel d'offres de stage et de contrat d'apprentissage pour aider nos compagnons étudiants de  la France entière. </p>
        </section>

        <section class="info-box">
            <h2>Nos statistiques</h2>
            <div>
                <div>
                    <div>
                        <h3>Statistiques générales</h3>
                        <ul>
                            <li>Nombre d'étudiants inscrits : </li>
                            <li>Nombre d'offres disponibles : </li>
                            <li>Nombre moyen de canidature par offre : </li>
                        </ul>
                    </div>

                    <div>
                        <h3>Top des offres</h3>
                        <ul>
                            <li>Offre 1 : Nombre de candidatures</li>
                            <li>Offre 2 : Nombre de candidatures</li>
                            <li>Offre 3 : Nombre de candidatures</li>
                        </ul>
                    </div>

                    <div>
                        <h3>Répartition des offres par rapport à leur durée</h3>
                        <div>1-3</div>
                        <div>4-6</div>
                        <div>6+</div>
                    </div>

                </div>
            </div>
        </section>
    </div>
</main>

    <?php include 'footer.php'; ?>
</body>

</html>