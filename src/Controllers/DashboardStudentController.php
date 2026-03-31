<?php

namespace App\Controllers;

use App\Models\Paginator;
use App\Controllers\BlockAccess;

class DashboardStudentController
{
    private $twig;
    private $model;

    /**
     * Builds the student dashboard controller
     */
    public function __construct($twig, $model)
    {
        $this->twig = $twig;
        $this->model = $model;
    }

    /**
     * Prepares and renders student dashboard data
     */
    public function index()
    {
        // Enforce role based access
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockPilotAccess();
        $blockAccess->blockAdminAccess();

        // Read the current student id from the session
        $studentId = $_SESSION['user_id'] ?? null;
        if (!$studentId) {
            // Redirect to home if no student is connected
            header('Location: /');
            exit;
        }

        // Load applications and wishlist
        $applications = $this->model->getCandidatures($studentId);
        $wishlist = $this->model->getWishlist($studentId);
        $applicationsPaginator = new Paginator($applications, 5);
        $paginatorWishlist = new Paginator($wishlist, 5, 'pageW');

        // Render paginated dashboard sections
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
     * Removes one offer from the connected student wishlist
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

        // Keep pagination state after deletion
        $redirectUrl = '/dashboard/student?page='
            . urlencode((string) $currentPage)
            . '&pageW='
            . urlencode((string) $currentPageW);
        header('Location: ' . $redirectUrl);
        exit;
    }
}
