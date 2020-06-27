<?php
	
	namespace MarwaDB\Builders\Common\Sql;
	
	class Insert {
		
		/**
		 * @var string
		 */
		protected $_table;
		/**
		 * @var array
		 */
		protected $_columns = [];
		/**
		 * @var array
		 */
		protected $_values = [];
		
		/**
		 * @return string
		 */
		public function getTable()
		{
			return $this->_table;
		}
		
		/**
		 * @param string $name
		 */
		public function setTable( string $name )
		{
			$this->_table = $name;
		}
		
		/**
		 * @param array $data
		 * @throws InvalidTableException
		 */
		public function addData( array $data ) : void
		{
			if ( is_array($data) )
			{
				$this->addColumnValue($data);
			}
		}
		
		/**
		 * @param array $data
		 * @throws InvalidTableException
		 */
		protected function addColumnValue( array $data )
		{
			if ( empty($data) )
			{
				throw new InvalidTableException("Empty data to insert", 1);
			}
			
			foreach ( $data as $key => $value )
			{
				array_push($this->_columns, $key);
				array_push($this->_values, "'{$value}'");
			}
		}
		
		/**
		 * @return string
		 */
		public function getCols()
		{
			if ( !empty($this->_columns) )
			{
				return implode(',', $this->_columns);
			}
			
			return '';
		}
		
		/**
		 * @return string
		 */
		public function getValues()
		{
			if ( !empty($this->_values) )
			{
				return implode(',', $this->_values);
			}
			
			return '';
		}
	}
