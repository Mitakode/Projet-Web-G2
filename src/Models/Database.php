<?php

namespace App\Models;

interface Database
{
    /**
     * Returns the underlying PDO connection
     *
     * @return \PDO
     */
    public function getConnection();

    /**
     * Retrieves all records from the database
     *
     * @return array Array of records
     */
    public function getAllRecords();

    /**
     * Retrieves one specific record from the database
     *
     * @param int $id Id of the record to retrieve
     * @return mixed Retrieved record or null
     */
    public function getRecord($id);

    /**
     * Inserts a new record into the database
     *
     * @param mixed $record Record to insert
     * @return int Last inserted id on success or -1 otherwise
     */
    public function insertRecord($record);

    /**
     * Updates a specific record in the database
     *
     * @param int $id Id of the record to update
     * @param mixed $record Updated record payload
     * @return bool True if updated successfully false otherwise
     */
    public function updateRecord($id, $record);

    /**
     * Deletes a specific record from the database
     *
     * @param int $id Id of the record to delete
     * @return bool True if deleted successfully false otherwise
     */
    public function deleteRecord($id);
}
