<?php

namespace App\Models;

class CompanyModel extends Model
{
    public function __construct(Database $connection)
    {
        parent::__construct($connection);
    }

    public function getCompanyById($id)
    {
        return $this->connection->getRecord($id);
    }

    public function searchCompanies($keyword = "")
    {
        $sql = "SELECT Entreprise.*, AVG(Evalue.Note_entreprise) as Moyenne_Note
        FROM Entreprise 
        LEFT JOIN Evalue ON Entreprise.ID_entreprise = Evalue.ID_entreprise
        WHERE 1=1";
        $params = [];
        if (!empty($keyword)) {
            $sql .= " AND (Entreprise.Nom_entreprise LIKE :key OR Entreprise.Description LIKE :key)";
            $params['key'] = '%' . $keyword . '%';
        }
        $sql .= " GROUP BY Entreprise.ID_entreprise";
        $pdo = $this->connection->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createCompany($data)
    {
        return $this->connection->insertRecord($data);
    }

    public function updateCompany($id, $data)
    {
        return $this->connection->updateRecord($id, $data);
    }

    public function deleteCompany($id)
    {
        return $this->connection->deleteRecord($id);
    }

    public function rateCompany($idEntreprise, $idUtilisateur, $note)
    {
        $pdo = $this->connection->getConnection();
        
        // Vérifier si une note existe déjà pour cet utilisateur et cette entreprise
        $checkSql = "SELECT COUNT(*) as count FROM Evalue WHERE ID_entreprise = :id_entreprise AND ID_utilisateur = :id_utilisateur";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([
            ':id_entreprise' => $idEntreprise,
            ':id_utilisateur' => $idUtilisateur
        ]);
        $result = $checkStmt->fetch(\PDO::FETCH_ASSOC);
        $existing = $result['count'] > 0;

        if ($existing) {
            // Mettre à jour la note existante
            $updateSql = "UPDATE Evalue SET Note_entreprise = :note WHERE ID_entreprise = :id_entreprise AND ID_utilisateur = :id_utilisateur";
            $updateStmt = $pdo->prepare($updateSql);
            return $updateStmt->execute([
                ':note' => $note,
                ':id_entreprise' => $idEntreprise,
                ':id_utilisateur' => $idUtilisateur
            ]);
        } else {
            // Créer une nouvelle note
            $insertSql = "INSERT INTO Evalue (ID_entreprise, ID_utilisateur, Note_entreprise) VALUES (:id_entreprise, :id_utilisateur, :note)";
            $insertStmt = $pdo->prepare($insertSql);
            return $insertStmt->execute([
                ':id_entreprise' => $idEntreprise,
                ':id_utilisateur' => $idUtilisateur,
                ':note' => $note
            ]);
        }
    }
}
