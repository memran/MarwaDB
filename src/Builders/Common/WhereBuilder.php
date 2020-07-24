<?php
	
	namespace MarwaDB\Builders\Common;
	
	use Exception;
	use MarwaDB\Builders\Common\Sql\Where;
	
	trait WhereBuilder {
		
		/**
		 * @var Where
		 */
		protected $_where;
		
		/**
		 * @param string $column
		 * @param string $condition
		 * @param mixed $value
		 * @throws Exception
		 */
		public function where( string $column, $condition, $value )
		{
			
			if ( isset($this->_where) )
			{
				$this->_where->addAndWhere($column, $condition, $value);
			}
			else
			{
				$this->_where = new Where();
				$this->_where->addWhere($column, $condition, $value);
			}
		}
		
		/**
		 * @param string $column
		 * @param string $condition
		 * @param mixed $value
		 * @throws Exception
		 */
		public function orWhere( string $column, $condition, $value )
		{
			if ( isset($this->_where) )
			{
				$this->_where->addOrWhere($column, $condition, $value);
			}
		}
		
		/**
		 * @param string $column
		 * @param string $condition
		 * @param mixed $value
		 * @throws Exception
		 */
		public function andWhere( string $column, $condition, $value )
		{
			if ( isset($this->_where) )
			{
				$this->_where->addAndWhere($column, $condition, $value);
			}
		}
		
		/**
		 * @param string $column
		 * @param string $condition
		 * @param mixed $value
		 * @throws Exception
		 */
		public function subOrWhere( string $column, $condition, $value )
		{
			if ( isset($this->_where) )
			{
				$this->_where->setSubWhereType('OR');
				$this->_where->addSubWhere($column, $condition, $value);
			}
		}
		
		/**
		 * @param string $column
		 * @param string $condition
		 * @param mixed $value
		 * @throws Exception
		 */
		public function subAndWhere( string $column, $condition, $value )
		{
			if ( isset($this->_where) )
			{
				$this->_where->setSubWhereType('AND');
				$this->_where->addSubWhere($column, $condition, $value);
			}
		}
		
		/**
		 * @param string $column
		 * @param mixed $value1
		 * @param mixed $value2
		 * @return mixed
		 */
		public function whereBetween( string $column, $value1, $value2 )
		{
			$this->_where = new Where();
			$this->_where->addWhereBetween($column, $value1, $value2);
			
			return $this;
		}
		
		/**
		 * @param string $column
		 * @param mixed $value1
		 * @param mixed $value2
		 * @return $this
		 */
		public function orWhereBetween( string $column, $value1, $value2 )
		{
			if ( isset($this->_where) )
			{
				$this->_where->addOrWhereBetween($column, $value1, $value2);
			}
			
			return $this;
		}
		
		/**
		 * @param string $column
		 * @param mixed $value1
		 * @param mixed $value2
		 * @return $this
		 */
		public function whereNotBetween( string $column, $value1, $value2 )
		{
			$this->_where = new Where();
			$this->_where->addWhereNotBetween($column, $value1, $value2);
			
			return $this;
		}
		
		/**
		 * @param string $column
		 * @param mixed $value1
		 * @param mixed $value2
		 * @return $this
		 */
		public function orWhereNotBetween( string $column, $value1, $value2 )
		{
			if ( isset($this->_where) )
			{
				$this->_where->addOrWhereNotBetween($column, $value1, $value2);
			}
			
			return $this;
		}
		
		/**
		 * @param string $column
		 * @param array $values
		 * @return $this
		 */
		public function whereIn( string $column, array $values )
		{
			$this->_where = new Where();
			$this->_where->addWhereIn($column, $values);
			
			return $this;
		}
		
		/**
		 * @param string $column
		 * @param array $values
		 * @return $this
		 */
		public function orWhereIn( string $column, array $values )
		{
			if ( isset($this->_where) )
			{
				$this->_where->addOrWhereIn($column, $values);
			}
			
			return $this;
		}
		
		/**
		 * @param string $column
		 * @param array $values
		 * @return $this
		 */
		public function whereNotIn( string $column, array $values )
		{
			$this->_where = new Where();
			$this->_where->addWhereNotIn($column, $values);
			
			return $this;
		}
		
		/**
		 * @param string $column
		 * @param array $values
		 * @return $this
		 */
		public function orWhereNotIn( string $column, array $values )
		{
			if ( isset($this->_where) )
			{
				$this->_where->addOrWhereNotIn($column, $values);
			}
			
			return $this;
		}
		
		/**
		 * @param string $column
		 * @return $this
		 */
		public function whereNull( string $column )
		{
			$this->_where = new Where();
			$this->_where->addWhereNull($column);
			
			return $this;
		}
		
		/**
		 * @param string $column
		 * @return $this
		 */
		public function whereNotNull( string $column )
		{
			$this->_where = new Where();
			$this->_where->addWhereNotNull($column);
			
			return $this;
		}
		
		/**
		 * @param string $column
		 * @return $this
		 */
		public function OrWhereNull( string $column )
		{
			if ( isset($this->_where) )
			{
				$this->_where->addOrWhereNull($column);
			}
			
			return $this;
		}
		
		/**
		 * @param string $column
		 * @return $this
		 */
		public function OrWhereNotNull( string $column )
		{
			if ( isset($this->_where) )
			{
				$this->_where->addOrWhereNotNull($column);
			}
			
			return $this;
		}
		
		/**
		 * @param string $column
		 * @param string $value
		 * @return $this
		 */
		public function whereDate( string $column, $value )
		{
			$this->_where = new Where();
			$this->_where->addWhereDate($column, $value);
			
			return $this;
		}
		
		/**
		 * @param string $column
		 * @param string $value
		 * @return $this
		 */
		public function whereDay( string $column, $value )
		{
			$this->_where = new Where();
			$this->_where->addWhereDay($column, $value);
			
			return $this;
		}
		
		/**
		 * @param string $column
		 * @param string $value
		 * @return $this
		 */
		public function whereYear( string $column, $value )
		{
			$this->_where = new Where();
			$this->_where->addWhereYear($column, $value);
			
			return $this;
		}
		
		/**
		 * @param string $column
		 * @param string $value
		 * @return $this
		 */
		public function whereMonth( string $column, $value )
		{
			$this->_where = new Where();
			$this->_where->addWhereMonth($column, $value);
			
			return $this;
		}
		
		/**
		 * @param string $column
		 * @param string $value
		 * @return $this
		 */
		public function whereTime( string $column, $value )
		{
			$this->_where = new Where();
			$this->_where->addWhereTime($column, $value);
			
			return $this;
		}
		
		/**
		 * @param callable $callable_value
		 * @return string
		 */
		public function whereExists( $callable_value )
		{
			if ( is_callable($callable_value) )
			{
				$whereBuilder = new Self;
				$sql = $callable_value($whereBuilder);
				
				return "( {$sql} )";
			}
		}
	}
