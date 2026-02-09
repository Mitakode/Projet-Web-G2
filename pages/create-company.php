<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/images/icon.png">
    <title>Créer une entreprise - ThePiston</title>
</head>
<body>
    <?php include '../partials/header.php'; ?>

    <form>
        <h2>Créer une entreprise</h2>

        <label>Nom de l'entreprise</label>
        <input type="text">

        <label>Email et téléphone de contact</label>
        <input type="text">
        
        <label>Description</label>
        <input type="text">
        
        <br><br>
        <button type="submit">
            Créer une entreprise
        </button>
    </form>

    <?php include '../partials/footer.php'; ?>
</body>
</html>