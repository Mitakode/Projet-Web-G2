<?php
namespace App\Models;

class StudentModel extends Model{

    public function __construct(Database $connection) {
        parent::__construct($connection);
    }

    public function getStudentById($id) {
        return $this->connection->getRecord($id);
    }

    public function searchStudents($surname = "", $name = "", $promotion = "") {
        $sql = "SELECT Etudiant.*, COUNT(Postule.ID_utilisateur) as nb_candidature
        FROM Etudiant 
        LEFT JOIN Postule ON Etudiant.ID_utilisateur = Postule.ID_utilisateur
        WHERE Etudiant.ID_Pilote=" . $_SESSION['user_id'];
        $params = [];

        if (!empty($surname)) {
            $sql .= " AND Etudiant.Nom LIKE :surname";
            $params['surname'] = '%' . $surname . '%';
        }

        if (!empty($name)) {
            $sql .= " AND Etudiant.Prenom LIKE :name";
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
}