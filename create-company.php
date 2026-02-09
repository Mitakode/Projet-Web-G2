<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/icon.png">
    <link rel="stylesheet" href="style.css">
    <title>Créer une entreprise - ThePiston</title>
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="frame-form">
        <h1>Créer une entreprise</h1>
        
        <form class="form-test" action="/accueil" method="POST">

            <label>Nom de l'entreprise</label>
            <input class="form-input" type="text">

            <label>Contact (Email et téléphone)</label>
            <input class="form-input" type="text">

            <label>Description</label>
            <textarea class="form-input" rows="10"></textarea>
            
            <br><br>
            <button class="form-button" type="submit">
                Créer une entreprise
            </button>
        </form>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>