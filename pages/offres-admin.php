<?php 
require_once '../pagination.php';?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les offres - ThePiston</title>
    <link rel="icon" type="image/png" href="../assets/images/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <?php include '../partials/header.php'; ?>

    <main class="list-page">

        <h1 class="main-title">Les offres disponibles</h1>
        <section class="search-section">
            <form class="search-form"  action="offres.php" method="GET">
                <div class="search-bar-wrapper">
                    <input class="search-input" type="text" name="recherche" placeholder="Rechercher...">
                    <button type="submit" class="search-btn" aria-label="Rechercher">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    <button class="btn-add" id="ouvrirPopup">+</button>
                </div>

                <div class="filters-container">
                    <select class="filter-item" name="competences">
                        <option value="">Mes compétences</option>
                        <option value="competence1">Compétence 1</option>
                        <option value="competence2">Compétence 2</option>
                        <option value="competence3">Compétence 3</option>
                    </select>

                    <select class="filter-item" name="type">
                        <option value="">Type d'offre</option>
                        <option value="stage">Stage</option>
                        <option value="Alternance">Alternance</option>
                    </select>

                    <input type="text" class="filter-item" name="loc" placeholder="Localisation...">
                    
                    <input type="text" class="filter-item" name="ent" placeholder="Entreprise...">
                </div>
            
            </form>
        </section>

<?php
// Tableau d'offres
$offres = [['offre' => 'Alternance - Développeur Systèmes Embarqués', 'entreprise' => 'RoboticsX'],
['offre' => 'Stage Développeur Full Stack', 'entreprise' => 'TechNova'],
['offre' => 'Alternance - Ingénieur Cybersécurité', 'entreprise' => 'CyberShield'],
['offre' => 'Stage Data Analyst', 'entreprise' => 'DataFlow'],
['offre' => 'Alternance - Développeur IA embarquée', 'entreprise' => 'AI Dynamics'],
['offre' => 'Stage Conception Électronique', 'entreprise' => 'MicroChip Solutions'],
['offre' => 'Alternance - Administrateur Réseaux', 'entreprise' => 'SecureNet'],
['offre' => 'Stage Développeur Applications Mobiles', 'entreprise' => 'CloudMatrix'],
['offre' => 'Alternance - Ingénieur DevOps', 'entreprise' => 'TechNova'],
['offre' => 'Stage Testeur Logiciel', 'entreprise' => 'BuildTech'],

['offre' => 'Alternance - Chef de Projet IT', 'entreprise' => 'FinanciaGroup'],
['offre' => 'Stage Business Analyst', 'entreprise' => 'FinanciaGroup'],
['offre' => 'Alternance - Développeur Web', 'entreprise' => 'Ecomarket'],
['offre' => 'Stage UX/UI Designer', 'entreprise' => 'MediaConnect'],
['offre' => 'Alternance - Développeur Backend Java', 'entreprise' => 'EduSmart'],
['offre' => 'Stage Intelligence Artificielle', 'entreprise' => 'QuantumLeap'],
['offre' => 'Alternance - Ingénieur Robotique', 'entreprise' => 'RoboticsX'],
['offre' => 'Stage Systèmes Temps Réel (RTOS)', 'entreprise' => 'AutoDrive'],
['offre' => 'Alternance - Développeur C/C++ Embarqué', 'entreprise' => 'AeroSky'],
['offre' => 'Stage Sécurité des Réseaux', 'entreprise' => 'CyberShield'],

['offre' => 'Alternance - Data Scientist', 'entreprise' => 'BioGenix'],
['offre' => 'Stage Développement API REST', 'entreprise' => 'CloudMatrix'],
['offre' => 'Alternance - Architecte Logiciel Junior', 'entreprise' => 'TechNova'],
['offre' => 'Stage Automatisation & Scripts Python', 'entreprise' => 'LogiTrans'],
['offre' => 'Alternance - Analyste SOC', 'entreprise' => 'SecureNet'],
['offre' => 'Stage Développement Frontend React', 'entreprise' => 'GameForge'],
['offre' => 'Alternance - Ingénieur Tests & Validation', 'entreprise' => 'AutoDrive'],
['offre' => 'Stage Traitement du Signal', 'entreprise' => 'NanoTech Labs'],
['offre' => 'Alternance - Développeur IoT', 'entreprise' => 'SmartHome Systems'],
['offre' => 'Stage Recherche en Vision par Ordinateur', 'entreprise' => 'AI Dynamics'],
    ];

        $paginator = new Paginator($offres, 10);

        foreach ($paginator->getCurrentPageItems() as $offres) {
            ?>
            <div class="offres-liste">
                <table class="company-table">
                    <tr>
                        <th class="company-name"><?php echo htmlspecialchars($offres['offre']); ?></th>
                        <td class="btn-table">
                            <button class="btn-action poubelle"><i class="fa-solid fa-trash"></i></button>
                            <button class="btn-action crayon"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="btn-action etoile active"><i class="fa-solid fa-star"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="company-info">
                            <?php echo htmlspecialchars($offres['entreprise']); ?>
                        </td>
                    </tr>
                </table>
            </div>
            <?php
        }

        $paginator->renderLinks();
                ?>
    </main>
    <?php include '../partials/footer.php'; ?>
</body>
</html>