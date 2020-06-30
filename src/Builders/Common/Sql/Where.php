<?php
	
	namespace MarwaDB\Builders\Common\Sql;
	
	use Exception;
	
	class Where {
		
		/**
		 * @var string
		 */
		protected $_whereSql;
		/**
		 * @var string
		 */
		protected $_subWhereSql;
		/**
		 * @var string
		 */
		protected $_subWhereType;
		
		/**
		 * @param mixed $column
		 * @param string $condition
		 * @param mixed $value
		 */
		public function addWhere( $column, $condition, $value )
		{
			$this->_whereSql = "WHERE {$column} {$condition} '{$value}'";
		}
		
		/**
		 * @param mixed $column
		 * @param string $condition
		 * @param mixed $value
		 * @throws Exception
		 */
		public function addOrWhere( $column, $condition, $value )
		{
			if ( isset($this->_whereSql) )
			{
				$this->_whereSql .= " OR {$column} {$condition} '{$value}'";
			}
			else
			{
				throw new Exception("where method not called");
			}
		}
		
		/**
		 * @param mixed $column
		 * @param string $condition
		 * @param mixed $value
		 * @throws Exception
		 */
		public function addAndWhere( $column, $condition, $value )
		{
			if ( isset($this->_whereSql) )
			{
				$this->_whereSql .= " AND {$column} {$condition} '{$value}'";
			}
			else
			{
				throw new Exception("where method not called");
			}
		}
		
		/**
		 * @param mixed $column
		 * @param string $condition
		 * @param mixed $value
		 * @throws Exception
		 */
		public function addSubWhere(  $column,  $condition, $value )
		{
			if ( isset($this->_whereSql) )
			{
				$this->_subWhereSql .= " {$column} {$condition} '{$value}'";
			}
			else
			{
				throw new Exception("where method not called");
			}
		}
		
		/**
		 * @param mixed $column
		 * @param mixed $value1
		 * @param mixed $value2
		 */
		public function addWhereBetween( $column, $value1, $value2 )
		{
			//WHERE column_name BETWEEN value1 AND value2;
			$this->_whereSql = "WHERE {$column} BETWEEN '{$value1}' AND '{$value2}'";
		}
		
		/**
		 * @param mixed $column
		 * @param mixed $value1
		 * @param mixed $value2
		 */
		public function addOrWhereBetween( $column, $value1, $value2 )
		{
			//OR column_name BETWEEN value1 AND value2;
			$this->_whereSql .= " OR {$column} BETWEEN '{$value1}' AND '{$value2}'";
		}
		
		/**
		 * @param mixed $column
		 * @param mixed $value1
		 * @param mixed $value2
		 */
		public function addWhereNotBetween( $column, $value1, $value2 )
		{
			//WHERE column_name NOT BETWEEN value1 AND value2;
			$this->_whereSql = "WHERE {$column} NOT BETWEEN '{$value1}' AND '{$value2}'";
		}
		
		/**
		 * @param mixed $column
		 * @param mixed $value1
		 * @param mixed $value2
		 */
		public function addOrWhereNotBetween( $column, $value1, $value2 )
		{
			//OR column_name NOT BETWEEN value1 AND value2;
			$this->_whereSql .= " OR {$column} NOT BETWEEN '{$value1}' AND '{$value2}'";
		}
		
		/**
		 * @param mixed $column
		 * @param array $values
		 */
		public function addWhereIn( $column, $values )
		{
			$data = implode(',', $values);
			//WHERE column_name IN (value1,value2);
			$this->_whereSql = "WHERE {$column} IN ({$data})";
		}
		
		/**
		 * @param mixed $column
		 * @param array $values
		 */
		public function addWhereNotIn( $column, array $values )
		{
			$data = implode(',', $values);
			//WHERE column_name IN (value1,value2);
			$this->_whereSql = "WHERE {$column} NOT IN ({$data})";
		}
		
		/**
		 * @param mixed $column
		 * @param array $values
		 */
		public function addOrWhereIn( $column, array $values )
		{
			$data = implode(',', $values);
			$this->_whereSql .= " OR {$column} IN ({$data})";
		}
		
		/**
		 * @param mixed $column
		 * @param array $values
		 */
		public function addOrWhereNotIn( $column, array $values )
		{
			$data = implode(',', $values);
			//WHERE column_name IN (value1,value2);
			$this->_whereSql .= " OR {$column} NOT IN ({$data})";
		}
		
		/**
		 * @param mixed $column
		 */
		public function addWhereNull( $column )
		{
			//WHERE column_name IS NULL;
			$this->_whereSql = "WHERE {$column} IS NULL";
		}
		
		/**
		 * @param mixed $column
		 */
		public function addWhereNotNull( $column )
		{
			//WHERE column_name IS NULL;
			$this->_whereSql = "WHERE {$column} IS NOT NULL";
		}
		
		/**
		 * @param mixed $column
		 */
		public function addOrWhereNull( $column )
		{
			//OR column_name IS NULL;
			$this->_whereSql .= " OR {$column} IS NULL";
		}
		
		/**
		 * @param mixed $column
		 */
		public function addOrWhereNotNull( $column )
		{
			//OR column_name IS NULL;
			$this->_whereSql .= " OR {$column} IS NOT NULL";
		}
		
		/**
		 * @param string $column
		 * @param string $value
		 */
		public function addWhereDate( string $column, $value )
		{
			$this->_whereSql = "WHERE DATE({$column}) = '{$value}'";
		}
		
		/**
		 * @param string $column
		 * @param string $value
		 */
		public function addWhereDay( string $column, $value )
		{
			$this->_whereSql = "WHERE DAY({$column}) = '{$value}'";
		}
		
		/**
		 * @param string $column
		 * @param string $value
		 */
		public function addWhereYear( string $column, $value )
		{
			$this->_whereSql = "WHERE YEAR({$column}) = '{$value}'";
		}
		
		/**
		 * @param string $column
		 * @param string $value
		 */
		public function addWhereMonth( string $column, $value )
		{
			$this->_whereSql = "WHERE MONTH({$column}) = '{$value}'";
		}
		
		/**
		 * @param string $column
		 * @param string $value
		 */
		public function addWhereTime( string $column, $value )
		{
			$this->_whereSql = "WHERE TIME({$column}) = '{$value}'";
		}
		
		/**
		 * @return string
		 */
		public function getWhere()
		{
			return $this->_whereSql . ' ' . $this->getSubWhere();
		}
		
		/**
		 * @return string
		 */
		public function getSubWhere()
		{
			if ( isset($this->_subWhereSql) )
			{
				return $this->getSubWhereType() . ' (' . $this->_subWhereSql . ')';
			}
		}
		
		/**
		 * @return mixed
		 */
		protected function getSubWhereType()
		{
			return $this->_subWhereType;
		}
		
		/**
		 * @param string $type
		 */
		public function setSubWhereType( string $type )
		{
			$this->_subWhereType = $type;
		}
	}
