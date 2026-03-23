<?php

namespace App\Models;

class HomepageModel extends Model
{
    public function __construct(Database $connection)
    {
        parent::__construct($connection);
    }

    public function countStudent()
    {
        $sql = "SELECT COUNT(ID_utilisateur) AS count_etudiant FROM Etudiant";
        $pdo = $this->connection->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function countOffer()
    {
        $sql = "SELECT COUNT(ID_offre)  AS count_offre FROM Offre";
        $pdo = $this->connection->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function avgApply()
    {
        $sql = "SELECT AVG(Nb_Candidatures) AS moyenne_candidature "
            . "FROM (SELECT COUNT(*) AS Nb_candidatures, p.ID_offre "
            . "FROM Postule p GROUP BY p.ID_offre) sum";
        $pdo = $this->connection->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function topOffers()
    {
        $sql = "SELECT COUNT(*) AS Nb_wishlist, o.ID_offre, o.Titre "
            . "FROM Souhaite s JOIN Offre o ON s.ID_offre = o.ID_offre "
            . "GROUP BY s.ID_offre "
            . "ORDER BY Nb_wishlist DESC "
            . "LIMIT 3";
        $pdo = $this->connection->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function timeDistribution()
    {
        $sql = "SELECT * FROM 
            (SELECT COUNT(*) AS total_court FROM Offre o WHERE o.Duree <= 3) o3
        JOIN 
            (SELECT COUNT(*) AS total_moyen FROM Offre o WHERE o.Duree <= 6 AND Duree > 3) o4
        JOIN 
            (SELECT COUNT(*) AS total_long FROM Offre o WHERE o.Duree > 6) o6;";
        $pdo = $this->connection->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
