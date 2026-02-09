<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/icon.png">
    <title>Connexion - ThePiston</title>
</head>
<body>
    <?php include 'header.php'; ?>

    <h2><i class="fa-solid fa-circle-user"></i> Connectez-vous</h2> 
    <h3>Connectez-vous pour accéder à votre espace</h3>

    <form action="/accueil" method="POST">
        <div>
            <label for="username">Email</label>
            <input type="text" id="username" name="username" required>
        </div>
        
        <br>

        <div>
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>

        <br>

        <button type="submit">Connexion</button>

        <p>Mot de passe oublié ? <a target="_blank" rel="noopener noreferrer" href="https://www.youtube.com/watch?v=xvFZjo5PgG0">réinitialiser le mot de passe</a></p>
    </form>

    <?php include 'footer.php'; ?>
</body>
</html>