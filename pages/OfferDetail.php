<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/images/icon.png">
    <title>ThePiston - Titre de l'offre</title>
</head>

<body>
    <?php include '../partials/header.php'; ?>

    <main>
        <section class="offer-container">

            <div class="offer-header">
                <h1 class="offer">Alternance- Mastère Développement de Systèmes d’Information Banque et Assurance LBP/CGI bla bla blablabla bla bla H/F</h1>
                <h2 class="offer">La Banque Postale</h2>
            </div>

            <div class="form-grid">
                
                <div class="form-column2">

                    <div class="frame2" >
                        <div class="infos-offre"> 
                            <p>Date de publication : 26/02/2026</p>
                            <p>Nombre de candidature : 12</p>
                        </div>

                        <p style="text-align: justify;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vel sapien eget nunc efficitur bibendum. Sed at ligula a nunc efficitur bibendum. Sed at ligula a nunc efficitur bibendum. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vel sapien eget nunc efficitur bibendum. Sed at ligula a nunc efficitur bibendum. Sed at ligula a nunc efficitur bibendum.</p>


                        <div>
                            <h2 style="margin-top: 20px;">Compétences requises</h2>
                            <ul class="skills">
                                <li>Compétence 1</li>
                                <li>Compétence 2</li>
                                <li>Compétence 3</li>
                                <li>Compétence 4</li>
                            </ul>
                        </div>

                        <div>
                            <h2>Rémunération</h2>
                            <p>1euro/mois</p>
                        </div>
                    </div>
                </div>
                <div class="form-column3">

                    <div class="frame2">
                        <div class="btn-container">
                            <button class="btn" type="button" id="postuler-btn">Postuler</button>
                        </div>
                        <div id="zone-depot" hidden>
                            <form class ="form-test" action="../upload.php" method="POST" enctype="multipart/form-data">
                                <p class="small-text">Pour postuler à cette offre, vous devez déposer votre CV ainsi qu'une lettre de motivation.</p>
                                <div>
                                    <label for="cv" class="file-label">Déposer votre CV ici (pdf)</label>
                                    <input id="cv" name="cv" class="input-file" type="file" accept=".pdf" required>
                                </div>
                                <span class="file-name">Aucun fichier choisi</span>
                                <div>
                                    <label for="lettre" class="file-label">Déposer votre lettre de motivation ici (pdf)</label>
                                    <input type="file" id="lettre" class="input-file" name="lettre" accept=".pdf" required>
                                </div>
                                <span class="file-name2">Aucun fichier choisi</span>
                                <button class="btn" type="submit" id="valider" >Valider</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        

    </main>

    <?php include '../partials/footer.php'; ?>

    <script src="../assets/js/zone-depot.js"></script>
</body>

</html>