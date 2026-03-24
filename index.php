<?php

session_start();
require "vendor/autoload.php";

// Configuration de Twig
$loader = new \Twig\Loader\FilesystemLoader('vue');
$twig = new \Twig\Environment($loader, ['debug' => true]);
$twig->addGlobal('session', $_SESSION);

// Connexion a la base de donnees
$dsn = 'mysql:host=localhost;dbname=thepiston;charset=utf8';
$username = 'userthepiston';
$password = 'Thepiston1%';

try {
    $pdo = new \PDO($dsn, $username, $password);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
} catch (\PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

// Initialisation des composants
$companyDbAdapter = new \App\Models\SqlDatabase($pdo, 'Entreprise', 'ID_entreprise');
$offerDbAdapter = new \App\Models\SqlDatabase($pdo, 'Offre', 'ID_offre');
$homepageDbAdapter = new \App\Models\SqlDatabase($pdo, 'Offre', 'ID_offre');
$dashboardDbAdapter = new \App\Models\SqlDatabase($pdo, 'Offre', 'ID_offre');


$companyModel = new \App\Models\CompanyModel($companyDbAdapter);
$offerModel = new \App\Models\OfferModel($offerDbAdapter);
$homepageModel = new \App\Models\HomepageModel($homepageDbAdapter);
$dashboardModel = new \App\Models\DashboardModel($dashboardDbAdapter);

$companyController = new \App\Controllers\CompanyController($twig, $companyModel);
$offerController = new \App\Controllers\OfferController($twig, $offerModel, $companyModel);
$homepageController = new \App\Controllers\HomepageController($twig, $homepageModel);
$dashboardController = new \App\Controllers\DashboardController($twig, $dashboardModel);
$authController = new \App\Controllers\AuthController($twig, $pdo);
$pagesController = new \App\Controllers\FooterPageController($twig);

// Routage simple
$uri = $_GET['uri'] ?? '/';
$uri = trim($uri, '/');

switch ($uri) {
    // Pages globales
    case '':
        $homepageController->home();
        break;

    // Pages statiques
    case 'cgu':
        $pagesController->page('cgu');
        break;
    case 'contact':
        $pagesController->page('contact');
        break;
    case 'legal':
        $pagesController->page('legal');
        break;
    case 'privacy':
        $pagesController->page('privacy');
        break;
    case 'terms':
        $pagesController->page('terms');
        break;

    // Gestion des entreprises
    case 'companies':
        $companyController->list();
        break;
    case 'companies/create':
        $companyController->create();
        break;
    case 'companies/update':
        $companyController->update();
        break;
    case 'companies/delete':
        $companyController->delete();
        break;

    // Gestion des offres
    case 'offers':
        $offerController->list();
        break;
    case 'offers/detail':
        $offerController->detail();
        break;
    case 'offers/create':
        $offerController->create();
        break;
    case 'offers/update':
        $offerController->update();
        break;
    case 'offers/delete':
        $offerController->delete();
        break;

    case 'offers/addWishlist':
        $offerController->addWishlist();
        break;
    case 'offers/deleteWishlist':
        $offerController->deleteWishlist();
        break;
    // Candidatures
    case 'apply':
        $offerController->apply($_GET['id_offre']);
        break;

    // Gestion des utilisateurs
    case 'dashboard':
        $authController->dashboard();
        break;

    // Pilot et administrateur
    case 'dashboard/admin':
        $dashboardController->listStudents();
        break;
    case 'dashboard/admin/create-student':
        $dashboardController->createStudent();
        break;
    case 'dashboard/admin/delete-student':
        $dashboardController->deleteStudent();
        break;
    case 'dashboard/admin/update-student':
        $dashboardController->updateStudent();
        break;
    case 'dashboard/admin/create-pilot':
        $dashboardController->createPilot();
        break;
    case 'dashboard/admin/delete-pilot':
        $dashboardController->deletePilot();
        break;
    case 'dashboard/admin/update-pilot':
        $dashboardController->updatePilot();
        break;

    // Authentification
    case 'login':
        $authController->login();
        break;
    case 'logout':
        $authController->logout();
        break;

    default:
        header('HTTP/1.0 404 Not Found');
        echo '<h1>404 - Page introuvable</h1>';
        break;
}
