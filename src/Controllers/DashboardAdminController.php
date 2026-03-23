<?php
namespace App\Controllers;

use App\Models\Paginator;

class DashboardAdminController{
    private $twig;
    private $model;

    public function __construct($twig, $model) {
        $this->twig = $twig;
        $this->model = $model;
    }

    public function list(){
        //Students
        $surname = $_GET['surname'] ?? '';
        $name = $_GET['name'] ?? '';
        $promotion = $_GET['promotion'] ?? '';

        $students = $this->model->searchStudents($surname, $name, $promotion);

        // Gérer la pagination
        $paginator = new Paginator($students, 5);

        //Pilots
        $surnameP = $_GET['surnameP'] ?? '';
        $nameP = $_GET['nameP'] ?? '';

        $pilots = $this->model->searchPilots($surnameP, $nameP);

        // Gérer la pagination
        $paginatorP = new Paginator($pilots, 5);
        
        // Envoyer le tout à la vue Twig
        echo $this->twig->render('DashboardAdmin.html.twig', [
            'etudiants' => $paginator->getCurrentPageItems(),
            'total_pages'      => $paginator->getTotalPages(),
            'current_page'     => $_GET['page'] ?? 1,
            'surname'      => $surname,
            'name'             => $name,
            'promotion'        => $promotion,

            'pilotes' => $paginatorP->getCurrentPageItems(),
            'total_pagesP'      => $paginatorP->getTotalPages(),
            'current_pageP'     => $_GET['page'] ?? 1,
            'surnameP'      => $surnameP,
            'nameP'             => $nameP
        ]);
    }

    public function createStudent(){
        
    }

    public function deleteStudent(){

    }

    public function updateStudent(){

    }

    public function createPilot(){

    }
    
    public function deletePilot(){

    }

    public function updatePilot(){

    }
}