<?php
// Déclaration du namespace du fichier
namespace App\Controllers;

// Déclaration d'une classe abstraite nommée "Controller"
abstract class Controller {

    // Déclaration d'une propriété protégée nommée $model
    protected $model = null;

    // Déclaration d'une propriété protégée nommée $templateEngine
    protected $templateEngine = null;
}
