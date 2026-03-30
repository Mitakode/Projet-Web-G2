<?php

namespace App\Controllers;

class BlockAccess
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    private function renderLoginRequired(bool $mustLogin = true): void
    {
        $message = $mustLogin
            ? 'Vous devez vous connecter pour accéder à cette page.'
            : 'Accès refusé : vous n\'avez pas les droits nécessaires pour accéder à cette page.';
        $redirectTo = $mustLogin ? '/login' : '/dashboard';

        echo $this->twig->render('AccessDenied.html.twig', [
            'session' => $_SESSION,
            'message' => $message,
            'redirect_to' => $redirectTo
        ]);
        exit;
    }

    public function requireAuthenticated(): void
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
            $this->renderLoginRequired(true);
        }
    }

    public function blockStudentAccess(): void
    {
        $this->requireAuthenticated();

        if (($_SESSION['user_role'] ?? null) === 'etudiant') {
            $this->renderLoginRequired(false);
        }
    }

    public function blockPilotAccess(): void
    {
        $this->requireAuthenticated();

        if (($_SESSION['user_role'] ?? null) === 'pilote') {
            $this->renderLoginRequired(false);
        }
    }

    public function blockAdminAccess(): void
    {
        $this->requireAuthenticated();

        if (($_SESSION['user_role'] ?? null) === 'admin') {
            $this->renderLoginRequired(false);
        }
    }
}
