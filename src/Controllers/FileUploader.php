<?php

namespace App\Controllers;

class FileUploader
{
    private $file;
    private $uploadDir = 'uploads/';
    private $allowedType = 'application/pdf';
    private $message = "";
    private $maxSize = 4194304; // 4 Mo en octets
    private $newFileName = null;

    public function __construct(array $file)
    {
        $this->file = $file;
    }

    public function setFileName(string $newName): void
    {
        $this->newFileName = $newName;
    }

    public function validate(): bool
    {
        if ($this->file['error'] !== UPLOAD_ERR_OK) {
            $this->message = "Erreur lors de l'upload du fichier "
                . basename($this->file['name'])
                . ". Code d'erreur : "
                . $this->file['error']
                . "<br>";
            return false;
        }
        if ($this->file['type'] !== $this->allowedType) {
            $this->message = "Le fichier " . basename($this->file['name']) . " doit être au format PDF.";
            return false;
        }
        if ($this->file['size'] > $this->maxSize) {
            $this->message = "Le fichier " . basename($this->file['name']) . " est trop lourd (maximum 4 Mo).";
            return false;
        }
        return true;
    }

    public function upload(): ?string
    {
        $fileName = $this->newFileName ?? basename($this->file['name']);
        $uploadPath = $this->uploadDir . $fileName;
        if (move_uploaded_file($this->file['tmp_name'], $uploadPath)) {
            $this->message = "Le fichier " . $fileName . " a été envoyé avec succès !<br>";
            return $uploadPath;
        } else {
            $this->message = "Erreur upload lors de l'upload du fichier " . $fileName . ". <br>";
            return null;
        }
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
