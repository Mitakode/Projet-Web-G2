<?php
namespace App\Controllers;

use App\Models\Paginator;

class StudentController{
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

        $students = $model->searchStudents($surname, $name, $promotion);
        echo $this->twig->render('admin.html.twig', ['etudiants' => $students]);
    }

}