<?php
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/memran
 * @see      http://www.memran.me
 **/
namespace MarwaDB\Connections;

use PDO;
use MarwaDB\Connections\Exceptions\NotFoundException;
use MarwaDB\Connections\Exceptions\InvalidException;
use MarwaDB\Connections\Exceptions\ConnectionException;
use MarwaDB\Connections\Interfaces\ConnectionInterface;
use MarwaDB\Connections\ConnectionLocator;
use MarwaDB\SqlTrait;
use MarwaDB\Util;

class Connection implements ConnectionInterface
{
    use SqlTrait;
    /**
     *  connection object
     * */
    protected $pdo;

    /**
     * [$mode description]
     * @var string
     */
    protected $mode = 'array';

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $fetchMode=[
            'array' => PDO::FETCH_ASSOC,
            'object' => PDO::FETCH_OBJ
    ];
    /**
     * var default connection name
     * */
    protected $default;

    /**
     * Array
     * */
    protected $_config;

    /**
     * var int
     * */
    protected $numRows=false;

    /**
     * The default PDO connection options.
     *
     * @var array
     */
    protected $options = [
            \PDO::ATTR_CASE => PDO::CASE_NATURAL,
            \PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
            \PDO::ATTR_STRINGIFY_FETCHES => false,
            \PDO::ATTR_EMULATE_PREPARES => false,
            \PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];
    /**
     * ConnectionInterface
     *
     * @var [type]
     */
    protected static $_instance;
    
    /**
     * ConnectionLocator
     *
     * @var [type]
     */
    protected $locator;
    
    /**
     * Function constructor
     * @param  $dbConfig description
     * */
    private function __construct(array $dbConfig)
    {
        //check PDO driver is available
        if (!defined('PDO::ATTR_DRIVER_NAME')) {
            throw new NotFoundException('PDO extension is not loaded');
        }
        $this->setDatabaseConfig($dbConfig);
    }
    
    /**
     * Undocumented function
     *
     * @param array $config
     * @return void
     */
    public static function getInstance(array $config) : ConnectionInterface
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Connection($config);
        }
        return self::$_instance;
    }
    
    /**
     * ValidateAndBuild Function to validate database configuration and setup connection
     *@param   $config Array description
     * */
    protected function setDatabaseConfig(array $config) : void
    {
        //check Database config exists
        if (is_null($config) || empty($config)) {
            throw new InvalidException("Invalid Database Connection Paramters");
        }
       
        $this->locator = new ConnectionLocator();
        
        foreach ($config as $name => $connection) {
            $this->locator->addConnection($name, $connection);
        }
    }
    /**
     * Undocumented function
     *
     * @param string $name
     * @return ConnectionInterface
     */
    public function connection(string $name) : ConnectionInterface
    {
        if (is_null($name)||empty($name)) {
            throw new InvalidException("Invalid Connection name");
        }
            
        $this->default = $name;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    protected function getDefaultConnectionName() : ?string
    {
        return $this->default;
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $name
     * @return void
     */
    public function getConnection() : array
    {
        return $this->locator->getConnection($this->getDefaultConnectionName());
    }
    /**
     * function buildDSN
     * @param   $paramname description
     * @return  String description
     * */
    protected function getDsn() : string
    {
        $format = "%s:host=%s;dbname=%s";
        $dsnStr = sprintf(
            $format,
            $this->getDriver(),
            $this->getHost(),
            $this->getDB()
        );
         
        if ($this->getDriver() === "mysql") {
            $dsnStr .= ';charset='.$this->getCharSet();
        }
        
        return $dsnStr;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getDriver() : string
    {
        return strtolower($this->getConnection()['driver']);
    }
    /**
     * Undocumented function
     *
     * @return string
     */
    public function getHost() : string
    {
        return $this->getConnection()['host'];
    }
    /**
     * Undocumented function
     *
     * @return string
     */
    public function getDB() : string
    {
        return $this->getConnection()['database'];
    }
    /**
     * Undocumented function
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->getConnection()['username'];
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getPassword(): string
    {
        if (empty($this->getConnection()['password']) || is_null($this->getConnection()['password'])) {
            return "";
        }
        return $this->getConnection()['password'];
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getOptions()
    {
        if (array_key_exists('options', $this->getConnection())) {
            return array_unique(array_merge($this->options, $this->getConnection()['options']));
        }
    }
    /**
     * Undocumented function
     *
     * @return string
     */
    public function getCharSet() : string
    {
        return $this->getConnection()['charset'];
    }
    
    /**
     * [connect description]
     *
     * @method connect
     *
     * @return [type] [description]
     */
    public function connect() : bool
    {
     
        //try to connect PDO
        try {
            $this->pdo = new PDO(
                $this->getDsn(),
                $this->getUsername(),
                $this->getPassword(),
                $this->getOptions()
            );
            return true;
        } catch (PDOException $e) {
            throw new ConnectionException("Failed to Connect Database");
        }
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    protected function getPdo()
    {
        //check alreayd connection established or not
        if (!is_null($this->pdo)) {
            return $this->pdo;
        }
        $this->connect();
        return $this->pdo;
    }
    /**
     * Undocumented function
     *
     * @param string $type
     * @return ConnectionInterface
     */
    public function setFetchMode(string $type): ConnectionInterface
    {
        $this->mode = strtolower($type);
        return $this;
    }
    /**
     * Undocumented function
     *
     * @return string
     */
    public function getFetchMode() : int
    {
        return $this->fetchMode[$this->mode];
    }
    
    /**
     * function to reconnect Database
     * @return  PDO description
     * */
    public function reconnect()
    {
        $this->pdo = null;
        return $this->connect();
    }

    /**
     * function database query execution
     * @param  $sqlQuery description
     * @param  $bindParam
     * */
    public function query(string $sqlQuery, array $bindParam=[])
    {
        if (empty($sqlQuery)) {
            throw new InvalidException("Query string is empty");
        }
       
        try {
            return $this->executeQuery($this->removeWhiteSpace($sqlQuery), $bindParam);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Undocumented function
     *
     * @param integer $rows
     * @return void
     */
    protected function setAffectedRows(int $rows): void
    {
        $this->numRows = $rows;
    }

    /**
     * Undocumented function
     *
     * @return integer
     */
    public function getLastId() : int
    {
        return $this->getPdo()->lastInsertId();
    }
    /**
     * Undocumented function
     *
     * @param [type] $sqlQuery
     * @param [type] $bindParam
     * @return void
     */
    protected function executeQuery($sqlQuery, $bindParam)
    {
        $stmt = $this->getPdo()->prepare($sqlQuery);
        $res = $stmt->execute($bindParam);
      
        $this->setAffectedRows($stmt->rowCount());
        
        //check query is startwith SELECT
        if ($this->detectSelectSql($sqlQuery)) {
            $res = $stmt->fetchAll($this->getFetchMode());
            if (is_array($res) && count($res) == 1) {
                return $res[0];
            }
            return $res;
        }
            
        return $res;
    }

    
    /**
     * function to return number of rows from sql query
     * @return  int description
     * */
    public function getAffectedRows() : int
    {
        return $this->numRows;
    }
    /**
     * function to get Server info from PDO connection
     * @return  String|null description
     * */
    public function status() : string
    {
        if (!is_null($this->pdo)) {
            return $this->pdo->getAttribute(constant("PDO::ATTR_SERVER_INFO"));
        }
        return '';
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function beginTrans()
    {
        $this->getPdo()->beginTransaction();
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function rollBackTrans()
    {
        $this->getPdo()->rollback();
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function commitTrans()
    {
        $this->getPdo()->commit();
    }
    /**
     * Undocumented function
     *
     * @param string $sql
     * @return void
     */
    protected function detectSelectSql(string $sql)
    {
        return Util::startsWith($sql, 'SELECT');
    }
 
    /**
     * Undocumented function
     *
     * @return void
     */
    protected function __destroy()
    {
        $this->pdo = null;
    }
}