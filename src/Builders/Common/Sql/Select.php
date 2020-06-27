<?php

	namespace MarwaDB\Builders\Common\Sql;
	
	use Exception;
	
	class Select {
		
		/**
		 * @var array
		 */
		protected $_from = [];
		/**
		 * @var array
		 */
		private $__sqlString;
		/**
		 * @var array
		 */
		private $__cols = [];
		/**
		 * @var array
		 */
		private $__aggregates = [];
		/**
		 * @var string
		 */
		private $__distinct;
		
		/**
		 * @var int
		 */
		private $__limit;
		/**
		 * @var int
		 */
		private $__offset;
		
		/**
		 * @var array
		 */
		private $_groupBy = [];
		/**
		 * @var string
		 */
		private $__havingSql;
		/**
		 * @var string
		 */
		private $__orderBy;
		
		/**
		 * Select constructor.
		 */
		public function __construct()
		{
		}
		
		/**
		 * @param $field
		 * @throws Exception
		 */
		public function addCols( $field )
		{
			if ( is_string($field) )
			{
				$fields = explode(',', $field);
			}
			else
			{
				$fields = $field;
			}
			foreach ( $fields as $key => $value )
			{
				$this->addCol($value);
			}
		}
		
		/**
		 * @param $field
		 * @throws Exception
		 */
		public function addCol( $field )
		{
			if ( empty($field) )
			{
				throw new Exception("Empty Column can not add", 1);
			}
			
			array_push($this->__cols, $field);
		}
		
		/**
		 * @param string $field
		 * @throws Exception
		 */
		public function addAggregateColumn( string $field )
		{
			if ( empty($field) )
			{
				throw new Exception("Aggregate Column can not add", 1);
			}
			array_push($this->__aggregates, $field);
		}
		
		/**
		 * @return string
		 * @throws InvalidTableException
		 */
		public function getCols()
		{
			if ( empty($this->__cols) && !empty($this->__aggregates) )
			{
				return implode(',', $this->getAggregateColumn());
			}
			
			if ( empty($this->__cols) && empty($this->__aggregates) )
			{
				return $this->getFrom() . '.*';
			}
			
			$columns = [];
			foreach ( $this->__cols as $key => $column )
			{
				//check if string contains "as" then donot add table name
				if ( strpos($column, '.') !== false )
				{
					$columns[ $key ] = $column;
				}
				else
				{ //add the table name and push to arary
					$columns[ $key ] = $this->getFrom() . "." . $column;
				}
			}
			
			if ( !empty($this->__aggregates) )
			{
				$columns = array_merge($columns, $this->getAggregateColumn());
			}
			
			return implode(',', $columns);
		}
		
		/**
		 * @return array
		 */
		public function getAggregateColumn()
		{
			return $this->__aggregates;
		}
		
		/**
		 * @return string
		 * @throws InvalidTableException
		 */
		public function getFrom()
		{
			if ( empty($this->_from) )
			{
				throw new InvalidTableException("Table name not assinged");
			}
			
			return implode(',', $this->_from);
		}
		
		/**
		 * @param string $name
		 * @throws InvalidTableException
		 */
		public function setFrom( string $name )
		{
			if ( empty($name) )
			{
				throw new InvalidTableException("Table name is empty");
			}
			else
			{
				array_push($this->_from, $name);
			}
		}
		
		/**
		 *
		 */
		public function addDistinct() : void
		{
			$this->__distinct = 'DISTINCT ';
		}
		
		/**
		 * @return string|null
		 */
		public function getDistinct() : ?string
		{
			return $this->__distinct;
		}
		
		/**
		 * @return string|null
		 */
		public function getOffset() : ?string
		{
			return $this->__offset;
		}
		
		/**
		 * @param int $offset
		 */
		public function setOffset( $offset = 0 ) : void
		{
			$this->__offset = "OFFSET {$offset}";
		}
		
		/**
		 * @return string|null
		 */
		public function getLimit() : ?string
		{
			return $this->__limit;
		}
		
		/**
		 * @param int $limit
		 */
		public function setLimit( $limit = 25 ) : void
		{
			$this->__limit = "LIMIT {$limit}";
		}
		
		/**
		 * @param string $columns
		 * @throws Exception
		 */
		public function orderByRaw( string $columns ) : void
		{
			//check if columns is null
			if ( empty($columns) )
			{
				throw new Exception("Order by parameter is empty");
			}
			
			$this->__orderBy = "ORDER BY {$columns}";
		}
		
		/**
		 * @return string|null
		 */
		public function getOrderBy() : ?string
		{
			return $this->__orderBy;
		}
		
		/**
		 * @param string $columns
		 * @throws Exception
		 */
		public function havingRaw( string $columns = "*" )
		{
			//check if columns is null
			if ( !is_string($columns) )
			{
				throw new Exception("Parameter must be string");
			}
			$this->__havingSql = sprintf('HAVING %s', trim($columns));
		}
		
		/**
		 * @return string|null
		 */
		public function getHavingRaw() : ?string
		{
			return $this->__havingSql;
		}
		
		/**
		 * @param string $group
		 * @throws InvalidTableException
		 */
		public function addGroupBy( string $group )
		{
			array_push($this->_groupBy, $this->getFrom() . '.' . $group);
		}
		
		/**
		 * @return string
		 */
		public function getGroupBy()
		{
			if ( !empty($this->_groupBy) )
			{
				return 'GROUP BY ' . implode(',', $this->_groupBy);
			}
		}
	}
