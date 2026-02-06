<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/icon.png">
    <title>Créer une offre - ThePiston</title>
</head>
<body>
    <?php include 'header.php'; ?>

    <form>
        <h2>Créer une offre</h2>

        <label>Titre de l'offre</label>
        <input type="text">

        <label>Entreprise</label>
        <input type="text">
        <label>Rémunération</label>
        <input type="text">

        <label>Compétences requises</label>
        <select>
            <option>Compétence 1</option>
            <option>Compétence 2</option>
        </select>

        <label>Description</label>
        <input type="text">
        
        <br><br>
        <button type="submit">
            Créer une offre
        </button>
    </form>

    <?php include 'footer.php'; ?>
</body>
</html>