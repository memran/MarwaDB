<?php
	
	namespace MarwaDB\Builders\Common\Sql;
	
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
		 * @param $column
		 * @param $condition
		 * @param $value
		 */
		public function addWhere( $column, $condition, $value )
		{
			$this->_whereSql = "WHERE {$column} {$condition} '{$value}'";
		}
		
		/**
		 * @param $column
		 * @param $condition
		 * @param $value
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
		 * @param $column
		 * @param $condition
		 * @param $value
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
		 * @param string $column
		 * @param string $condition
		 * @param $value
		 */
		public function addSubWhere( string $column, string $condition, $value )
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
		 * @param $column
		 * @param $value1
		 * @param $value2
		 */
		public function addWhereBetween( $column, $value1, $value2 )
		{
			//WHERE column_name BETWEEN value1 AND value2;
			$this->_whereSql = "WHERE {$column} BETWEEN '{$value1}' AND '{$value2}'";
		}
		
		/**
		 * @param $column
		 * @param $value1
		 * @param $value2
		 */
		public function addOrWhereBetween( $column, $value1, $value2 )
		{
			//OR column_name BETWEEN value1 AND value2;
			$this->_whereSql .= " OR {$column} BETWEEN '{$value1}' AND '{$value2}'";
		}
		
		/**
		 * @param $column
		 * @param $value1
		 * @param $value2
		 */
		public function addWhereNotBetween( $column, $value1, $value2 )
		{
			//WHERE column_name NOT BETWEEN value1 AND value2;
			$this->_whereSql = "WHERE {$column} NOT BETWEEN '{$value1}' AND '{$value2}'";
		}
		
		/**
		 * @param $column
		 * @param $value1
		 * @param $value2
		 */
		public function addOrWhereNotBetween( $column, $value1, $value2 )
		{
			//OR column_name NOT BETWEEN value1 AND value2;
			$this->_whereSql .= " OR {$column} NOT BETWEEN '{$value1}' AND '{$value2}'";
		}
		
		/**
		 * @param $column
		 * @param $values
		 */
		public function addWhereIn( $column, $values )
		{
			$datas = implode(',', $values);
			//WHERE column_name IN (value1,value2);
			$this->_whereSql = "WHERE {$column} IN ({$datas})";
		}
		
		/**
		 * @param $column
		 * @param $values
		 */
		public function addWhereNotIn( $column, $values )
		{
			$datas = implode(',', $values);
			//WHERE column_name IN (value1,value2);
			$this->_whereSql = "WHERE {$column} NOT IN ({$datas})";
		}
		
		/**
		 * @param $column
		 * @param $values
		 */
		public function addOrWhereIn( $column, $values )
		{
			$datas = implode(',', $values);
			$this->_whereSql .= " OR {$column} IN ({$datas})";
		}
		
		/**
		 * @param $column
		 * @param $values
		 */
		public function addOrWhereNotIn( $column, $values )
		{
			$datas = implode(',', $values);
			//WHERE column_name IN (value1,value2);
			$this->_whereSql .= " OR {$column} NOT IN ({$datas})";
		}
		
		/**
		 * @param $column
		 */
		public function addWhereNull( $column )
		{
			//WHERE column_name IS NULL;
			$this->_whereSql = "WHERE {$column} IS NULL";
		}
		
		/**
		 * @param $column
		 */
		public function addWhereNotNull( $column )
		{
			//WHERE column_name IS NULL;
			$this->_whereSql = "WHERE {$column} IS NOT NULL";
		}
		
		/**
		 * @param $column
		 */
		public function addOrWhereNull( $column )
		{
			//OR column_name IS NULL;
			$this->_whereSql .= " OR {$column} IS NULL";
		}
		
		/**
		 * @param $column
		 */
		public function addOrWhereNotNull( $column )
		{
			//OR column_name IS NULL;
			$this->_whereSql .= " OR {$column} IS NOT NULL";
		}
		
		/**
		 * @param string $column
		 * @param $value
		 */
		public function addWhereDate( string $column, $value )
		{
			$this->_whereSql = "WHERE DATE({$column}) = '{$value}'";
		}
		
		/**
		 * @param string $column
		 * @param $value
		 */
		public function addWhereDay( string $column, $value )
		{
			$this->_whereSql = "WHERE DAY({$column}) = '{$value}'";
		}
		
		/**
		 * @param string $column
		 * @param $value
		 */
		public function addWhereYear( string $column, $value )
		{
			$this->_whereSql = "WHERE YEAR({$column}) = '{$value}'";
		}
		
		/**
		 * @param string $column
		 * @param $value
		 */
		public function addWhereMonth( string $column, $value )
		{
			$this->_whereSql = "WHERE MONTH({$column}) = '{$value}'";
		}
		
		/**
		 * @param string $column
		 * @param $value
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
