<?php

namespace pTonev\database\PDO\Query;

/**
 * Interface PDOQueryHelperInterface
 *
 * @package pTonev\database\PDO\Query
 */
interface PDOQueryHelperInterface
{
    /**
     * PDOQueryHelper constructor
     *
     * @param \PDOStatement $statement PDO statement
     */
    public function __construct($statement);

    /**
     * Get SQL error information
     *
     * @return array Returns SQL error information
     */
    public function errorInfo();

    /**
     * Get the number of columns in result set, 0 on error
     *
     * @return int Returns the number of columns in the result set, 0 on error
     */
    public function columns();

    /**
     * Get the number of rows in result set, 0 on error
     *
     * @return int Returns the number of rows in the result set, 0 on error
     */
    public function rows();

    /**
     * Fetches the next row from a result set
     *
     * @return mixed Returns the next row in array from a result set or false on error
     */
    public function fetch();

    /**
     * Fetch the all rows from a result set
     *
     * @return array Returns an array containing all of the result set rows or empty array on error
     */
    public function fetchAll();
}
