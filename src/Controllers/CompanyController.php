<?php
namespace App\Controllers;

use App\Models\Paginator;

class CompanyController {
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
        $allCompanies = $this->model->searchCompanies($search);

        // Gérer la pagination
        $paginator = new Paginator($allCompanies, 10);
        
        // Envoyer le tout à la vue Twig
        echo $this->twig->render('Companies.html.twig', [
            'entreprises_page' => $paginator->getCurrentPageItems(),
            'total_pages'      => $paginator->getTotalPages(),
            'current_page'     => $_GET['page'] ?? 1,
            'search_term'      => $search
        ]);
    }

    public function create(){
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $Name= isset($_POST['nameCompany']) ? htmlspecialchars(trim($_POST['nameCompany'])):'';
            $Description=isset($_POST['descriptionCompany']) ? htmlspecialchars(trim($_POST['descriptionCompany'])):'';
            $Contact=isset($_POST['contactCompany']) ? htmlspecialchars(trim($_POST['contactCompany'])):'';

            if(empty($Name)||empty($Description)||empty($Contact)){
                echo "Veulliez remplir correctement tous les champs.";
            }
            else{
                $this->model->createCompany(['Nom_entreprise'=> $Name, 'Description'=> $Description, 'Contact'=> $Contact]);
                header('Location: /entreprises');
                exit;
            } 
        }

        echo $this->twig->render('CompaniesForm.html.twig', [
        'is_edit'=> false
    ]);
    }

    public function update() {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        header('Location: /entreprises');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'Nom_entreprise' => htmlspecialchars(trim($_POST['nameCompany'])),
            'Description'    => htmlspecialchars(trim($_POST['descriptionCompany'])),
            'Contact'        => htmlspecialchars(trim($_POST['contactCompany']))
        ];

        $this->model->updateCompany($id, $data);
        header('Location: /entreprises');
        exit;
    }

    $company = $this->model->getCompanyById($id);

    echo $this->twig->render('CompaniesForm.html.twig', [
        'entreprise' => $company,
        'is_edit'    => true
    ]);
}

    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->model->deleteCompany($id);
        }
        header('Location: /entreprises');
    }
}