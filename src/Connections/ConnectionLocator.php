<?php
    
    namespace MarwaDB\Connections;
    
    use MarwaDB\Connections\Exceptions\InvalidException;
    
class ConnectionLocator
{
        
    /**
     * @var array
     */
    protected $_connections = [];
        
    /**
     * @param  string $name
     * @param  array  $connection
     * @throws InvalidException
     */
    public function addConnection( string $name, array $connection )
    {
    	if(empty($connection))
    		return false;
    	
        $validator = new ConnectionValidator();
        
        if ($validator->valid($connection) ) {
            $this->_connections[ $name ] = $connection;
        }
        else
        {
            throw new InvalidException("Invalid Database config");
        }
    }
        
    /**
     * @param  string|null $key
     * @return mixed
     */
    public function getConnection( string $key = null )
    {
        if (is_null($key) || empty($key) ) {
            return reset($this->_connections);
        }
            
        if (array_key_exists($key, $this->_connections) ) {
            return $this->_connections[ $key ];
        }
    }
}
