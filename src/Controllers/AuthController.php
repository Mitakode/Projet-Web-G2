<?php

namespace App\Controllers;

class AuthController
{
    private $twig;
    private $pdo;

    public function __construct($twig, $pdo)
    {
        $this->twig = $twig;
        $this->pdo  = $pdo;
    }

    public function login()
    {
        $error = null;

        if (!empty($_SESSION)) {
            $this->redirectToDashboardByRole($_SESSION['user_role']);
        }

        // on traite le résultat du form de connexion
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // recherche l'utilisateur par email
            $stmt = $this->pdo->prepare(
                "SELECT * FROM Utilisateur WHERE Email = ? LIMIT 1"
            );
            $stmt->execute([$email]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($user && $this->verifyPassword($user, $password)) {
                $id = $user['ID_utilisateur'];

                // détermine le rôle
                $role = $this->detectRole($id);

                // enregistre la session de façon sécurisée en remplaçant l'ancienne
                session_regenerate_id(true);
                $_SESSION['user_id']   = $id;
                $_SESSION['user_role'] = $role;
                $_SESSION['user_nom']  = $user['Nom'];
                $_SESSION['user_prenom'] = $user['Prenom'];

                $this->redirectToDashboardByRole($role);
            }

            $error = 'Email ou mot de passe incorrect.';
        }

        echo $this->twig->render('Login.html.twig', ['error' => $error]);
    }

    // détecte le rôle de l'utilisateur en testant chaque table avec l'ID_utilisateur
    private function detectRole(int $id): string
    {
        $stmt = $this->pdo->prepare(
            "SELECT 'admin' AS role FROM Administrateur WHERE ID_utilisateur = ?
             UNION
             SELECT 'pilote' FROM Pilote WHERE ID_utilisateur = ?
             UNION
             SELECT 'etudiant' FROM Etudiant WHERE ID_utilisateur = ?
             LIMIT 1"
        );
        $stmt->execute([$id, $id, $id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['role'];
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        header('Location: /login');
        exit;
    }

    public function dashboard()
    {
        $this->requireAuthenticated();
        $this->redirectToDashboardByRole($_SESSION['user_role'] ?? '');
    }

    private function requireAuthenticated(): void
    {
        if (empty($_SESSION['user_id']) || empty($_SESSION['user_role'])) {
            header('Location: /login');
            exit;
        }
    }

    private function redirectToDashboardByRole(string $role): void
    {
        switch ($role) {
            case 'admin':
                header('Location: /dashboard/admin');
                break;
            case 'pilote':
                header('Location: /dashboard/admin');
                break;
            case 'etudiant':
                header('Location: /dashboard/student');
                break;
            default:
                header('Location: /login');
                break;
        }

        exit;
    }

    private function verifyPassword(array $user, string $plainPassword): bool
    {
        $storedPassword = $user['Mot_de_passe'] ?? '';

        if ($storedPassword === '') {
            return false;
        }

        return password_verify($plainPassword, $storedPassword);
    }
}
