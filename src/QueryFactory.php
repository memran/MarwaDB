<?php
    
    namespace MarwaDB;
    
class QueryFactory
{
        
    /**
     * @var string
     */
    private $_driver = 'mysql';
        
    /**
     * @param  string $driver
     * @param  string $type
     * @param  array  $cols
     * @return mixed
     */
    public static function getInstance( string $driver, string $type, array $cols = [] )
    {
        $instance = new Self();
        $instance->_driver = $driver;
            
        // $type can be select/insert/update/delete. it is dynamic function
        return $instance->$type($cols);
    }
        
    /**
     * @param  array $cols
     * @return mixed
     */
    public function select( $cols = [] )
    {
        return $this->newInstance('SelectBuilder', $this->getDriver(), $cols);
    }
        
    /**
     * @param  string $instance
     * @param  string $driver
     * @param  array  $cols
     * @return mixed
     */
    public function newInstance( string $instance, string $driver, $cols = [] )
    {
        $object = '\\MarwaDB\\Builders\\' . $driver . '\\' . $instance;
            
        return new $object($cols);
    }
        
    /**
     * Undocumented function
     *
     * @return string
     */
    public function getDriver() : string
    {
        return $this->_driver;
    }
        
    /**
     * @param  array $data
     * @return mixed
     */
    public function insert( array $data = [] )
    {
        return $this->newInstance('InsertBuilder', $this->getDriver(), $data);
    }
        
    /**
     * @param  array $data
     * @return mixed
     */
    public function update( array $data = [] )
    {
        return $this->newInstance('UpdateBuilder', $this->getDriver(), $data);
    }
        
    /**
     * @return mixed
     */
    public function delete()
    {
        return $this->newInstance('DeleteBuilder', $this->getDriver());
    }
}
