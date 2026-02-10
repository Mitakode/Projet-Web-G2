<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/images/icon.png">
    <title>ThePiston</title>
</head>

<body>
    <?php include '../partials/header.php'; ?>

    <main>
        <div>
            <h1>Titre de l'offre</h1>
            <h1>Nom de l'entreprise</h1>
        </div>

        <div class="frame">
            <button type="button" id="postuler-btn">Postuler</button>

            <div id="zone-depot" hidden>
                <form>
                    <div>
                        <label for="cv">Déposer votre CV</label>
                        <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" required>
                    </div>
                    <div>
                        <label for="lettre">Déposer votre lettre de motivation</label>
                        <input type="file" id="lettre" name="lettre" accept=".pdf,.doc,.docx" required>
                    </div>
                    <button type="submit" id="valider" disabled>Valider</button>
                </form>
            </div>
        </div>

        <div class="frame">
            <h2>Compétences requises</h2>
            <ul>
                <li>Compétence 1</li>
                <li>Compétence 2</li>
                <li>Compétence 3</li>
                <li>Compétence 4</li>
            </ul>
        </div>

        <div class="frame">
            <h2>Description de l'offre</h2>
            <div>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vel sapien eget nunc efficitur bibendum. Sed at ligula a nunc efficitur bibendum. Sed at ligula a nunc efficitur bibendum.</p>
            </div>
        </div>

        <div class="frame">
            <h2>Rémunération</h2>
            <p>1euro/mois</p>
        </div>

    </main>

    <?php include '../partials/footer.php'; ?>

    <script src="../assets/js/zone-depot.js"></script>
</body>

</html>