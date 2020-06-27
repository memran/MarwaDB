<?php
	
	
	namespace MarwaDB\Builders\Common\Sql;
	
	class InnerJoin {
		
		/**
		 * @var array
		 */
		protected $_joins = [];
		
		/**
		 * @param string $joinTable
		 * @param string $leftColumn
		 * @param string $condition
		 * @param string $rightColumn
		 */
		public function addJoin( string $joinTable, string $leftColumn, string $condition, string $rightColumn ) : void
		{
			$inner = "INNER JOIN {$joinTable} ON {$leftColumn} {$condition} {$rightColumn}";
			array_push($this->_joins, $inner);
		}
		
		/**
		 * @return string
		 */
		public function getJoins() : string
		{
			if ( !empty($this->_joins) )
			{
				return implode(' ', $this->_joins);
			}
		}
	}
