<?php
    
    namespace MarwaDB;
    
    use Exception;
    use MarwaDB\Connections\Connection;
    use MarwaDB\Connections\Interfaces\ConnectionInterface;
    use MarwaDB\Exceptions\InvalidArgumentException;
    
class DB
{
        
    /**
     * @var PDO
     */
    protected $pdo = null;
        
        
    /**
     * @var array
     */
    protected $result = [];
    /**
     * @var ConnectionInterface|void
     */
    private $__conn;
        
    /**
     * DB constructor.
     *
     * @param  array $dbArray
     * @throws \Exception
     */
    public function __construct( array $dbArray )
    {
        if (empty($dbArray) ) {
            throw new Exception("Database Configuration is empty");
        }
            
        $this->__conn = Connection::getInstance($dbArray);
    }
        
    /**
     * @return string
     */
    public function status()
    {
        return $this->__conn->status();
    }
        
    /**
     * @param  $sqlQuery
     * @param  array $bindParam
     * @return array|Connections\Interfaces\return
     */
    public function raw( $sqlQuery, $bindParam = [] )
    {
        return $this->rawQuery($sqlQuery, $bindParam);
    }
        
    /**
     * @param  $sqlQuery
     * @param  array $bindParam
     * @return array|Connections\Interfaces\return
     */
    public function rawQuery( $sqlQuery, $bindParam = [] )
    {
        $this->result = $this->__conn->query($sqlQuery, $bindParam);
            
        return $this->result;
    }
        
    /**
     * @param  string $name
     * @return $this
     */
    public function connection( string $name = '' )
    {
        $this->pdo = $this->__conn->getConnection($name);
            
        return $this;
    }
        
    /**
     * @return ConnectionInterface
     */
    public function getConnection() : ConnectionInterface
    {
        return $this->__conn;
    }
        
    /**
     * @return int
     */
    public function rows() : int
    {
        return $this->__conn->getAffectedRows();
    }
        
    /**
     * @param  string $type
     * @return $this
     */
    public function setFetchMode( string $type = 'array' )
    {
        $this->__conn->setFetchMode($type);
            
        return $this;
    }
        
    /**
     * @param  string $name
     * @return QueryBuilder
     * @throws InvalidArgumentException
     */
    public function table( string $name )
    {
        if (empty($name) ) {
            throw new InvalidArgumentException("Table name is empty");
        }
        $query = new QueryBuilder($this, $name);
        $query->setDriver($this->getDriver());
            
        return $query;
    }
        
    /**
     * @return string
     */
    public function getDriver() : string
    {
        return $this->__conn->getDriver();
    }
        
    /**
     * @param  string $sql
     * @param  array  $params
     * @return array|Connections\Interfaces\return
     */
    public function select( string $sql, array $params = [] )
    {
        return $this->rawQuery($sql, $params);
    }
        
    /**
     * @param  string $sql
     * @param  array  $params
     * @return array|Connections\Interfaces\return
     */
    public function insert( string $sql, array $params = [] )
    {
        return $this->rawQuery($sql, $params);
    }
        
    /**
     * @param  string $sql
     * @param  array  $params
     * @return array|Connections\Interfaces\return
     */
    public function update( string $sql, array $params = [] )
    {
        return $this->rawQuery($sql, $params);
    }
        
    /**
     * @param  string $sql
     * @param  array  $params
     * @return array|Connections\Interfaces\return
     */
    public function delete( string $sql, array $params = [] )
    {
        return $this->rawQuery($sql, $params);
    }
        
    /**
     * @param  callable $function_name
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function transaction( callable $function_name )
    {
        if (!is_callable($function_name) ) {
            throw new InvalidArgumentException('Parameter is not callable!');
        }
            
        try
        {
            $this->beginTrans();
            $function_name($this);
            $this->commit();
        } catch ( Exception $e )
        {
            $this->rollback();
            throw new Exception('Transaction Failed');
        }
    }
        
    /**
     *
     */
    public function beginTrans()
    {
        $this->__conn->beginTrans();
    }
        
    /**
     *
     */
    public function commit()
    {
        $this->__conn->commitTrans();
    }
        
    /**
     *
     */
    public function rollback()
    {
        $this->__conn->rollBackTrans();
    }
        
    /**
     * @return $this
     */
    public function enableQueryLog()
    {
        $this->__conn->enableLog();
            
        return $this;
    }
        
    /**
     * @return mixed
     */
    public function getQueryLog()
    {
        return $this->__conn->getQueryLog();
    }
}
