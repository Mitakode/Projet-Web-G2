<?php

namespace App\Models;

class OfferModel extends Model
{
    /**
     * Builds the offer model and reuses the shared database adapter
     */
    public function __construct(Database $connection)
    {
        parent::__construct($connection);
    }

    /**
     * Returns one offer by id with company and application metadata
     */
    public function getOfferById($id)
    {
        $sql = "SELECT Offre.*, Entreprise.Nom_entreprise, nb.nb_candidatures";
        $params = ['id' => $id];

        if ($_SESSION['user_role'] === 'etudiant') {
            $sql .= ", IF(Postule.ID_utilisateur IS NOT NULL, 1, 0) AS has_applied";
        }

        $sql .= "
            FROM Offre
            JOIN Entreprise ON Offre.ID_entreprise = Entreprise.ID_entreprise
            LEFT JOIN (SELECT Postule.ID_offre, COUNT(*) AS nb_candidatures
                       FROM Postule
                       GROUP BY Postule.ID_offre) nb ON nb.ID_offre=Offre.ID_offre
        ";

        if ($_SESSION['user_role'] === 'etudiant') {
            $sql .= "LEFT JOIN Postule ON Offre.ID_offre = Postule.ID_offre 
                AND Postule.ID_utilisateur = :userId";
            $params['userId'] = $_SESSION['user_id'];
        }

        $sql .= " WHERE Offre.ID_offre = :id";

        $stmt = $this->connection->getConnection()->prepare($sql);
        $stmt->execute($params);
                // Return a single record as an associative array
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
         * Searches offers using optional keyword company type and duration filters
     */
    public function searchOffers($keyword = "", $company = "", $type = "", $duration = "")
    {
        $sql = "SELECT Offre.*, Entreprise.Nom_entreprise " ;
        $params = [];
        
        if (($_SESSION['user_role'] === 'etudiant')) {
            $sql .= ", IF(Souhaite.ID_utilisateur IS NOT NULL, 1, 0) AS is_in_wishlist, IF(Postule.ID_utilisateur IS NOT NULL, 1, 0) AS has_applied ";
        }

        $sql .= "
            FROM Offre 
            JOIN Entreprise ON Offre.ID_entreprise = Entreprise.ID_entreprise ";

        if (($_SESSION['user_role'] === 'etudiant')) {
            $sql .= " LEFT JOIN Souhaite ON Offre.ID_offre = Souhaite.ID_offre 
                AND Souhaite.ID_utilisateur = :userId
            LEFT JOIN Postule ON Offre.ID_offre = Postule.ID_offre 
                AND Postule.ID_utilisateur = :userId";

            $params = ['userId' => $_SESSION['user_id']];
        }
        $sql .= " WHERE 1=1";
        // Keep a stable base condition to append dynamic AND clauses


        // Search in title description and skills when a keyword is provided
        if (!empty($keyword)) {
            $sql .= " AND (Offre.Titre LIKE :key OR Offre.Description LIKE :key OR Offre.Liste_competences LIKE :key)";
            $params['key'] = '%' . $keyword . '%';
        }

        if (!empty($company)) {
            $sql .= " AND Entreprise.Nom_entreprise LIKE :company";
            $params['company'] = '%' . $company . '%';
        }

        if (!empty($type)) {
            $sql .= " AND Offre.Titre LIKE :type";
            $params['type'] = '%' . $type . '%';
        }

        if (!empty($duration)) {
            $bornes = explode(',', $duration);
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
        // Return all matching offers
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Creates a new offer record
     */
    public function createOffer($data)
    {
        return $this->connection->insertRecord($data);
    }

    /**
     * Updates an existing offer by id
     */
    public function updateOffer($id, $data)
    {
        return $this->connection->updateRecord($id, $data);
    }

    /**
     * Deletes an offer by id
     */
    public function deleteOffer($id)
    {
        return $this->connection->deleteRecord($id);
    }

    /**
     * Adds an offer to a student wishlist
     */
    public function addWishlist($offerId, $studentId)
    {
        $sql = "INSERT INTO Souhaite (ID_utilisateur, ID_offre) VALUES (:studentId, :offerId)";
        $params = ['studentId' => $studentId, 'offerId' => $offerId];
        $stmt = $this->connection->getConnection()->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Returns wishlist linkage for one offer and one student
     */
    public function isInWishlist($offerId, $studentId)
    {
        $sql = "SELECT * FROM Souhaite WHERE ID_offre = :idOffre AND ID_utilisateur = :studentId";
        $params = ['idOffre' => $offerId, 'studentId' => $studentId];
        $stmt = $this->connection->getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Returns application linkage for one offer and one student
     */
    public function hasApplied($offerId, $studentId)
    {
        $sql = "SELECT * FROM Postule WHERE ID_offre = :idOffre AND ID_utilisateur = :studentId";
        $params = ['idOffre' => $offerId, 'studentId' => $studentId];
        $stmt = $this->connection->getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Creates a student application with uploaded document paths
     */
    public function addPostule($offerId, $studentId, $cvPath, $letterPath)
    {
        $sql = "INSERT INTO Postule (ID_utilisateur, ID_offre, CV, Lettre_motivation, Date_candidature) "
            . "VALUES (:studentId, :offerId, :cvPath, :letterPath, :dateCandidature)";
        $params = [
            'studentId' => $studentId,
            'offerId' => $offerId,
            'cvPath' => $cvPath,
            'letterPath' => $letterPath,
            'dateCandidature' => date('Y-m-d')
        ];
        $stmt = $this->connection->getConnection()->prepare($sql);
        return $stmt->execute($params);
    }
}
