<?php

namespace pTonev\database\PDO\Hobo;

use \pTonev\database\PDO\PDOHelperInterface as BasePDOHelperInterface;
use \pTonev\database\PDO\Query\PDOQueryHelperInterface;

/**
 * Interface PDOHelperInterface
 *
 * @package pTonev\database\PDO\Hobo
 */
interface PDOHelperInterface extends BasePDOHelperInterface
{
    /**
     * Performs a SELECT query and returns the result as a PDOQueryHelper object
     *
     * @param string $sql               SQL statement or table name
     * @param array  $params            Array with SQL parameters ['name' => 'value']
     * @param array  $paramTypes        Array with type on SQL parameters ['name' => PDO::]
     * @param string $whereConditions   Additional SQL WHERE conditions (only for simple select)
     *
     * @return PDOQueryHelperInterface  Returns PDOQueryHelper object
     */
    public function select($sql, $params = [], $paramTypes = [], $whereConditions = '');

    /**
     * Performs an INSERT statement and returns the number of affected rows,
     * or a negative value on error
     *
     * @param string $sql               SQL statement or table name
     * @param array $params             Array with SQL parameters ['name' => 'value']
     * @param array $paramTypes         Array with type on SQL parameters ['name' => PDO::]
     * @param string $whereConditions   Additional SQL WHERE conditions (only for simple insert)
     * @param array $excludeParams      Array with SET exclude parameters ['id' , 'name']
     *
     * @return int  Returns the number of affected rows or a negative value on error
     */
    public function insert($sql, $params = [], $paramTypes = [], $whereConditions = '', $excludeParams = []);

    /**
     * Performs an UPDATE statement and returns the number of affected rows,
     * or a negative value on error
     *
     * @param string $sql               SQL statement or table name
     * @param array $params             Array with SQL parameters ['name' => 'value']
     * @param array $paramTypes         Array with type on SQL parameters ['name' => PDO::]
     * @param string $whereConditions   Additional SQL WHERE conditions (only for simple update)
     * @param array $excludeParams      Array with SET exclude parameters ['id' , 'name']
     *
     * @return int  Returns the number of affected rows or a negative value on error
     */
    public function update($sql, $params = [], $paramTypes = [], $whereConditions = '', $excludeParams = []);

    /**
     * Performs an DELETE statement and returns the number of affected rows,
     * or a negative value on error
     *
     * @param string $sql               SQL statement or table name
     * @param array $params             Array with SQL parameters ['name' => 'value']
     * @param array $paramTypes         Array with type on SQL parameters ['name' => PDO::]
     * @param string $whereConditions   Additional SQL WHERE conditions (only for simple delete)
     *
     * @return int  Returns the number of affected rows or a negative value on error
     */
    public function delete($sql, $params = [], $paramTypes = [], $whereConditions = '');
}
