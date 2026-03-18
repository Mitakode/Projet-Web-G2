<?php
namespace App\Models;

class OfferModel extends Model {

    public function __construct(Database $connection) {
        parent::__construct($connection);
    }

    public function getOfferById($id) {
        $sql = "SELECT Offre.*, Entreprise.Nom_entreprise 
                FROM Offre 
                JOIN Entreprise ON Offre.ID_entreprise = Entreprise.ID_entreprise 
                WHERE Offre.ID_offre = :id";
        $stmt = $this->connection->getConnection()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function searchOffers($keyword = "") {
        $sql = "SELECT Offre.*, Entreprise.Nom_entreprise 
                FROM Offre 
                JOIN Entreprise ON Offre.ID_entreprise = Entreprise.ID_entreprise 
                WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (Offre.Titre LIKE :key OR Offre.Description LIKE :key OR Offre.Liste_competences LIKE :key)";
            $params['key'] = '%' . $keyword . '%';
        }

        $stmt = $this->connection->getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createOffer($data) {
        return $this->connection->insertRecord($data);
    }

    public function updateOffer($id, $data) {
        return $this->connection->updateRecord($id, $data);
    }

    public function deleteOffer($id) {
        return $this->connection->deleteRecord($id);
    }
}