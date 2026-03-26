<?php

namespace App\Controllers;

Class BlockAccess {
    private $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    public function blockStudentAccess(): void {
        if (($_SESSION['user_role'] ?? null) === 'etudiant') {
            echo $this->twig->render('AccessDenied.html.twig', [
                'session' => $_SESSION
            ]);
            exit;
        }
    }

    public function blockPilotAccess(): void {
        if (($_SESSION['user_role'] ?? null) === 'pilote') {
            echo $this->twig->render('AccessDenied.html.twig', [
                'session' => $_SESSION
            ]);
            exit;
        }
    }

    public function blockAdminAccess(): void {
        if (($_SESSION['user_role'] ?? null) === 'admin') {
            echo $this->twig->render('AccessDenied.html.twig', [
                'session' => $_SESSION
            ]);
            exit;
        }
    }


};