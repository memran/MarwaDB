<?php
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/memran
 * @see      http://www.memran.me
 **/

namespace MarwaDB;

use MarwaDB\Exceptions\InvalidArgumentException;
use MarwaDB\QueryFactory;
use MarwaDB\BuilderFactory;

class QueryBuilder
{
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $_table;

    /**
     * Undocumented variable
     *
     * @var string
     */
    protected $_driver = 'mysql';
    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $_methods=[];
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $_builderName='select';
    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $_builderList=['select','insert','update','delete'];
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $_updateOrInsert=[];
    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $_data=[];
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $_db;
    /**
     * Undocumented function
     *
     * @param string $table
     */
    public function __construct(DB $db, string $table)
    {
        $this->_db = $db;
        
        if (empty($table)) {
            throw new InvalidArgumentException("Table name is empty", 1);
        }
        $this->_table = $table;
    }
    
    /**
     * Undocumented function
     *
     * @return string
     */
    public function getTable() : string
    {
        return $this->_table;
    }
    
    /**
     * Undocumented function
     *
     * @param string $driver
     * @return void
     */
    public function setDriver(string $driver)
    {
        $this->_driver = $driver;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getDriver() : string
    {
        return ucfirst(strtolower($this->_driver));
    }
    /**
     * Undocumented function
     *
     * @param array $data
     * @return void
     */
    public function insert(array $data)
    {
        if (Util::is_multi($data)) {
            foreach ($data as $key => $value) {
                $res = $this->runQuery('insert', $value);
            }
        } else {
            return $this->runQuery('insert', $data);
        }
        return $res;
    }
    /**
     * Undocumented function
     *
     * @param array $data
     * @return void
     */
    public function insertGetId(array $data)
    {
        if (!Util::is_multi($data)) {
            $this->runQuery('insert', $data);
            return $this->_db->getConnection()->getLastId();
        } else {
            throw new InvalidArgumentException("Multi-dimentional array not supported");
        }
    }
    /**
     * Undocumented function
     *
     * @param [type] $type
     * @param [type] $data
     * @return void
     */
    protected function runQuery($type, $data)
    {
        $this->setBuilderName($type);
        $this->setData($data);
        //dump($this->buildQuery());
        return $this->_db->raw($this->buildQuery());
    }
    /**
     * Undocumented function
     *
     * @param array $data
     * @return void
     */
    public function update(array $data)
    {
        $this->setBuilderName('update');
        $this->setData($data);
        //dump($this->buildQuery());
        return $this->_db->raw($this->buildQuery());
    }
  
    /**
     * Undocumented function
     *
     * @param array $attributes
     * @param array $values
     * @return void
     */
    public function updateOrInsert(array $attributes, array $values)
    {
        $this->setBuilderName('insert');
        $this->setData($values);
        $this->setUpdateOrInsert($attributes);
        //dump($this->buildQuery());
        return $this->_db->raw($this->buildQuery());
    }
    /**
     * Undocumented function
     *
     * @param array $attributes
     * @return void
     */
    protected function setUpdateOrInsert(array $attributes)
    {
        $this->_updateOrInsert = $attributes;
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    protected function getUpdateOrInsert()
    {
        return $this->_updateOrInsert;
    }
    
    /**
     * Undocumented function
     *
     * @param array $data
     * @return void
     */
    public function delete()
    {
        $this->setBuilderName('delete');
        return $this->_db->raw($this->buildQuery());
    }
    /**
     * Undocumented function
     *
     * @param string $type
     * @return void
     */
    public function dump(string $type='select')
    {
        $this->setBuilderName($type);
        dump($this->buildQuery());
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function dd()
    {
        $this->dump();
        die;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function get()
    {
        $this->setBuilderName('select');
        return $this->_db->raw($this->buildQuery());
    }
    /**
     * Undocumented function
     *
     * @param array $data
     * @return void
     */
    protected function setData(array $data)
    {
        $this->_data = $data;
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    protected function getData()
    {
        return $this->_data;
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    protected function buildQuery()
    {
        //dump($this->_target);
        //if ('select' === $this->_target) {
        //get SelectBuilder Object based on Driver
        $builder = QueryFactory::getInstance(
            $this->getDriver(),
            $this->getBuilderName()
        );
            
        // Builder Director based on Driver
        $director = BuilderFactory::getInstance(
            $builder,
            $this->getBuilderName(),
            $this->getTable(),
            $this->getDriver()
        );
        //set list of methods to build sql query
        
        if ($this->getBuilderName() === 'insert') {
            $director->updateOrInsert($this->getUpdateOrInsert());
            $director->setData($this->getdata());
        } elseif ($this->getBuilderName() === 'update') {
            $director->setData($this->getdata());
            $director->setMethods($this->_methods);
        } else {
            $director->setMethods($this->_methods);
        }
        //build sql query
        $director->buildQuery();
        return $director->getSql();
        //}
    }
    /**
     * Undocumented function
     *
     * @param string $name
     * @return void
     */
    protected function setBuilderName(string $name) : void
    {
        if (in_array($name, $this->_builderList)) {
            $this->_builderName = strtolower($name);
        } else {
            throw new InvalidArgumentException("Wrong Builder name provided");
        }
    }
    /**
     * Undocumented function
     *
     * @return string
     */
    protected function getBuilderName() : string
    {
        return $this->_builderName;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function __toString()
    {
        return $this->buildQuery();
    }
       
    /**
     * Undocumented function
     *
     * @param [type] $method
     * @param [type] $args
     * @return void
     */
    public function __call($method, $args)
    {
        $this->_methods[$method]=$args;
        return $this;
    }
}