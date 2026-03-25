<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

$cvPresent = isset($_FILES['cv']);
$lettrePresent = isset($_FILES['lettre']);

if (!$cvPresent || !$lettrePresent) {
    echo 'Veuillez remplir correctement tous les champs.';
    exit;
}

$allowedType = 'application/pdf';
$uploadDir = 'uploads/';
$fileKeys = ['cv', 'lettre'];

foreach ($fileKeys as $key) {
    $file = $_FILES[$key];
    $fileName = basename($file['name']);

    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo "Erreur lors de l'upload du fichier {$fileName}.";
        continue;
    }

    if ($file['type'] !== $allowedType) {
        echo "Le fichier {$fileName} doit être au format PDF.";
        continue;
    }

    $uploadPath = $uploadDir . $fileName;
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        echo "{$fileName} a été envoyé avec succès !<br>";
        continue;
    }

    echo "Erreur lors de l'upload du fichier {$fileName}.<br>";
}
