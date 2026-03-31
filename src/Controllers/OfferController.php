<?php

namespace App\Controllers;

use App\Models\Paginator;
use App\Models\DashboardStudentModel;
use App\Models\DashboardAdminModel;
use App\Models\SqlDatabase;
use App\Controllers\FileUploader;
use App\Controllers\BlockAccess;

class OfferController
{
    private $twig;
    private $model;
    // Handles company records needed by offer forms
    private $companyModel;

    /**
     * Builds the offer controller with Twig and model dependencies
     */
    public function __construct($twig, $model, $companyModel)
    {
        $this->twig = $twig;
        $this->model = $model;
        $this->companyModel = $companyModel;
    }

    /**
     * Displays the offer list with filters and pagination
     */
    public function list()
    {
        // Read search filters from the query string
        $search = $_GET['recherche'] ?? '';
        $company = $_GET['company'] ?? '';
        $type = $_GET['type'] ?? '';
        $duration = $_GET['duree'] ?? '';

        // Load matching offers from the model
        $allOffers = $this->model->searchOffers($search, $company, $type, $duration);
        // Show 10 offers per page
        $paginator = new Paginator($allOffers, 10);
        // Render the page with current filters
        echo $this->twig->render('Offers.html.twig', [
            'offers_page'  => $paginator->getCurrentPageItems(),
            'total_pages'  => $paginator->getTotalPages(),
            'current_page' => $_GET['page'] ?? 1,
            'search_term'  => $search,
            'selected_company' => $company,
            'selected_type' => $type,
            'selected_duration' => $duration
        ]);
    }

    /**
     * Displays the detail page for one offer
     */
    public function detail()
    {
        $id = $_GET['id'] ?? null;
        $popup = $_GET['popup'] ?? null;
        // Redirect to the offer list when no id is provided
        if (!$id) {
            header('Location: /offers');
            exit;
        }

        // Load the requested offer from the database
        $offer = $this->model->getOfferById($id);
        // Redirect when the requested offer does not exist
        if (!$offer) {
            header('Location: /offers');
            exit;
        }

        // Render the detail template
        echo $this->twig->render('OfferDetail.html.twig', [
            'offer' => $offer,
            'popup' => $popup
        ]);
    }

    /**
     * Handles offer applications with file validation and upload
     */
    public function apply()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockPilotAccess();
        $blockAccess->blockAdminAccess();

        $offerId = $_POST['id_offre'] ?? null;
        $studentId = $_SESSION['user_id'] ?? null;

        $cvPath = null;
        $letterPath = null;

        $alreadyApplied = $this->model->hasApplied($offerId, $studentId);

        if ($alreadyApplied && isset($alreadyApplied['ID_offre']) && $alreadyApplied['ID_offre']) {
            header('Location: /offers/detail?id=' . urlencode((string) $offerId) . '&popup=already_applied');
            exit;
        } else {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $cvPresent = isset($_FILES['cv']);
                $coverLetterPresent = isset($_FILES['lettre']);

                if (!$cvPresent || !$coverLetterPresent) {
                    header('Location: /offers/detail?id=' . urlencode((string) $offerId) . '&popup=application_send_error');
                    exit;
                } else {
                    // Read student names to generate readable file names
                    $dashboardModel = new DashboardAdminModel($this->model->getDb());
                    $studentInfo = $dashboardModel->getStudentById($studentId);

                    if ($studentInfo) {
                        $lastName = str_replace(' ', '_', $studentInfo['Nom']);
                        $firstName = str_replace(' ', '_', $studentInfo['Prenom']);
                        $dateTime = date('d-m-Y_H-i-s');

                        $cvFileName = 'CV_' . $lastName . '_' . $firstName . '_' . $dateTime . '.pdf';
                        $letterFileName = 'LM_' . $lastName . '_' . $firstName . '_' . $dateTime . '.pdf';
                    } else {
                        // Fall back to id based names when student names are unavailable
                        $dateTime = date('d-m-Y_H-i-s');
                        $cvFileName = 'CV_' . $studentId . '_' . $dateTime . '.pdf';
                        $letterFileName = 'LM_' . $studentId . '_' . $dateTime . '.pdf';
                    }

                    $uploaderCV = new FileUploader($_FILES['cv']);
                    $uploaderCV->setFileName($cvFileName);
                    $uploaderLettre = new FileUploader($_FILES['lettre']);
                    $uploaderLettre->setFileName($letterFileName);

                    $cvValid = $uploaderCV->validate();
                    $coverLetterValid = $uploaderLettre->validate();

                    if ($cvValid && $coverLetterValid) {
                        $cvPath = $uploaderCV->upload();
                        if ($cvPath) {
                            $cvPath = basename($cvPath);
                        }
                        $letterPath = $uploaderLettre->upload();
                        if ($letterPath) {
                            $letterPath = basename($letterPath);
                        }
                    } else {
                        // Build an upload error message for user feedback
                        $errorMsg = !$cvValid ? $uploaderCV->getMessage() : '';
                        $errorMsg .= !$coverLetterValid ? $uploaderLettre->getMessage() : '';
                        // Redirect with a detailed upload error
                        header('Location: /offers/detail?id=' . urlencode((string) $offerId) . '&popup=error&msg=' . urlencode($errorMsg));
                        exit;
                    }
                }

                if ($offerId && $studentId && $cvPath && $letterPath) {
                    $this->model->addPostule($offerId, $studentId, $cvPath, $letterPath);
                    $inWishlist = $this->model->isInWishlist($offerId, $studentId);
                    if ($inWishlist && isset($inWishlist['ID_offre']) && $inWishlist['ID_offre']) {
                        $studentDashboardModel = new DashboardStudentModel($this->model->getDb());
                        $studentDashboardModel->removeFromWishlist($studentId, $offerId);
                    }
                    header('Location: /offers/detail?id=' . urlencode((string) $offerId) . '&popup=application_sent');
                    exit;
                } else {
                    header('Location: /offers/detail?id=' . urlencode((string) $offerId) . '&popup=error');
                    exit;
                }
            }
        }
    }

    /**
     * Handles offer creation and form validation
     */
    public function create()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();

        $error = '';
        $offerFormData = [];

        if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Process form submission
                // Sanitize form values
                $titre = isset($_POST['Titre']) ? htmlspecialchars(trim($_POST['Titre'])) : '';
                $description = isset($_POST['Description']) ? htmlspecialchars(trim($_POST['Description'])) : '';
                $baseSalary = isset($_POST['Base_remuneration']) ? floatval($_POST['Base_remuneration']) : null;
                $duration = isset($_POST['Duree']) ? intval($_POST['Duree']) : null;
                $skillsList = isset($_POST['Liste_competences'])
                ? htmlspecialchars(trim($_POST['Liste_competences']))
                : '';
                $companyId = isset($_POST['ID_entreprise']) ? intval($_POST['ID_entreprise']) : null;
                $publicationDate = date('Y-m-d'); // Use current day as publication date

                // Apply required field checks
                if (empty($titre)) {
                    $error .= 'Titre&';
                }
                if (empty($description)) {
                    $error .= 'Description&';
                }
                if (empty($companyId)) {
                    $error .= 'ID_entreprise&';
                }

                $offerFormData = [
                    'Titre' => $titre,
                    'Description' => $description,
                    'Base_remuneration' => $baseSalary,
                    'Duree' => $duration,
                    'Liste_competences' => $skillsList,
                    'ID_entreprise' => $companyId
                ];

                if (empty($error)) {
                    // Persist the new offer in the database
                    $this->model->createOffer([
                        'Titre'             => $titre,
                        'Description'       => $description,
                        'Base_remuneration' => $baseSalary,
                        'Date_publication'  => $publicationDate,
                        'Duree'             => $duration,
                        'Liste_competences' => $skillsList,
                        'ID_entreprise'     => $companyId
                    ]);
                    header('Location: /offers');
                    exit;
                }
            }

            // Load companies for the form select input
            $companies = $this->companyModel->searchCompanies();
            // Render the creation form
            echo $this->twig->render('OffersForm.html.twig', [
                'is_edit'     => false,
                'companies' => $companies,
                'offer' => $offerFormData,
                'error' => $error
            ]);
        } else {
            header('Location: /');
            exit;
        }
    }

    /**
     * Handles updates for an existing offer
     */
    public function update()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();

        if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote') {
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
                header('Location: /offers?popup=offer_updated');
                exit;
            }

            $offer = $this->model->getOfferById($id);
            $companies = $this->companyModel->searchCompanies();
            // Render the form with existing values
            echo $this->twig->render('OffersForm.html.twig', [
                'offer'       => $offer,
                'companies' => $companies,
                'is_edit'     => true
            ]);
        } else {
            header('Location: /');
            exit;
        }
    }

    /**
     * Deletes an offer and redirects with contextual popup feedback
     */
    public function delete()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();

        if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote') {
            $id = $_GET['id'] ?? null;
            if ($id) {
                try {
                    $this->model->deleteOffer($id);
                    header('Location: /offers?popup=offer_deleted');
                    exit;
                } catch (\Throwable $e) {
                    if ($e instanceof \PDOException && $e->getCode() === '23000') {
                        header('Location: /offers?popup=offer_delete_blocked');
                        exit;
                    }

                    header('Location: /offers?popup=offer_delete_error');
                    exit;
                }
            }
            header('Location: /offers?popup=offer_delete_error');
            exit;
        } else {
            header('Location: /');
            exit;
        }
    }


    /**
     * Adds an offer to the connected student wishlist
     */
    public function addWishlist()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockPilotAccess();
        $blockAccess->blockAdminAccess();

        $data = [
            'recherche' => $_GET['recherche'] ?? '',
            'company' => $_GET['company'] ?? '',
            'type' => $_GET['type'] ?? '',
            'duree' => $_GET['duree'] ?? '',
            'page' => $_GET['page'] ?? 1
        ];
        $offerId = $_GET['id'] ?? null;
        $studentId = $_SESSION['user_id'] ?? null;

        $alreadyApplied = $this->model->hasApplied($offerId, $studentId);

        if ($offerId && $studentId && !$alreadyApplied['ID_offre']) {
            $this->model->addWishlist($offerId, $studentId);
        }
        header('Location: /offers?' . http_build_query($data));
    }

    /**
     * Removes an offer from the connected student wishlist
     */
    public function deleteWishlist()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockPilotAccess();
        $blockAccess->blockAdminAccess();

        $data = [
            'recherche' => $_GET['recherche'] ?? '',
            'company' => $_GET['company'] ?? '',
            'type' => $_GET['type'] ?? '',
            'duree' => $_GET['duree'] ?? '',
            'page' => $_GET['page'] ?? 1
        ];
        $offerId = $_GET['id'] ?? null;
        $studentId = $_SESSION['user_id'] ?? null;

        if ($offerId && $studentId) {
            $studentDashboardModel = new DashboardStudentModel($this->model->getDb());
            $studentDashboardModel->removeFromWishlist($studentId, $offerId);
        }
        header('Location: /offers?' . http_build_query($data));
        exit;
    }
}
