<?php
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/memran
 * @see      http://www.memran.me
 **/

namespace MarwaDB;

use MarwaDB\QueryBuilder;
use MarwaDB\Connections\Connection;
use MarwaDB\Connections\Interfaces\ConnectionInterface;
use MarwaDB\Exceptions\InvalidArgumentException;

use MarwaDB\Debug;

class DB
{

    /**
     * database connection
     * */
    private $__conn;

    /*
    * var raw PDO
    */
    protected $pdo=null;

    /**
     * [protected description]
     *
     * @var [type]
     */
    protected $result=null;
    /**
     * function __construct
     * */
    public function __construct($dbArray)
    {
        $this->__conn = Connection::getInstance($dbArray);
    }
    
    /**
     * Function to read pdo connection status report
     *
     * @return void
     */
    public function status()
    {
        return $this->__conn->status();
    }

    /**
     * function database rawQuery alias
     * @param  $sqlQuery description
     * @param  $bindParam
     * */
    public function raw($sqlQuery, $bindParam=[])
    {
        return $this->rawQuery($sqlQuery, $bindParam);
    }

    /**
     * function database query
     * @param  $sqlQuery description
     * @param  $bindParam
     * */
    public function rawQuery($sqlQuery, $bindParam=[])
    {
        $this->result = $this->__conn->query($sqlQuery, $bindParam);
        return $this->result;
    }

    /**
     * function to retrieve conenction pdo
     * @return  $this description
     * */
    public function connection(string $name='')
    {
        $this->pdo = $this->__conn->getConnection($name);
        return $this;
    }

    /**
     * function to retrieve conenction pdo
     * @return  Connection description
     * */
    public function getConnection() : ConnectionInterface
    {
        return $this->__conn;
    }
    /**
     * get Connection Driver
     *
     * @return string
     */
    public function getDriver(): string
    {
        return $this->__conn->getDriver();
    }

    /**
     * function to return of result affetched rows
     * @return  int number of rows
     * */
    public function rows() : int
    {
        return $this->__conn->getAffectedRows();
    }

    /**
     * [setFetchMode description]
     *
     * @method setFetchMode
     *
     * @param [type] $type [description]
     */
    public function setFetchMode(string $type='array')
    {
        $this->__conn->setFetchMode($type);
        return $this;
    }

    /**
     * function to move on QueryBuilder Class
     * @param   $name table name
     * @return  QueryBuilder description
     * */
    public function table(string $name)
    {
        if (empty($name)) {
            throw new InvalidArgumentException("Table name is empty");
        }
        $query = new QueryBuilder($this, $name);
        $query->setDriver($this->getDriver());
        
        return $query;
    }
    /**
     * alias function of database select
     * */
    public function select(string $sql, array $params=[])
    {
        return $this->rawQuery($sql, $params);
    }

    /**
     * alias function of query for insert data
     * */
    public function insert(string $sql, array $params=[])
    {
        return $this->rawQuery($sql, $params);
    }
    /**
     * alias function of Query
     * */
    public function update(string $sql, array $params=[])
    {
        return $this->rawQuery($sql, $params);
    }

    /**
     * alias function to delete data
     * */
    public function delete(string $sql, array $params=[])
    {
        return $this->rawQuery($sql, $params);
    }

    /**
     * begin Transaction
     * */

    public function beginTrans()
    {
        $this->__conn->beginTrans();
    }

    /**
     * commit transaction
     * */
    public function commit()
    {
        $this->__conn->commitTrans();
    }

    /**
     * rollback Transaction
     * */
    public function rollback()
    {
        $this->__conn->rollBackTrans();
    }


    /**
     * function for atabase transaction with callback function
     *@param   $function_name description
     * */
    public function transaction(callable $function_name)
    {
        if (!is_callable($function_name)) {
            throw new InvalidArgumentException('Parameter is not callable!');
        }

        //try to call function with start/commit transaction
        try {
            $this->beginTrans();
            $function_name($this);
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw new Exception('Transaction Failed');
        }
    }
}