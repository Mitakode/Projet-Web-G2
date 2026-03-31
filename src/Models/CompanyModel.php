<?php

namespace App\Models;

class CompanyModel extends Model
{
    /**
     * Builds the company model with the shared database adapter
     */
    public function __construct(Database $connection)
    {
        parent::__construct($connection);
    }

    /**
     * Returns one company by its identifier
     */
    public function getCompanyById($id)
    {
        return $this->connection->getRecord($id);
    }

    /**
     * Searches companies with optional keyword and student rating context
     */
    public function searchCompanies($keyword = "", $currentUserId = null)
    {
        $sql = "SELECT Entreprise.*, AVG(Evalue.Note_entreprise) as Moyenne_Note";
        $params = [];

        if ($currentUserId !== null) {
            $sql .= ", MAX(CASE WHEN Evalue.ID_utilisateur = :current_user_id"
                . " THEN 1 ELSE 0 END) as is_rated";
            $sql .= ", MAX(CASE WHEN Evalue.ID_utilisateur = :current_user_id"
                . " THEN Evalue.Note_entreprise ELSE NULL END) as my_rating";
            $params['current_user_id'] = (int) $currentUserId;
        } else {
            $sql .= ", 0 as is_rated";
            $sql .= ", NULL as my_rating";
        }

        $sql .= "
        FROM Entreprise 
        LEFT JOIN Evalue ON Entreprise.ID_entreprise = Evalue.ID_entreprise
        WHERE 1=1";

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

    /**
     * Creates a new company record
     */
    public function createCompany($data)
    {
        return $this->connection->insertRecord($data);
    }

    /**
     * Updates an existing company record
     */
    public function updateCompany($id, $data)
    {
        return $this->connection->updateRecord($id, $data);
    }

    /**
     * Deletes a company record by id
     */
    public function deleteCompany($id)
    {
        return $this->connection->deleteRecord($id);
    }

    /**
     * Creates or updates a company rating for one user
     */
    public function rateCompany($companyId, $idUtilisateur, $rating)
    {
        $pdo = $this->connection->getConnection();

        // Check whether this user has already rated this company
        $checkSql = "SELECT COUNT(*) as count FROM Evalue "
            . "WHERE ID_entreprise = :id_entreprise "
            . "AND ID_utilisateur = :id_utilisateur";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([
            ':id_entreprise' => $companyId,
            ':id_utilisateur' => $idUtilisateur
        ]);
        $result = $checkStmt->fetch(\PDO::FETCH_ASSOC);
        $existing = $result['count'] > 0;

        if ($existing) {
            // Update the existing rating
            $updateSql = "UPDATE Evalue SET Note_entreprise = :note "
                . "WHERE ID_entreprise = :id_entreprise "
                . "AND ID_utilisateur = :id_utilisateur";
            $updateStmt = $pdo->prepare($updateSql);
            return $updateStmt->execute([
                ':note' => $rating,
                ':id_entreprise' => $companyId,
                ':id_utilisateur' => $idUtilisateur
            ]);
        } else {
            // Create a new rating
            $insertSql = "INSERT INTO Evalue (ID_entreprise, ID_utilisateur, Note_entreprise) "
                . "VALUES (:id_entreprise, :id_utilisateur, :note)";
            $insertStmt = $pdo->prepare($insertSql);
            return $insertStmt->execute([
                ':id_entreprise' => $companyId,
                ':id_utilisateur' => $idUtilisateur,
                ':note' => $rating
            ]);
        }
    }
}
