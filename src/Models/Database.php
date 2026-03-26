<?php

namespace App\Models;

interface Database
{
    /**
     * Returns the underlying PDO connection.
     *
     * @return \PDO
     */
    public function getConnection();

    /**
     * Retrieves all records from the database.
     *
     * @return array An array of records.
     */
    public function getAllRecords();

    /**
     * Retrieves a specific record from the database.
     *
     * @param int $id The ID of the record to retrieve.
     * @return mixed The retrieved record, null otherwise.
     */
    public function getRecord($id);

    /**
     * Inserts a new record into the database.
     *
     * @param mixed $record The record to insert.
     * @return int The last inserted index if the record was inserted successfully, -1 otherwise.
     */
    public function insertRecord($record);

    /**
     * Updates a specific record in the database.
     *
     * @param int $id The ID of the record to update.
     * @param mixed $record The updated record.
     * @return bool True if the record was updated successfully, false otherwise.
     */
    public function updateRecord($id, $record);

    /**
     * Deletes a specific record from the database.
     *
     * @param int $id The ID of the record to delete.
     * @return bool True if the record was deleted successfully, false otherwise.
     */
    public function deleteRecord($id);
}
