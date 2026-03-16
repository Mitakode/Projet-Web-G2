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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // recherche l'utilisateur par email
            $stmt = $this->pdo->prepare(
                "SELECT * FROM utilisateur WHERE Email = ? LIMIT 1"
            );
            $stmt->execute([$email]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($user && $user['Mot_de_passe'] === $password) {
                $id = $user['ID_utilisateur'];

                // détermine le rôle
                $role = $this->detectRole($id);

                // enregistre la session
                session_regenerate_id(true);
                $_SESSION['user_id']   = $id;
                $_SESSION['user_role'] = $role;
                $_SESSION['user_nom']  = $user['Nom'];
                $_SESSION['user_prenom'] = $user['Prenom'];

                // redirige selon le rôle
                switch ($role) {
                    case 'admin':
                        header('Location: index.php?uri=dashboard/admin');
                        break;
                    case 'pilote':
                        header('Location: index.php?uri=dashboard/pilote');
                        break;
                    default: // etudiant
                        header('Location: index.php?uri=dashboard/student');
                        break;
                }
                exit;
            }

            $error = 'Email ou mot de passe incorrect.';
        }

        echo $this->twig->render('Login.html.twig', ['error' => $error]);
    }

    private function detectRole(int $id): string
    {
        $stmt = $this->pdo->prepare(
            "SELECT 'admin' AS role FROM administrateur WHERE ID_utilisateur = ?
             UNION
             SELECT 'pilote' FROM pilote WHERE ID_utilisateur = ?
             UNION
             SELECT 'etudiant' FROM etudiant WHERE ID_utilisateur = ?
             LIMIT 1"
        );
        $stmt->execute([$id, $id, $id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['role'] ?? 'etudiant';
    }

    public function logout()
    {
        session_destroy();
        header('Location: ?uri=login');
        exit;
    }
}
