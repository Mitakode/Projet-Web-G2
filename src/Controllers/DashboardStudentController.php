<?php
namespace App\Controllers;

class DashboardStudentController {
    private $twig;
    private $model;

    public function __construct($twig, $model) {
        $this->twig = $twig;
        $this->model = $model;
    }

    public function index() {

        $idEtudiant = $_SESSION['user_id'] ?? null;

        if (!$idEtudiant) {
            header('Location: /');
            exit;
        }

        $candidatures = $this->model->getCandidatures($idEtudiant);
        $wishlist = $this->model->getWishlist($idEtudiant);

        echo $this->twig->render('DashboardStudent.html.twig', [
            'candidatures' => $candidatures,
            'wishlist'     => $wishlist
        ]);
    }

    public function removeWishlist() {
        $idEtudiant = $_SESSION['user_id'] ?? null;
        $idOffre = $_GET['id'] ?? null;

        if ($idEtudiant && $idOffre) {
            $this->model->removeFromWishlist($idEtudiant, $idOffre);
        }

        header('Location: index.php?uri=dashboard/student');
        exit;
    }
}