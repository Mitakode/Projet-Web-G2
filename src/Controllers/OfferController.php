<?php
namespace App\Controllers;

use App\Models\Paginator;

class OfferController {
    private $twig;
    private $model;
    private $companyModel;

    public function __construct($twig, $model, $companyModel) {
        $this->twig = $twig;
        $this->model = $model;
        $this->companyModel = $companyModel;
    }

    public function list() {
        $search = $_GET['recherche'] ?? '';
        
        $allOffers = $this->model->searchOffers($search);

        $paginator = new Paginator($allOffers, 10);
        
        echo $this->twig->render('Offers.html.twig', [
            'offres_page'  => $paginator->getCurrentPageItems(),
            'total_pages'  => $paginator->getTotalPages(),
            'current_page' => $_GET['page'] ?? 1,
            'search_term'  => $search
        ]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = isset($_POST['Titre']) ? htmlspecialchars(trim($_POST['Titre'])) : '';
            $description = isset($_POST['Description']) ? htmlspecialchars(trim($_POST['Description'])) : '';
            $baseRemuneration = isset($_POST['Base_remuneration']) ? floatval($_POST['Base_remuneration']) : null;
            $duree = isset($_POST['Duree']) ? intval($_POST['Duree']) : null;
            $listeCompetences = isset($_POST['Liste_competences']) ? htmlspecialchars(trim($_POST['Liste_competences'])) : '';
            $idEntreprise = isset($_POST['ID_entreprise']) ? intval($_POST['ID_entreprise']) : null;
            $datePublication = date('Y-m-d');

            if (empty($titre) || empty($description) || empty($idEntreprise)) {
                echo "Veuillez remplir correctement tous les champs obligatoires.";
            } else {
                $this->model->createOffer([
                    'Titre'             => $titre,
                    'Description'       => $description,
                    'Base_remuneration' => $baseRemuneration,
                    'Date_publication'  => $datePublication,
                    'Duree'             => $duree,
                    'Liste_competences' => $listeCompetences,
                    'ID_entreprise'     => $idEntreprise
                ]);
                header('Location: /offers');
                exit;
            } 
        }

        $entreprises = $this->companyModel->searchCompanies();

        echo $this->twig->render('OffersForm.html.twig', [
            'is_edit'     => false,
            'entreprises' => $entreprises
        ]);
    }

    public function update() {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: /offers');
            exit;
        }

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

        $offer = $this->model->getOfferById($id);
        $entreprises = $this->companyModel->searchCompanies();

        echo $this->twig->render('OffersForm.html.twig', [
            'offre'       => $offer,
            'entreprises' => $entreprises,
            'is_edit'     => true
        ]);
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->model->deleteOffer($id);
        }
        header('Location: /offers');
    }
}