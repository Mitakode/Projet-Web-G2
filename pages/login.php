<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/images/icon.png">
    <title>Connexion - ThePiston</title>
</head>
<body>
    <?php include '../partials/header.php'; ?>

    <section class="frame-form">
        <h1><i class="fa-solid fa-circle-user"></i>Connectez-vous</h1>
        <h2>Connectez-vous pour accéder à votre espace</h2>

        <form class ="form-test" action="/accueil" method="POST">
            <label for="username">Email</label>
            <input class="form-input" type="text" id="username" name="username" required>
        
            <label for="password">Mot de passe</label>
            <input class="form-input" type="password" id="password" name="password" required>

            <button class="btn" type="submit">Connexion</button>

            <p class="infos">Mot de passe oublié ? <a target="_blank" rel="noopener noreferrer" href="https://www.youtube.com/watch?v=xvFZjo5PgG0">Réinitialiser le mot de passe</a></p>
        </form>
    </section>

    <?php include '../partials/footer.php'; ?>
</body>
</html>