<?php

namespace App\Models;

use PDO;

// Uses PHP native PDO class

class SqlDatabase implements Database
{
    private $pdo;
    private $tableName;
    private $primaryKey;

    /**
     * Builds the SQL adapter with PDO table name and primary key
     */
    public function __construct($pdo, $tableName, $primaryKey = 'id')
    {
        $this->pdo = $pdo;
        $this->tableName = $tableName;
        $this->primaryKey = $primaryKey;
    }

    /**
     * Returns the underlying PDO connection
     */
    public function getConnection()
    {
        return $this->pdo;
    }

    /**
     * Returns all rows from the configured table
     */
    public function getAllRecords()
    {
        // Fetch every row from the configured table
        $stmt = $this->pdo->query("SELECT * FROM {$this->tableName}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Returns one record by primary key value
     * @param int $id
     * @return array|null Found record or null
     */
    public function getRecord($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->tableName} WHERE {$this->primaryKey} = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Inserts a new record
     * @param array $record
     * @return int Inserted record id
     */
    public function insertRecord($record)
    {
        // Build dynamic column and placeholder lists
        $columns = implode(', ', array_keys($record));
        $placeholders = implode(', ', array_fill(0, count($record), '?'));

        $sql = "INSERT INTO {$this->tableName} ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($record));

        return $this->pdo->lastInsertId();
    }

    /**
     * Updates an existing record
     * @param int $id
     * @param array $record
     * @return bool True on success false otherwise
     */
    public function updateRecord($id, $record)
    {
        $sets = [];
        foreach (array_keys($record) as $key) {
            $sets[] = "$key = ?";
        }
        $sql = "UPDATE {$this->tableName} SET " . implode(', ', $sets) . " WHERE {$this->primaryKey} = ?";

        $params = array_values($record);
        $params[] = $id; // Append id used by the WHERE clause

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Deletes a specific record by id
     * @param int $id Id of the row to delete
     * @return bool True on success false otherwise
     */
    public function deleteRecord($id)
    {
        // Prepare the query to keep SQL injection protection
        $sql = "DELETE FROM {$this->tableName} WHERE {$this->primaryKey} = ?";
        $stmt = $this->pdo->prepare($sql);

        // Execute with the requested id
        return $stmt->execute([$id]);
    }
}
