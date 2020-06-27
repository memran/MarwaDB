<?php
    
    namespace MarwaDB\Connections\Interfaces;
    
interface ConnectionInterface
{
        
    /**
     * @param  array $config
     * @return ConnectionInterface
     */
    public static function getInstance( array $config ) : ConnectionInterface;
        
        
    /**
     * @param  string $name
     * @return ConnectionInterface
     */
    public function connection( string $name ) : ConnectionInterface;
        
    /**
     * @return array
     */
    public function getConnection() : array;
        
    /**
     * @return bool
     */
    public function connect() : bool;
        
    /**
     * @param  string $sql
     * @param  array  $bindParams
     * @return mixed
     */
    public function query( string $sql, array $bindParams = [] );
        
    /**
     * @return string
     */
    public function getLastId() : string ;
        
    /**
     * @param  string $type
     * @return ConnectionInterface
     */
    public function setFetchMode( string $type ) : ConnectionInterface;
        
    /**
     * @return int
     */
    public function getFetchMode() : int;
        
    /**
     * @return int
     */
    public function getAffectedRows() : int;
        
    /**
     * @return string
     */
    public function status() : string;
        
    /**
     * @return mixed
     */
    public function beginTrans();
        
    /**
     * @return mixed
     */
    public function rollBackTrans();
        
    /**
     * @return mixed
     */
    public function commitTrans();
	
	/**
	 * @return $this
	 */
    public function enableLog();
	
	/**
	 * @return array
	 */
    public function getQueryLog(): array;
	
	/**
	 * @return string
	 */
    public function getDriver(): string ;
    
    
}
