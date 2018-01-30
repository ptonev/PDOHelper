<?php

namespace pTonev\database\PDO\Hobo;

use \Exception;
use \pTonev\database\PDO\PDOHelper as BasePDOHelper;
use \pTonev\database\PDO\Hobo\PDOHelperInterface as BasePDOHelperInterface;
use \pTonev\database\PDO\Query\PDOQueryHelperInterface;

/**
 * Class PDOHelper
 *
 * @package pTonev\database\PDO\Hobo
 */
class PDOHelper extends BasePDOHelper implements BasePDOHelperInterface
{
    /** @var string Message at missing INSERT parameters */
    const MSG_MISSING_INSERT_PARAMETERS = 'Missing INSERT parameters!';

    /** @var string Message at missing UPDATE parameters */
    const MSG_MISSING_UPDATE_PARAMETERS = 'Missing UPDATE parameters!';

    /** @var string Message at missing DELETE parameters */
    const MSG_MISSING_DELETE_PARAMETERS = 'Missing DELETE parameters!';

    /** @var string Message explanation at missing parameters */
    const MSG_MISSING_PARAMETERS = ' When you use method in short format need aways to present parameters.';

    /**
     * Is SQL statement
     *
     * @param string $sql   SQL statement
     *
     * @return bool Returns is a SQL statement
     */
    protected function isSQLStatement($sql)
    {
        //  Returns is SQL statement
        return str_word_count($sql) != 1;
    }

    /**
     * Performs a SELECT query and returns the result as a PDOQueryHelper object
     *
     * @param string $sql               SQL statement or table name
     * @param array $params             Array with SQL parameters ['name' => 'value']
     * @param array $paramTypes         Array with type on SQL parameters ['name' => PDO::]
     * @param string $whereConditions   Additional SQL WHERE conditions (only for simple select)
     *
     * @return PDOQueryHelperInterface  Returns PDOQueryHelper object
     */
    public function select($sql, $params = [], $paramTypes = [], $whereConditions = '')
    {
        //  Clear error information
        $this->clearErrorInfo();

        //  Check for simple or SQL statement
        if ($this->isSQLStatement($sql)) {
            //  SELECT SQL statement
            return $this->query($sql, $params, $paramTypes);
        }
        else {
            //  Simple Select statement
            return $this->selectSimple($sql, $params, $paramTypes, $whereConditions);
        }
    }

    /**
     * Performs a SELECT query and returns the result as a PDOQueryHelper object
     *
     * @param string $tableName         Table name
     * @param array $params             Array with SQL parameters ['name' => 'value']
     * @param array $paramTypes         Array with type on SQL parameters ['name' => PDO::]
     * @param string $whereConditions   Additional SQL WHERE conditions (only for simple delete)
     *
     * @return PDOQueryHelperInterface Returns PDOQueryHelper object
     */
    protected function selectSimple($tableName, $params = [], $paramTypes = [], $whereConditions = '')
    {
        //  Check for WHERE conditions
        if ($whereConditions == '') {
            //  Compose SQL mock-up
            $sql = "SELECT * FROM $tableName";
        }
        else {
            //  Compose SQL mock-up
            $sql = "SELECT * FROM $tableName WHERE $whereConditions";
        }

        //  Returns result from execute on SQL Insert
        return $this->query($sql, $params, $paramTypes);
    }

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
    public function insert($sql, $params = [], $paramTypes = [], $whereConditions = '', $excludeParams = [])
    {
        //  Clear error information
        $this->clearErrorInfo();

        //  Check for simple or SQL statement
        if ($this->isSQLStatement($sql)) {
            //  Insert SQL statement
            return $this->perform($sql, $params, $paramTypes);
        }
        else {
            //  Simple Insert statement
            return $this->insertSimple($sql, $params, $paramTypes, $whereConditions, $excludeParams);
        }
    }

    /**
     * Performs an INSERT statement and returns the number of affected rows,
     * or a negative value on error
     *
     * @param string $tableName         SQL statement or table name
     * @param array $params             Array with SQL parameters ['name' => 'value']
     * @param array $paramTypes         Array with type on SQL parameters ['name' => PDO::]
     * @param string $whereConditions   Additional SQL WHERE conditions (only for simple insert)
     * @param array $excludeParams      Array with SET exclude parameters ['id' , 'name']
     *
     * @return int  Returns the number of affected rows or a negative value on error
     *
     * @throws Exception Throws exception when missing parameters
     */
    protected function insertSimple($tableName, $params = [], $paramTypes = [], $whereConditions = '', $excludeParams = [])
    {
        //  Check for missing parameters
        if (empty($params)) {
            throw new Exception(PDOHelper::MSG_MISSING_INSERT_PARAMETERS . PDOHelper::MSG_MISSING_PARAMETERS);
        }

        //  Clone params into setParams
        $insertParams = $params;

        //  Check for exclude params
        if (count($excludeParams) > 0) {
            //  Loop by exclude params
            foreach ($excludeParams as $excludeParam) {
                //  Check is need to exclude param
                if (isset($insertParams[$excludeParam])) {
                    //  Unset parameter
                    unset($insertParams[$excludeParam]);
                }
            }
        }

        //  Get array keys
        $keys = array_keys($insertParams);

        //  Compose fields string
        $fields = implode(',', $keys);

        //  Prepare keys for values
        foreach ($keys as &$key) {
            $key = ':' . $key;
        }

        //  Compose values string
        $values = implode(',', $keys);

        //  Check for WHERE conditions
        if ($whereConditions == '') {
            //  Compose SQL mock-up
            $sql = "INSERT INTO $tableName ($fields) VALUES ($values)";
        }
        else {
            //  Compose SQL mock-up
            $sql = "INSERT INTO $tableName ($fields) VALUES ($values) WHERE $whereConditions ";
        }

        //  Returns result from execute on SQL Insert
        return $this->perform($sql, $params, $paramTypes);
    }

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
    public function update($sql, $params = [], $paramTypes = [], $whereConditions = '', $excludeParams = [])
    {
        //  Clear error information
        $this->clearErrorInfo();

        //  Check for simple or SQL statement
        if ($this->isSQLStatement($sql)) {
            //  Update SQL statement
            return $this->perform($sql, $params, $paramTypes);
        }
        else {
            //  Simple Update statement
            return $this->updateSimple($sql, $params, $paramTypes, $whereConditions, $excludeParams);
        }
    }

    /**
     * Performs an UPDATE statement and returns the number of affected rows,
     * or a negative value on error
     *
     * @param string $tableName         SQL statement or table name
     * @param array $params             Array with SQL parameters ['name' => 'value']
     * @param array $paramTypes         Array with type on SQL parameters ['name' => PDO::]
     * @param string $whereConditions   Additional SQL WHERE conditions (only for simple update)
     * @param array $excludeParams      Array with SET exclude parameters ['id' , 'name']
     *
     * @return int  Returns the number of affected rows or a negative value on error
     *
     * @throws Exception Throws exception when missing parameters
     */
    protected function updateSimple($tableName, $params = [], $paramTypes = [], $whereConditions = '', $excludeParams = [])
    {
        //  Check for missing parameters
        if (empty($params)) {
            throw new Exception(PDOHelper::MSG_MISSING_INSERT_PARAMETERS . PDOHelper::MSG_MISSING_PARAMETERS);
        }

        //  Clone params into setParams
        $setParams = $params;

        //  Check for exclude params
        if (count($excludeParams) > 0) {
            //  Loop by exclude params
            foreach ($excludeParams as $excludeParam) {
                //  Check is need to exclude param
                if (isset($setParams[$excludeParam])) {
                    //  Unset parameter
                    unset($setParams[$excludeParam]);
                }
            }
        }

        //  Get array keys
        $keys = array_keys($setParams);

        //  Prepare keys for values
        foreach ($keys as &$key) {
            $key = $key . ' = :' . $key;
        }

        //  Compose fields string
        $fields = implode(',', $keys);

        //  Check for WHERE conditions
        if ($whereConditions == '') {
            //  Compose SQL mock-up
            $sql = "UPDATE $tableName SET $fields";
        }
        else {
            //  Compose SQL mock-up
            $sql = "UPDATE $tableName SET $fields WHERE $whereConditions";
        }

        //  Returns result from execute on SQL Insert
        return $this->perform($sql, $params, $paramTypes);
    }

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
    public function delete($sql, $params = [], $paramTypes = [], $whereConditions = '')
    {
        //  Clear error information
        $this->clearErrorInfo();

        //  Check for simple or SQL statement
        if ($this->isSQLStatement($sql)) {
            //  Update SQL statement
            return $this->perform($sql, $params, $paramTypes);
        }
        else {
            //  Simple Delete statement
            return $this->deleteSimple($sql, $params, $paramTypes, $whereConditions);
        }
    }

    /**
     * Performs an DELETE statement and returns the number of affected rows,
     * or a negative value on error
     *
     * @param string $tableName         SQL statement or table name
     * @param array $params             Array with SQL parameters ['name' => 'value']
     * @param array $paramTypes         Array with type on SQL parameters ['name' => PDO::]
     * @param string $whereConditions   Additional SQL WHERE conditions (only for simple delete)
     *
     * @return int  Returns the number of affected rows or a negative value on error
     *
     * @throws Exception Throws exception when missing parameters
     */
    protected function deleteSimple($tableName, $params = [], $paramTypes = [], $whereConditions = '')
    {
        //  Check for missing parameters
        if (empty($params)) {
            throw new Exception(PDOHelper::MSG_MISSING_INSERT_PARAMETERS . PDOHelper::MSG_MISSING_PARAMETERS);
        }

        //  Check for WHERE conditions
        if ($whereConditions == '') {
            //  Compose SQL mock-up
            $sql = "DELETE FROM $tableName";
        }
        else {
            //  Compose SQL mock-up
            $sql = "DELETE FROM $tableName WHERE $whereConditions";
        }

        //  Returns result from execute on SQL Insert
        return $this->perform($sql, $params, $paramTypes);
    }
}
