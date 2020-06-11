<?php
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/memran
 * @see      http://www.memran.me
 **/

namespace MarwaDB\Builders\Common;

use MarwaDB\Exceptions\NotFoundException;
use MarwaDB\Builders\Common\Sql\Select;
use MarwaDB\Builders\Common\Sql\Where;
use MarwaDB\Builders\Common\Sql\InnerJoin;
use MarwaDB\Builders\Common\Sql\LeftJoin;
use MarwaDB\Builders\Common\Sql\RightJoin;
use MarwaDB\Builders\Common\BuilderInterface;
use MarwaDB\SqlTrait;
use MarwaDB\Builders\Common\WhereBuilder;

class CommonSelectBuilder implements BuilderInterface
{
    use SqlTrait,WhereBuilder;
    
    /**
     * Undocumented variable
     *
     * @var MarwaDB\Builders\Common\Sql\Select
     */
    protected $_select ;

    /**
     * Undocumented variable
     *
     * @var string
     */
    protected $__sqlString;
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $_innerJoin;
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $_rightJoin;
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $_leftJoin;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $_union;
  
    /**
     * Undocumented function
     *
     * @param string $cols
     */
    public function __construct($cols=[])
    {
        $this->_select = new Select();
        if (!empty($cols)) {
            $this->_select->addCols($cols);
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $name
     * @return void
     */
    public function table(string $name)
    {
        $this->_select->setFrom($name);
        return $this;
    }
    /**
     * Undocumented function
     *
     * @param array $cols
     * @return void
     */
    public function select($cols=[])
    {
        if (!empty($cols)) {
            $this->addSelect($cols);
        }
        return $this;
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function addSelect($columns)
    {
        if (empty($columns)) {
            throw new NotFoundException("Column name is empty", 1);
        }
        $this->_select->addCols($columns);
        return $this;
    }
    /**
     * Undocumented function
     *
     * @param string $column
     * @return void
     */
    public function count(string $column ="*")
    {
        if (!empty($column)) {
            $this->_select->addAggregateColumn("COUNT($column)");
        }
    }
    /**
     * Undocumented function
     *
     * @param string $column
     * @return void
     */
    public function avg(string $column)
    {
        if (!empty($column)) {
            $this->_select->addAggregateColumn("AVG($column)");
        }
    }
    /**
     * Undocumented function
     *
     * @param string $column
     * @return void
     */
    public function sum(string $column)
    {
        if (!empty($column)) {
            $this->_select->addAggregateColumn("SUM($column)");
        }
    }
    /**
     * Undocumented function
     *
     * @param string $column
     * @return void
     */
    public function min(string $column)
    {
        if (!empty($column)) {
            $this->_select->addAggregateColumn("MIN($column)");
        }
    }
    /**
     * Undocumented function
     *
     * @param string $column
     * @return void
     */
    public function max(string $column)
    {
        if (!empty($column)) {
            $this->_select->addAggregateColumn("MAX($column)");
        }
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function distinct()
    {
        $this->_select->addDistinct();
        return $this;
    }
    
    /**
     * Undocumented function
     *
     * @param integer $limit
     * @return void
     */
    public function limit(int $limit)
    {
        if ($limit >= 1) {
            $this->_select->setLimit(abs($limit));
        }
        return $this;
    }
    /**
     * Undocumented function
     *
     * @param integer $limit
     * @return void
     */
    public function take(int $limit)
    {
        return $this->limit($limit);
    }

    /**
     * Undocumented function
     *
     * @param integer $offset
     * @return void
     */
    public function offset(int $offset)
    {
        $this->_select->setOffset(abs($offset));
        return $this;
    }
    /**
     * Undocumented function
     *
     * @param integer $offset
     * @return void
     */
    public function skip(int $offset)
    {
        return $this->offset($offset);
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function latest()
    {
        return $this->orderBy('created_at', 'ASC');
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function oldest()
    {
        return $this->orderBy('created_at', 'DESC');
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function inRandomOrder()
    {
        return $this->orderBy('created_at', 'RAND');
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function first()
    {
        return $this->limit(1);
    }

    /**
     * function for orderby sql statement
     * @param   $colName description
     * @param   $sortBy description
     * */
    public function orderBy(string $colName, string $sortBy='ASC')
    {
        $sortArray=['ASC','DESC','RAND'];

        if (!in_array($sortBy, $sortArray)) {
            throw new Exception("Sort By Key is not valid");
        }
        
        if (strpos($colName, '.') !== false) {
            $this->_select->orderByRaw("{$colName} {$sortBy}");
        } else { //add the table name
            $this->_select->orderByRaw($this->_select->getFrom().".{$colName} {$sortBy}");
        }

        return $this;
    }
    /**
     * Undocumented function
     *
     * @param string $condition
     * @return void
     */
    public function having(string $condition)
    {
        $this->_select->havingRaw($condition);
        return $this;
    }

    /**
     * Group By string
     *
     * @param string $group
     * @return void
     */
    public function groupBy(string $group)
    {
        $this->_select->addGroupBy($group);
        return $this;
    }
    /**
     * Group By arraw
     *
     * @param [type] ...$groups
     * @return void
     */
    public function groupByRaw(...$groups)
    {
        foreach ($groups as $key => $value) {
            $this->_select->addGroupBy($value);
        }
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $joinTable
     * @param string $leftColumn
     * @param string $condition
     * @param string $rightColumn
     * @return self
     */
    public function join(string $joinTable, string $leftColumn, string $condition, string $rightColumn)
    {
        $this->_innerJoin = new InnerJoin();
        $this->_innerJoin->addJoin($joinTable, $leftColumn, $condition, $rightColumn);
        return $this;
    }
    /**
     * Undocumented function
     *
     * @param string $joinTable
     * @param string $leftColumn
     * @param string $condition
     * @param string $rightColumn
     * @return void
     */
    public function leftjoin(string $joinTable, string $leftColumn, string $condition, string $rightColumn)
    {
        $this->_leftJoin = new LeftJoin();
        $this->_leftJoin->addJoin($joinTable, $leftColumn, $condition, $rightColumn);
        return $this;
    }
    /**
     * Undocumented function
     *
     * @param string $joinTable
     * @param string $leftColumn
     * @param string $condition
     * @param string $rightColumn
     * @return void
     */
    public function rightjoin(string $joinTable, string $leftColumn, string $condition, string $rightColumn)
    {
        $this->_rightJoin = new RightJoin();
        $this->_rightJoin->addJoin($joinTable, $leftColumn, $condition, $rightColumn);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $unionSql
     * @return void
     */
    public function union(string $unionSql)
    {
        $this->_union = 'UNION '.$unionSql;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $unionSql
     * @return void
     */
    public function unionAll(string $unionSql)
    {
        $this->_union = 'UNION ALL '.$unionSql;
        return $this;
    }
   
   
    /**
     * Undocumented function
     *
     * @return void
     */
    public function formatSql()
    {
        $this->__sqlString =
            sprintf(
                '%s %s %s FROM %s',
                'SELECT',
                $this->_select->getDistinct(),
                $this->_select->getCols(),
                $this->_select->getFrom()
            );
        //if inner join called
        if (!empty($this->_innerJoin)) {
            $this->__sqlString .= ' '.$this->_innerJoin->getJoins();
        }
        //if left join called
        if (!empty($this->_leftJoin)) {
            $this->__sqlString .= ' '.$this->_leftJoin->getJoins();
        }
        //if rightjoin called
        if (!empty($this->_rightJoin)) {
            $this->__sqlString .= ' '.$this->_rightJoin->getJoins();
        }
        if (!empty($this->_where)) {
            $this->__sqlString .= ' '.$this->_where->getWhere();
        }
        $this->__sqlString .= ' '.$this->_select->getGroupBy();
        $this->__sqlString .= ' '.$this->_select->getHavingRaw();
        $this->__sqlString .= ' '.$this->_select->getOrderBy();
        $this->__sqlString .= ' '.$this->_select->getLimit();
        $this->__sqlString .= ' '.$this->_select->getOffset();

        if (isset($this->_union)) {
            $this->__sqlString.= ' '. $this->_union;
        }
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getSql()
    {
        if (is_null($this->__sqlString)) {
            throw new NotFoundException("Sql string null", 1);
        }
        return $this->removeWhiteSpace($this->__sqlString);
    }
    /**
     * Undocumented function
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getSql();
    }
}