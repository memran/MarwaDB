<?php
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/memran
 * @see      http://www.memran.me
 **/
namespace MarwaDB\Builders\Common\Sql;

use MarwaDB\Builders\Common\Sql\InvalidTableException;

class Where
{
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $_whereSql;
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $_subWhereSql;
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $_subWhereType;
    
    /**
     * Undocumented function
     *
     * @param [type] $column
     * @param [type] $condtion
     * @param [type] $value
     * @return void
     */
    public function addWhere($column, $condition, $value)
    {
        $this->_whereSql = "WHERE {$column} {$condition} '{$value}'";
    }
    /**
     * Undocumented function
     *
     * @param [type] $column
     * @param [type] $condtion
     * @param [type] $value
     * @return void
     */
    public function addOrWhere($column, $condition, $value)
    {
        if (isset($this->_whereSql)) {
            $this->_whereSql .= " OR {$column} {$condition} '{$value}'";
        } else {
            throw new Exception("where method not called");
        }
    }
    /**
     * Undocumented function
     *
     * @param [type] $column
     * @param [type] $condtion
     * @param [type] $value
     * @return void
     */
    public function addAndWhere($column, $condition, $value)
    {
        if (isset($this->_whereSql)) {
            $this->_whereSql .= " AND {$column} {$condition} '{$value}'";
        } else {
            throw new Exception("where method not called");
        }
    }
    /**
     * Undocumented function
     *
     * @param [type] $column
     * @param [type] $condition
     * @param [type] $value
     * @return void
     */
    public function addSubWhere(string $column, string $condition, $value)
    {
        if (isset($this->_whereSql)) {
            $this->_subWhereSql .= " {$column} {$condition} '{$value}'";
        } else {
            throw new Exception("where method not called");
        }
    }
    /**
     * Undocumented function
     *
     * @param string $type
     * @return void
     */
    public function setSubWhereType(string $type)
    {
        $this->_subWhereType = $type;
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    protected function getSubWhereType()
    {
        return $this->_subWhereType;
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getSubWhere()
    {
        if (isset($this->_subWhereSql)) {
            return $this->getSubWhereType().' ('.$this->_subWhereSql.')';
        }
    }
    /**
     * Undocumented function
     *
     * @param [type] $column
     * @param [type] $value1
     * @param [type] $value2
     * @return void
     */
    public function addWhereBetween($column, $value1, $value2)
    {
        //WHERE column_name BETWEEN value1 AND value2;
        $this->_whereSql = "WHERE {$column} BETWEEN '{$value1}' AND '{$value2}'";
    }
    /**
     * Undocumented function
     *
     * @param [type] $column
     * @param [type] $value1
     * @param [type] $value2
     * @return void
     */
    public function addOrWhereBetween($column, $value1, $value2)
    {
        //OR column_name BETWEEN value1 AND value2;
        $this->_whereSql .= " OR {$column} BETWEEN '{$value1}' AND '{$value2}'";
    }
    /**
     * 'Undocumented function'
     *
     * @param [type] $column
     * @param [type] $value1
     * @param [type] $value2
     * @return void
     */
    public function addWhereNotBetween($column, $value1, $value2)
    {
        //WHERE column_name NOT BETWEEN value1 AND value2;
        $this->_whereSql = "WHERE {$column} NOT BETWEEN '{$value1}' AND '{$value2}'";
    }
    /**
     * Undocumented function
     *
     * @param [type] $column
     * @param [type] $value1
     * @param [type] $value2
     * @return void
     */
    public function addOrWhereNotBetween($column, $value1, $value2)
    {
        //OR column_name NOT BETWEEN value1 AND value2;
        $this->_whereSql .= " OR {$column} NOT BETWEEN '{$value1}' AND '{$value2}'";
    }
    /**
     * Undocumented function
     *
     * @param [type] $column
     * @param [type] $values
     * @return void
     */
    public function addWhereIn($column, $values)
    {
        $datas = implode(',', $values);
        //WHERE column_name IN (value1,value2);
        $this->_whereSql = "WHERE {$column} IN ({$datas})";
    }
    /**
     * Undocumented function
     *
     * @param [type] $column
     * @param [type] $values
     * @return void
     */
    public function addWhereNotIn($column, $values)
    {
        $datas = implode(',', $values);
        //WHERE column_name IN (value1,value2);
        $this->_whereSql = "WHERE {$column} NOT IN ({$datas})";
    }

    /**
    * Undocumented function
    *
    * @param [type] $column
    * @param [type] $values
    * @return void
    */
    public function addOrWhereIn($column, $values)
    {
        $datas = implode(',', $values);
        $this->_whereSql .= " OR {$column} IN ({$datas})";
    }
    /**
     * Undocumented function
     *
     * @param [type] $column
     * @param [type] $values
     * @return void
     */
    public function addOrWhereNotIn($column, $values)
    {
        $datas = implode(',', $values);
        //WHERE column_name IN (value1,value2);
        $this->_whereSql .= " OR {$column} NOT IN ({$datas})";
    }
    /**
     * Undocumented function
     *
     * @param [type] $column
     * @return void
     */
    public function addWhereNull($column)
    {
        //WHERE column_name IS NULL;
        $this->_whereSql = "WHERE {$column} IS NULL";
    }
    /**
     * Undocumented function
     *
     * @param [type] $column
     * @return void
     */
    public function addWhereNotNull($column)
    {
        //WHERE column_name IS NULL;
        $this->_whereSql = "WHERE {$column} IS NOT NULL";
    }
    /**
     * Undocumented function
     *
     * @param [type] $column
     * @return void
     */
    public function addOrWhereNull($column)
    {
        //OR column_name IS NULL;
        $this->_whereSql .= " OR {$column} IS NULL";
    }
    /**
     * Undocumented function
     *
     * @param [type] $column
     * @return void
     */
    public function addOrWhereNotNull($column)
    {
        //OR column_name IS NULL;
        $this->_whereSql .= " OR {$column} IS NOT NULL";
    }

    public function addWhereDate(string $column, $value)
    {
        $this->_whereSql = "WHERE DATE({$column}) = '{$value}'";
    }

    public function addWhereDay(string $column, $value)
    {
        $this->_whereSql = "WHERE DAY({$column}) = '{$value}'";
    }

    public function addWhereYear(string $column, $value)
    {
        $this->_whereSql = "WHERE YEAR({$column}) = '{$value}'";
    }
    public function addWhereMonth(string $column, $value)
    {
        $this->_whereSql = "WHERE MONTH({$column}) = '{$value}'";
    }
    public function addWhereTime(string $column, $value)
    {
        $this->_whereSql = "WHERE TIME({$column}) = '{$value}'";
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getWhere()
    {
        return $this->_whereSql.' '.$this->getSubWhere();
    }
}