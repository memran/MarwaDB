<?php
	
	namespace MarwaDB\Builders\Common\Sql;
	
	use MarwaDB\Exceptions\ArrayNotFoundException;
	
	class Update {
		
		/**
		 * @var string
		 */
		protected $_table;
		
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
		 * @throws ArrayNotFoundException
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
		 * @throws ArrayNotFoundException
		 */
		protected function addColumnValue( array $data )
		{
			if ( empty($data) )
			{
				throw new ArrayNotFoundException("Empty data to insert", 1);
			}
			
			foreach ( $data as $key => $value )
			{
				array_push($this->_values, "{$key} = '{$value}'");
			}
		}
		
		/**
		 * @return string
		 */
		public function getColumnValues()
		{
			if ( !empty($this->_values) )
			{
				return implode(',', $this->_values);
			}
		}
	}
