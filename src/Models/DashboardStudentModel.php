<?php

namespace App\Models;

class DashboardStudentModel extends Model
{
    /**
     * Builds the student dashboard model with the shared database adapter
     */
    public function __construct(Database $connection)
    {
        parent::__construct($connection);
    }

    /**
     * Returns student applications with offer and company details
     * Uses joins across Postule Offre and Entreprise
     */
    public function getCandidatures($idUtilisateur)
    {
        $sql = "SELECT Offre.ID_offre, Offre.Titre, Entreprise.Nom_entreprise, Postule.CV, Postule.Lettre_motivation, Postule.Date_candidature
                FROM Postule 
                JOIN Offre ON Postule.ID_offre = Offre.ID_offre 
                JOIN Entreprise ON Offre.ID_entreprise = Entreprise.ID_entreprise 
                WHERE Postule.ID_utilisateur = :idUtilisateur";
        $pdo = $this->connection->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['idUtilisateur' => $idUtilisateur]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Returns offers saved in a student wishlist
     */
    public function getWishlist($idUtilisateur)
    {
        $sql = "SELECT Offre.ID_offre, Offre.Titre, Entreprise.Nom_entreprise 
                FROM Souhaite 
                JOIN Offre ON Souhaite.ID_offre = Offre.ID_offre 
                JOIN Entreprise ON Offre.ID_entreprise = Entreprise.ID_entreprise 
                WHERE Souhaite.ID_utilisateur = :idUtilisateur";
        $pdo = $this->connection->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['idUtilisateur' => $idUtilisateur]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Removes one offer from a student wishlist
     */
    public function removeFromWishlist($idUtilisateur, $offerId)
    {
        $sql = "DELETE FROM Souhaite WHERE Souhaite.ID_utilisateur = :idUtilisateur AND Souhaite.ID_offre = :idOffre";
        $pdo = $this->connection->getConnection();
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            'idUtilisateur' => $idUtilisateur,
            'idOffre' => $offerId
        ]);
    }
}
