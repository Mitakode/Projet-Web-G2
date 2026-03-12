<?php
namespace App\Models;

use PDO; // On utilise la classe PDO native de PHP

class SqlDatabase implements Database {
    private $pdo;
    private $tableName;
    private $primaryKey;

    /**
     * Le constructeur reçoit l'instance PDO et le nom de la table
     */
    public function __construct($pdo, $tableName, $primaryKey = 'id') {
        $this->pdo = $pdo;
        $this->tableName = $tableName;
        $this->primaryKey = $primaryKey;
    }

    public function getAllRecords() {
        // On récupère tout dans la table spécifiée
        $stmt = $this->pdo->query("SELECT * FROM {$this->tableName}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Récupère un enregistrement spécifique par ID.
     * @param int $id
     * @return array|null Enregistrement trouvé ou null
     */
    public function getRecord($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->tableName} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Insère un nouvel enregistrement
     * @param array $record
     * @return int ID du nouvel enregistrement
     */
    public function insertRecord($record) {
        // Préparation dynamique des colonnes (ex: task, status)
        $columns = implode(', ', array_keys($record));
        $placeholders = implode(', ', array_fill(0, count($record), '?'));
        
        $sql = "INSERT INTO {$this->tableName} ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($record));
        
        return $this->pdo->lastInsertId();
    }

    /**
     * Met à jour un enregistrement existant
     * @param int $id
     * @param array $record
     * @return bool True si succès, False sinon
     */
    public function updateRecord($id, $record) {
        $sets = [];
        foreach (array_keys($record) as $key) {
            $sets[] = "$key = ?";
        }
        $sql = "UPDATE {$this->tableName} SET " . implode(', ', $sets) . " WHERE id = ?";
        
        $params = array_values($record);
        $params[] = $id; // L'ID pour le WHERE
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Supprime un enregistrement spécifique par son ID
     * @param int $id L'identifiant de la ligne à supprimer
     * @return bool True si la suppression a réussi, False sinon
     */
    public function deleteRecord($id) {
        // On prépare la requête pour éviter les injections SQL
        $sql = "DELETE FROM {$this->tableName} WHERE {$this->primaryKey} = ?";
        $stmt = $this->pdo->prepare($sql);
        
        // On exécute en passant l'ID
        return $stmt->execute([$id]);
    }
}