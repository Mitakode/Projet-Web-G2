<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/icon.png">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style/style.css">
    <title>Inscription - ThePiston</title>
</head>

<body>
    <?php include 'header.php'; ?>

    <section class="frame-form">
        <h1>Créez un compte</h1>

        <form class="form-test" action="/accueil" method="POST">
            <div class="form-grid">
                <div class="form-column">
                    <label>Prénom</label>
                    <input class="form-input" type="text">
                </div>
                <div class="form-column">
                    <label>Nom</label>
                    <input class="form-input" type="text">
                </div>
            </div>

            <label>Type de compte</label>
            <select class="form-input">
                <option>Étudiant</option>
                <option>Pilote</option>
            </select>

            <div class="form-grid">
                <div class="form-column">
                    <label>Promotion</label>
                    <input class="form-input" type="text">
                </div>
                <div class="form-column">
                    <label>Campus</label>
                    <input class="form-input" type="text">
                </div>
            </div>

            <label>Email</label>
            <input class="form-input" type="email">

            <label>Mot de passe</label>
            <input class="form-input" type="password">

            <label>Confirmation du mot de passe</label>
            <input class="form-input" type="password">

            <br><br>
            <button class="form-button" type="submit">
                Créer un compte
            </button>
        </form>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>