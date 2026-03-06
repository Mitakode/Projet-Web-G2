<!DOCTYPE html>
<html lang="fr">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/images/icon.png">
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://kit.fontawesome.com/votre_codea076d05399.js" crossorigin="anonymous"></script>
    <title>Accueil - ThePiston</title>
</head>

<body>
    <?php include '../partials/header.php'; ?>

    <main>
        <section class="search-section">
            <h1>Les entreprises</h1>
            
            <form class="search-form">
                <div class="search-bar-wrapper">
                    <input type="text" class="search-input" placeholder="Rechercher une entreprise...">
                    <button class="search-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                
            </form>
        </section>

        <?php

        class Paginator {

        private $items;
        private $perPage;
        private $currentPage;


        public function __construct(array $items, int $perPage = 5) {
            $this->items = $items;
            $this->perPage = $perPage;
            $this->currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        }


        public function getCurrentPageItems(): array {
            $start = ($this->currentPage - 1) * $this->perPage;
            return array_slice($this->items, $start, $this->perPage);
        }


        public function getTotalPages(): int {
            return ceil(count($this->items) / $this->perPage);
        }


        public function renderLinks(): void {
            $totalPages = $this->getTotalPages();
            echo '<div class="pagination">';
            echo '<a href="?page=1">Première page</a>';
            echo '<a href="?page=' . max(1, $this->currentPage - 1) . '">Précédent</a>';

            for ($i = 1; $i <= $totalPages; $i++) {
                if ($i === $this->currentPage) {
                    echo "<strong>$i</strong> ";
                } else {
                    echo '<a href="?page=' . $i . '">' . $i . '</a> ';
                }
            }

            echo '<a href="?page=' . min($totalPages, $this->currentPage + 1) . '">Suivant</a>';
            echo '<a href="?page=' . $totalPages . '">Dernière page</a>';
            echo '</div>';
        }

        }


        // Exemple d'utilisation sans SQL

        $entreprises = [
            ['nom' => 'RoboticsX', 'description' => 'RoboticsX est une entreprise spécialisée dans la conception de robots industriels et de solutions d’automatisation intelligente. Elle développe des systèmes robotisés avancés destinés à l’industrie, à la logistique et à la recherche. Ses équipes travaillent sur l’intégration de capteurs, d’IA embarquée et de logiciels temps réel.', 'contact' => '0145632890 - contact@roboticsx.com'],
            ['nom' => 'TechNova', 'description' => 'TechNova conçoit des solutions logicielles et des plateformes numériques pour accompagner la transformation digitale des entreprises. L’entreprise développe des applications cloud, des infrastructures sécurisées et des outils d’analyse de données. Elle intervient dans de nombreux secteurs technologiques.', 'contact' => '0182745632 - contact@technova.com'],
            ['nom' => 'CyberShield', 'description' => 'CyberShield est spécialisée dans la cybersécurité et la protection des infrastructures numériques. Elle propose des audits de sécurité, des solutions de surveillance réseau et des services de réponse aux incidents. Son objectif est d’aider les organisations à protéger leurs données sensibles.', 'contact' => '0173829456 - contact@cybershield.com'],
            ['nom' => 'DataFlow', 'description' => 'DataFlow accompagne les entreprises dans la gestion et l’analyse de leurs données. L’entreprise développe des outils de traitement de données, de visualisation et de machine learning. Ses solutions permettent d’optimiser les décisions stratégiques grâce à la data.', 'contact' => '0193847561 - contact@dataflow.com'],
            ['nom' => 'AI Dynamics', 'description' => 'AI Dynamics développe des solutions basées sur l’intelligence artificielle pour l’industrie et les services. L’entreprise travaille notamment sur la vision par ordinateur, le traitement du langage naturel et l’IA embarquée. Elle accompagne ses clients dans l’intégration de systèmes intelligents.', 'contact' => '0156483927 - contact@aidynamics.com'],
            ['nom' => 'MicroChip Solutions', 'description' => 'MicroChip Solutions conçoit des composants électroniques et des systèmes embarqués pour l’industrie technologique. Ses équipes développent des microcontrôleurs, cartes électroniques et plateformes IoT. L’entreprise intervient dans les domaines de l’automobile, de l’énergie et de la robotique.', 'contact' => '0162839475 - contact@microchipsolutions.com'],
            ['nom' => 'SecureNet', 'description' => 'SecureNet propose des solutions d’infrastructures réseaux et de télécommunications sécurisées. L’entreprise accompagne les organisations dans la mise en place de réseaux performants et fiables. Elle offre également des services de gestion et de supervision des systèmes informatiques.', 'contact' => '0175648392 - contact@securenet.com'],
            ['nom' => 'CloudMatrix', 'description' => 'CloudMatrix développe des solutions cloud pour les entreprises souhaitant moderniser leur infrastructure informatique. Elle propose des services d’hébergement, d’automatisation et de gestion d’applications distribuées. Ses outils facilitent la scalabilité et la performance des systèmes.', 'contact' => '0183927465 - contact@cloudmatrix.com'],
            ['nom' => 'BuildTech', 'description' => 'BuildTech conçoit des solutions numériques pour le secteur de la construction et de l’ingénierie. L’entreprise développe des logiciels de gestion de projet, de modélisation et de suivi de chantier. Ses technologies permettent d’améliorer la collaboration et la productivité.', 'contact' => '0159372846 - contact@buildtech.com'],
            ['nom' => 'FinanciaGroup', 'description' => 'FinanciaGroup accompagne les institutions financières dans leur transformation numérique. L’entreprise propose des solutions en data, cybersécurité et gestion des systèmes d’information. Elle aide les organisations à améliorer leurs performances grâce aux technologies digitales.', 'contact' => '0568235561 - contact@financiagroup.fr'],
            ['nom' => 'Ecomarket', 'description' => 'Ecomarket développe des plateformes de commerce électronique et des solutions digitales pour la vente en ligne. L’entreprise propose des outils de gestion de catalogue, de paiement sécurisé et d’analyse des ventes. Elle accompagne les marques dans leur stratégie e-commerce.', 'contact' => '0192736458 - contact@ecomarket.com'],
            ['nom' => 'MediaConnect', 'description' => 'MediaConnect est spécialisée dans la création de contenus numériques et de plateformes médiatiques. L’entreprise développe des solutions de diffusion digitale et d’expérience utilisateur. Elle accompagne les entreprises dans leur communication digitale.', 'contact' => '0164728391 - contact@mediaconnect.com'],
            ['nom' => 'EduSmart', 'description' => 'EduSmart développe des technologies éducatives innovantes destinées aux écoles et aux universités. L’entreprise conçoit des plateformes d’apprentissage en ligne et des outils interactifs pour les enseignants et les étudiants. Elle contribue à moderniser les méthodes pédagogiques.', 'contact' => '0147283956 - contact@edusmart.com'],
            ['nom' => 'QuantumLeap', 'description' => 'QuantumLeap est une entreprise de recherche spécialisée dans les technologies quantiques et les algorithmes avancés. Elle développe des solutions pour le calcul haute performance et la cryptographie. Ses travaux visent à repousser les limites de la puissance informatique.', 'contact' => '0172839465 - contact@quantumleap.com'],
            ['nom' => 'AutoDrive', 'description' => 'AutoDrive conçoit des technologies innovantes pour l’industrie automobile, notamment dans les systèmes d’aide à la conduite et les véhicules autonomes. L’entreprise développe des solutions électroniques et logicielles embarquées. Elle collabore avec de nombreux constructeurs pour améliorer la sécurité et la mobilité.', 'contact' => '0272698561 - contact@autodrive.fr'],
            ['nom' => 'BioGenix', 'description' => 'BioGenix est une société de biotechnologie spécialisée dans la recherche biomédicale et les technologies de santé. Elle développe des solutions basées sur l’analyse génétique et l’intelligence artificielle. Ses innovations contribuent à améliorer le diagnostic et les traitements.', 'contact' => '0184739265 - contact@biogenix.com'],
            ['nom' => 'LogiTrans', 'description' => 'LogiTrans propose des solutions numériques pour la gestion de la logistique et du transport. L’entreprise développe des outils de suivi en temps réel, d’optimisation des itinéraires et de gestion des flottes. Ses plateformes permettent d’améliorer l’efficacité des chaînes d’approvisionnement.', 'contact' => '0165839472 - contact@logitrans.com'],
            ['nom' => 'GameForge', 'description' => 'GameForge est un studio spécialisé dans le développement de jeux vidéo et d’expériences interactives. L’entreprise conçoit des moteurs de jeu, des univers virtuels et des applications immersives. Elle collabore avec des éditeurs et des plateformes de distribution internationales.', 'contact' => '0173928456 - contact@gameforge.com'],
            ['nom' => 'NanoTech Labs', 'description' => 'NanoTech Labs mène des recherches avancées dans le domaine des nanotechnologies et des matériaux innovants. L’entreprise développe des capteurs miniaturisés et des composants de haute précision. Ses technologies sont utilisées dans l’électronique, la médecine et l’énergie.', 'contact' => '0156283749 - contact@nanotechlabs.com'],
            ['nom' => 'SmartHome Systems', 'description' => 'SmartHome Systems développe des solutions domotiques permettant de connecter et d’automatiser les équipements domestiques. L’entreprise propose des systèmes intelligents pour la sécurité, l’énergie et le confort des habitations. Ses technologies s’appuient sur l’IoT et les plateformes cloud.', 'contact' => '0182647395 - contact@smarthomesystems.com'],
        ];

        $paginator = new Paginator($entreprises, 10);

        foreach ($paginator->getCurrentPageItems() as $entreprise) {
            ?>
            <div class="offres-liste">
                <table class="company-table">
                    <tr>
                        <th class="company-name"><?php echo htmlspecialchars($entreprise['nom']); ?></th>
                        <td class="company-rating">Note : <strong>7/10</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="company-info">
                            <?php echo htmlspecialchars($entreprise['contact']); ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="company-description">
                            <?php echo htmlspecialchars($entreprise['description']); ?>
                        </td>
                    </tr>
                </table>
            </div>
            <?php
        }

        // Affichage des liens de pagination en dessous
        $paginator->renderLinks();
        ?>
    </main>
    <?php include '../partials/footer.php'; ?>
</body>

</html>