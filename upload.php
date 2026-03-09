<?php

class FileUploader {
    private $file;
    private $uploadDir = 'uploads/';
    private $allowedType = 'application/pdf';

    public function __construct(array $file) {$this->file = $file;}

    public function validate(): bool {
        if ($this->file['error'] !== UPLOAD_ERR_OK) {
            echo "Erreur lors de l'upload du fichier".basename($this->file['name']).".";

            return false;
        }

        if ($this->file['type'] !== $this->allowedType) {
            echo "Le fichier".basename($this->file['name'])." doit être au format PDF.";
            return false;
        }
        //$safeName = validateInput($originalName); à utiliser pour vérif le nom ??
        return true;
    }

    public function upload(): bool {
        $uploadPath = $this->uploadDir . basename($this->file['name']);
        if (move_uploaded_file($this->file['tmp_name'], $uploadPath)) {
            echo basename($this->file['name'])." a été envoyée avec succès !";
            return true;
        } else {
            echo "Erreur lors de l'upload du fichier".basename($this->file['name']).".";
            return false;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cvPresent = isset($_FILES['cv']);
    $lettrePresent = isset($_FILES['lettre']);

    if (!$cvPresent || !$lettrePresent) {
        echo "Veuillez remplir correctement tous les champs.";
    } else {
        $uploaderCV = new FileUploader($_FILES['cv']);
        $uploaderLettre = new FileUploader($_FILES['lettre']);

        if ($uploaderCV->validate()) {
            $uploaderCV->upload();
        }

        if ($uploaderLettre->validate()) {
            $uploaderLettre->upload();
        }
    }
}

?>