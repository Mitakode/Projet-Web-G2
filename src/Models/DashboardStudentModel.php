<?php
namespace App\Models;

class DashboardStudentModel extends Model {

    public function __construct(Database $connection) {
        parent::__construct($connection);
    }

    public function getCandidatures($idUtilisateur) {
        $sql = "SELECT Offre.ID_offre, Offre.Titre, Entreprise.Nom_entreprise, Postule.CV, Postule.Lettre_motivation 
                FROM Postule 
                JOIN Offre ON Postule.ID_offre = Offre.ID_offre 
                JOIN Entreprise ON Offre.ID_entreprise = Entreprise.ID_entreprise 
                WHERE Postule.ID_utilisateur = :idUtilisateur";
        
        $pdo = $this->connection->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['idUtilisateur' => $idUtilisateur]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getWishlist($idUtilisateur) {
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

    public function removeFromWishlist($idUtilisateur, $idOffre) {
        $sql = "DELETE FROM Souhaite WHERE ID_utilisateur = :idUtilisateur AND ID_offre = :idOffre";
        $pdo = $this->connection->getConnection();
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            'idUtilisateur' => $idUtilisateur,
            'idOffre' => $idOffre
        ]);
    }
}