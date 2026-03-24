<?php

namespace App\Models;

class OfferModel extends Model
{
    // Le constructeur appelle celui du parent (Model) pour initialiser la connexion à la BDD
    public function __construct(Database $connection)
    {
        parent::__construct($connection);
    }

    /**
     * Récupère une offre spécifique par son ID.
     * Utilise une jointure (JOIN) pour récupérer également le nom de l'entreprise associée.
     */
    public function getOfferById($id)
    {
        $sql = "SELECT Offre.*, Entreprise.Nom_entreprise 
                FROM Offre 
                JOIN Entreprise ON Offre.ID_entreprise = Entreprise.ID_entreprise 
                WHERE Offre.ID_offre = :id";
        $stmt = $this->connection->getConnection()->prepare($sql);
        $stmt->execute(['id' => $id]);
// Retourne un seul enregistrement sous forme de tableau associatif
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Recherche des offres en fonction d'un mot-clé.
     * La jointure permet d'afficher le nom de l'entreprise dans la liste des résultats.
     */
    public function searchOffers($keyword = "", $company = "", $type = "", $duree = "")
    {
        $sql = "SELECT Offre.*, Entreprise.Nom_entreprise, IF(Souhaite.ID_utilisateur IS NOT NULL, 1, 0) AS is_in_wishlist
            FROM Offre 
            JOIN Entreprise ON Offre.ID_entreprise = Entreprise.ID_entreprise
            LEFT JOIN Souhaite ON Offre.ID_offre = Souhaite.ID_offre 
                AND Souhaite.ID_utilisateur = 16
            WHERE 1=1";
// "1=1" est une astuce pour pouvoir ajouter facilement des "AND" dynamiquement
        $params = [];
// Si un mot-clé est tapé, on cherche dans le titre, la description et les compétences
        if (!empty($keyword)) {
            $sql .= " AND (Offre.Titre LIKE :key OR Offre.Description LIKE :key OR Offre.Liste_competences LIKE :key)";
            $params['key'] = '%' . $keyword . '%';
// Les '%' permettent de chercher le mot n'importe où dans la chaîne
        }

        if (!empty($company)) {
            $sql .= " AND Entreprise.Nom_entreprise LIKE :company";
            $params['company'] = '%' . $company . '%';
        }

        if (!empty($type)) {
            $sql .= " AND Offre.Titre LIKE :type";
            $params['type'] = '%' . $type . '%';
        }

        if (!empty($duree)) {
            $bornes = explode(',', $duree);
            if (count($bornes) == 1) {
                $sql .= " AND Offre.Duree = :duree";
                $params['duree'] = $bornes[0];
            } else {
                $sql .= " AND Offre.Duree BETWEEN :duree_min AND :duree_max";
                $params['duree_min'] = $bornes[0];
                $params['duree_max'] = $bornes[1];
            }
        }

        $stmt = $this->connection->getConnection()->prepare($sql);
        $stmt->execute($params);
// Retourne toutes les offres correspondantes
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Insère une nouvelle offre dans la base de données.
     */
    public function createOffer($data)
    {
        return $this->connection->insertRecord($data);
    }

    /**
     * Met à jour une offre existante ciblée par son ID.
     */
    public function updateOffer($id, $data)
    {
        return $this->connection->updateRecord($id, $data);
    }

    /**
     * Supprime une offre de la base de données via son ID.
     */
    public function deleteOffer($id)
    {
        return $this->connection->deleteRecord($id);
    }

    
    public function addWishlist($offerId, $studentId)
    {
        $sql = "INSERT INTO Souhaite (ID_utilisateur, ID_offre) VALUES (:studentId, :offerId)";
        $params = ['studentId' => $studentId, 'offerId' => $offerId];
        $stmt = $this->connection->getConnection()->prepare($sql);
        return $stmt->execute($params);
    }

}
