<?php
namespace App\Controllers;

use App\Models\Paginator;

class OfferController {
    private $twig;
    private $model;

    public function __construct($twig, $model) {
        $this->twig = $twig;
        $this->model = $model;
    }

    public function list() {
        $search = $_GET['recherche'] ?? '';
        
        // Le modèle doit retourner les offres avec le nom de l'entreprise (via JOIN)
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
            // Récupération des données du formulaire
            $data = [
                'Titre'             => htmlspecialchars(trim($_POST['title'])),
                'Description'       => htmlspecialchars(trim($_POST['description'])),
                'Base_remuneration' => $_POST['salary'] ?? 0,
                'Duree'             => $_POST['duration'] ?? 0,
                'Liste_competences' => htmlspecialchars(trim($_POST['skills'])),
                'ID_entreprise'     => $_POST['companyId'],
                'Date_publication'  => date('Y-m-d')
            ];

            // Validation simple
            if (empty($data['Titre']) || empty($data['ID_entreprise'])) {
                $error = "Le titre et l'entreprise sont obligatoires.";
            } else {
                $this->model->createOffer($data);
                header('Location: /offers');
                exit;
            }
        }

        $companies = $this->model->getAllCompanies(); 
        echo $this->twig->render('OffersForm.html.twig', [
            'is_edit'   => false,
            'companies' => $companies,
            'error'     => $error ?? null
        ]);
    }

    public function update() {
        $id = $_GET['id'] ?? null;
        if (!$id) { header('Location: /offers'); exit; }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'Titre'             => htmlspecialchars(trim($_POST['title'])),
                'Description'       => htmlspecialchars(trim($_POST['description'])),
                'Base_remuneration' => $_POST['salary'],
                'Duree'             => $_POST['duration'],
                'Liste_competences' => htmlspecialchars(trim($_POST['skills'])),
                'ID_entreprise'     => $_POST['companyId']
            ];

            $this->model->updateOffer($id, $data);
            header('Location: /offers');
            exit;
        }

        $offer = $this->model->getOfferById($id);
        $companies = $this->model->getAllCompanies();

        echo $this->twig->render('OffersForm.html.twig', [
            'offre'     => $offer,
            'companies' => $companies,
            'is_edit'   => true
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