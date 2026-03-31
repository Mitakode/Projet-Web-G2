<?php

namespace App\Controllers;

use App\Models\Paginator;
use App\Controllers\BlockAccess;

class DashboardAdminController
{
    private $twig;
    private $model;

    /**
     * Normalizes a surname in uppercase
     */
    private function normalizeSurname(string $value): string
    {
        return mb_strtoupper(trim($value), 'UTF-8');
    }

    /**
     * Normalizes a first name with title case
     */
    private function normalizeFirstname(string $value): string
    {
        $clean = mb_strtolower(trim($value), 'UTF-8');
        return mb_convert_case($clean, MB_CASE_TITLE, 'UTF-8');
    }

    /**
     * Reads and validates shared user form fields
     */
    private function getFormData(string &$error, bool $requirePassword): array
    {
        $postData = [
            'surname' => isset($_POST['surname'])
                ? htmlspecialchars($this->normalizeSurname($_POST['surname']))
                : '',
            'firstname' => isset($_POST['firstname'])
                ? htmlspecialchars($this->normalizeFirstname($_POST['firstname']))
                : '',
            'email' => isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '',
            'password' => isset($_POST['password']) ? htmlspecialchars(trim($_POST['password'])) : '',
            'confirm_password' => isset($_POST['confirm_password'])
                ? htmlspecialchars(trim($_POST['confirm_password']))
                : ''
        ];
        if (empty($postData['surname'])) {
            $error .= 'surname&';
        }

        if (empty($postData['firstname'])) {
            $error .= 'firstname&';
        }

        if (empty($postData['email'])) {
            $error .= 'email&';
        }

        if ($requirePassword && empty($postData['password'])) {
            $error .= 'password&';
        } elseif ($postData['password'] !== '' || $postData['confirm_password'] !== '') {
            if (
                $postData['password'] === ''
                || $postData['confirm_password'] === ''
                || $postData['password'] !== $postData['confirm_password']
            ) {
                $error .= 'confirm&';
            }
        }

        return $postData;
    }

    /**
     * Builds user persistence payload from normalized form values
     */
    private function getUserData(array $postData, bool $includePassword): array
    {
        $userData = [
            'Nom' => $postData['surname'],
            'Prenom' => $postData['firstname'],
            'Email' => $postData['email']
        ];
        if ($includePassword && $postData['password'] !== '') {
            $userData['Mot_de_passe'] = password_hash($postData['password'], PASSWORD_BCRYPT);
        }

        return $userData;
    }

    /**
     * Builds the admin dashboard controller
     */
    public function __construct($twig, $model)
    {
        $this->twig = $twig;
        $this->model = $model;
    }

    /**
     * Displays the admin dashboard with paginated students and pilots
     */
    public function list()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();
        if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote') {
            $popup = $_GET['popup'] ?? '';

            // Load filters and student records
            $surname = $_GET['surname'] ?? '';
            $name = $_GET['name'] ?? '';
            $promotion = $_GET['promotion'] ?? '';
            $students = $this->model->searchStudents($surname, $name, $promotion);

            // Build student pagination
            $paginator = new Paginator($students, 5);

            // Load filters and pilot records
            $surnameP = $_GET['surnameP'] ?? '';
            $nameP = $_GET['nameP'] ?? '';
            $pilots = $this->model->searchPilots($surnameP, $nameP);

            // Build pilot pagination
            $paginatorP = new Paginator($pilots, 5, 'pageP');

            // Render the view with both paginated lists
            echo $this->twig->render('DashboardAdmin.html.twig', [
                'students' => $paginator->getCurrentPageItems(),
                'total_pages'      => $paginator->getTotalPages(),
                'current_page'     => $_GET['page'] ?? 1,
                'surname'      => $surname,
                'name'             => $name,
                'promotion'        => $promotion,

                'pilots' => $paginatorP->getCurrentPageItems(),
                'total_pagesP'      => $paginatorP->getTotalPages(),
                'current_pageP'     => $_GET['pageP'] ?? 1,
                'surnameP'      => $surnameP,
                'nameP'             => $nameP,
                'popup'             => $popup
            ]);
        } else {
            header('Location: /');
            exit;
        }
    }

    /**
     * Displays a detailed page for one student
     */
    public function studentDetails()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();
        if ($_SESSION['user_role'] === 'pilote' || $_SESSION['user_role'] === 'admin') {
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
            echo $this->twig->render('StudentDetails.html.twig', [
                'student' => $student,
                'session' => $_SESSION,
                'pilot' => $pilot,

                'applications' => $paginator->getCurrentPageItems(),
                'total_pages'      => $paginator->getTotalPages(),
                'current_page'     => $_GET['page'] ?? 1
            ]);
        } else {
            header('Location: /');
            exit;
        }
    }

    /**
     * Handles student account creation
     */
    public function createStudent()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();
        if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote') {
            $error = "";
            $formStudent = [];
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $postData = $this->getFormData($error, true);
                $promotion = isset($_POST['promotion']) ? htmlspecialchars(trim($_POST['promotion'])) : '';
                if (($_SESSION['user_role'] ?? null) === 'admin') {
                    $pilotId = isset($_POST['id_pilote']) ? intval($_POST['id_pilote']) : null;
                } else {
                    $pilotId = $_SESSION['user_id'] ?? null;
                }

                if (empty($promotion)) {
                    $error .= 'promotion&';
                }

                if (empty($pilotId)) {
                    $error .= 'id_pilote&';
                }

                $formStudent = [
                    'Nom' => $postData['surname'],
                    'Prenom' => $postData['firstname'],
                    'Email' => $postData['email'],
                    'Promotion' => $promotion,
                    'ID_pilote' => $pilotId
                ];
                if (empty($error)) {
                    ;
                        $userData = $this->getUserData($postData, true);
                    $studentData = [
                        'Promotion' => $promotion,
                        'ID_pilote' => $pilotId
                    ];
                    $this->model->createStudent($userData, $studentData);
                    header('Location: /dashboard/admin?popup=student_created');
                    exit;
                }
            }

            $pilots = $this->model->getAllPilots();
            echo $this->twig->render('StudentForm.html.twig', [
                'pilots' => $pilots,
                'is_edit' => false,
                'session' => $_SESSION,
                'student' => $formStudent,
                'error' => $error
            ]);
        } else {
            header('Location: /');
            exit;
        }
    }

    /**
     * Deletes a student and redirects with contextual feedback
     */
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
                header('Location: /dashboard/admin?popup=student_deleted');
                exit;
            } catch (\Throwable $e) {
                if ($e instanceof \PDOException && $e->getCode() === '23000') {
                    header('Location: /dashboard/admin?popup=student_delete_blocked');
                    exit;
                }

                header('Location: /dashboard/admin?popup=student_delete_error');
                exit;
            }
        } else {
            header('Location: /');
            exit;
        }
    }

    /**
     * Handles student account updates
     */
    public function updateStudent()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();
        if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote') {
            $error = "";
            $id = $_GET['id'] ?? null;
            if (!$id) {
                header('Location: /dashboard/admin');
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $postData = $this->getFormData($error, false);
                $promotion = isset($_POST['promotion']) ? htmlspecialchars(trim($_POST['promotion'])) : '';
                if (($_SESSION['user_role'] ?? null) === 'admin') {
                    $pilotId = isset($_POST['id_pilote']) ? intval($_POST['id_pilote']) : null;
                } else {
                    $pilotId = $_SESSION['user_id'] ?? null;
                }

                if (empty($promotion)) {
                    $error .= 'promotion&';
                }

                if (empty($pilotId)) {
                    $error .= 'id_pilote&';
                }

                $userData = $this->getUserData($postData, true);
                $studentData = [
                    'Promotion' => $promotion,
                    'ID_pilote' => $pilotId
                ];
                if (empty($error)) {
                    $this->model->updateStudent($id, $userData, $studentData);
                    header('Location: /dashboard/admin?popup=student_updated');
                    exit;
                }
            }

            $student = $this->model->getStudentById($id);
            $pilots = $this->model->getAllPilots();
            echo $this->twig->render('StudentForm.html.twig', [
                'student' => $student,
                'pilots' => $pilots,
                'is_edit'  => true,
                'session'  => $_SESSION,
                'error' => $error
            ]);
        } else {
            header('Location: /');
            exit;
        }
    }

    /**
     * Handles pilot account creation
     */
    public function createPilot()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();
        $blockAccess->blockPilotAccess();
        $error = "";
        $formPilot = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postData = $this->getFormData($error, true);
            $formPilot = [
                'Nom' => $postData['surname'],
                'Prenom' => $postData['firstname'],
                'Email' => $postData['email']
            ];
            if (empty($error)) {
                $userData = $this->getUserData($postData, true);
                $pilotData = [];
                $this->model->createPilot($userData, $pilotData);
                header('Location: /dashboard/admin?popup=pilot_created');
                exit;
            }
        }

        $pilots = $this->model->getAllPilots();
        echo $this->twig->render('PilotForm.html.twig', [
            'is_edit' => false,
            'session' => $_SESSION,
            'pilot' => $formPilot,
            'error' => $error
        ]);
    }

    /**
     * Deletes a pilot only when no students are attached
     */
    public function deletePilot()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();
        $blockAccess->blockPilotAccess();
        $id = intval($_GET['id'] ?? 0);
        if ($id == 0) {
            header('Location: /dashboard/admin');
            exit;
        }

        try {
            if ($this->model->pilotHasStudents($id)) {
                header('Location: /dashboard/admin?popup=pilot_delete_blocked');
                exit;
            }

            $this->model->deletePilot($id);
            header('Location: /dashboard/admin?popup=pilot_deleted');
            exit;
        } catch (\Throwable $e) {
            if ($e instanceof \PDOException && $e->getCode() === '23000') {
                header('Location: /dashboard/admin?popup=pilot_delete_blocked');
                exit;
            }

            header('Location: /dashboard/admin?popup=pilot_delete_error');
            exit;
        }
    }

    /**
     * Handles pilot account updates
     */
    public function updatePilot()
    {
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();
        $blockAccess->blockPilotAccess();
        $error = "";
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /dashboard/admin');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postData = $this->getFormData($error, false);
            if (empty($error)) {
                $userData = $this->getUserData($postData, true);
                $this->model->updatePilot($id, $userData);
                header('Location: /dashboard/admin?popup=pilot_updated');
                exit;
            }
        }

        $pilot = $this->model->getPilotById($id);
        echo $this->twig->render('PilotForm.html.twig', [
            'pilot' => $pilot,
            'is_edit'  => true,
            'session'  => $_SESSION,
            'error' => $error
        ]);
    }
}
