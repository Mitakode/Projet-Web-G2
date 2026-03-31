<?php

namespace App\Controllers;

class HomepageController
{
    private $twig;
    private $model;

    /**
     * Builds the homepage controller with view and model dependencies
     */
    public function __construct($twig, $model)
    {
        $this->twig = $twig;
        $this->model = $model;
    }

    /**
     * Loads homepage metrics and renders the landing page
     */
    public function home()
    {
        $countStudent = $this->model->countStudent();
        $countOffer = $this->model->countOffer();
        $avgApply = $this->model->avgApply();
        $topOffers = $this->model->topOffers();
        $timeDistribution = $this->model->timeDistribution();
        echo $this->twig->render('Homepage.html.twig', [
            'students' => $countStudent,
            'offers' => $countOffer,
            'apply' => $avgApply,
            'topOffers' => $topOffers,
            'timeDistribution' => $timeDistribution
        ]);
    }
}
