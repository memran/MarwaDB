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
use MarwaDB\Connections\Exceptions\ArrayNotFoundException;
use MarwaDB\Connections\Exceptions\InvalidException;
use MarwaDB\Connections\ConnectionValidator;

class ConnectionLocator
{
    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $_connections=[];
         
    /**
     * Undocumented function
     *
     * @param [type] $name
     * @param [type] $connection
     * @return void
     */
    public function addConnection(string $name, array $connection)
    {
        $validator = new ConnectionValidator();
        if ($validator->valid($connection)) {
            $this->_connections[$name]=$connection;
        } else {
            throw new InvalidException("Invalid Database config");
        }
    }

    /**
     * Undocumented function
     *
     * @param string $key
     * @return void
     */
    public function getConnection(string $key=null)
    {
        if (is_null($key) || empty($key)) {
            return reset($this->_connections);
        }
        
        if (array_key_exists($key, $this->_connections)) {
            return $this->_connections[$key];
        }
    }
}