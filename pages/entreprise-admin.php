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
                
            </div>
        </section>

        <div class="offres-liste">
            <table class="company-table">
                <tr>
                    <th class="company-name">Nom de l'entreprise</th>
                    <td class="company-rating">Note de l'entreprise <strong>/10</strong></td>
                </tr>
                
                <tr>
                    <td colspan="2" class="company-info">
                    Infos (email et téléphone de contact – nombre de stagiaires ayant postulé)
                    </td>
                </tr>
                
                <tr>
                    <td colspan="2" class="company-description">
                    Description de l'entreprise... Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    </td>
                </tr>
            </table>

            <table class="company-table">
                <tr>
                    <th class="company-name">Nom de l'entreprise</th>
                    <td class="company-rating">Note de l'entreprise <strong>/10</strong></td>
                </tr>
                
                <tr>
                    <td colspan="2" class="company-info">
                    Infos (email et téléphone de contact – nombre de stagiaires ayant postulé)
                    </td>
                </tr>
                
                <tr>
                    <td colspan="2" class="company-description">
                    Description de l'entreprise... Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    </td>
                </tr>
            </table>
        </div>

        

    </main>

    <?php include '../partials/footer.php'; ?>

</body>
</html>


