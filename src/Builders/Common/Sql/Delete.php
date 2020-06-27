<?php
	
	
	namespace MarwaDB\Builders\Common\Sql;
	
	class Delete {
		
		/**
		 * @var string
		 */
		protected $_table;
		
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
	}
