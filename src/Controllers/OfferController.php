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
// Gère les données des Offres
    private $companyModel;   // Gère les données des Entreprises (nécessaire pour les formulaires)

    // Injection des dépendances
    public function __construct($twig, $model, $companyModel)
    {
        $this->twig = $twig;
        $this->model = $model;
        $this->companyModel = $companyModel;
    }

    /**
     * Gère l'affichage de la liste des offres avec recherche et pagination.
     */
    public function list()
    {
        // Récupère le mot-clé tapé dans la barre de recherche (ou vide par défaut)
        $search = $_GET['recherche'] ?? '';
        $company = $_GET['company'] ?? '';
        $type = $_GET['type'] ?? '';
        $duree = $_GET['duree'] ?? '';

// Demande au modèle de trouver les offres correspondantes
        $allOffers = $this->model->searchOffers($search, $company, $type, $duree);
// Divise les résultats pour n'en afficher que 10 par page
        $paginator = new Paginator($allOffers, 10);
// Envoie les variables à la vue Twig pour générer le HTML
        echo $this->twig->render('Offers.html.twig', [
            'offres_page'  => $paginator->getCurrentPageItems(),
            'total_pages'  => $paginator->getTotalPages(),
            'current_page' => $_GET['page'] ?? 1,
            'search_term'  => $search,
            'selected_company' => $company,
            'selected_type' => $type,
            'selected_duree' => $duree
        ]);
    }

    /**
     * Affiche la page de détails d'une offre spécifique (NOUVEAU)
     */
    public function detail()
    {
        $id = $_GET['id'] ?? null;
        $popup = $_GET['popup'] ?? null;
// Si aucun ID n'est fourni, on redirige vers la liste des offres
        if (!$id) {
            header('Location: /offers');
            exit;
        }

        // On récupère les informations de l'offre depuis la base de données
        $offer = $this->model->getOfferById($id);
// Sécurité supplémentaire : si l'ID tapé n'existe pas en BDD
        if (!$offer) {
            header('Location: /offers');
            exit;
        }

        // On affiche le template de détail en lui passant la variable "offre"
        echo $this->twig->render('OfferDetail.html.twig', [
            'offre' => $offer,
            'popup' => $popup
        ]);
    }

    public function apply()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockPilotAccess();
        $blockAccess->blockAdminAccess();

        $idOffre = $_POST['id_offre'] ?? null;
        $studentId = $_SESSION['user_id'] ?? null;

        $cvPath = null;
        $letterPath = null;

        $alreadyApplied = $this->model->hasApplied($idOffre, $studentId);

        if ($alreadyApplied && isset($alreadyApplied['ID_offre']) && $alreadyApplied['ID_offre']) {
            header('Location: /offers/detail?id=' . urlencode((string) $idOffre) . '&popup=already_applied');
            exit;
        } else {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $cvPresent = isset($_FILES['cv']);
                $lettrePresent = isset($_FILES['lettre']);

                if (!$cvPresent || !$lettrePresent) {
                    header('Location: /offers/detail?id=' . urlencode((string) $idOffre) . '&popup=error');
                    exit;
                } else {
                    // Récupérer les infos de l'étudiant pour renommer les fichiers
                    $dashboardModel = new DashboardAdminModel($this->model->getDb());
                    $studentInfo = $dashboardModel->getStudentById($studentId);

                    if ($studentInfo) {
                        $nom = str_replace(' ', '_', $studentInfo['Nom']);
                        $prenom = str_replace(' ', '_', $studentInfo['Prenom']);
                        $dateTime = date('d-m-Y_H-i-s');

                        $cvFileName = 'CV_' . $nom . '_' . $prenom . '_' . $dateTime . '.pdf';
                        $letterFileName = 'LM_' . $nom . '_' . $prenom . '_' . $dateTime . '.pdf';
                    } else {
                        // Fallback si les infos ne peuvent pas être récupérées
                        $dateTime = date('d-m-Y_H-i-s');
                        $cvFileName = 'CV_' . $studentId . '_' . $dateTime . '.pdf';
                        $letterFileName = 'LM_' . $studentId . '_' . $dateTime . '.pdf';
                    }

                    $uploaderCV = new FileUploader($_FILES['cv']);
                    $uploaderCV->setFileName($cvFileName);
                    $uploaderLettre = new FileUploader($_FILES['lettre']);
                    $uploaderLettre->setFileName($letterFileName);

                    $cvValid = $uploaderCV->validate();
                    $lettreValid = $uploaderLettre->validate();

                    if ($cvValid && $lettreValid) {
                        $cvPath = $uploaderCV->upload();
                        if ($cvPath) {
                            $cvPath = basename($cvPath);
                        }
                        $letterPath = $uploaderLettre->upload();
                        if ($letterPath) {
                            $letterPath = basename($letterPath);
                        }
                    } else {
                        // On stocke le message d'erreur pour affichage éventuel
                        $errorMsg = !$cvValid ? $uploaderCV->getMessage() : '';
                        $errorMsg .= !$lettreValid ? $uploaderLettre->getMessage() : '';
                        // Redirection avec message d'erreur (à adapter selon gestion front)
                        header('Location: /offers/detail?id=' . urlencode((string) $idOffre) . '&popup=error&msg=' . urlencode($errorMsg));
                        exit;
                    }
                }

                if ($idOffre && $studentId && $cvPath && $letterPath) {
                    $this->model->addPostule($idOffre, $studentId, $cvPath, $letterPath);
                    $inWishlist = $this->model->isInWishlist($idOffre, $studentId);
                    if ($inWishlist && isset($inWishlist['ID_offre']) && $inWishlist['ID_offre']) {
                        $wishlistModel = new DashboardStudentModel($this->model->getDb());
                        $wishlistModel->removeFromWishlist($studentId, $idOffre);
                    }
                    header('Location: /offers/detail?id=' . urlencode((string) $idOffre) . '&popup=success');
                    exit;
                } else {
                    header('Location: /offers/detail?id=' . urlencode((string) $idOffre) . '&popup=error');
                    exit;
                }
            }
        }
    }

    /**
     * Gère l'ajout d'une nouvelle offre (Affichage du formulaire + Traitement).
     */
    public function create()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();

        $error = '';
        $offerFormData = [];

        if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Si le formulaire a été soumis
                // Nettoyage des données
                $titre = isset($_POST['Titre']) ? htmlspecialchars(trim($_POST['Titre'])) : '';
                $description = isset($_POST['Description']) ? htmlspecialchars(trim($_POST['Description'])) : '';
                $baseRemuneration = isset($_POST['Base_remuneration']) ? floatval($_POST['Base_remuneration']) : null;
                $duree = isset($_POST['Duree']) ? intval($_POST['Duree']) : null;
                $listeCompetences = isset($_POST['Liste_competences'])
                ? htmlspecialchars(trim($_POST['Liste_competences']))
                : '';
                $idEntreprise = isset($_POST['ID_entreprise']) ? intval($_POST['ID_entreprise']) : null;
                $datePublication = date('Y-m-d');// Date du jour automatique

                // Vérification basique
                if (empty($titre)) {
                    $error .= 'Titre&';
                }
                if (empty($description)) {
                    $error .= 'Description&';
                }
                if (empty($idEntreprise)) {
                    $error .= 'ID_entreprise&';
                }

                $offerFormData = [
                    'Titre' => $titre,
                    'Description' => $description,
                    'Base_remuneration' => $baseRemuneration,
                    'Duree' => $duree,
                    'Liste_competences' => $listeCompetences,
                    'ID_entreprise' => $idEntreprise
                ];

                if (empty($error)) {
                // Insertion en BDD
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

            // Récupère la liste des entreprises pour le <select> du formulaire
            $entreprises = $this->companyModel->searchCompanies();
            // Affiche le formulaire vierge
            echo $this->twig->render('OffersForm.html.twig', [
                'is_edit'     => false,
                'entreprises' => $entreprises,
                'offre' => $offerFormData,
                'error' => $error
            ]);
        } else {
            header('Location: /');
            exit;
        }
    }

    /**
     * Gère la modification d'une offre existante.
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
            $entreprises = $this->companyModel->searchCompanies();
            // Affiche le formulaire pré-rempli
            echo $this->twig->render('OffersForm.html.twig', [
                'offre'       => $offer,
                'entreprises' => $entreprises,
                'is_edit'     => true
            ]);
        } else {
            header('Location: /');
            exit;
        }
    }

    /**
     * Gère la suppression d'une offre.
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
            $wishlistModel = new DashboardStudentModel($this->model->getDb());
            $wishlistModel->removeFromWishlist($studentId, $offerId);
        }
        header('Location: /offers?' . http_build_query($data));
        exit;
    }
}
