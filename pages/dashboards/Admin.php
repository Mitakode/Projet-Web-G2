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
    <?php include '../../partials/header.php'; 
    require_once '../../pagination.php';?>
   
    <main>
        <h1 class="main-title">Mon espace Administrateur</h1>

        <section class="dashboards-section">
            <h2 class="dashboard-title">Les comptes élèves</h2>

            <div class="search-container">
                <form action="/rechercher-etudiant" method="GET" class="search-bar-admin">
                    <input type="text" name="nom" placeholder="Nom">
                    <input type="text" name="prenom" placeholder="Prénom">
                    <input type="text" name="promotion" placeholder="Promotion">
                    <button type="submit" class="search-btn" aria-label="Rechercher">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    <button class="btn-add" id="ouvrirPopup">+</button>
                </form>
            </div>

            <?php
            $etudiants = [['nom' => 'Dupont', 'prénom' => 'Jean', 'promotion' => 'A1'],
                    ['nom' => 'Martin', 'prénom' => 'Marie', 'promotion' => 'A2'],
                    ['nom' => 'Bernard', 'prénom' => 'Pierre', 'promotion' => 'A1'],
                    ['nom' => 'Dubois', 'prénom' => 'Sophie', 'promotion' => 'A2'],
                    ['nom' => 'Lecrom', 'prénom' => 'Luc', 'promotion' => 'A1'],
                    ['nom' => 'Richard', 'prénom' => 'Claire', 'promotion' => 'A2'],
                    ['nom' => 'Petit', 'prénom' => 'Thomas', 'promotion' => 'A1'],
                    ['nom' => 'Lubin', 'prénom' => 'Emma', 'promotion' => 'A2'],
                    ['nom' => 'Girard', 'prénom' => 'Hugo', 'promotion' => 'A1'],
                    ['nom' => 'Moreau', 'prénom' => 'Léa', 'promotion' => 'A2']
                ];

                $paginator = new Paginator($etudiants, 5);
            ?>
            
            <div class="box-list">

                <?php foreach ($paginator->getCurrentPageItems() as $etudiants) {?>
                <table class="admin-item">
                    <tr>
                        <td class="user-name"><?php echo htmlspecialchars($etudiants['nom'] . ' ' . $etudiants['prénom'] . '   ' . $etudiants['promotion']); ?></td>
                        <td class="stats"><strong>Nombre de candidatures : 35</strong></td>
                        <td class="actions">
                            <button class="btn-action poubelle"><i class="fa-solid fa-trash"></i></button>
                            <button class="btn-action crayon"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                    </tr>
                </table>
                <?php }
                $paginator->renderLinks();
                ?>
            </div>
        </section>

        <section class="dashboards-section">
            <h2 class="dashboard-title">Les comptes pilotes</h2>

            <div class="search-container">
                <form action="/rechercher-pilote" method="GET" class="search-bar-admin">
                    <input type="text" name="nom" placeholder="Nom">
                    <input type="text" name="prenom" placeholder="Prénom">
                    <button type="submit" class="search-btn" aria-label="Rechercher">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    <button class="btn-add" id="ouvrirPopup">+</button>
                </form>
            </div>

            <?php
            $pilotes = [['nom' => 'Durand', 'prénom' => 'Jacques'],
                    ['nom' => 'Rougerie', 'prénom' => 'Laurent'],
                    ['nom' => 'Lemoine', 'prénom' => 'Sophie'],
                    ['nom' => 'Morel', 'prénom' => 'Isabelle'],
                    ['nom' => 'Fournier', 'prénom' => 'Nicolas'],
                    ['nom' => 'Garnier', 'prénom' => 'Céline'],
                    ['nom' => 'Chevalier', 'prénom' => 'David'],
                    ['nom' => 'Blanc', 'prénom' => 'Sophie'],
                    ['nom' => 'Rousseau', 'prénom' => 'Jean'],
                    ['nom' => 'Faure', 'prénom' => 'Marie']
                ];

                $paginator2 = new Paginator($pilotes, 5);
            ?>
            
            <div class="box-list">

                <?php foreach ($paginator2->getCurrentPageItems() as $pilotes) {?>
                <table class="admin-item">
                    <tr>
                        <td class="user-name"><?php echo htmlspecialchars($pilotes['nom'] . ' ' . $pilotes['prénom']); ?></td>
                        <td class="stats"><strong>Nombre d'élèves : 35</strong></td>
                        <td class="actions">
                            <button class="btn-action poubelle"><i class="fa-solid fa-trash"></i></button>
                            <button class="btn-action crayon"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                    </tr>
                </table>
                <?php }
                
                $paginator2->renderLinks();
                ?>
            </div>
        </section>
        </section>
    </main>

    <?php include '../../partials/footer.php'; ?>
</body>
</html>


