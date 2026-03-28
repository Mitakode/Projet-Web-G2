<?php

namespace App\Controllers;

use App\Models\Paginator;
use App\Controllers\BlockAccess;

class DashboardAdminController
{
    private $twig;
    private $model;

    private function normalizeSurname(string $value): string
    {
        return mb_strtoupper(trim($value), 'UTF-8');
    }

    private function normalizeFirstname(string $value): string
    {
        $clean = mb_strtolower(trim($value), 'UTF-8');
        return mb_convert_case($clean, MB_CASE_TITLE, 'UTF-8');
    }

    public function __construct($twig, $model)
    {
        $this->twig = $twig;
        $this->model = $model;
    }


    public function list()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();

        if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote') {
            $currentPage = max(1, (int)($_GET['page'] ?? 1));
            $currentPageP = max(1, (int)($_GET['pageP'] ?? 1));

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
        } else {
            header('Location: /');
            exit;
        }
    }

    public function studentDetails()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();

        if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote') {
            $id = intval($_GET['id'] ?? 0);

            if ($id == 0) {
                header('Location: /dashboard/admin');
                exit;
            }

            $student = $this->model->getStudentById($id);

            if (!$student) {
                header('Location: /dashboard/admin');
                exit;
            }

            $pilot = $this->model->getPilotById($student['ID_pilote']);
            $applications = $this->model->getStudentApplications($id);

            $paginator = new Paginator($applications, 5);

            // Envoyer le tout à la vue Twig
            echo $this->twig->render('StudentDetails.html.twig', [
                'student' => $student,
                'pilot' => $pilot,
                'applications' => $paginator->getCurrentPageItems(),
                'total_pages' => $paginator->getTotalPages(),
                'current_page' => $_GET['page'] ?? 1
            ]);
        } else {
            header('Location: /');
            exit;
        }
    }

    public function createStudent()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();

        if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote') {
            $error = "";

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $surname = isset($_POST['surname']) ? htmlspecialchars($this->normalizeSurname($_POST['surname'])) : '';
                $firstname = isset($_POST['firstname']) ? htmlspecialchars($this->normalizeFirstname($_POST['firstname'])) : '';
                $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
                $password = isset($_POST['password']) ? htmlspecialchars(trim($_POST['password'])) : '';
                $confirmPassword = isset($_POST['confirm_password']) ? htmlspecialchars(trim($_POST['confirm_password'])) : '';
                $promotion = isset($_POST['promotion']) ? htmlspecialchars(trim($_POST['promotion'])) : '';
                $id_pilote = null;

                if ($_SESSION['user_role'] === 'admin') {
                    $id_pilote = isset($_POST['id_pilote']) ? intval($_POST['id_pilote']) : null;
                } else {
                    $id_pilote = $_SESSION['user_id'];
                }

                if (empty($surname)) {
                    $error .= 'surname&';
                }

                if (empty($firstname)) {
                    $error .= 'firstname&';
                }

                if (empty($email)) {
                    $error .= 'email&';
                }

                if (empty($password)) {
                    $error .= 'password&';
                } else if ($password !== $confirmPassword) {
                    $error .= 'confirm&';
                }

                if (empty($promotion)) {
                    $error .= 'promotion&';
                }

                if (empty($id_pilote)) {
                    $error .= 'id_pilote&';
                }

                if (empty($error)) {
                    $userData = [
                        'Nom' => $surname,
                        'Prenom' => $firstname,
                        'Email' => $email,
                        'Mot_de_passe' => password_hash($password, PASSWORD_BCRYPT)
                    ];

                    $studentData = [
                        'Promotion' => $promotion,
                        'ID_pilote' => $id_pilote
                    ];

                    $this->model->createStudent($userData, $studentData);

                    header('Location: /dashboard/admin');
                    exit;
                }
            }

                $pilots = $this->model->getAllPilots();

                echo $this->twig->render('StudentForm.html.twig', [
                    'pilotes' => $pilots,
                    'is_edit'  => false,
                    'session'  => $_SESSION,
                    'error' => $error
                ]);
        
        } else {
            header('Location: /');
            exit;
        }
    }

    public function deleteStudent()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();

        if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote') {

            $id = intval($_GET['id'] ?? 0);
            if ($id == 0) {
                header('Location: /dashboard/admin');
                exit;
            }

            try {
                $this->model->deleteStudent($id);
                header('Location: /dashboard/admin');
                exit;
            } catch (\Exception $e) {
                echo "Erreur lors de la suppression de l'étudiant.";
            }
        } else {
            header('Location: /');
            exit;
        }
    }

    public function updateStudent()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();

        if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote') {
            $id = $_GET['id'] ?? null;

            if (!$id) {
                header('Location: /dashboard/admin');
                exit;
            }

            $error = "";

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $surname = isset($_POST['surname']) ? htmlspecialchars($this->normalizeSurname($_POST['surname'])) : '';
                $firstname = isset($_POST['firstname']) ? htmlspecialchars($this->normalizeFirstname($_POST['firstname'])) : '';
                $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
                $password = isset($_POST['password']) ? htmlspecialchars(trim($_POST['password'])) : '';
                $confirmPassword = isset($_POST['confirm_password']) ? htmlspecialchars(trim($_POST['confirm_password'])) : '';
                $promotion = isset($_POST['promotion']) ? htmlspecialchars(trim($_POST['promotion'])) : '';
                $id_pilote = null;

                if ($_SESSION['user_role'] === 'admin') {
                    $id_pilote = isset($_POST['id_pilote']) ? intval($_POST['id_pilote']) : null;
                } else {
                    $id_pilote = $_SESSION['user_id'];
                }

                if (empty($surname)) {
                    $error .= 'surname&';
                }

                if (empty($firstname)) {
                    $error .= 'firstname&';
                }

                if (empty($email)) {
                    $error .= 'email&';
                }

                if (empty($password)) {
                } else if ($password !== $confirmPassword) {
                    $error .= 'confirm&';
                }

                if (empty($promotion)) {
                    $error .= 'promotion&';
                }

                if (empty($id_pilote)) {
                    $error .= 'id_pilote&';
                }

                if (empty($error)) {
                    $userData = [
                        'Nom' => $surname,
                        'Prenom' => $firstname,
                        'Email' => $email,
                        'Mot_de_passe' => password_hash($password, PASSWORD_BCRYPT)
                    ];

                    $studentData = [
                        'Promotion' => $promotion,
                        'ID_pilote' => $id_pilote
                    ];

                    $this->model->updateStudent($id, $userData, $studentData);

                    header('Location: /dashboard/admin');
                    exit;
                }
            }
            
            $student = $this->model->getStudentById($id);
            $pilots = $this->model->getAllPilots();

            echo $this->twig->render('StudentForm.html.twig', [
                'etudiant' => $student,
                'pilotes' => $pilots,
                'is_edit'  => true,
                'session'  => $_SESSION,
                'error' => $error
            ]);

        } else {
            header('Location: /');
            exit;
        }
        
    }

    public function createPilot()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();
        $blockAccess->blockPilotAccess();

        if ($_SESSION['user_role'] === 'admin') {
            $error = "";

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $surname = isset($_POST['surname']) ? htmlspecialchars($this->normalizeSurname($_POST['surname'])) : '';
                $firstname = isset($_POST['firstname']) ? htmlspecialchars($this->normalizeFirstname($_POST['firstname'])) : '';
                $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
                $password = isset($_POST['password']) ? htmlspecialchars(trim($_POST['password'])) : '';
                $confirmPassword = isset($_POST['confirm_password']) ? htmlspecialchars(trim($_POST['confirm_password'])) : '';

                if (empty($surname)) {
                    $error .= 'surname&';
                }

                if (empty($firstname)) {
                    $error .= 'firstname&';
                }

                if (empty($email)) {
                    $error .= 'email&';
                }

                if (empty($password)) {
                    $error .= 'password&';
                } else if ($password !== $confirmPassword) {
                    $error .= 'confirm&';
                }

                if (empty($error)) {
                    $userData = [
                        'Nom' => $surname,
                        'Prenom' => $firstname,
                        'Email' => $email,
                        'Mot_de_passe' => password_hash($password, PASSWORD_BCRYPT)
                    ];

                    $this->model->createPilot($userData);

                    header('Location: /dashboard/admin');
                    exit;
                }
            }

            $pilots = $this->model->getAllPilots();

            echo $this->twig->render('PilotForm.html.twig', [
                'is_edit' => false,
                'session' => $_SESSION,
                'error' => $error
            ]);
        } else {
            header('Location: /');
            exit;
        }
    }

    public function deletePilot()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();
        $blockAccess->blockPilotAccess();

        if ($_SESSION['user_role'] === 'admin') {

            $id = intval($_GET['id'] ?? 0);
            if ($id == 0) {
                header('Location: /dashboard/admin');
                exit;
            }

            try {
                if ($this->model->pilotHasStudents($id)) {
                    echo "Impossible de supprimer ce pilote : des étudiants lui sont encore associés.";
                    return;
                }

                $this->model->deletePilot($id);
                header('Location: /dashboard/admin');
                exit;
            } catch (\Exception $e) {
                echo "Erreur lors de la suppression du pilote.";
            }
        } else {
            header('Location: /');
            exit;
        }
    }


    public function updatePilot()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();
        $blockAccess->blockPilotAccess();

        if ($_SESSION['user_role'] === 'admin') {
            $error = "";

            $id = $_GET['id'] ?? null;

            if (!$id) {
                header('Location: /dashboard/admin');
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $surname = isset($_POST['surname']) ? htmlspecialchars($this->normalizeSurname($_POST['surname'])) : '';
                $firstname = isset($_POST['firstname']) ? htmlspecialchars($this->normalizeFirstname($_POST['firstname'])) : '';
                $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
                $password = isset($_POST['password']) ? htmlspecialchars(trim($_POST['password'])) : '';
                $confirmPassword = isset($_POST['confirm_password']) ? htmlspecialchars(trim($_POST['confirm_password'])) : '';

                if (empty($surname)) {
                    $error .= 'surname&';
                }

                if (empty($firstname)) {
                    $error .= 'firstname&';
                }

                if (empty($email)) {
                    $error .= 'email&';
                }

                if (empty($password)) {
                } else if ($password !== $confirmPassword) {
                    $error .= 'confirm&';
                }

                if (empty($error)) {
                    $userData = [
                        'Nom' => $surname,
                        'Prenom' => $firstname,
                        'Email' => $email,
                        'Mot_de_passe' => password_hash($password, PASSWORD_BCRYPT)
                    ];

                    $this->model->updatePilot($id, $userData);

                    header('Location: /dashboard/admin');
                    exit;
                }
            }

            $pilot = $this->model->getPilotById($id);

            echo $this->twig->render('PilotForm.html.twig', [
                'pilote' => $pilot,
                'is_edit'  => true,
                'session'  => $_SESSION,
                'error' => $error
            ]);
        } else {
            header('Location: /');
            exit;
        }
    }
}
