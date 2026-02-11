<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration des Offres - ThePiston</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <?php include '../partials/header.php'; ?>

    <main class="admin-page">
        
        <section class="search-section">
            <h1>Les offres disponibles</h1>
            
            <div class="search-form">
                <div class="search-bar-wrapper">
                    <input type="text" class="search-input" placeholder="Rechercher une offre...">
                    <button class="search-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                
                <button class="btn btn-add" id="ouvrirPopup">+</button>
            </div>

            <div class="filters-container">
                <button class="filter-item">Mes compétences</button>
                <button class="filter-item">Type d'offre</button>
                <button class="filter-item">Localisation</button>
                <button class="filter-item">Entreprises</button>
            </div>
        </section>

        <div class="offres-liste">
            <article class="carte-offre">
                <div class="groupe-boutons">
                    <button class="btn-action poubelle"><i class="fa-solid fa-trash"></i></button>
                    <button class="btn-action crayon"><i class="fa-solid fa-pen-to-square"></i></button>
                    <button class="btn-action etoile"><i class="fa-regular fa-star"></i></button>
                </div>

                <a href="details-offres.php?id=1" style="text-decoration:none; color:inherit;">
                    <h3>Stage Développeur Web Junior H/F</h3>
                    <p><strong>ThePiston Tech</strong></p>
                    <div class="bloc-gris">Compétences requises : PHP, JavaScript, CSS</div>
                    <div class="bloc-gris">Nous recherchons un développeur pour maintenir notre plateforme...</div>
                </a>
            </article>

            <article class="carte-offre">
                <div class="groupe-boutons">
                    <button class="btn-action poubelle"><i class="fa-solid fa-trash"></i></button>
                    <button class="btn-action crayon"><i class="fa-solid fa-pen-to-square"></i></button>
                    <button class="btn-action etoile active"><i class="fa-solid fa-star"></i></button>
                </div>

                <a href="details-offres.php?id=2" style="text-decoration:none; color:inherit;">
                    <h3>Alternance Designer UX/UI</h3>
                    <p><strong>Agence Créative</strong></p>
                    <div class="bloc-gris">Compétences requises : Figma, Adobe Suite, Design System</div>
                    <div class="bloc-gris">Venez concevoir les interfaces de demain au sein de notre agence...</div>
                </a>
            </article>
        </div>

    </main>

    <?php include '../partials/footer.php'; ?>

</body>
</html>