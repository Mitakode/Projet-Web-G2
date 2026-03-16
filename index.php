<?php
session_start();
//$twig->addGlobal('session', $_SESSION); // Permet d'accéder à la session dans tous les templates Twig
require "vendor/autoload.php";

use App\Controllers\CompanyController;
use App\Models\CompanyModel; // On importe le modèle
use App\Controllers\AuthController;

// Configuration de Twig
$loader = new \Twig\Loader\FilesystemLoader('vue');
$twig = new \Twig\Environment($loader, ['debug' => true]);

// Connexion à la base de données
$dsn = 'mysql:host=localhost;dbname=thepiston;charset=utf8';
$username = 'userthepiston';
$password = 'Thepiston1%';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

// Initialisation des composants
// Adaptateur BDD des différentes tables
$companyDbAdapter = new \App\Models\SqlDatabase($pdo, 'Entreprise', 'ID_entreprise');

// On crée le modèle avec la connexion PDO
$companyModel = new App\Models\CompanyModel($companyDbAdapter);
//$offerModel      = new App\Models\OfferModel($pdo);
//$userModel       = new App\Models\UserModel($pdo); // Gère Etudiants, Pilotes, Admins

// Contrôleurs
//$mainController = new App\Controllers\MainController($twig, $offerModel, $enterpriseModel);
$companyController = new App\Controllers\CompanyController($twig, $companyModel);
//$offerController = new App\Controllers\OfferController($twig, $offerModel, $enterpriseModel);
//$userController = new App\Controllers\UserController($twig, $userModel);
$authController = new App\Controllers\AuthController($twig, $pdo);

// Routage simple
$uri = $_GET['uri'] ?? '/';

switch ($uri) {
    // Pages Globales
    case '/':
        $mainController->home();
        break;
    case 'stats':
        $mainController->showStats();
        break;
    case 'mentions-legales':
        $mainController->legal();
        break;

    // Gestion des entreprises
    case 'companies': // Rechercher et afficher
        $companyController->list();
        break;
    case 'companies/create': // Créer
        $companyController->create();
        break;
    case 'companies/update': //Modifier
        $companyController->update();
        break;
    case 'companies/delete': // Supprimer
        $companyController->delete();
        break;

    // Gestion des Offres
    case 'offers':
        $offerController->list();
        break;
    case 'offers/details':
        $offerController->details($_GET['id']);
        break;
    case 'offers/create':
        $offerController->create();
        break;
    case 'offers/delete':
        $offerController->delete();
        break;

    // Candidatures
    case 'apply':
        $offerController->apply($_GET['id_offre']);
        break;

    // Gestion des Utilisateurs
    case 'users/students':
        $userController->listStudents();
        break;
    case 'users/pilots':
        $userController->listPilots();
        break;

    // Dashboards
    case 'dashboard/student':
        echo $twig->render('student.html.twig');
        break;
    case 'dashboard/admin':
        echo $twig->render('admin.html.twig');
        break;
    case 'dashboard/pilote':
        echo $twig->render('admin.html.twig');
        break;

    case 'login':
        $authController->login();
        break;
    case 'logout':
        $authController->logout();
        break;

    default:
        header("HTTP/1.0 404 Not Found");
        echo '<h1>404 - Page introuvable</h1>';
        break;
}