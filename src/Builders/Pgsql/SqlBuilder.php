<?php
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/memran
 * @see      http://www.memran.me
 **/

namespace MarwaDB\Builders\Pgsql;

use MarwaDB\Exceptions\NotFoundException;
use MarwaDB\Builders\Common\BuilderInterface;
use MarwaDB\Builders\AbstractQueryBuilder;

class SqlBuilder extends AbstractQueryBuilder
{
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $_builder;
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $_type;

    /**
     * List of method store here
     *
     * @var array
     */
    protected $_methods=[];
    /**
     * Table name set
     *
     * @var [type]
     */
    protected $_table;

    /**
     * Insert Variable
     *
     * @var [type]
     */
    protected $_data;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $_updateOrInsert;
    
    /**
     * Undocumented function
     *
     * @param BuilderInterface $builder_in
     * @param string $type
     */
    public function __construct(BuilderInterface $builder_in, string $type, string $table)
    {
        $this->_builder = $builder_in;
        $this->_type  = strtolower($type);
        $this->_table = $table;
    }
    /**
     * Undocumented function
     *
     * @param [type] $methods
     * @return void
     */
    public function setMethods($methods)
    {
        $this->_methods =  $methods;
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getMethods()
    {
        return $this->_methods;
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function buildQuery()
    {
        $this->_builder->table($this->_table);

        if ($this->_type === 'select') {
            $this->buildSelectQuery();
        } elseif ($this->_type === 'insert') {
            $this->buildInsertQuery();
        } elseif ($this->_type === 'delete') {
            $this->buildDeleteQuery();
        } elseif ($this->_type === 'update') {
            $this->buildUpdateQuery();
        } else {
            throw new NotFoundException("Invalid Query Type");
        }
    }

    /**
     * function to set data in insert/update query
     *
     * @param array $data
     * @return void
     */
    public function setData(array $data)
    {
        $this->_data = $data;
    }
    
    /**
     * Undocumented function
     *
     * @param array $data
     * @return void
     */
    public function updateOrInsert(array $data)
    {
        if (!empty($data)) {
            $this->_updateOrInsert = $data;
        }
    }
    /**
     * function for insert query build
     *
     * @return void
     */
    protected function buildInsertQuery()
    {
        $this->_builder->setData($this->_data);
        if (isset($this->_updateOrInsert)) {
            $this->_builder->updateOrInsert($this->_updateOrInsert);
        }
        $this->_builder->formatSql();
    }
    /**
     * Function to build Sql
     *
     * @return void
     */
    protected function buildSelectQuery()
    {
        if (!empty($this->getMethods())) {
            foreach ($this->getMethods() as $key => $value) {
                $this->execute($key, $value);
            }
        }
        $this->_builder->formatSql();
    }

    /**
     * Function to build Sql
     *
     * @return void
     */
    protected function buildDeleteQuery()
    {
        if (!empty($this->getMethods())) {
            foreach ($this->getMethods() as $key => $value) {
                $this->execute($key, $value);
            }
        }
        $this->_builder->formatSql();
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    protected function buildUpdateQuery()
    {
        $this->_builder->setData($this->_data);
        if (!empty($this->getMethods())) {
            foreach ($this->getMethods() as $key => $value) {
                $this->execute($key, $value);
            }
        }
        
        $this->_builder->formatSql();
    }
    /**
     * Undocumented function
     *
     * @param [type] $method
     * @param [type] $args
     * @return void
     */
    private function execute($method, $args)
    {
        if (method_exists($this->_builder, $method)) {
            try {
                call_user_func_array([$this->_builder,$method], $args);
            } catch (Throwable $th) {
                throw new MethodNotFoundException("Error Processing Method", 1);
            }
        }
    }
    /**
     * Function to return sql string
     *
     * @return void
     */
    public function getSql()
    {
        return $this->_builder->getSql();
    }
}