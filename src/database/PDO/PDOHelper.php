<?php

namespace pTonev\database\PDO;

use \PDO;
use \PDOStatement;
use \pTonev\database\PDO\Query\PDOQueryHelperInterface;
use \pTonev\database\PDO\Query\PDOQueryHelper;
use \pTonev\database\PDO\Query\PDOQueryHelperMock;

/**
 * Class PDOHelper
 *
 * @package pTonev\database\PDO
 */
class PDOHelper implements PDOHelperInterface
{
    /** @var PDO PDO database instance */
    protected $db = null;

    /** @var array PDOHelper class instances */
    protected static $instances = [];

    /** @var array Error information */
    protected $errorInfo = [];

    /**
     *
     * PDO parameter types:
     *  PDO::PARAM_BOOL     Represents a boolean data type.
     *  PDO::PARAM_NULL     Represents the SQL NULL data type.
     *  PDO::PARAM_INT      Represents the SQL INTEGER data type.
     *  PDO::PARAM_STR      Represents the SQL CHAR, VARCHAR, or other string data type.
     *  PDO::PARAM_STR_NATL *** Flag to denote a string uses the national character set. Available since PHP 7.2.0
     *  PDO::PARAM_STR_CHAR *** Flag to denote a string uses the regular character set. Available since PHP 7.2.0
     *  PDO::PARAM_LOB      *** Represents the SQL large object data type.
     *
     *  *** Don't use yet.
     *
     */

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
     */
    protected function __construct($connectionSettings)
    {
        //  Parse connection settings
        $csChunks = parse_url($connectionSettings);

        //  Prepare connection settings
        $scheme = isset($csChunks['scheme']) ? $csChunks['scheme'] : 'mysql';
        $host = isset($csChunks['host']) ? $csChunks['host'] : 'localhost';
        $port = isset($csChunks['port']) ? $csChunks['port'] : '3306';
        $dbName = isset($csChunks['path']) ? trim($csChunks['path'], '/') : '';
        $user = isset($csChunks['user']) ? $csChunks['user'] : 'root';
        $password = isset($csChunks['pass']) ? $csChunks['pass'] : '';
        $extSettings = isset($csChunks['query']) ? $csChunks['query'] : '';

        //  Parse extended settings
        parse_str($extSettings, $extSettingsChunks);

        //  Prepare PDO extended settings
        $charSet = isset($extSettingsChunks['charset']) ? $extSettingsChunks['charset'] : 'utf8';

        //  Compose DSN
        $dsn = $scheme . ':host=' . $host . ';port=' . $port . ';dbname=' . $dbName . ';charset=' . $charSet;

        //  Create PDO instance
        $this->db = new PDO($dsn, $user, $password);

        //  TODO Need to see what and how will maintains attributes
        //  (dev) ERRMODE_EXCEPTION | (live) ERRMODE_SILENT
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
    }

    /**
     * Get unique PDOHelper instance
     *
     * @param string $connectionSettings    Connection settings
     *
     * Connection settings format:
     *      uri://user:password@host[:port][/database]?setting1=value1&...
     *
     * Example:
     *      mysql://ptonev:mypassword@localhost/test?charset=utf8
     *
     * @return static    PDO helper instance
     */
    public static function getInstance($connectionSettings)
    {
        //  Check for instance
        if (empty(self::$instances[$connectionSettings])) {
            //  Make a unique instance
            self::$instances[$connectionSettings] = new static($connectionSettings);
        }

        //  Returns exist instance
        return self::$instances[$connectionSettings];
    }

    /**
     * Get SQL error information
     *
     * @return array    Returns SQL error information
     */
    public function getErrorInfo()
    {
        return $this->errorInfo;
    }

    /**
     * Set SQL error information
     *
     * @param array $errorInfo  SQL error information
     */
    protected function setErrorInfo($errorInfo = [])
    {
        $this->errorInfo = $errorInfo;
    }

    /**
     * Clear SQL error information
     */
    protected function clearErrorInfo()
    {
        $this->setErrorInfo([]);
    }

    /**
     * Get the ID of the last inserted row or sequence value
     *
     * @return int  Returns the ID of the last inserted row or sequence value
     */
    public function getInsertId()
    {
        return $this->db->lastInsertId();
    }

    /**
     * Get the appropriate PDO::PARAM_XXX type constant from a PHP value
     *
     * @param mixed $value  Any PHP scalar or null value
     *
     * @return int  Returns PDO::PARAM_ type
     */
    protected function getPDOType($value)
    {
        //  By default type is PARAM_STR
        $type = PDO::PARAM_STR;

        if (is_null($value)) {
            $type = PDO::PARAM_NULL;
        }
        else if (is_bool($value)) {
            $type = PDO::PARAM_BOOL;
        }
        else if (is_int($value)) {
            $type = PDO::PARAM_INT;
        }

        return $type;
    }

    /**
     * Bind query parameters into PDOStatement
     *
     * @param PDOStatement $stmt    PDO Statement instance
     * @param string $sql           SQL statement
     * @param array $params         Array with SQL parameters ['name' => 'value']
     * @param array $paramTypes     Array with PDO type on SQL parameters ['name' => PDO::]
     */
    protected function bindParameters(&$stmt, &$sql, &$params, &$paramTypes)
    {
        //	Loop by SQL statement parameters
        foreach ($params as $paramName => $paramValue) {
            //  Check if parameter exist in SQL
            if (strpos($sql, ":$paramName") === false) {
                //  Skip bind on this parameter
                continue;
            }

            //  Get parameter type from paramTypes or from native PHP type of variable type
            $paramType = (!empty($paramTypes[$paramName])) ? $paramTypes[$paramName] : $this->getPDOType($paramValue);

            //	Bind parameter
            $stmt->bindValue(":$paramName", $paramValue, $paramType);
        }
    }

    /**
     * Performs a SELECT query and returns the result as a PDOQueryHelper object
     *
     * @param string $sql       SQL statement
     * @param array $params     Array with SQL parameters ['name' => 'value']
     * @param array $paramTypes Array with PDO type on SQL parameters ['name' => PDO::]
     *
     * @return PDOQueryHelperInterface  Returns PDOQueryHelper object
     */
    public function query($sql, $params = [], $paramTypes = [])
    {
        //	Prepare SQL statement
        $stmt = $this->db->prepare($sql);

        //  Check for error
        if (!$stmt) {
            //  Store error information
            $this->setErrorInfo($this->db->errorInfo());

            //  Create Error mock-up on PDO Statement
            $stmt = new PDOQueryHelperMock(null);

            //  Returns error mock-up on PDO Statement
            return $stmt;
        }

        //  Bind query parameters
        $this->bindParameters($stmt, $sql, $params, $paramTypes);

        //	Execute SQL statement
        if ($stmt->execute()) {
            //  Returns number of affected rows
            return new PDOQueryHelper($stmt);
        }
        else {
            //  Store error information
            $this->setErrorInfo($stmt->errorInfo());

            //  Create Error mock-up on PDO Statement
            $stmt = new PDOQueryHelperMock(null);

            //  Returns error mock-up on PDO Statement
            return $stmt;
        }
    }

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
    public function perform($sql, $params = [], $paramTypes = [])
    {
        //	Prepare SQL statement
        $stmt = $this->db->prepare($sql);

        //  Check for error
        if (!$stmt) {
            //  Store error information
            $this->setErrorInfo($this->db->errorInfo());

            //  Returns negative value on error
            return -1;
        }

        //  Bind query parameters
        $this->bindParameters($stmt, $sql, $params, $paramTypes);

        //	Execute SQL statement
        if ($stmt->execute()) {
            //  Returns number of affected rows
            return $stmt->rowCount();
        }
        else {
            //  Store error information
            $this->setErrorInfo($stmt->errorInfo());

            //  Returns negative value on error
            return -1;
        }
    }
}
