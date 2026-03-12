<?php
namespace App\Controllers;

use App\Models\Paginator;

class EnterpriseController {
    private $twig;
    private $model;

    public function __construct($twig, $model) {
        $this->twig = $twig;
        $this->model = $model;
    }

    public function list() {
        // Récupérer les filtres depuis l'URL
        $search = $_GET['recherche'] ?? '';
        
        // Demander les données filtrées au modèle
        $allEnterprises = $this->model->searchEnterprises($search);

        // Gérer la pagination
        $paginator = new Paginator($allEnterprises, 10);
        
        // Envoyer le tout à la vue Twig
        echo $this->twig->render('entreprises.twig.html', [
            'entreprises_page' => $paginator->getCurrentPageItems(),
            'total_pages'      => $paginator->getTotalPages(),
            'current_page'     => $_GET['page'] ?? 1,
            'search_term'      => $search
        ]);
    }

    public function create(){

    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->model->deleteCompany($id);
        }
        header('Location: /entreprises');
    }
}