<!DOCTYPE html>
<html lang="fr">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../images/icon.png">
    <link rel="stylesheet" href="../style/style.css">
    <title>Mon espace - ThePiston</title>
</head>

<body>
    <?php include '../header.php'; ?>
 
    <main>
        <h1 class="main-title">Mon espace étudiant</h1>

        <section class="dashboards-section">
            <h2 class="dashboard-title">Mes candidatures</h2>

            <div class="table-container">
            <table>
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

        <section class="dashboards-section">
            <h2 class="dashboard-title">Ma wishlist</h2>

            <table class="wishlist-table">
                <thead>
                    <tr>
                        <th>Titre de l'offre</th>
                        <th>Entreprise</th>
                        <th class="col-actions"></th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>Titre de l'offre 1</td>
                        <td>Entreprise 1</td>
                        <td class="col-actions">
                            <button type="button" class="btn-delete">🗑️</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Titre de l'offre 2</td>
                        <td>Entreprise 2</td>
                        <td class="col-actions">
                            <button type="button" class="btn-delete">🗑️</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Titre de l'offre 3</td>
                        <td>Entreprise 3</td>
                        <td class="col-actions">
                            <button type="button" class="btn-delete">🗑️</button>
                        </td>
                    </tr>
                </tbody>
            </table>

        </section>

    </main>
<?php include '../footer.php'; ?>

</body>
</html>