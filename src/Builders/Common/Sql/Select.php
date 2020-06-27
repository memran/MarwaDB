<?php
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2018
 *
 * @see https://www.github.com/memran
 * @see http://www.memran.me
 **/
namespace MarwaDB\Builders\Common\Sql;

use MarwaDB\Builders\Common\Sql\InvalidTableException;
use Exception;

class Select
{

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $_from=[];
    /**
     * Undocumented variable
     *
     * @var string
     */
    private $__sqlString;
    /**
     * Undocumented variable
     *
     * @var array
     */
    private $__cols=[];
    /**
     * Undocumented variable
     *
     * @var array
     */
    private $__aggregates=[];
    /**
     * Undocumented variable
     *
     * @var string
     */
    private $__distinct;

    /**
     * Undocumented variable
     *
     * @var int
     */
    private $__limit;
    /**
     * Undocumented variable
     *
     * @var int
     */
    private $__offset;

    /**
     * Undocumented variable
     *
     * @var array
     */
    private $_groupBy=[];
    /**
     * Undocumented variable
     *
     * @var string
     */
    private $__havingSql;
    /**
     * Undocumented variable
     *
     * @var string
     */
    private $__orderBy;

    /**
     * Undocumented function
     */
    public function __construct()
    {
    }

    /**
     * Undocumented function
     *
     * @param  string $name
     * @return void
     */
    public function setFrom(string $name)
    {
        if (empty($name)) {
            throw new InvalidTableException("Table name is empty");
        } else {
            array_push($this->_from, $name);
        }
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getFrom()
    {
        if (empty($this->_from)) {
            throw new InvalidTableException("Table name not assinged");
        }
        return implode(',', $this->_from);
    }

    /**
     * Undocumented function
     *
     * @param  [type] $field
     * @return void
     */
    public function addCol($field)
    {
        if (empty($field)) {
            throw new Exception("Empty Column can not add", 1);
        }
            
        array_push($this->__cols, $field);
    }

    /**
     * Undocumented function
     *
     * @param  string $field
     * @return void
     */
    public function addCols($field)
    {
        if (is_string($field)) {
            $fields = explode(',', $field);
        } else {
            $fields = $field;
        }
        foreach ($fields as $key => $value) {
            $this->addCol($value);
        }
    }

    public function addAggregateColumn(string $field)
    {
        if (empty($field)) {
            throw new Exception("Aggregate Column can not add", 1);
        }
        array_push($this->__aggregates, $field);
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getAggregateColumn()
    {
        return $this->__aggregates;
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getCols()
    {
        if (empty($this->__cols) && !empty($this->__aggregates)) {
            return implode(',', $this->getAggregateColumn());
        }
        
        if (empty($this->__cols) && empty($this->__aggregates)) {
            return $this->getFrom().'.*';
        }
        
        $columns =[];
        foreach ($this->__cols as $key => $column) {
            //check if string contains "as" then donot add table name
            if (strpos($column, '.') !== false) {
                $columns[$key] = $column;
            } else { //add the table name and push to arary
                $columns[$key] = $this->getFrom().".".$column;
            }
        }

        if (!empty($this->__aggregates)) {
            $columns = array_merge($columns, $this->getAggregateColumn());
        }
        return implode(',', $columns);
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function addDistinct() : void
    {
        $this->__distinct = 'DISTINCT ';
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getDistinct() : ?string
    {
        return $this->__distinct;
    }

    /**
     * function for offset
     *
     * @param  $offset description
     * @return $this description
     * */
    public function setOffset($offset=0) : void
    {
        $this->__offset = "OFFSET {$offset}";
    }

    /**
     * function for limit sql data
     *
     * @param  $limit description
     * @return $this description
     * */
    public function setLimit($limit=25) : void
    {
        $this->__limit = "LIMIT {$limit}";
    }

    /**
     * Undocumented function
     *
     * @return string|null
     */
    public function getOffset() : ?string
    {
        return $this->__offset;
    }
    /**
     * Undocumented function
     *
     * @return string|null
     */
    public function getLimit() : ?string
    {
        return $this->__limit;
    }
    
    /**
     * function for orderByRaw
     *
     * @param  string $columns description
     * @return $this description
     * */
    public function orderByRaw(string $columns) : void
    {
        //check if columns is null
        if (empty($columns)) {
            throw new Exception("Order by parameter is empty");
        }
        
        $this->__orderBy = "ORDER BY {$columns}";
    }
    /**
     * Undocumented function
     *
     * @return string
     */
    public function getOrderBy() : ?string
    {
        return $this->__orderBy;
    }

    /**
     * function for havingRaw for group condition
     *
     * @param  string $columns description
     * @return $this description
     * */
    public function havingRaw(string $columns="*")
    {
        //check if columns is null
        if (!is_string($columns)) {
            throw new Exception("Parameter must be string");
        }
        $this->__havingSql = sprintf('HAVING %s', trim($columns));
    }
    /**
     * Undocumented function
     *
     * @return string|null
     */
    public function getHavingRaw() : ?string
    {
        return $this->__havingSql;
    }

    /**
     * Undocumented function
     *
     * @param  string $group
     * @return void
     */
    public function addGroupBy(string $group)
    {
        array_push($this->_groupBy, $this->getFrom().'.'.$group);
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getGroupBy()
    {
        if (!empty($this->_groupBy)) {
            return 'GROUP BY '.implode(',', $this->_groupBy);
        }
    }
}
