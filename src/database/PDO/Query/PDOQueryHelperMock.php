<?php

namespace pTonev\database\PDO\Query;

use \PDO;
use \PDOStatement;

/**
 * Class PDOQueryHelperMock
 *
 * @author     Petio Tonev <ptonev@gmail.com>
 * @copyright  2018 Petio Tonev
 * @package    pTonev\database\PDO
 * @link       https://github.com/ptonev/PDOHelper
 */
class PDOQueryHelperMock implements PDOQueryHelperInterface
{
    /**
     * PDOQueryHelper constructor
     *
     * @param PDOStatement $statement   PDO statement
     * @param mixed $fetchStyle         Query fetch style
     */
    public function __construct($statement, $fetchStyle)
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
     * @return mixed Returns false
     */
    public function fetch($fetchStyle = null)
    {
        return false;
    }

    /**
     * Mock fetch the all rows from a result set
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
     * @return array Returns empty array
     */
    public function fetchAll($fetchStyle = null)
    {
        return [];
    }
}
