<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/icon.png">
    <title>Inscription - ThePiston</title>
</head>
<body>
    <?php include 'header.php'; ?>

    <form>
        <h2>Créer un compte</h2>

        <label>Prénom</label>
        <input type="text">
        <label>Nom</label>
        <input type="text">

        <label>Type de compte</label>
        <select>
            <option>Étudiant</option>
            <option>Pilote</option>
        </select>

        <label>Promotion</label>
        <input type="text">
        <label>Campus</label>
        <input type="text">

        <label>Email</label>
        <input type="email">

        <label>Mot de passe</label>
        <input type="password">

        <label>Confirmation du mot de passe</label>
        <input type="password">

        <br><br>
        <button type="submit">
            Créer un compte
        </button>
    </form>

    <?php include 'footer.php'; ?>
</body>
</html>