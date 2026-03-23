<?php

namespace App\Models;

class DashboardModel extends Model
{
    public function __construct(Database $connection)
    {
        parent::__construct($connection);
    }

    public function getStudentById($id)
    {
        return $this->connection->getRecord($id);
    }

    public function searchStudents($surname = "", $name = "", $promotion = "")
    {
        $sql = "SELECT Utilisateur.*, Etudiant.Promotion, Etudiant.ID_Pilote, "
            . "COUNT(Postule.ID_utilisateur) as nb_candidature "
            . "FROM Utilisateur "
            . "JOIN Etudiant ON Utilisateur.ID_utilisateur = Etudiant.ID_utilisateur "
            . "LEFT JOIN Postule ON Etudiant.ID_utilisateur = Postule.ID_utilisateur "
            . "WHERE 1=1 ";
        $params = [];
        if ($_SESSION['user_role'] === 'pilote') {
            $sql .= " AND Etudiant.ID_Pilote=" . $_SESSION['user_id'];
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
}
