<?php
namespace App\Models;

class EntrepriseModel extends Model {

    public function __construct(Database $connection) {
        parent::__construct($connection);
    }

    public function getEntrepriseById($id) {
        return $this->connection->getRecord($id);
    }
    
    public function searchEntreprises($keyword = "") {
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

    public function createCompany($data){
        return $this->connection->insertRecord($data);
    }

    public function updateCompany($id, $data) {
        return $this->connection->updateRecord($id, $data);
    }

    public function deleteCompany($id){
        return $this->connection->deleteRecord($id);
    }
}