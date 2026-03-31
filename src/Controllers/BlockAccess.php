<?php

namespace App\Controllers;

class BlockAccess
{
    private $twig;

    /**
     * Builds the access guard helper used by controllers
     */
    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    /**
     * Renders the access denied page with the matching redirect target
     */
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

    /**
     * Ensures a user is logged in before entering a protected page
     */
    public function requireAuthenticated(): void
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
            $this->renderLoginRequired(true);
        }
    }

    /**
     * Blocks student users from routes reserved to other roles
     */
    public function blockStudentAccess(): void
    {
        $this->requireAuthenticated();

        if (($_SESSION['user_role'] ?? null) === 'etudiant') {
            $this->renderLoginRequired(false);
        }
    }

    /**
     * Blocks pilot users from routes reserved to other roles
     */
    public function blockPilotAccess(): void
    {
        $this->requireAuthenticated();

        if (($_SESSION['user_role'] ?? null) === 'pilote') {
            $this->renderLoginRequired(false);
        }
    }

    /**
     * Blocks admin users from routes reserved to other roles
     */
    public function blockAdminAccess(): void
    {
        $this->requireAuthenticated();

        if (($_SESSION['user_role'] ?? null) === 'admin') {
            $this->renderLoginRequired(false);
        }
    }
}
