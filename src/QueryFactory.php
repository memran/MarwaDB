<?php
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/memran
 * @see      http://www.memran.me
 **/

namespace MarwaDB;

use MarwaDB\Exceptions\ArrayNotFoundException;
use MarwaDB\Exceptions\NotFoundException;
use MarwaDB\Builders\Common\BuilderInterface;

class QueryFactory
{
    /**
     * Default database driver
     *
     * @var string
     */
    private $_driver = 'mysql';

    /**
     * Undocumented function
     *
     * @param string $driver
     * @param string $type
     * @param array $cols
     * @return void
     */
    public static function getInstance(string $driver, string $type, array $cols=[])
    {
        $instance = new Self();
        $instance->_driver = $driver;
        // $type can be select/insert/update/delete. it is dynamic function
        return $instance->$type($cols);
    }
 
    /**
     * Undocumented function
     *
     * @return string
     */
    public function getDriver(): string
    {
        return $this->_driver;
    }
    /**
     * Undocumented function
     *
     * @param string $driver
     * @param array $cols
     * @return void
     */
    public function select($cols=[])
    {
        return $this->newInstance('SelectBuilder', $this->getDriver(), $cols);
    }

    public function insert(array $data=[])
    {
        return $this->newInstance('InsertBuilder', $this->getDriver(), $data);
    }

    public function update(array $data=[])
    {
        return $this->newInstance('UpdateBuilder', $this->getDriver(), $data);
    }
    
    public function delete()
    {
        return $this->newInstance('DeleteBuilder', $this->getDriver());
    }

    /**
     * Undocumented function
     *
     * @param string $instance
     * @param string $driver
     * @param array $cols
     * @return void
     */
    public function newInstance(string $instance, string $driver, $cols=[])
    {
        $object='\\MarwaDB\\Builders\\'.$driver.'\\'.$instance;
        return new $object($cols);
    }
}