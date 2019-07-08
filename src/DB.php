<?php
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/memran
 * @see      http://www.memran.me
 **/

namespace MarwaDB;

use PDO;
use PDOException;
use MarwaDB\QueryBuilder;
use MarwaDB\Connection;

class DB
{

	/**
	 * database connection
	 * */
	var $conn = null;

	/*
	* var raw PDO
	*/
	protected $pdo=null;

	/**
	 * function __construct
	 * */
	public function __construct($dbArray)
	{
		$this->conn = new Connection($dbArray);
	}

	/**
	 * function the raw PDO Connection
	 * @return  \PDO description
	 * */
	public function getPdo()
	{
		return $this->conn->connect();
	}

	/**
	 * function database query
	 * @param  $sqlQuery description
	 * @param  $bindParam
	 * */
	public function rawQuery($sqlQuery,$bindParam=[])
	{
			return $this->conn->query($sqlQuery,$bindParam);
	}

	/**
	 * function to retrieve conenction pdo
	 * @return  $this description
	 * */
	public function connection($name=null)
	{
		$this->pdo = $this->conn->getConnection($name);
		return $this;
	}

	/**
	 * function to retrieve conenction pdo
	 * @return  Connection description
	 * */
	public function getConnection()
	{
		return $this->conn;
	}

	/**
	 * function to return number of rows
	 * @return  int number of rows
	 * */
	public function count()
	{
		return $this->conn->rows();
	}


	/**
	 * function to move on QueryBuilder Class
	 * @param   $name table name
	 * @return  QueryBuilder description
	 * */
	public function table($name)
	{
		$this->conn->connect();
		$qb = new QueryBuilder($this,$name);
		return $qb;
	}


	/**
	 * alias function of database select
	 * */
	public function select($sql,$params=[])
	{
			return $this->conn->query($sql,$params);
	}

	/**
	 * alias function of query for insert data
	 * */
	public function insert($sql,$params=[])
	{
			return $this->conn->query($sql,$params);
	}
	/**
	 * alias function of Query
	 * */
	public function update($sql,$params=[])
	{
			return $this->conn->query($sql,$params);
	}

	/**
	 * alias function to delete data
	 * */
	public function delete($sql,$params=[])
	{
			return $this->conn->query($sql,$params);
	}

	/**
	 * begin Transaction
	 * */

	public function beginTrans()
	{
		$this->pdo = $this->conn->connect();
		$this->pdo->beginTransaction();
	}

	/**
	 * commit transaction
	 * */
	public function commit()
	{
		$this->pdo = $this->conn->connect();
		$this->pdo->commit();
	}

	/**
	 * rollback Transaction
	 * */
	public function rollback()
	{
		$this->pdo = $this->conn->connect();
		$this->pdo->rollback();
	}


	/**
	 * function for atabase transaction with callback function
	 *@param   $function_name description
	 * */
	public function transaction($function_name)
	{

		if(!is_callable($function_name))
		{
			throw new Exception('Function is not callable!');
		}

		//try to call function with start/commit transaction
		try
		{
			$this->beginsTrans();
			$function_name();
			$this->commit();
		}
		catch (Exception $e)
		{
    		$this->rollback();
    		throw $e;
		}

	}

}

?>
