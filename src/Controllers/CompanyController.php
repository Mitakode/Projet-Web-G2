<?php

namespace App\Controllers;

use App\Models\Paginator;
use App\Controllers\BlockAccess;

class CompanyController
{
    private $twig;
    private $model;

    /**
     * Builds the company controller with rendering and data dependencies
     */
    public function __construct($twig, $model)
    {
        $this->twig = $twig;
        $this->model = $model;
    }

    /**
     * Displays the company list with filters and pagination
     */
    public function list()
    {
        // Read filters from the query string
        $search = $_GET['recherche'] ?? '';
        $currentUserId = (
            isset($_SESSION['user_role'])
            && $_SESSION['user_role'] === 'etudiant'
            && isset($_SESSION['user_id'])
        )
            ? $_SESSION['user_id']
            : null;
        // Load filtered records from the model
        $allCompanies = $this->model->searchCompanies($search, $currentUserId);
        // Prepare pagination
        $paginator = new Paginator($allCompanies, 10);
        // Render the page
        echo $this->twig->render('Companies.html.twig', [
            'companies_page' => $paginator->getCurrentPageItems(),
            'total_pages'      => $paginator->getTotalPages(),
            'current_page'     => $_GET['page'] ?? 1,
            'search_term'      => $search
        ]);
    }

    /**
     * Handles company creation and validates submitted fields
     */
    public function create()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();

        $error = '';
        $companyFormData = [];

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

            if (empty($name)) {
                $error .= 'nameCompany&';
            }
            if (empty($contact)) {
                $error .= 'contactCompany&';
            }
            if (empty($description)) {
                $error .= 'descriptionCompany&';
            }

            $companyFormData = [
                'Nom_entreprise' => $name,
                'Description' => $description,
                'Contact' => $contact
            ];

            if (empty($error)) {
                $this->model->createCompany([
                    'Nom_entreprise' => $name,
                    'Description' => $description,
                    'Contact' => $contact
                ]);
                header('Location: /companies?popup=company_created');
                exit;
            }
        }

        echo $this->twig->render('CompaniesForm.html.twig', [
            'is_edit' => false,
            'company' => $companyFormData,
            'error' => $error
        ]);
    }

    /**
     * Handles company edition for an existing record
     */
    public function update()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();

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
            header('Location: /companies?popup=company_updated');
            exit;
        }

        $company = $this->model->getCompanyById($id);
        echo $this->twig->render('CompaniesForm.html.twig', [
        'company' => $company,
        'is_edit'    => true
        ]);
    }

    /**
     * Deletes a company and redirects with contextual popup feedback
     */
    public function delete()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();

        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                $this->model->deleteCompany($id);
                header('Location: /companies?popup=company_deleted');
                exit;
            } catch (\Throwable $e) {
                if ($e instanceof \PDOException && $e->getCode() === '23000') {
                    header('Location: /companies?popup=company_delete_blocked');
                    exit;
                }

                header('Location: /companies?popup=company_delete_error');
                exit;
            }
        }
        header('Location: /companies?popup=company_delete_error');
        exit;
    }

    /**
     * Stores or updates the current student rating for a company
     */
    public function rate()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockPilotAccess();
        $blockAccess->blockAdminAccess();

        $companyId = $_GET['id'] ?? null;
        $rating = $_GET['rating'] ?? null;
        $search = $_GET['recherche'] ?? '';
        $page = $_GET['page'] ?? 1;

        if (!$companyId || !$rating) {
            header('Location: /companies');
            exit;
        }

        // Validate that the rating stays between 1 and 10
        $rating = intval($rating);
        if ($rating < 1 || $rating > 10) {
            header('Location: /companies');
            exit;
        }

        // Save the rating
        $this->model->rateCompany($companyId, $_SESSION['user_id'], $rating);

        $redirectParams = http_build_query([
            'recherche' => $search,
            'page' => $page,
            'popup' => 'company_rated'
        ]);

        header('Location: /companies?' . $redirectParams);
        exit;
    }
}
