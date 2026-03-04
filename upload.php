<?php

require_once 'validateInput.php';

//Vérification qu'un fichier a été televersé via un formulaire POST
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    //Recupérer les infos du fichier
    $file = $_FILES['file'];

    //Définition du répertoire où le fichier sera stocké
    $uploadDir = 'uploads/';

    //Définitionde la taille maximale autorisée pour le fichier (2Mo ici)
    $maxFileSize = 2*1024*1024; //2Mo converti en octets

    //Vérification des errreurs de téléversement
    //UPLOAD_ERR_OK = 0 ; pas d'erreur
    if($file['error'] !== UPLOAD_ERR_OK){
        die('Erreur lors du téléversement. Code: '.$file['error']);
        //Arrête le script et affiche le code d'erreur
    }

    //Vérification de la taille du fichier
    if($file['size'] > $maxFileSize){
        die('Le fichier est trop volumineux (max 2Mo).');
        //Arrête le script si trop gros
    }

    //Vérification du type MIME du fichier (PDF)
    $fileType = mime_content_type($file['tmp_name']);
    
    if($fileType !== 'application/pdf'){
        die('Le fichier doit être au format PDF.');
        // Arrête le script si le fichier n'est pas un PDF
    }

    // Validation du nom de fichier original avant toute utilisation
    // Récupération du nom sans extension
    $originalName = pathinfo($file['name'], PATHINFO_FILENAME);
    // Validation avec validateInput
    $safeName = validateInput($originalName);
    //Génération d'un nom de fichier unique pour éviter d'écraser un fichier existant
    //uniqid('file_',true) crée un identifiant unique basé sur le temps actuel
    $fileName = uniqid($safeName . '_', true) . '.pdf';

    //Déplacement du fichier depuis le dossier temporaire vers le répertoire final
    if(move_uploaded_file($file['tmp_name'],$uploadDir . $fileName)){
        //Succès : message de validation et lien vers le fichier
        echo 'Fichier téléversé avec succès ! <a href="'.$uploadDir . $fileName .'">Voir le fichier</a>';
    }
    else{
        echo 'Erreur lors du déplacement du fichier.';
    }
}

?>