<?php
    
    namespace MarwaDB\Connections;
    
    use MarwaDB\Connections\Exceptions\InvalidException;
    use MarwaDB\Exceptions\NotFoundException;
    use Throwable;
    
class ConnectionValidator
{
        
    /**
     * @var array
     */
    protected $_config;
        
    /**
     * ConnectionValidator constructor.
     */
    public function __construct()
    {
    }
        
    /**
     * @param  array $config
     * @return bool
     */
    public function valid( array $config ) : bool
    {
        try
        {
            $this->_config = $config;
            $this->_validateKey();
            return true;
        } catch ( Throwable $th )
        {
            return false;
        }
            
            
    }
        
    /**
     * @throws InvalidException
     * @throws NotFoundException
     */
    protected function _validateKey()
    {
        $this->__validDriver();
        $this->__validHost();
        $this->__validUser();
        $this->__validPassword();
        $this->__validDBName();
    }
        
    /**
     * @throws InvalidException
     * @throws NotFoundException
     */
    private function __validDriver()
    {
        if (!array_key_exists('driver', $this->_getConfig()) ) {
            throw new NotFoundException("Database Driver parameter not found");
        }
            
        if (is_null($this->_getConfig()['driver']) or !isset($this->_getConfig()['driver']) || empty($this->_getConfig()['driver']) ) {
            throw new InvalidException("Database Driver parameter is not valid");
        }
    }
        
    /**
     * @return array
     */
    protected function _getConfig()
    {
        return $this->_config;
    }
        
    /**
     * @throws InvalidException
     * @throws NotFoundException
     */
    private function __validHost()
    {
        //check host is valid
        if (!array_key_exists('host', $this->_getConfig()) ) {
            throw new NotFoundException("Database host parameter not found");
        }
            
        if (is_null($this->_getConfig()['host']) or !isset($this->_getConfig()['host']) || empty($this->_getConfig()['driver']) ) {
            throw new InvalidException("Host Parameter is not valid");
        }
    }
        
    /**
     * @throws InvalidException
     * @throws NotFoundException
     */
    private function __validUser()
    {
        //check username
        if (!array_key_exists('username', $this->_getConfig()) ) {
            throw new NotFoundException("Database Username not found");
        }
        if (is_null($this->_getConfig()['username']) or !isset($this->_getConfig()['username']) || empty($this->_getConfig()['driver']) ) {
            throw new InvalidException("Database Username is not valid");
        }
    }
        
    /**
     * @throws NotFoundException
     */
    private function __validPassword()
    {
        //check password is  null
        if (!array_key_exists('password', $this->_getConfig()) ) {
            throw new NotFoundException("Password Parameter is not found");
        }
    }
        
    /**
     * @throws InvalidException
     * @throws NotFoundException
     */
    private function __validDBName()
    {
        //check database name is provided or not
        if (!array_key_exists('database', $this->_getConfig()) ) {
            throw new NotFoundException("Database name not found");
        }
        if (is_null($this->_getConfig()['database']) or !isset($this->_getConfig()['database']) ) {
            throw new InvalidException("Database name is not valid");
        }
    }
}
