<?php

namespace pTonev\database\PDO\Query;

use \PDO;
use \PDOStatement;

/**
 * Class PDOQueryHelper
 *
 * @author     Petio Tonev <ptonev@gmail.com>
 * @copyright  2018 Petio Tonev
 * @package    pTonev\database\PDO
 * @link       https://github.com/ptonev/PDOHelper
 */
class PDOQueryHelper implements PDOQueryHelperInterface
{
    /** @var PDOStatement PDO statement */
    protected $statement;
    protected $fetchStyle;

    /**
     * PDOQueryHelper constructor
     *
     * @param PDOStatement $statement   PDO statement
     * @param mixed $fetchStyle         Query fetch style
     */
    public function __construct($statement, $fetchStyle)
    {
        //  Store PDO Statement
        $this->statement = $statement;

        //  Store PDO Statement default fetch style
        $this->fetchStyle = $fetchStyle;
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
     * Get the number of columns in result set, 0 on error
     *
     * @return int Returns the number of columns in the result set, 0 on error
     */
    public function columns()
    {
        //  Returns count of columns
        return $this->statement->columnCount();
    }

    /**
     * Get the number of rows in result set, 0 on error
     *
     * @return int Returns the number of rows in the result set, 0 on error
     */
    public function rows()
    {
        //  Returns count of rows
        return $this->statement->rowCount();
    }

    /**
     * Fetches the next row from a result set
     *
     * @param mixed $fetchStyle Controls how the next row will be returned to the caller.
     *                          This value must be one of the PDO::FETCH_* constants,
     *                          defaulting to value of PDO::ATTR_DEFAULT_FETCH_MODE
     *                          (which defaults to PDO::FETCH_ASSOC).
     *
     * Supported fetch styles:
     *
     *      PDO::FETCH_ASSOC: Returns an array indexed by column name as returned in your result set.
     *
     *      PDO::FETCH_NUM: Returns an array indexed by column number as returned in your result set.
     *
     *      PDO::FETCH_BOTH (default): Returns an array indexed by both column name and 0-indexed
     *          column number as returned in your result set.
     *
     *      PDO::FETCH_NAMED: Returns an array with the same form as PDO::FETCH_ASSOC, except that
     *          if there are multiple columns with the same name, the value referred to by that key
     *          will be an array of all the values in the row that had that column name.
     *
     *      PDO::FETCH_KEY_PAIR: Fetch a two-column result into an array where the first column
     *          is a key and the second column is the value.
     *
     * @return mixed Returns the next row in array from a result set or false on error
     */
    public function fetch($fetchStyle = null)
    {
        //  Returns result from fetch on data
        return $this->statement->fetch(empty($fetchStyle) ? $this->fetchStyle : $fetchStyle);
    }

    /**
     * Fetch the all rows from a result set
     *
     * @param mixed $fetchStyle Controls how the next row will be returned to the caller.
     *                          This value must be one of the PDO::FETCH_* constants,
     *                          defaulting to value of PDO::ATTR_DEFAULT_FETCH_MODE
     *                          (which defaults to PDO::FETCH_ASSOC).
     *
     * Supported fetch styles:
     *
     *      PDO::FETCH_ASSOC: Returns an array indexed by column name as returned in your result set.
     *
     *      PDO::FETCH_NUM: Returns an array indexed by column number as returned in your result set.
     *
     *      PDO::FETCH_BOTH (default): Returns an array indexed by both column name and 0-indexed
     *          column number as returned in your result set.
     *
     *      PDO::FETCH_NAMED: Returns an array with the same form as PDO::FETCH_ASSOC, except that
     *          if there are multiple columns with the same name, the value referred to by that key
     *          will be an array of all the values in the row that had that column name.
     *
     *      PDO::FETCH_KEY_PAIR: Fetch a two-column result into an array where the first column
     *          is a key and the second column is the value.
     *
     * @return array Returns an array containing all of the result set rows or empty array on error
     */
    public function fetchAll($fetchStyle = null)
    {
        //  Returns result from fetch on data
        return $this->statement->fetchAll(empty($fetchStyle) ? $this->fetchStyle : $fetchStyle);
    }
}
