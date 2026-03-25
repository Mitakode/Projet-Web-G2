<?php
namespace App\Controllers;

use App\Models\Paginator;

class DashboardStudentController {
    private $twig;
    private $model;

    public function __construct($twig, $model) {
        $this->twig = $twig;
        $this->model = $model;
    }

    /**
     * Prépare et affiche les données du dashboard de l'étudiant connecté.
     */
    public function index() {
        // Sécurité : On s'assure que l'utilisateur est bien connecté
        // Remplacer 'user_id' par la clé exacte utilisée dans ton système de session
        $idEtudiant = $_SESSION['user_id'] ?? null;

        if (!$idEtudiant) {
            // Si non connecté, redirection vers l'accueil ou page de connexion
            header('Location: /');
            exit;
        }

        // Récupération des données via le modèle
        $candidatures = $this->model->getCandidatures($idEtudiant);
        $wishlist = $this->model->getWishlist($idEtudiant);

        $paginatorCandidatures = new Paginator($candidatures, 5);
        $paginatorWishlist = new Paginator($wishlist, 5);

        // Affichage de la vue Twig en y injectant les données
        echo $this->twig->render('DashboardStudent.html.twig', [
            'candidatures' => $paginatorCandidatures->getCurrentPageItems(),
            'wishlist'     => $paginatorWishlist->getCurrentPageItems(),
            'total_pages' => $paginatorCandidatures->getTotalPages(),
            'current_page' => $_GET['page'] ?? 1,
            'total_pagesW' => $paginatorWishlist->getTotalPages(),
            'current_pageW' => $_GET['page'] ?? 1
        ]);
    }

    /**
     * Gère l'action de suppression d'une offre de la wishlist.
     */
    public function removeWishlist() {
        $idEtudiant = $_SESSION['user_id'] ?? null;
        $idOffre = $_GET['id'] ?? null;

        if ($idEtudiant && $idOffre) {
            $this->model->removeFromWishlist($idEtudiant, $idOffre);
        }

        // Redirection vers le dashboard après suppression
        header('Location: index.php?uri=dashboard/student');
        exit;
    }
}