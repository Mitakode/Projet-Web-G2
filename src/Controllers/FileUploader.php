<?php

namespace App\Controllers;

class FileUploader {
    private $file;
    private $uploadDir = 'uploads/';
    private $allowedType = 'application/pdf';
    private $message = "";

    public function __construct(array $file) {$this->file = $file;}

    public function validate(): bool {
        if ($this->file['error'] !== UPLOAD_ERR_OK) {
            $this->message = "Erreur validate lors de l'upload du fichier ".basename($this->file['name']).". Code d'erreur : " . $this->file['error'] . "<br>";

            return false;
        }

        if ($this->file['type'] !== $this->allowedType) {
            $this->message = "Le fichier ".basename($this->file['name'])." doit être au format PDF.";
            return false;
        }
        //$safeName = validateInput($originalName); à utiliser pour vérif le nom ??
        return true;
    }

    public function upload(): ?string {
        $uploadPath = $this->uploadDir . basename($this->file['name']);
        if (move_uploaded_file($this->file['tmp_name'], $uploadPath)) {
            $this->message = "Le fichier ".basename($this->file['name'])." a été envoyé avec succès !<br>";
            return $uploadPath;
        } else {
            $this->message = "Erreur upload lors de l'upload du fichier ".basename($this->file['name']).". <br>";
            return null;
        }
    }

    public function getMessage(): string {
        return $this->message;
    }
}


?>