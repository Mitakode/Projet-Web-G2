<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/images/icon.png">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>ThePiston</title>
</head>
<body>
    <?php include '../partials/header.php'; 
    require_once '../pagination.php';
    ?>
    <main>
        <h1>Sarah Durand A2</h1>
        <div class="info-box ib2">sarah.durand@viacesi.fr</div>

        <section class="dashboards-section">
            <h2 class="dashboard-titl   e">Liste des candidatures</h2>
            
            <?php
            $candidatures = [['offre' => 'Alternance - Développeur Systèmes Embarqués', 'entreprise' => 'RoboticsX'],
            ['offre' => 'Stage Développeur Full Stack', 'entreprise' => 'TechNova'],
            ['offre' => 'Alternance - Ingénieur Cybersécurité', 'entreprise' => 'CyberShield'],
            ['offre' => 'Stage Data Analyst', 'entreprise' => 'DataFlow'],
            ['offre' => 'Alternance - Développeur IA embarquée', 'entreprise' => 'AI Dynamics'],
            ['offre' => 'Stage Conception Électronique', 'entreprise' => 'MicroChip Solutions'],
            ['offre' => 'Alternance - Administrateur Réseaux', 'entreprise' => 'SecureNet'],
            ['offre' => 'Stage Développeur Applications Mobiles', 'entreprise' => 'CloudMatrix'],
            ['offre' => 'Alternance - Ingénieur DevOps', 'entreprise' => 'TechNova'],
            ['offre' => 'Stage Testeur Logiciel', 'entreprise' => 'BuildTech'],
            ['offre' => 'Alternance - Analyste de données', 'entreprise' => 'DataInsights'],
            ['offre' => 'Stage Développeur Front-end', 'entreprise' => 'WebSolutions'],
            ['offre' => 'Alternance - Ingénieur en apprentissage automatique', 'entreprise' => 'AI Innovations'],
            ['offre' => 'Stage Conception de circuits intégrés', 'entreprise' => 'ChipDesign Inc.'],
            ['offre' => 'Alternance - Spécialiste en cybersécurité', 'entreprise' => 'SecureTech'],
            ['offre' => 'Stage Développeur de jeux vidéo', 'entreprise' => 'GameStudio'],
            ['offre' => 'Alternance - Administrateur de bases de données', 'entreprise' => 'DataManagement Co.'],
            ['offre' => 'Stage Développeur d’applications mobiles', 'entreprise' => 'AppCreators'],
            ['offre' => 'Alternance - Ingénieur DevOps', 'entreprise' => 'TechNova'],
            ['offre' => 'Stage Testeur Logiciel', 'entreprise' => 'BuildTech']
            ];
            $paginator = new Paginator($candidatures, 10);
            ?>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Titre de l'offre</th>
                            <th>Entreprise</th>
                        </tr>
                    </thead>

                <?php
                foreach ($paginator->getCurrentPageItems() as $candidatures) {
                    ?>
                            <tr>
                                <td>
                                    <?php echo htmlspecialchars($candidatures['offre']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($candidatures['entreprise']); ?>
                                </td>
                            </tr>
                    
                    </div>
                    <?php
                }?>
            
                </table>
            </div>
                
            <?php
            // Affichage des liens de pagination en dessous
                $paginator->renderLinks();
                ?>
        </section>
    </main>
    <?php include '../partials/footer.php'; ?>
</body>
</html>