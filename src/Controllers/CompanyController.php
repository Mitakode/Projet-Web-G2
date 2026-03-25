<?php

namespace App\Controllers;

use App\Models\Paginator;

class CompanyController
{
    private $twig;
    private $model;

    public function __construct($twig, $model)
    {
        $this->twig = $twig;
        $this->model = $model;
    }

    public function list()
    {
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

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = isset($_POST['nameCompany'])
                ? htmlspecialchars(trim($_POST['nameCompany']))
                : '';
            $description = isset($_POST['descriptionCompany'])
                ? htmlspecialchars(trim($_POST['descriptionCompany']))
                : '';
            $contact = isset($_POST['contactCompany'])
                ? htmlspecialchars(trim($_POST['contactCompany']))
                : '';
            if (empty($name) || empty($description) || empty($contact)) {
                echo "Veulliez remplir correctement tous les champs.";
            } else {
                $this->model->createCompany([
                    'Nom_entreprise' => $name,
                    'Description' => $description,
                    'Contact' => $contact
                ]);
                header('Location: /companies');
                exit;
            }
        }

        echo $this->twig->render('CompaniesForm.html.twig', [
        'is_edit' => false
        ]);
    }

    public function update()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /companies');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
            'Nom_entreprise' => htmlspecialchars(trim($_POST['nameCompany'])),
            'Description'    => htmlspecialchars(trim($_POST['descriptionCompany'])),
            'Contact'        => htmlspecialchars(trim($_POST['contactCompany']))
            ];
            $this->model->updateCompany($id, $data);
            header('Location: /companies');
            exit;
        }

        $company = $this->model->getCompanyById($id);
        echo $this->twig->render('CompaniesForm.html.twig', [
        'entreprise' => $company,
        'is_edit'    => true
        ]);
    }

    public function delete()
    {
        // Vérifier que l'utilisateur est connecté et est admin ou pilote
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'pilote'])) {
            header('Location: /login');
            exit;
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->model->deleteCompany($id);
        }
        header('Location: /companies');
    }

    public function rate()
    {
        // Vérifier que l'utilisateur est connecté
        if ($_SESSION['user_id'] != 'etudiant') {
            header('Location: /companies');
            exit;
        }

        $idEntreprise = $_GET['id'] ?? null;
        $note = $_GET['rating'] ?? null;

        if (!$idEntreprise || !$note) {
            header('Location: /companies');
            exit;
        }

        // verifier que la note est entre 1 et 10
        $note = intval($note);
        if ($note < 1 || $note > 10) {
            header('Location: /companies');
            exit;
        }

        // save la note
        $this->model->rateCompany($idEntreprise, $_SESSION['user_id'], $note);
        
        header('Location: /companies');
        exit;
    }
}
