<?php
namespace App\Models;

class DashboardAdminModel extends Model{

    public function __construct(Database $connection) {
        parent::__construct($connection);
    }

    public function getStudentById($id) {
        return $this->connection->getRecord($id);
    }

    public function searchStudents($surname = "", $name = "", $promotion = "") {
        $sql = "SELECT Utilisateur.*, Etudiant.Promotion, Etudiant.ID_Pilote, COUNT(Postule.ID_utilisateur) as nb_candidature 
        FROM Utilisateur JOIN Etudiant ON Utilisateur.ID_utilisateur = Etudiant.ID_utilisateur LEFT JOIN Postule ON Etudiant.ID_utilisateur = Postule.ID_utilisateur 
        WHERE 1=1 ";
        $params = [];

        if($_SESSION['user_role'] === 'pilote') {
            $sql .= " AND Etudiant.ID_Pilote=". $_SESSION['user_id'];
        }

        if (!empty($surname)) {
            $sql .= " AND Utilisateur.Nom LIKE :surname";
            $params['surname'] = '%' . $surname . '%';
        }

        if (!empty($name)) {
            $sql .= " AND Utilisateur.Prenom LIKE :name";
            $params['name'] = '%' . $name . '%';
        }

        if (!empty($promotion)) {
            $sql .= " AND Etudiant.Promotion LIKE :promotion";
            $params['promotion'] = '%' . $promotion . '%';
        }
        $sql .= " GROUP BY Etudiant.ID_utilisateur";

        $pdo = $this->connection->getConnection();

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function searchPilots($surnameP = "", $nameP = "") {
        $sql = "SELECT Utilisateur.*
        FROM Utilisateur JOIN Pilote ON Utilisateur.ID_utilisateur = Pilote.ID_utilisateur 
        WHERE 1=1 ";
        $params = [];

        if (!empty($surnameP)) {
            $sql .= " AND Utilisateur.Nom LIKE :surnameP";
            $params['surnameP'] = '%' . $surnameP . '%';
        }

        if (!empty($nameP)) {
            $sql .= " AND Utilisateur.Prenom LIKE :nameP";
            $params['nameP'] = '%' . $nameP . '%';
        }
        
        $pdo = $this->connection->getConnection();

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createStudent($userData, $studentData) {
    try {
        $this->pdo->beginTransaction();

        $columns = implode(', ', array_keys($userData));
        $placeholders = implode(', ', array_fill(0, count($userData), '?'));
        $sqlUser = "INSERT INTO Utilisateur ($columns) VALUES ($placeholders)";
        
        $stmt = $this->pdo->prepare($sqlUser);
        $stmt->execute(array_values($userData));
        
        $userId = $this->pdo->lastInsertId();
        $studentData['ID_utilisateur'] = $userId;

        $colStudent = implode(', ', array_keys($studentData));
        $placeStudent = implode(', ', array_fill(0, count($studentData), '?'));
        $sqlStudent = "INSERT INTO Etudiant ($colStudent) VALUES ($placeStudent)";

        $stmt = $this->pdo->prepare($sqlStudent);
        $stmt->execute(array_values($studentData));

        $this->pdo->commit();
        return $userId;

    } catch (\Exception $e) {
        $this->pdo->rollBack();
        throw $e;
    }
}
}