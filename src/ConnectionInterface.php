<?php
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/memran
 * @see      http://www.memran.me
 **/

namespace MarwaDB;

interface ConnectionInterface
{

	/**
	 * PDO Database Connection
	 * @param   $dsn database dsn string
	 * @param  $user Database user
	 * @param   $password database password
	 * @return  PDO Connection
	 **/
	public function connect();

	/**
	 * Database Configuration Validation check and Setup Connection
	 * */
	public function validateAndBuild($config);

	/**
	 * function to setup PDO connection
	 * @param   $paramname description
	 * @param   $paramname description
	 * @return  void description
	 * */
	public function setConnection($name,$config);

	/**
	 * function to get PDO Connection
	 * @param   $paramname description
	 * @return  PDO description
	 * */
	public function getConnection($name='');
	/**
	 * Function to execute Sql Query
	 * @param   $sql Database Sql Query
	 * @return   return array or result of execute query
	 * */
	public function query($sql);

	/**
	 * function to return affected rows
	 * @return  int description
	 * */
	public  function getRows();

	/**
	 * function to retrive server status from PDO
	 * @return  string description
	 * */
	public function status();

	/**
	 * function to close connection
	 * @return  void description
	 * */
	public function close();


}

?>
