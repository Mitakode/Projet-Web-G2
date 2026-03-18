<?php
namespace App\Controllers;

use App\Models\Paginator;

class OfferController {
    private $twig;
    private $model;          // Gère les données des Offres
    private $companyModel;   // Gère les données des Entreprises (nécessaire pour le formulaire)

    // Injection des dépendances : on fournit au contrôleur les outils dont il a besoin
    public function __construct($twig, $model, $companyModel) {
        $this->twig = $twig;
        $this->model = $model;
        $this->companyModel = $companyModel;
    }

    /**
     * Gère l'affichage de la liste des offres avec recherche et pagination.
     */
    public function list() {
        // Récupère le mot-clé tapé dans la barre de recherche (ou vide par défaut)
        $search = $_GET['recherche'] ?? '';
        
        // Demande au modèle de trouver les offres correspondantes
        $allOffers = $this->model->searchOffers($search);

        // Divise les résultats pour en afficher que 10 par page
        $paginator = new Paginator($allOffers, 10);
        
        // Envoie les variables à la vue Twig pour générer le html
        echo $this->twig->render('Offers.html.twig', [
            'offres_page'  => $paginator->getCurrentPageItems(), // offres de la page actuelle
            'total_pages'  => $paginator->getTotalPages(),       // nombre total de pages
            'current_page' => $_GET['page'] ?? 1,                // numéro de la page actuelle
            'search_term'  => $search                            // permet de garder le mot-clé dans la barre de recherche
        ]);
    }

    /**
     * Gère l'ajout d'une nouvelle offre (Affichage du formulaire et traitement).
     */
    public function create() {
        // Si le formulaire a été soumis (méthode POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Nettoyage des données pour éviter les failles XSS (htmlspecialchars)
            $titre = isset($_POST['Titre']) ? htmlspecialchars(trim($_POST['Titre'])) : '';
            $description = isset($_POST['Description']) ? htmlspecialchars(trim($_POST['Description'])) : '';
            $baseRemuneration = isset($_POST['Base_remuneration']) ? floatval($_POST['Base_remuneration']) : null;
            $duree = isset($_POST['Duree']) ? intval($_POST['Duree']) : null;
            $listeCompetences = isset($_POST['Liste_competences']) ? htmlspecialchars(trim($_POST['Liste_competences'])) : '';
            $idEntreprise = isset($_POST['ID_entreprise']) ? intval($_POST['ID_entreprise']) : null;
            $datePublication = date('Y-m-d'); // Date du jour automatique

            // Vérification basique des champs obligatoires
            if (empty($titre) || empty($description) || empty($idEntreprise)) {
                echo "Veuillez remplir correctement tous les champs obligatoires.";
            } else {
                // Envoi des données nettoyées au modèle pour insertion en BDD
                $this->model->createOffer([
                    'Titre'             => $titre,
                    'Description'       => $description,
                    'Base_remuneration' => $baseRemuneration,
                    'Date_publication'  => $datePublication,
                    'Duree'             => $duree,
                    'Liste_competences' => $listeCompetences,
                    'ID_entreprise'     => $idEntreprise
                ]);
                // Redirection vers la liste des offres après succès
                header('Location: /offers');
                exit;
            } 
        }

        // Si on est en méthode GET (simple affichage de la page), on récupère la liste des entreprises 
        // pour que l'utilisateur puisse choisir à quelle entreprise lier l'offre
        $entreprises = $this->companyModel->searchCompanies();

        // Affiche le formulaire en mode "Création" (is_edit = false)
        echo $this->twig->render('OffersForm.html.twig', [
            'is_edit'     => false,
            'entreprises' => $entreprises
        ]);
    }

    /**
     * Gère la modification d'une offre existante.
     */
    public function update() {
        $id = $_GET['id'] ?? null;

        // Si aucun ID n'est fourni dans l'URL, on redirige par sécurité
        if (!$id) {
            header('Location: /offers');
            exit;
        }

        // Si le formulaire de modification est soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'Titre'             => htmlspecialchars(trim($_POST['Titre'])),
                'Description'       => htmlspecialchars(trim($_POST['Description'])),
                'Base_remuneration' => floatval($_POST['Base_remuneration']),
                'Duree'             => intval($_POST['Duree']),
                'Liste_competences' => htmlspecialchars(trim($_POST['Liste_competences'])),
                'ID_entreprise'     => intval($_POST['ID_entreprise'])
            ];

            $this->model->updateOffer($id, $data);
            header('Location: /offers');
            exit;
        }

        // Récupération des données actuelles de l'offre pour pré-remplir les champs
        $offer = $this->model->getOfferById($id);
        $entreprises = $this->companyModel->searchCompanies();

        // Affiche le formulaire en mode "Édition" (is_edit = true)
        echo $this->twig->render('OffersForm.html.twig', [
            'offre'       => $offer,
            'entreprises' => $entreprises,
            'is_edit'     => true
        ]);
    }

    /**
     * Gère la suppression d'une offre.
     */
    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->model->deleteOffer($id);
        }
        // Redirige vers la liste une fois supprimée
        header('Location: /offers');
    }
}