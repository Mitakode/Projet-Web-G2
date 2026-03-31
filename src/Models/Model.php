<?php

namespace App\Models;

abstract class Model
{
    // Stores the database adapter shared by concrete models
    protected $connection = null;

    /**
     * Builds the model with a database adapter implementation
     */
    public function __construct(Database $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Returns the database adapter used by the model
     */
    public function getDb()
    {
        return $this->connection;
    }
}
