<?php

namespace pTonev\database\PDO\Query;

use \PDOStatement;

/**
 * Class PDOQueryHelperMock
 *
 * @package pTonev\database\PDO\Query
 */
class PDOQueryHelperMock implements PDOQueryHelperInterface
{
    /**
     * PDOQueryHelper constructor
     *
     * @param PDOStatement $statement PDO statement
     */
    public function __construct($statement)
    {
        //  nothing
    }

    /**
     * Get SQL error information
     *
     * @return array Returns SQL error information
     */
    public function errorInfo()
    {
        //  Returns empty array if is error mock-up
        return [];
    }

    /**
     * Mock get the number of columns in result set
     *
     * @return int Returns 0
     */
    public function columns()
    {
        return 0;
    }

    /**
     * Mock get the number of rows in result set
     *
     * @return int Returns 0
     */
    public function rows()
    {
        return 0;
    }

    /**
     * Mock fetches the next row from a result set
     *
     * @return mixed Returns false
     */
    public function fetch()
    {
        return false;
    }

    /**
     * Mock fetch the all rows from a result set
     *
     * @return array Returns empty array
     */
    public function fetchAll()
    {
        return [];
    }
}
