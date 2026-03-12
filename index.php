<?php
require "vendor/autoload.php";

use App\Controllers\EnterpriseController;
use App\Models\EnterpriseModel; // On importe le modèle

// Configuration de Twig
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, ['debug' => true]);

// Connexion à la base de données
$dsn = 'mysql:host=localhost;dbname=thepiston;charset=utf8';
$username = 'root';
$password = '4NTFDDZVyqot?';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

// Initialisation des composants
// Adaptateur BDD des différentes tables
$enterpriseDbAdapter = new \App\Models\SqlDatabase($pdo, 'Entreprise', 'ID_entreprise');

// On crée le modèle avec la connexion PDO
$enterpriseModel = new App\Models\EnterpriseModel($enterpriseDbAdapter);
//$offerModel      = new App\Models\OfferModel($pdo);
//$userModel       = new App\Models\UserModel($pdo); // Gère Etudiants, Pilotes, Admins

// Contrôleurs
//$mainController = new App\Controllers\MainController($twig, $offerModel, $enterpriseModel);
$enterpriseController = new App\Controllers\EnterpriseController($twig, $enterpriseModel);
//$offerController = new App\Controllers\OfferController($twig, $offerModel, $enterpriseModel);
//$userController = new App\Controllers\UserController($twig, $userModel);

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
    case 'entreprises': // Rechercher et afficher
        $enterpriseController->list();
        break;
    case 'entreprise/create': // Créer
        $enterpriseController->create();
        break;
    case 'entreprise/delete': // Supprimer
        $enterpriseController->delete();
        break;

    // Gestion des Offres
    case 'offers':
        $offerController->list();
        break;
    case 'offer/details':
        $offerController->details($_GET['id']);
        break;
    case 'offer/create':
        $offerController->create();
        break;
    case 'offer/delete':
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

    default:
        header("HTTP/1.0 404 Not Found");
        echo $twig->render('404.twig');
        break;
}