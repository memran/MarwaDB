<?php
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/memran
 * @see      http://www.memran.me
 **/
namespace MarwaDB\Builders\Common\Sql;

use MarwaDB\Exceptions\ArrayNotFoundException;

class Update
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
            throw new ArrayNotFoundException("Empty data to insert", 1);
        }
        
        foreach ($data as $key => $value) {
            array_push($this->_values, "{$key} = '{$value}'");
        }
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getColumnValues()
    {
        if (!empty($this->_values)) {
            return implode(',', $this->_values);
        }
    }
}