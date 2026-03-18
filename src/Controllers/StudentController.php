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

    
}