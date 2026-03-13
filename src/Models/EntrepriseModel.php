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
        $sql = "SELECT * FROM Entreprise WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (Nom_entreprise LIKE :key OR Description LIKE :key)";
            $params['key'] = '%' . $keyword . '%';
        }

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