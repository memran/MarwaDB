<?php
	/**
	 * @author    Mohammad Emran <memran.dhk@gmail.com>
	 * @copyright 2018
	 *
	 * @see https://www.github.com/memran
	 * @see http://www.memran.me
	 **/
	
	namespace MarwaDB\Builders\Common;
	
	use Exception;
	use MarwaDB\Builders\Common\Sql\InnerJoin;
	use MarwaDB\Builders\Common\Sql\LeftJoin;
	use MarwaDB\Builders\Common\Sql\RightJoin;
	use MarwaDB\Builders\Common\Sql\Select;
	use MarwaDB\Exceptions\NotFoundException;
	use MarwaDB\SqlTrait;
	
	class CommonSelectBuilder implements BuilderInterface {
		
		use SqlTrait, WhereBuilder;
		
		/**
		 * @var Select
		 */
		protected $_select;
		
		/**
		 * @var string
		 */
		protected $__sqlString;
		/**
		 * @var InnerJoin
		 */
		protected $_innerJoin;
		/**
		 * @var RightJoin
		 */
		protected $_rightJoin;
		
		/**
		 * @var LeftJoin
		 */
		protected $_leftJoin;
		
		/**
		 * @var string
		 */
		protected $_union;
		
		/**
		 * CommonSelectBuilder constructor.
		 * @param array $cols
		 * @throws Exception
		 */
		public function __construct( $cols = [] )
		{
			$this->_select = new Select();
			if ( !empty($cols) )
			{
				$this->_select->addCols($cols);
			}
		}
		
		/**
		 * @param string $name
		 * @return $this
		 * @throws Sql\InvalidTableException
		 */
		public function table( string $name )
		{
			$this->_select->setFrom($name);
			
			return $this;
		}
		
		/**
		 * @param array $cols
		 * @return $this
		 * @throws NotFoundException
		 */
		public function select( $cols = [] )
		{
			if ( !empty($cols) )
			{
				$this->addSelect($cols);
			}
			
			return $this;
		}
		
		/**
		 * @param string|array $columns
		 * @return $this
		 * @throws NotFoundException
		 * @throws Exception
		 */
		public function addSelect( $columns )
		{
			if ( empty($columns) )
			{
				throw new NotFoundException("Column name is empty", 1);
			}
			$this->_select->addCols($columns);
			
			return $this;
		}
		
		/**
		 * @param string $column
		 * @param string $alias
		 * @throws Exception
		 */
		public function count( string $column = "*" , string $alias='total')
		{
			if ( !empty($column) )
			{
				$this->_select->addAggregateColumn("COUNT($column) AS {$alias}");
			}
		}
		
		/**
		 * @param string $column
		 * @throws Exception
		 */
		public function avg( string $column )
		{
			if ( !empty($column) )
			{
				$this->_select->addAggregateColumn("AVG($column)");
			}
		}
		
		/**
		 * @param string $column
		 * @throws Exception
		 */
		public function sum( string $column )
		{
			if ( !empty($column) )
			{
				$this->_select->addAggregateColumn("SUM($column)");
			}
		}
		
		/**
		 * @param string $column
		 * @throws Exception
		 */
		public function min( string $column )
		{
			if ( !empty($column) )
			{
				$this->_select->addAggregateColumn("MIN($column)");
			}
		}
		
		/**
		 * @param string $column
		 * @throws Exception
		 */
		public function max( string $column )
		{
			if ( !empty($column) )
			{
				$this->_select->addAggregateColumn("MAX($column)");
			}
		}
		
		/**
		 * @return $this
		 */
		public function distinct()
		{
			$this->_select->addDistinct();
			
			return $this;
		}
		
		/**
		 * @param int $limit
		 * @return $this|void
		 */
		public function take( int $limit )
		{
			return $this->limit($limit);
		}
		
		/**
		 * @param int $limit
		 * @return $this
		 */
		public function limit( int $limit )
		{
			if ( $limit >= 1 )
			{
				$this->_select->setLimit(abs($limit));
			}
			
			return $this;
		}
		
		/**
		 * @param array $data
		 */
		public function setData( array $data )
		{
			// TODO: Implement setData() method.
		}
		
		/**
		 * @param int $offset
		 * @return $this|void
		 */
		public function skip( int $offset )
		{
			return $this->offset($offset);
		}
		
		/**
		 * @param int $offset
		 * @return $this
		 */
		public function offset( int $offset )
		{
			$this->_select->setOffset(abs($offset));
			
			return $this;
		}
		
		/**
		 * @return $this
		 * @throws Exception
		 */
		public function latest()
		{
			return $this->orderBy('created_at', 'ASC');
		}
		
		/**
		 * @param string $colName
		 * @param string $sortBy
		 * @return $this
		 * @throws Sql\InvalidTableException
		 */
		public function orderBy( string $colName, string $sortBy = 'ASC' )
		{
			$sortArray = ['ASC', 'DESC', 'RAND'];
			
			if ( !in_array($sortBy, $sortArray) )
			{
				throw new Exception("Sort By Key is not valid");
			}
			
			if ( strpos($colName, '.') !== false )
			{
				$this->_select->orderByRaw("{$colName} {$sortBy}");
			}
			else
			{ //add the table name
				$this->_select->orderByRaw($this->_select->getFrom() . ".{$colName} {$sortBy}");
			}
			
			return $this;
		}
		
		/**
		 * @return $this
		 * @throws Sql\InvalidTableException
		 */
		public function oldest()
		{
			return $this->orderBy('created_at', 'DESC');
		}
		
		/**
		 * @return $this
		 * @throws Sql\InvalidTableException
		 */
		public function inRandomOrder()
		{
			return $this->orderBy('created_at', 'RAND');
		}
		
		/**
		 * @return $this
		 */
		public function first()
		{
			return $this->limit(1);
		}
		
		/**
		 * @param string $condition
		 * @return $this
		 * @throws Exception
		 */
		public function having( string $condition )
		{
			$this->_select->havingRaw($condition);
			
			return $this;
		}
		
		/**
		 * @param string $group
		 * @return $this
		 * @throws Sql\InvalidTableException
		 */
		public function groupBy( string $group )
		{
			$this->_select->addGroupBy($group);
			
			return $this;
		}
		
		/**
		 * @param mixed ...$groups
		 * @return $this
		 * @throws Sql\InvalidTableException
		 */
		public function groupByRaw( ...$groups )
		{
			foreach ( $groups as $key => $value )
			{
				$this->_select->addGroupBy($value);
			}
			
			return $this;
		}
		
		/**
		 * @param string $joinTable
		 * @param string $leftColumn
		 * @param string $condition
		 * @param string $rightColumn
		 * @return $this
		 */
		public function join( string $joinTable, string $leftColumn, string $condition, string $rightColumn )
		{
			$this->_innerJoin = new InnerJoin();
			$this->_innerJoin->addJoin($joinTable, $leftColumn, $condition, $rightColumn);
			
			return $this;
		}
		
		/**
		 * @param string $joinTable
		 * @param string $leftColumn
		 * @param string $condition
		 * @param string $rightColumn
		 * @return $this
		 */
		public function leftjoin( string $joinTable, string $leftColumn, string $condition, string $rightColumn )
		{
			$this->_leftJoin = new LeftJoin();
			$this->_leftJoin->addJoin($joinTable, $leftColumn, $condition, $rightColumn);
			
			return $this;
		}
		
		/**
		 * @param string $joinTable
		 * @param string $leftColumn
		 * @param string $condition
		 * @param string $rightColumn
		 * @return $this
		 */
		public function rightjoin( string $joinTable, string $leftColumn, string $condition, string $rightColumn )
		{
			$this->_rightJoin = new RightJoin();
			$this->_rightJoin->addJoin($joinTable, $leftColumn, $condition, $rightColumn);
			
			return $this;
		}
		
		/**
		 * @param string $unionSql
		 * @return $this
		 */
		public function union( string $unionSql )
		{
			$this->_union = 'UNION ' . $unionSql;
			
			return $this;
		}
		
		/**
		 * @param string $unionSql
		 * @return $this
		 */
		public function unionAll( string $unionSql )
		{
			$this->_union = 'UNION ALL ' . $unionSql;
			
			return $this;
		}

		/**
		 * @throws Sql\InvalidTableException
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
			if ( !empty($this->_innerJoin) )
			{
				$this->__sqlString .= ' ' . $this->_innerJoin->getJoins();
			}
			//if left join called
			if ( !empty($this->_leftJoin) )
			{
				$this->__sqlString .= ' ' . $this->_leftJoin->getJoins();
			}
			//if rightjoin called
			if ( !empty($this->_rightJoin) )
			{
				$this->__sqlString .= ' ' . $this->_rightJoin->getJoins();
			}
			if ( !empty($this->_where) )
			{
				$this->__sqlString .= ' ' . $this->_where->getWhere();
			}
			$this->__sqlString .= ' ' . $this->_select->getGroupBy();
			$this->__sqlString .= ' ' . $this->_select->getHavingRaw();
			$this->__sqlString .= ' ' . $this->_select->getOrderBy();
			$this->__sqlString .= ' ' . $this->_select->getLimit();
			$this->__sqlString .= ' ' . $this->_select->getOffset();
			
			if ( isset($this->_union) )
			{
				$this->__sqlString .= ' ' . $this->_union;
			}
		}
		
		/**
		 * @throws NotFoundException
		 */
		public function __toString()
		{
			return $this->getSql();
		}
		
		/**
		 * @return string
		 * @throws NotFoundException
		 */
		public function getSql()
		{
			if ( !isset($this->__sqlString) )
			{
				throw new NotFoundException("Sql string null", 1);
			}
			
			return $this->removeWhiteSpace($this->__sqlString);
		}
	}
