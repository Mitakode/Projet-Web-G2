<?php
namespace App\Controllers;

use App\Models\Paginator;

class DashboardAdminController{
    private $twig;
    private $model;

    public function __construct($twig, $model) {
        $this->twig = $twig;
        $this->model = $model;
    }

    private function blockStudentAccess(): void {
        if (($_SESSION['user_role'] ?? null) === 'etudiant') {
            echo $this->twig->render('AccessDenied.html.twig', [
                'session' => $_SESSION
            ]);
            exit;
        }
    }

    public function list(){
        $this->blockStudentAccess();

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
    }

    public function createStudent(){
        $this->blockStudentAccess();

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $surname= isset($_POST['surname']) ? htmlspecialchars(trim($_POST['surname'])):'';
            $name= isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])):'';
            $promotion=isset($_POST['promotion']) ? htmlspecialchars(trim($_POST['promotion'])):'';
            $email=isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])):'';
            $password= isset($_POST['password']) ? htmlspecialchars(trim($_POST['password'])):'';
            $id_pilot =null;

            if ($_SESSION['user_role'] === 'admin') {
            $id_pilote = isset($_POST['id_pilote']) ? intval($_POST['id_pilote']) : null;
            } else {
                $id_pilote = $_SESSION['user_id']; 
            }

            if (empty($surname) || empty($name) || empty($promotion) || empty($email) || empty($password) || empty($id_pilote)) {
                echo "Veuillez remplir tous les champs, y compris le pilote référent.";
            } else {
                $userData = [
                    'Nom' => $surname,
                    'Prenom' => $name,
                    'Email' => $email,
                    'Mot_de_passe' => password_hash($password, PASSWORD_BCRYPT)
                ];

                $studentData = [
                    'Promotion' => $promotion,
                    'ID_pilote' => $id_pilote
                ];

                $this->model->createStudent($userData, $studentData);
                echo "<script>alert('Étudiant créé avec succès !'); window.location.href='/dashboard/admin';</script>";
                exit;
            }
        }

        $pilots = $this->model->getAllPilots();

        echo $this->twig->render('StudentForm.html.twig', [
            'pilotes' => $pilots,
            'is_edit'=> false,
            'session' => $_SESSION
        ]);
    }

    public function deleteStudent(){
        $this->blockStudentAccess();

        $id = intval($_GET['id'] ?? 0);
        if ($id == 0) {
            header('Location: /dashboard/admin');
            exit;
        }

        try {
            $this->model->deleteStudent($id);
            echo "<script>alert('Étudiant supprimé avec succès !'); window.location.href='/dashboard/admin';</script>";
            exit;
        } catch (\Exception $e) {
            echo "<script>alert('Erreur lors de la suppression de l\'étudiant.'); window.history.back();</script>";
            exit;
        }

    }

    public function updateStudent(){
        $this->blockStudentAccess();

        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: /dashboard/admin');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'Nom' => htmlspecialchars(trim($_POST['surname'])),
                'Prenom' => htmlspecialchars(trim($_POST['name'])),
                'Email' => htmlspecialchars(trim($_POST['email']))
            ];
            if(!empty($_POST['password'])){
               $userData['Mot_de_passe'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
            }

            $studentData = [
                'Promotion' => htmlspecialchars(trim($_POST['promotion'])),
            ];

            if (isset($_POST['id_pilote'])) {
                $studentData['ID_pilote'] = intval($_POST['id_pilote']);
            }

            $this->model->updateStudent($id, $userData, $studentData);
            echo "<script>alert('Étudiant modifié avec succès !'); window.location.href='/dashboard/admin';</script>";
            exit;
        }

        $student = $this->model->getStudentById($id);
        $pilots = $this->model->getAllPilots();
        
        echo $this->twig->render('StudentForm.html.twig', [
            'etudiant' => $student,
            'pilotes' => $pilots,
            'is_edit'  => true,
            'session'  => $_SESSION
        ]);
    }

    public function createPilot(){
        $this->blockStudentAccess();

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $surname= isset($_POST['surname']) ? htmlspecialchars(trim($_POST['surname'])):'';
            $name= isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])):'';
            $email=isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])):'';
            $password= isset($_POST['password']) ? htmlspecialchars(trim($_POST['password'])):'';

            if (empty($surname) || empty($name) || empty($email) || empty($password)) {
                echo "Veuillez remplir tous les champs, y compris le pilote référent.";
            } else {
                $userData = [
                    'Nom' => $surname,
                    'Prenom' => $name,
                    'Email' => $email,
                    'Mot_de_passe' => password_hash($password, PASSWORD_BCRYPT)
                ];

                $this->model->createPilot($userData);
                echo "<script>alert('Pilote créé avec succès !'); window.location.href='/dashboard/admin';</script>";
                exit;
            }
        }

        $pilots = $this->model->getAllPilots();

        echo $this->twig->render('PilotForm.html.twig', [
            'is_edit'=> false,
            'session' => $_SESSION
        ]);
    }
    
    public function deletePilot(){
        $this->blockStudentAccess();

        $id = intval($_GET['id'] ?? 0);
        if ($id == 0) {
            header('Location: /dashboard/admin');
            exit;
        }

        try {
            if ($this->model->pilotHasStudents($id)) {
                echo "<script>alert('Impossible de supprimer ce pilote : des étudiants lui sont encore associés.'); window.history.back();</script>";
                exit;
            }

            $this->model->deletePilot($id);
            echo "<script>alert('Pilote supprimé avec succès !'); window.location.href='/dashboard/admin';</script>";
            exit;
        } catch (\Exception $e) {
            echo "<script>alert('Erreur lors de la suppression du pilote.'); window.history.back();</script>";
            exit;
        }

    }

    public function updatePilot(){
        $this->blockStudentAccess();

        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: /dashboard/admin');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'Nom' => htmlspecialchars(trim($_POST['surname'])),
                'Prenom' => htmlspecialchars(trim($_POST['name'])),
                'Email' => htmlspecialchars(trim($_POST['email']))
            ];
            if(!empty($_POST['password'])){
               $userData['Mot_de_passe'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
            }

            $this->model->updatePilot($id, $userData);
            echo "<script>alert('Pilote modifié avec succès !'); window.location.href='/dashboard/admin';</script>";
            exit;
        }

        $pilot = $this->model->getPilotById($id);
        
        echo $this->twig->render('PilotForm.html.twig', [
            'pilote' => $pilot,
            'is_edit'  => true,
            'session'  => $_SESSION
        ]);
    }
}