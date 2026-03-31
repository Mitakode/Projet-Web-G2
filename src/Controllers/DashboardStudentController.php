<?php

namespace App\Controllers;

use App\Models\Paginator;
use App\Controllers\BlockAccess;

class DashboardStudentController
{
    private $twig;
    private $model;

    public function __construct($twig, $model)
    {
        $this->twig = $twig;
        $this->model = $model;
    }

    /**
     * Prépare et affiche les données du dashboard étudiant
     */
    public function index()
    {
        // Vérifie les droits d'accès de l'étudiant
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockPilotAccess();
        $blockAccess->blockAdminAccess();

        // Récupère l'identifiant de l'étudiant connecté
        $studentId = $_SESSION['user_id'] ?? null;
        if (!$studentId) {
            // Redirige vers l'accueil si aucun étudiant n'est connecté
            header('Location: /');
            exit;
        }

        // Charge les candidatures et la wishlist
        $applications = $this->model->getCandidatures($studentId);
        $wishlist = $this->model->getWishlist($studentId);
        $applicationsPaginator = new Paginator($applications, 5);
        $paginatorWishlist = new Paginator($wishlist, 5, 'pageW');

        // Affichage de la vue (Twig) en y injectant les données (page)
        echo $this->twig->render('DashboardStudent.html.twig', [
            'applications' => $applicationsPaginator->getCurrentPageItems(),
            'wishlist'     => $paginatorWishlist->getCurrentPageItems(),
            'total_pages' => $applicationsPaginator->getTotalPages(),
            'current_page' => $_GET['page'] ?? 1,
            'total_pagesW' => $paginatorWishlist->getTotalPages(),
            'current_pageW' => $_GET['pageW'] ?? 1
        ]);
    }

    /**
     * Supprime une offre de la wishlist de l'étudiant connecté
     */
    public function removeWishlist()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockPilotAccess();
        $blockAccess->blockAdminAccess();
        $studentId = $_SESSION['user_id'] ?? null;
        $offerId = $_GET['id'] ?? null;
        if ($studentId && $offerId) {
            $this->model->removeFromWishlist($studentId, $offerId);
        }

        $currentPage = $_GET['page'] ?? 1;
        $currentPageW = $_GET['pageW'] ?? 1;

        // Conserve la pagination après suppression
        $redirectUrl = '/dashboard/student?page='
            . urlencode((string) $currentPage)
            . '&pageW='
            . urlencode((string) $currentPageW);
        header('Location: ' . $redirectUrl);
        exit;
    }
}
