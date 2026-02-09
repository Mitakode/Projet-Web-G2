<!DOCTYPE html>
<html lang="fr">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../assets/images/icon.png">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>Mon espace - ThePiston</title>
</head>

<body>
    <?php include '../../partials/header.php'; ?>
   
    <main>
        <h1 class="main-title">Mon espace Administrateur</h1>

        <section class="dashboards-section">
            <h2 class="section-title">Les comptes élèves</h2>

            <div class="search-container">
                <form action="/rechercher-etudiant" method="GET" class="search-bar-admin">
                    <input type="text" name="nom" placeholder="Nom">
                    <input type="text" name="prenom" placeholder="Prénom">
                    <input type="text" name="promotion" placeholder="Promotion">
                    <input type="text" name="campus" placeholder="Campus">
                    <button type="submit" class="search-btn">🔍</button>
                    <button type="button" class="btn">+</button>
                </form>
            </div>

            <div class="box-list">
                <div class="admin-item">
                    <span class="user-name">Nom Prenom Etudiant 1</span>
                    <span class="stats"><strong>Nombre de candidatures : 35</strong></span>
                    <div class="actions">
                        <button type="button" class="btn-delete">🗑️</button>
                        <button type="button" class="btn">✏️</button>
                    </div>
                </div>

                <div class="admin-item">
                    <span class="user-name">Nom Prenom Etudiant 2</span>
                    <span class="stats"><strong>Nombre de candidatures : 35</strong></span>
                    <div class="actions">
                        <button type="button" class="btn-delete">🗑️</button>
                        <button type="button" class="btn">✏️</button>
                    </div>
                </div>

                <div class="admin-item">
                    <span class="user-name">Nom Prenom Etudiant 2</span>
                    <span class="stats"><strong>Nombre de candidatures : 35</strong></span>
                    <div class="actions">
                        <button type="button" class="btn-delete">🗑️</button>
                        <button type="button" class="btn">✏️</button>
                    </div>
                </div>

            </div>
        </section>

        <section class="dashboards-section">
            <h2 class="section-title">Les comptes pilotes</h2>

            <div class="search-container">
                <form action="/rechercher-pilote" method="GET" class="search-bar-admin">
                    <input type="text" name="nom" placeholder="Nom">
                    <input type="text" name="prenom" placeholder="Prénom">
                    <input type="text" name="campus" placeholder="Campus">
                    <button type="submit" class="search-btn">🔍</button>
                    <button type="button" class="btn">+</button>
                </form>
            </div>

            <div class="box-list">
                <div class="admin-item">
                    <span class="user-name">Nom Prenom Pilote 1</span>
                    <div class="actions">
                        <button type="button" class="btn-delete">🗑️</button>
                        <button type="button" class="btn">✏️</button>
                    </div>
                </div>

                <div class="admin-item">
                    <span class="user-name">Nom Prenom Pilote 2</span>
                    <div class="actions">
                        <button type="button" class="btn-delete">🗑️</button>
                        <button type="button" class="btn">✏️</button>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include '../../partials/footer.php'; ?>
</body>
</html>