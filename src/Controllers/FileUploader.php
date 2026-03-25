<?php

namespace App\Controllers;

class FileUploader {
    private $file;
    private $uploadDir = 'uploads/';
    private $allowedType = 'application/pdf';

    public function __construct(array $file) {$this->file = $file;}

    public function validate(): bool {
        if ($this->file['error'] !== UPLOAD_ERR_OK) {
            echo "Erreur validate lors de l'upload du fichier ".basename($this->file['name']).".";

            return false;
        }

        if ($this->file['type'] !== $this->allowedType) {
            echo "Le fichier ".basename($this->file['name'])." doit être au format PDF.";
            return false;
        }
        //$safeName = validateInput($originalName); à utiliser pour vérif le nom ??
        return true;
    }

    public function upload(): bool {
        $uploadPath = $this->uploadDir . basename($this->file['name']);
        if (move_uploaded_file($this->file['tmp_name'], $uploadPath)) {
            echo "Le fichier ".basename($this->file['name'])." a été envoyé avec succès !<br>";
            return true;
        } else {
            echo "Erreur upload lors de l'upload du fichier ".basename($this->file['name']).". <br>";
            return false;
        }
    }
}


?>