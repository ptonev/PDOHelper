<?php

namespace pTonev\database\PDO;

/**
 * Interface PDOHelperInterface
 *
 * @package pTonev\database\PDO
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
     * @param string $sql        SQL statement
     * @param array  $params     Array with SQL parameters ['name' => 'value']
     * @param array  $paramTypes Array with type on SQL parameters ['name' => PDO::]
     *
     * @return int  Returns the number of affected rows or a negative value on error
     */
    public function perform($sql, $params = [], $paramTypes = []);
}
