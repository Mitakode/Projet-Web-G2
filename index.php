<?php
session_start();
require "vendor/autoload.php";

use App\Controllers\CompanyController;
use App\Models\CompanyModel; // On importe le modèle
use App\Controllers\AuthController;
use App\Controllers\OfferController;
use App\Models\OfferModel;
use App\Controllers\HomepageController;
use App\Models\HomepageModel;
use App\Controllers\DashboardController;
use App\Models\DashboardModel;

// Configuration de Twig
$loader = new \Twig\Loader\FilesystemLoader('vue');
$twig = new \Twig\Environment($loader, ['debug' => true]);
$twig->addGlobal('session', $_SESSION); // Permet d'accéder à la session dans tous les templates Twig

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
$offerDbAdapter = new \App\Models\SqlDatabase($pdo, 'Offre', 'ID_offre'); // AJOUT : Adaptateur pour les offres
$homepageDbAdapter = new \App\Models\SqlDatabase($pdo, 'Offre', 'ID_offre');
$dashboardAdminDbAdapter = new \App\Models\SqlDatabase($pdo, 'Utilisateur', 'ID_utilisateur');

// On crée le modèle avec la connexion PDO
$companyModel = new App\Models\CompanyModel($companyDbAdapter);
$offerModel = new App\Models\OfferModel($offerDbAdapter); // MODIFICATION : Modèle décommenté avec le bon adaptateur
$homepageModel = new App\Models\HomepageModel($homepageDbAdapter);
$dashboardAdminModel = new App\Models\DashboardAdminModel($dashboardAdminDbAdapter);

// Contrôleurs
$companyController = new App\Controllers\CompanyController($twig, $companyModel);
$offerController = new App\Controllers\OfferController($twig, $offerModel, $companyModel); // MODIFICATION : Contrôleur décommenté avec les bons arguments
$homepageController = new App\Controllers\HomepageController($twig, $homepageModel);
$dashboardAdminController = new App\Controllers\DashboardAdminController($twig, $dashboardAdminModel);
$authController = new App\Controllers\AuthController($twig, $pdo);

// Routage simple
$uri = $_GET['uri'] ?? '/';
$uri = trim($uri, '/');

switch ($uri) {
    // Pages Globales
    case '':
        $homepageController->home();
        break;
    case 'mentions-legales':
        $homepageController->legal();
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
    case 'offers/detail': // MODIFICATION : 'details' devient 'detail' pour correspondre à la méthode
        $offerController->detail(); 
        break;
    case 'offers/create':
        $offerController->create();
        break;
    case 'offers/update': // AJOUT : Route update
        $offerController->update();
        break;
    case 'offers/delete':
        $offerController->delete();
        break;

    // Candidatures
    case 'apply':
        $offerController->apply($_GET['id_offre']);
        break;

    // Gestion des Utilisateurs
    // Pilot et Administrateur
    case 'dashboard/admin':
        $dashboardAdminController->list();
        break;
    case 'dashboard/admin/create-student':
        $dashboardAdminController->createStudent();
        break;
    case 'dashboard/admin/delete-student':
        $dashboardAdminController->deleteStudent();
        break;
    case 'dashboard/admin/update-student':
        $dashboardAdminController->updateStudent();
        break;
    case 'dashboard/admin/create-pilot':
        $dashboardAdminController->createPilot();
        break;
    case 'dashboard/admin/delete-pilot':
        $dashboardAdminController->deletePilot();
        break;
    case 'dashboard/admin/update-pilot':
        $dashboardAdminController->updatePilot();
        break;
    /* Student
    case 'dashboard/student':
        $studentController->list();
        break;
    case 'dashboard/student/delete-wishlist':
        $dashboardController->deleteWishlist();
        break;
*/
    
    // Authentification
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