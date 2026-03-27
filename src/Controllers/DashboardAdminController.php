<?php
namespace App\Controllers;

use App\Models\Paginator;
use App\Controllers\BlockAccess;

class DashboardAdminController{
    private $twig;
    private $model;

    public function __construct($twig, $model) {
        $this->twig = $twig;
        $this->model = $model;
    }


    public function list(){
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();

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
        }
        else {
            header('Location: /');
            exit;
        }
    }

    public function studentDetails(){
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();

        // Gérer la pagination
        $paginator = new Paginator($students, 5, 'page');

            $student = $this->model->getStudentById($id);
            $pilot = $this->model->getPilotById($student['ID_pilote']);
            $applications = $this->model->getStudentApplications($id);

            $paginator = new Paginator($applications, 5);

        // Gérer la pagination
        $paginatorP = new Paginator($pilots, 5, 'pageP');
        
        // Envoyer le tout à la vue Twig
        echo $this->twig->render('DashboardAdmin.html.twig', [
            'etudiants' => $paginator->getCurrentPageItems(),
            'total_pages'      => $paginator->getTotalPages(),
            'current_page'     => $currentPage,
            'surname'      => $surname,
            'name'             => $name,
            'promotion'        => $promotion,

            'pilotes' => $paginatorP->getCurrentPageItems(),
            'total_pagesP'      => $paginatorP->getTotalPages(),
            'current_pageP'     => $currentPageP,
            'surnameP'      => $surnameP,
            'nameP'             => $nameP
        ]);
    }

    public function createStudent(){
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();

        if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote') {

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
                    
                    header('Location: /dashboard/admin');
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
        else {
            header('Location: /');
            exit;
        }
    }

    public function deleteStudent(){
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();
        
        if($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote') {

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
        }
        else {
            header('Location: /');
            exit;
        }

    }

    public function updateStudent(){
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();

        if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote') {

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
                header('Location: /dashboard/admin');
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
        else {
            header('Location: /');
            exit;
        }
    }

    public function createPilot(){
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();
        $blockAccess->blockPilotAccess();

        if ($_SESSION['user_role'] === 'admin') {

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
                    
                    header('Location: /dashboard/admin');
                    exit;
                }
            }

            $pilots = $this->model->getAllPilots();

            echo $this->twig->render('PilotForm.html.twig', [
                'is_edit'=> false,
                'session' => $_SESSION
            ]);
        }
        else {
            header('Location: /');
            exit;
        }
    }
    
    public function deletePilot(){
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
        }
        else {
            header('Location: /');
            exit;
        }
    }


    public function updatePilot(){
        $blockAccess = new BlockAccess($this->twig);
        $blockAccess->blockStudentAccess();
        $blockAccess->blockPilotAccess();

        if($_SESSION['user_role'] === 'admin') {

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
                header('Location: /dashboard/admin');
                exit;
            }

            $pilot = $this->model->getPilotById($id);
            
            echo $this->twig->render('PilotForm.html.twig', [
                'pilote' => $pilot,
                'is_edit'  => true,
                'session'  => $_SESSION
            ]);
        }
        else {
            header('Location: /');
            exit;
        }
    }

}