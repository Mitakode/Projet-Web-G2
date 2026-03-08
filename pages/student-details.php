<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/images/icon.png">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>ThePiston</title>
</head>
<body>
    <?php include '../partials/header.php'; ?>
    <main>
        <section class="info-section">
            <h1 class="h1-student">Prénom Nom et Promo de l'étudiant</h1>
            <div class="info-box" style="padding: 8px;">Email</div>

            <div class="info-box">
                <h3 class="h3-student">Liste des candidatures</h3>

                <table class="table-container">
                    <thead>
                        <tr>
                            <th>Titre de l'offre</th>
                            <th>Entreprise</th>
                            <th>CV</th>
                            <th>Lettre de motivation</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>Titre de l'offre 1</td>
                            <td>Entreprise 1</td>
                            <td>CV_1.pdf</td> 
                            <td>LM_1.pdf</td>
                        </tr>
                        <tr>
                            <td>Titre de l'offre 2</td>
                            <td>Entreprise 2</td>
                            <td>CV_2.pdf</td> 
                            <td>LM_2.pdf</td>
                        </tr>
                        <tr>
                            <td>Titre de l'offre 3</td>
                            <td>Entreprise 3</td>
                            <td>CV_3.pdf</td> 
                            <td>LM_3.pdf</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <?php include '../partials/footer.php'; ?>
</body>
</html>