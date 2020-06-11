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
use Throwable;
use MarwaDB\Exceptions\NotFoundException;
use MarwaDB\Exceptions\ArrayNotFoundException;
use MarwaDB\Exceptions\InvalidException;

class ConnectionValidator
{
    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $_config;

    public function __construct()
    {
    }
    /**
     * Undocumented function
     *
     * @param array $config
     * @return boolean
     */
    public function valid(array $config) : bool
    {
        try {
            $this->_config = $config;
            $this->_validateKey();
        } catch (Throwable $th) {
            return false;
        }
        
        return true;
    }

    /**
    * function validation key for database
    * @param   $dbConfig description
    * @return  $dbConfig description
    * */
    protected function _validateKey()
    {
        $this->__validDriver();
        $this->__validHost();
        $this->__validUser();
        $this->__validPassword();
        $this->__validDBName();
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    protected function _getConfig()
    {
        return $this->_config;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function __validDriver()
    {
        if (!array_key_exists('driver', $this->_getConfig())) {
            throw new NotFoundException("Database Driver parameter not found");
        }

        if (is_null($this->_getConfig()['driver']) or !isset($this->_getConfig()['driver']) || empty($this->_getConfig()['driver'])) {
            throw new InvalidException("Database Driver parameter is not valid");
        }
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    private function __validHost()
    {
        //check host is valid
        if (!array_key_exists('host', $this->_getConfig())) {
            throw new NotFoundException("Database host parameter not found");
        }
        
        if (is_null($this->_getConfig()['host']) or !isset($this->_getConfig()['host']) || empty($this->_getConfig()['driver'])) {
            throw new InvalidException("Host Parameter is not valid");
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function __validUser()
    {
        //check username
        if (!array_key_exists('username', $this->_getConfig())) {
            throw new NotFoundException("Database Username not found");
        }
        if (is_null($this->_getConfig()['username']) or !isset($this->_getConfig()['username'])|| empty($this->_getConfig()['driver'])) {
            throw new InvalidException("Database Username is not valid");
        }
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    private function __validPassword()
    {
        //check password is  null
        if (!array_key_exists('password', $this->_getConfig())) {
            throw new NotFoundException("Password Parameter is not found");
        }
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    private function __validDBName()
    {
        //check database name is provided or not
        if (!array_key_exists('database', $this->_getConfig())) {
            throw new NotFoundException("Database name not found");
        }
        if (is_null($this->_getConfig()['database']) or !isset($this->_getConfig()['database'])) {
            throw new InvalidException("Database name is not valid");
        }
    }
}