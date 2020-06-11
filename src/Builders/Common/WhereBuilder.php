<?php
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/memran
 * @see      http://www.memran.me
 **/

namespace MarwaDB\Builders\Common;

use MarwaDB\Exceptions\ArrayNotFoundException;
use MarwaDB\Exceptions\NotFoundException;
use MarwaDB\Builders\Common\Sql\Select;
use MarwaDB\Builders\Common\Sql\Where;
use MarwaDB\Builders\Common\Sql\InnerJoin;
use MarwaDB\Builders\Common\Sql\LeftJoin;
use MarwaDB\Builders\Common\Sql\RightJoin;
use MarwaDB\Builders\Common\BuilderInterface;
use MarwaDB\SqlTrait;

trait WhereBuilder
{
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $_where;
    /**
    * Undocumented function
    *
    * @param [type] $column
    * @param [type] $condtion
    * @param [type] $value
    * @return void
    */
    public function where(string $column, $condition, $value)
    {
        $this->_where = new Where();
        $this->_where->addWhere($column, $condition, $value);
    }
    /**
     * Undocumented function
     *
     * @param [type] $column
     * @param [type] $condtion
     * @param [type] $value
     * @return void
     */
    public function orWhere(string $column, $condition, $value)
    {
        if (isset($this->_where)) {
            $this->_where->addOrWhere($column, $condition, $value);
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
    public function andWhere(string $column, $condition, $value)
    {
        if (isset($this->_where)) {
            $this->_where->addAndWhere($column, $condition, $value);
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
    public function subOrWhere(string $column, $condition, $value)
    {
        if (isset($this->_where)) {
            $this->_where->setSubWhereType('OR');
            $this->_where->addSubWhere($column, $condition, $value);
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
    public function subAndWhere(string $column, $condition, $value)
    {
        if (isset($this->_where)) {
            $this->_where->setSubWhereType('AND');
            $this->_where->addSubWhere($column, $condition, $value);
        }
    }
    /**
     * Undocumented function
     *
     * @param string $column
     * @param string $value1
     * @param string $value2
     * @return void
     */
    public function whereBetween(string $column, $value1, $value2)
    {
        $this->_where = new Where();
        $this->_where->addWhereBetween($column, $value1, $value2);
        return $this;
    }
    /**
     * Undocumented function
     *
     * @param string $column
     * @param string $value1
     * @param string $value2
     * @return void
     */
    public function orWhereBetween(string $column, $value1, $value2)
    {
        if (isset($this->_where)) {
            $this->_where->addOrWhereBetween($column, $value1, $value2);
        }
        return $this;
    }
    /**
     * Undocumented function
     *
     * @param string $column
     * @param string $value1
     * @param string $value2
     * @return void
     */
    public function whereNotBetween(string $column, $value1, $value2)
    {
        $this->_where = new Where();
        $this->_where->addWhereNotBetween($column, $value1, $value2);
        return $this;
    }
    /**
     * Undocumented function
     *
     * @param string $column
     * @param string $value1
     * @param string $value2
     * @return void
     */
    public function orWhereNotBetween(string $column, $value1, $value2)
    {
        if (isset($this->_where)) {
            $this->_where->addOrWhereNotBetween($column, $value1, $value2);
        }
        return $this;
    }
    /**
     * Undocumented function
     *
     * @param string $column
     * @param array $values
     * @return void
     */
    public function whereIn(string $column, array $values)
    {
        $this->_where = new Where();
        $this->_where->addWhereIn($column, $values);
        return $this;
    }
    /**
     * Undocumented function
     *
     * @param string $column
     * @param array $values
     * @return void
     */
    public function orWhereIn(string $column, array $values)
    {
        if (isset($this->_where)) {
            $this->_where->addOrWhereIn($column, $values);
        }
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $column
     * @param array $values
     * @return void
     */
    public function whereNotIn(string $column, array $values)
    {
        $this->_where = new Where();
        $this->_where->addWhereNotIn($column, $values);
        return $this;
    }
    /**
     * Undocumented function
     *
     * @param string $column
     * @param array $values
     * @return void
     */
    public function orWhereNotIn(string $column, array $values)
    {
        if (isset($this->_where)) {
            $this->_where->addOrWhereNotIn($column, $values);
        }
        return $this;
    }
    /**
     * Undocumented function
     *
     * @param string $column
     * @return void
     */
    public function whereNull(string $column)
    {
        $this->_where = new Where();
        $this->_where->addWhereNull($column);
        return $this;
    }
    /**
     * Undocumented function
     *
     * @param string $column
     * @return void
     */
    public function whereNotNull(string $column)
    {
        $this->_where = new Where();
        $this->_where->addWhereNotNull($column);
        return $this;
    }
    /**
     * Undocumented function
     *
     * @param string $column
     * @return void
     */
    public function OrWhereNull(string $column)
    {
        if (isset($this->_where)) {
            $this->_where->addOrWhereNull($column);
        }
        return $this;
    }
    /**
     * Undocumented function
     *
     * @param string $column
     * @return void
     */
    public function OrWhereNotNull(string $column)
    {
        if (isset($this->_where)) {
            $this->_where->addOrWhereNotNull($column);
        }
        return $this;
    }
    /**
     * Undocumented function
     *
     * @param string $column
     * @param [type] $value
     * @return void
     */
    public function whereDate(string $column, $value)
    {
        $this->_where = new Where();
        $this->_where->addWhereDate($column, $value);
        return $this;
    }
    /**
     * Undocumented function
     *
     * @param string $column
     * @param [type] $value
     * @return void
     */
    public function whereDay(string $column, $value)
    {
        $this->_where = new Where();
        $this->_where->addWhereDay($column, $value);
        return $this;
    }
    /**
     * Undocumented function
     *
     * @param string $column
     * @param [type] $value
     * @return void
     */
    public function whereYear(string $column, $value)
    {
        $this->_where = new Where();
        $this->_where->addWhereYear($column, $value);
        return $this;
    }
    /**
     * Undocumented function
     *
     * @param string $column
     * @param [type] $value
     * @return void
     */
    public function whereMonth(string $column, $value)
    {
        $this->_where = new Where();
        $this->_where->addWhereMonth($column, $value);
        return $this;
    }
    /**
     * Undocumented function
     *
     * @param string $column
     * @param [type] $value
     * @return void
     */
    public function whereTime(string $column, $value)
    {
        $this->_where = new Where();
        $this->_where->addWhereTime($column, $value);
        return $this;
    }
    /**
     * Undocumented function
     *
     * @param [type] $callable_value
     * @return void
     */
    public function whereExists($callable_value)
    {
        if (is_callable($callable_value)) {
            $whereBuilder = new Self;
            $sql=$callable_value($whereBuilder);
            return "( {$sql} )";
        }
    }
}