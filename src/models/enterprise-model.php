<?php
namespace App\Models;

class EnterpriseModel extends Model {

    public function __construct(Database $connection) {
        parent::__construct($connection);
    }
    
    public function searchEnterprises($keyword = "") {
        $sql = "SELECT * FROM Entreprise WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (Nom_entreprise LIKE :key OR Description LIKE :key)";
            $params['key'] = '%' . $keyword . '%';
        }

        $pdo = $this->connection->getConnection();

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createCompany(){
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $Name= isset($_POST['nameCompany']) ? htmlspecialchars(trim($_POST['nameCompany'])):'';
            $Description=isset($_POST['descriptonCompany']) ? htmlspecialchars(trim($_POST['descriptionCompany'])):'';
            $Contact=isset($_POST['contactCompany']) ? htmlspecialchars(trim($_POST['contactCompany'])):'';

            if(empty($Name)||empty($Description)||empty($Contact)){
                echo "Veulliez remplir correctement tous les champs.";
            }
            else{
                return $this->connection->insertRecord(['Nom_entreprise'=> $Name, 'Description'=> $Description, 'Contact'=> $Contact]);
            }
        }
    }

    public function deleteCompany($id){
        return $this->connection->deleteRecord($id);
    }
}