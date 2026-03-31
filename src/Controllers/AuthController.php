<?php

namespace App\Controllers;

class AuthController
{
    private $twig;
    private $pdo;

    /**
     * Builds the auth controller with Twig rendering and PDO access
     */
    public function __construct($twig, $pdo)
    {
        $this->twig = $twig;
        $this->pdo  = $pdo;
    }

    /**
     * Handles login and initializes the authenticated session
     */
    public function login()
    {
        $error = null;

        if (!empty($_SESSION)) {
            header('Location: /dashboard');
            exit;
        }

        // Process the login form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // Look up the user by email
            $stmt = $this->pdo->prepare(
                "SELECT * FROM Utilisateur WHERE Email = ? LIMIT 1"
            );
            $stmt->execute([$email]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$user) {
                $error = '1';
            } elseif (!$this->verifyPassword($user, $password)) {
                $error = '2';
            } else {
                $id = $user['ID_utilisateur'];

                // Detect the user role
                $role = $this->detectRole($id);

                // Regenerate the session id to prevent session fixation
                session_regenerate_id(true);
                $_SESSION['user_id']   = $id;
                $_SESSION['user_role'] = $role;
                $_SESSION['user_nom']  = $user['Nom'];
                $_SESSION['user_prenom'] = $user['Prenom'];

                header('Location: /dashboard');
                exit;
            }
        }

        echo $this->twig->render('Login.html.twig', ['error' => $error]);
    }

    /**
     * Detects the user role by checking each role table with the same user id
     */
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

    /**
     * Redirects authenticated users to the role specific dashboard
     */
    public function dashboard($dashboardAdminController, $dashboardStudentController)
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->requireAuthenticated();

        $role = $_SESSION['user_role'] ?? null;
        if ($role == 'admin' || $role == 'pilote') {
            $dashboardAdminController->list();
        } elseif ($role == 'etudiant') {
            $dashboardStudentController->index();
        } else {
            header('Location: /login');
            exit;
        }
    }

    /**
     * Logs out the current user and destroys the active session
     */
    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        header('Location: /login');
        exit;
    }

    /**
     * Verifies a plain password against the stored password hash
     */
    private function verifyPassword(array $user, string $plainPassword): bool
    {
        $storedPassword = $user['Mot_de_passe'] ?? '';

        if ($storedPassword === '') {
            return false;
        }

        return password_verify($plainPassword, $storedPassword);
    }
}
