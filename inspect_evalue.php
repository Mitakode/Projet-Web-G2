<?php
// Connexion à la base de données
$dsn = 'mysql:host=localhost;dbname=thepiston;charset=utf8';
$username = 'userthepiston';
$password = 'Thepiston1%';

try {
    $pdo = new \PDO($dsn, $username, $password);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    
    echo "=== STRUCTURE DE LA TABLE EVALUE ===\n";
    $stmt = $pdo->query("DESCRIBE Evalue");
    $columns = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    echo json_encode($columns, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
    echo "\n\n=== INFORMATIONS COMPLEMENTAIRES ===\n";
    
    // Afficher aussi la structure des autres tables liées pour contexte
    echo "\nStructure de la table Utilisateur:\n";
    $stmt = $pdo->query("DESCRIBE Utilisateur");
    $columns = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    echo json_encode($columns, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
    echo "\n\nStructure de la table Etudiant:\n";
    $stmt = $pdo->query("DESCRIBE Etudiant");
    $columns = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    echo json_encode($columns, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
    echo "\n\nStructure de la table Entreprise:\n";
    $stmt = $pdo->query("DESCRIBE Entreprise");
    $columns = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    echo json_encode($columns, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
    // Essayer de voir les données
    echo "\n\n=== APERÇU DES DONNEES DANS EVALUE ===\n";
    $stmt = $pdo->query("SELECT * FROM Evalue LIMIT 5");
    $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
    // Compter les enregistrements
    echo "\n\nNombre d'enregistrements dans Evalue: ";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM Evalue");
    $count = $stmt->fetch(\PDO::FETCH_ASSOC);
    echo $count['count'];
    
} catch (\PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}
?>
