<?php

namespace pTonev\database\PDO;

/**
 * Interface PDOHelperInterface
 *
 * @author     Petio Tonev <ptonev@gmail.com>
 * @copyright  2018 Petio Tonev
 * @package    pTonev\database\PDO
 * @link       https://github.com/ptonev/PDOHelper
 */
interface PDOHelperInterface
{
    /**
     * Get PDOHelper instance
     *
     * @param string $connectionSettings    Connection settings
     *
     * Connection settings format:
     *      uri://user:password@host[:port][/database]?setting1=value1&...
     *
     * Example:
     *      mysql://ptonev:mypassword@localhost/test?charset=utf8
     *
     * @return PDOHelper    PDOHelper instance
     */
    public static function getInstance($connectionSettings);

    /**
     * Get SQL error information
     *
     * @return array    Returns SQL error information
     */
    public function getErrorInfo();

    /**
     * Get last insert Id
     *
     * @return int  Returns last insert Id
     */
    public function getInsertId();

    /**
     * Set an PDO attribute
     *
     * @param int $attribute    PDO attribute
     * @param mixed $value      PDO attribute value
     *
     * Supported attributes and values:
     *
     *  - PDO::ATTR_ERRMODE: Error reporting (default: ERRMODE_SILENT).
     *      PDO::ERRMODE_SILENT: Just set error codes.
     *      PDO::ERRMODE_WARNING: Raise E_WARNING.
     *      PDO::ERRMODE_EXCEPTION: Throw exceptions.
     *
     *  - PDO::ATTR_TIMEOUT: Specifies the timeout duration in seconds.
     *
     *  - PDO::ATTR_AUTOCOMMIT: Whether to autocommit every single statement.
     *      (available in OCI, Firebird and MySQL).
     *
     *  - PDO::MYSQL_ATTR_USE_BUFFERED_QUERY: Use buffered queries.
     *      (available in MySQL)
     *
     *  - PDO::ATTR_DEFAULT_FETCH_MODE: Set default fetch mode.
     *      PDO::FETCH_ASSOC: Returns an array indexed by column name as returned in your result set.
     *
     *      PDO::FETCH_BOTH (default): Returns an array indexed by both column name and 0-indexed
     *          column number as returned in your result set.
     *
     *      PDO::FETCH_NAMED: Returns an array with the same form as PDO::FETCH_ASSOC, except that
     *          if there are multiple columns with the same name, the value referred to by that key
     *          will be an array of all the values in the row that had that column name.
     *
     *      PDO::FETCH_NUM: Returns an array indexed by column number as returned in your result set,
     *          starting at column 0.
     *
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function setAttribute($attribute, $value);

    /**
     * Performs a SELECT query and returns the result as a PDOQueryHelper object
     *
     * @param string $sql       SQL statement
     * @param array $params     Array with SQL parameters ['name' => 'value']
     * @param array $paramTypes Array with PDO type on SQL parameters ['name' => PDO::]
     *
     * @return PDOQueryHelperInterface  Returns PDOQueryHelper object
     */
    public function query($sql, $params = [], $paramTypes = []);

    /**
     * Performs an update query (INSERT, UPDATE and DELETE) and returns the number of affected rows,
     * or a negative value on error
     *
     * @param string $sql       SQL statement
     * @param array $params     Array with SQL parameters ['name' => 'value']
     * @param array $paramTypes Array with type on SQL parameters ['name' => PDO::]
     *
     * @return int  Returns the number of affected rows or a negative value on error
     */
    public function perform($sql, $params = [], $paramTypes = []);
}
