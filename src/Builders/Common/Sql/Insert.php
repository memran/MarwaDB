<?php
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/memran
 * @see      http://www.memran.me
 **/
namespace MarwaDB\Builders\Common\Sql;

use MarwaDB\Util;
use MarwaDB\Builders\Common\Sql\InvalidTableException;

class Insert
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
     * @var array
     */
    protected $_columns=[];
    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $_values=[];

    /**
     * set table name
     *
     * @param string $name
     * @return void
     */
    public function setTable(string $name)
    {
        $this->_table = $name;
    }
    /**
     * get Table name
     *
     * @return void
     */
    public function getTable()
    {
        return $this->_table;
    }
   
    /**
     * Undocumented function
     *
     * @param array $data
     * @return void
     */
    public function addData(array $data) : void
    {
        if (is_array($data)) {
            $this->addColumnValue($data);
        }
    }
    /**
     * Undocumented function
     *
     * @param array $data
     * @return void
     */
    protected function addColumnValue(array $data)
    {
        if (empty($data)) {
            throw new InvalidTableException("Empty data to insert", 1);
        }
        
        foreach ($data as $key => $value) {
            array_push($this->_columns, $key);
            array_push($this->_values, "'{$value}'");
        }
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getCols()
    {
        if (!empty($this->_columns)) {
            return implode(',', $this->_columns);
        }
        return '';
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getValues()
    {
        if (!empty($this->_values)) {
            return implode(',', $this->_values);
        }
        return '';
    }
}