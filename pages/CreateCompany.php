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

    <section class="frame">
        <h1 class="main-title">Créer une entreprise</h1>
        
        <form class="form-test" action="/accueil" method="POST">

            <label>Nom de l'entreprise</label>
            <input class="form-input" type="text">

            <label>Contact (Email et téléphone)</label>
            <input class="form-input" type="text">

            <label>Description</label>
            <textarea class="form-input" rows="10"></textarea>
            

            <button class="btn" type="submit">
                Créer une entreprise
            </button>
        </form>
    </section>

    <?php include '../partials/footer.php'; ?>
</body>
</html>