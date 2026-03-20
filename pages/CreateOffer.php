<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/images/icon.png">
    <title>Créer une offre - ThePiston</title>
</head>
<body>
    <?php include '../partials/header.php'; ?>

    <section class="frame">
        <h1 class="main-title">Créer une offre</h1>
        
        <form class="form-test" action="/accueil" method="POST">

            <label>Titre de l'offre</label>
            <input class="form-input" type="text">

            <div class="form-grid">
                <div class="form-column2">
                    <label>Entreprise</label>
                    <input class="form-input" type="text">
                </div>
                <div class="form-column3">
                    <label>Rémunération</label>
                    <input class="form-input" type="text">
                </div>
            </div>

            <label>Compétences requises</label>
            <div class="skills-selector">
                <p>Sélectionnez vos compétences :</p>
                <div class="skills-list">
                    <label><input type="checkbox" name="skill[]" value="comp1"> Compétence 1</label>
                    <label><input type="checkbox" name="skill[]" value="comp2"> Compétence 2</label>
                    <label><input type="checkbox" name="skill[]" value="comp3"> Compétence 3</label>
                    <label><input type="checkbox" name="skill[]" value="comp4"> Compétence 4</label>
                    <label><input type="checkbox" name="skill[]" value="comp5"> Compétence 5</label>
                    </div>
            </div>

            <label>Description</label>
            <textarea class="form-input" rows="10"></textarea>
            

            <button class="btn" type="submit">
                Créer une offre
            </button>
        </form>
    </section>

    <?php include '../partials/footer.php'; ?>
</body>
</html>