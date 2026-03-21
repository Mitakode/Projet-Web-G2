<?php
namespace App\Controllers;

use App\Models\Paginator;

class DashboardController{
    private $twig;
    private $model;

    public function __construct($twig, $model) {
        $this->twig = $twig;
        $this->model = $model;
    }

    public function listStudents(){
        $surname = $_GET['surname'] ?? '';
        $name = $_GET['name'] ?? '';
        $promotion = $_GET['promotion'] ?? '';

        $students = $this->model->searchStudents($surname, $name, $promotion);

        // Gérer la pagination
        $paginator = new Paginator($students, 5);
        
        // Envoyer le tout à la vue Twig
        echo $this->twig->render('DashboardAdmin.html.twig', [
            'etudiants' => $paginator->getCurrentPageItems(),
            'total_pages'      => $paginator->getTotalPages(),
            'current_page'     => $_GET['page'] ?? 1,
            'surname'      => $surname,
            'name'             => $name,
            'promotion'        => $promotion
        ]);
    }

}