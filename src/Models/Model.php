<?php

// Déclaration du namespace pour ce fichier
namespace App\Models;

// Déclaration d'une classe abstraite nommée "Model"
abstract class Model
{
    // Déclaration d'une propriété protégée nommée $connection
    protected $connection = null;

    public function __construct(Database $connection)
    {
        $this->connection = $connection;
    }

    
    public function getDb()
    {
        return $this->connection;
    }
}
