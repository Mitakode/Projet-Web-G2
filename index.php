<?php

session_start();
require "vendor/autoload.php";

// Configure Twig environment
$loader = new \Twig\Loader\FilesystemLoader('vue');
$twig = new \Twig\Environment($loader, ['debug' => true]);
$twig->addGlobal('session', $_SESSION);

// Connect to the database
$dbConfigPath = __DIR__ . '/config/database.local.php';
if (!file_exists($dbConfigPath)) {
    die('Fichier de configuration base de donnees manquant : config/database.local.php');
}

$dbConfig = require $dbConfigPath;
$dsn = $dbConfig['dsn'] ?? '';
$username = $dbConfig['username'] ?? '';
$password = $dbConfig['password'] ?? '';

if ($dsn === '' || $username === '') {
    die('Configuration base de donnees invalide dans config/database.local.php');
}

try {
    $pdo = new \PDO($dsn, $username, $password);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
} catch (\PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

// Initialize database adapters and models
$companyDbAdapter = new \App\Models\SqlDatabase($pdo, 'Entreprise', 'ID_entreprise');
$offerDbAdapter = new \App\Models\SqlDatabase($pdo, 'Offre', 'ID_offre');
$homepageDbAdapter = new \App\Models\SqlDatabase($pdo, 'Offre', 'ID_offre');
$dashboardAdminDbAdapter = new \App\Models\SqlDatabase($pdo, 'Utilisateur', 'ID_utilisateur');
// Initialize student dashboard model and controller
// Reuse an existing adapter because this model runs its own SQL queries
$dashboardStudentModel = new \App\Models\DashboardStudentModel($companyDbAdapter);
$dashboardStudentController = new \App\Controllers\DashboardStudentController($twig, $dashboardStudentModel);

// Build models using their adapters
$companyModel = new App\Models\CompanyModel($companyDbAdapter);
$offerModel = new App\Models\OfferModel($offerDbAdapter); // Use the dedicated offer adapter
$homepageModel = new App\Models\HomepageModel($homepageDbAdapter);
$dashboardAdminModel = new App\Models\DashboardAdminModel($dashboardAdminDbAdapter);

// Build controllers
$companyController = new App\Controllers\CompanyController($twig, $companyModel);
$offerController = new App\Controllers\OfferController(
    $twig,
    $offerModel,
    $companyModel
); // Inject offer and company models
$homepageController = new App\Controllers\HomepageController($twig, $homepageModel);
$dashboardAdminController = new App\Controllers\DashboardAdminController($twig, $dashboardAdminModel);
$authController = new App\Controllers\AuthController($twig, $pdo);
$footerPagesController = new App\Controllers\FooterPageController($twig);

// Resolve route from query parameter
$uri = $_GET['uri'] ?? '/';
$uri = trim($uri, '/');

switch ($uri) {
    // Public home page
    case '':
        $homepageController->home();
        break;

    // Static footer pages
    case 'cgu':
        $footerPagesController->page('cgu');
        break;
    case 'contact':
        $footerPagesController->page('contact');
        break;
    case 'legal':
        $footerPagesController->page('legal');
        break;
    case 'privacy':
        $footerPagesController->page('privacy');
        break;
    case 'terms':
        $footerPagesController->page('terms');
        break;

    // Company routes
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
    case 'companies/rate':
        $companyController->rate();
        break;

    // Offer routes
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
    // Application route
    case 'apply':
        $offerController->apply();
        break;

    // User dashboard route
    // Redirects to admin or student dashboard based on role
    case 'dashboard':
        $authController->dashboard($dashboardAdminController, $dashboardStudentController);
        break;

    // Pilot and admin dashboard routes
    case 'dashboard/admin':
        $dashboardAdminController->list();
        break;
    case 'dashboard/admin/student-details':
        $dashboardAdminController->studentDetails();
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
    // Student dashboard routes
    case 'dashboard/student':
        $dashboardStudentController->index(); // Render student dashboard
        break;
    case 'dashboard/student/remove-wishlist':
        $dashboardStudentController->removeWishlist(); // Remove one wishlist item
        break;

    // Authentication routes
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
