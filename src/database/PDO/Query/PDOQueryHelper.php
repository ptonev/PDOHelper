<?php

namespace pTonev\database\PDO\Query;

use \PDO;
use \PDOStatement;

/**
 * Class PDOQueryHelper
 *
 * @package pTonev\database\PDO\Query
 */
class PDOQueryHelper implements PDOQueryHelperInterface
{
    /** @var PDOStatement PDO statement */
    protected $statement;

    /**
     * PDOQueryHelper constructor
     *
     * @param PDOStatement $statement PDO statement
     */
    public function __construct($statement)
    {
        //  Store PDO Statement
        $this->statement = $statement;
    }

    /**
     * Get SQL error information
     *
     * @return array Returns SQL error information
     */
    public function errorInfo()
    {
        //  Returns error information
        return $this->statement->errorInfo();
    }

    /**
     * Get the number of columns in result set
     *
     * @return int Returns the number of columns in the result set
     */
    public function columns()
    {
        //  Returns count of columns
        return $this->statement->columnCount();
    }

    /**
     * Get the number of rows in result set
     *
     * @return int Returns the number of rows in the result set
     */
    public function rows()
    {
        //  Returns count of rows
        return $this->statement->rowCount();
    }

    /**
     * Fetches the next row from a result set
     *
     * @return mixed Returns the next row in array from a result set or false on error
     */
    public function fetch()
    {
        //  Returns result from fetch on data
        return $this->statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Fetch the all rows from a result set
     *
     * @return array Returns an array containing all of the result set rows or empty array on error
     */
    public function fetchAll()
    {
        //  Returns result from fetch on data
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
