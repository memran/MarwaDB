<?php
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/memran
 * @see      http://www.memran.me
 **/

namespace MarwaDB\Connections\Interfaces;

interface ConnectionInterface
{
    /**
     * Undocumented function
     *
     * @param array $config
     * @return ConnectionInterface
     */
    public static function getInstance(array $config) : ConnectionInterface;
    

    /**
     * Undocumented function
     *
     * @param string $name
     * @return ConnectionInterface
     */
    public function connection(string $name): ConnectionInterface;

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getConnection() : array;

    /**
     * PDO Database Connection
     * @param   $dsn database dsn string
     * @param  $user Database user
     * @param   $password database password
     * @return  PDO Connection
     **/
    public function connect(): bool;

    /**
     * Function to execute Sql Query
     * @param   $sql Database Sql Query
     * @return   return array or result of execute query
     * */
    public function query(string $sql, array $bindParams=[]);

    /**
     * Undocumented function
     *
     * @return integer
     */
    public function getLastId() : int;

    /**
     * Undocumented function
     *
     * @param string $type
     * @return ConnectionInterface
     */
    public function setFetchMode(string $type): ConnectionInterface;

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getFetchMode() : int;

    /**
     * function to return affected rows
     * @return  int description
     * */
    public function getAffectedRows(): int;

    /**
     * function to retrive server status from PDO
     * @return  string description
     * */
    public function status():string;
    /**
     * Undocumented function
     *
     * @return void
     */
    public function beginTrans();
    /**
     * Undocumented function
     *
     * @return void
     */
    public function rollBackTrans();
    /**
     * Undocumented function
     *
     * @return void
     */
    public function commitTrans();
}