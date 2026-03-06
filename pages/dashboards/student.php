<!DOCTYPE html>
<html lang="fr">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../assets/images/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>Mon espace - ThePiston</title>
</head>

<body>
    <?php include '../partials/header.php'; 
    require_once '../../pagination.php'?>

    <main>
        <h1 class="main-title">Mon espace étudiant</h1>

        <section class="dashboards-section">
            <h2 class="dashboard-title">Mes candidatures</h2>
            
            <?php
                $candidatures = [['offre' => 'Alternance - Développeur Systèmes Embarqués', 'entreprise' => 'RoboticsX', 'CV' => 'CV_RoboticsX.pdf', 'LM' => 'LM_RoboticsX.pdf'],
                ['offre' => 'Stage Développeur Full Stack', 'entreprise' => 'TechNova', 'CV' => 'CV_TechNova.pdf', 'LM' => 'LM_TechNova.pdf'],
                ['offre' => 'Alternance - Ingénieur Cybersécurité', 'entreprise' => 'CyberShield', 'CV' => 'CV_CyberShield.pdf', 'LM' => 'LM_CyberShield.pdf'],
                ['offre' => 'Stage Data Analyst', 'entreprise' => 'DataFlow', 'CV' => 'CV_DataFlow.pdf', 'LM' => 'LM_DataFlow.pdf'],
                ['offre' => 'Alternance - Développeur IA embarquée', 'entreprise' => 'AI Dynamics', 'CV' => 'CV_AI_Dynamics.pdf', 'LM' => 'LM_AI_Dynamics.pdf'],
                ['offre' => 'Stage Conception Électronique', 'entreprise' => 'MicroChip Solutions', 'CV' => 'CV_MicroChip_Solutions.pdf', 'LM' => 'LM_MicroChip_Solutions.pdf'],
                ['offre' => 'Alternance - Administrateur Réseaux', 'entreprise' => 'SecureNet', 'CV' => 'CV_SecureNet.pdf', 'LM' => 'LM_SecureNet.pdf'],
                ['offre' => 'Stage Développeur Applications Mobiles', 'entreprise' => 'CloudMatrix', 'CV' => 'CV_CloudMatrix.pdf', 'LM' => 'LM_CloudMatrix.pdf'],
                ['offre' => 'Alternance - Ingénieur DevOps', 'entreprise' => 'TechNova', 'CV' => 'CV_TechNova.pdf', 'LM' => 'LM_TechNova.pdf'],
                ['offre' => 'Stage Testeur Logiciel', 'entreprise' => 'BuildTech', 'CV' => 'CV_BuildTech.pdf', 'LM' => 'LM_BuildTech.pdf']
                ];
                $paginator = new Paginator($candidatures, 5);
                ?>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Titre de l'offre</th>
                                <th>Entreprise</th>
                                <th>CV</th>
                                <th>Lettre de motivation</th>
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
                                    <td>
                                        <?php echo htmlspecialchars($candidatures['CV']); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($candidatures['LM']); ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php
                    }

                    // Affichage des liens de pagination en dessous
                    $paginator->renderLinks();
                    ?>
        </section>


        <section class="dashboards-section">
            <h2 class="dashboard-title">Mes candidatures</h2>
            
            <?php
                $wishlist = [['offre' => 'Alternance - Chef de Projet IT', 'entreprise' => 'FinanciaGroup'],
                    ['offre' => 'Stage Business Analyst', 'entreprise' => 'FinanciaGroup'],
                    ['offre' => 'Alternance - Développeur Web', 'entreprise' => 'Ecomarket'],
                    ['offre' => 'Stage UX/UI Designer', 'entreprise' => 'MediaConnect'],
                    ['offre' => 'Alternance - Développeur Backend Java', 'entreprise' => 'EduSmart'],
                    ['offre' => 'Stage Intelligence Artificielle', 'entreprise' => 'QuantumLeap'],
                    ['offre' => 'Alternance - Ingénieur Robotique', 'entreprise' => 'RoboticsX'],
                    ['offre' => 'Stage Systèmes Temps Réel (RTOS)', 'entreprise' => 'AutoDrive'],
                    ['offre' => 'Alternance - Développeur C/C++ Embarqué', 'entreprise' => 'AeroSky'],
                    ['offre' => 'Stage Sécurité des Réseaux', 'entreprise' => 'CyberShield']
                ];

                $paginator2 = new Paginator($wishlist, 5);
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
                                    <td>
                                        <button class="btn-action poubelle"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                                
                            </table>
                        </div>
                        <?php
                    }

                    // Affichage des liens de pagination en dessous
                    $paginator->renderLinks();
                    ?>
        </section>

    </main>
<?php include '../../partials/footer.php'; ?>

</body>
</html>


