<?php
	
	namespace MarwaDB\Builders\Common;
	
	use MarwaDB\Builders\Common\Sql\Delete;
	use MarwaDB\SqlTrait;
	
	class CommonDeleteBuilder implements BuilderInterface {
		
		use SqlTrait;
		use WhereBuilder;
		
		/**
		 * @var Delete
		 */
		protected $_delete;
		/**
		 * @var string
		 */
		private $__sqlString;
		
		/**
		 * CommonDeleteBuilder constructor.
		 */
		public function __construct()
		{
			$this->_delete = new Delete();
		}
		
		/**
		 * @param string $name
		 */
		public function table( string $name )
		{
			$this->_delete->setTable($name);
		}
		
		public function setData( array $data )
		{
			// TODO: Implement setData() method.
		}
		
		/**
		 *
		 */
		public function formatSql()
		{
			$this->__sqlString = 'DELETE FROM ' . $this->_delete->getTable();
			if ( !empty($this->_where) )
			{
				$this->__sqlString .= ' ' . $this->_where->getWhere();
			}
		}
		
		/**
		 * @return string
		 */
		public function getSql()
		{
			return $this->removeWhiteSpace($this->__sqlString);
		}
	}
