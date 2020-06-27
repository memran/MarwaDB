<?php
	
	namespace MarwaDB\Builders\Common;
	
	use MarwaDB\Builders\Common\Sql\Update;
	use MarwaDB\Exceptions\ArrayNotFoundException;
	use MarwaDB\SqlTrait;
	
	class CommonUpdateBuilder implements BuilderInterface {
		
		use SqlTrait;
		use WhereBuilder;
		
		/**
		 * @var Update
		 */
		protected $_update;
		/**
		 * @var string
		 */
		private $__sqlString;
		
		/**
		 * CommonUpdateBuilder constructor.
		 */
		public function __construct()
		{
			$this->_update = new Update();
		}
		
		/**
		 * @param string $name
		 */
		public function table( string $name )
		{
			$this->_update->setTable($name);
		}
		
		/**
		 * @param array $data
		 * @throws ArrayNotFoundException
		 */
		public function setData( array $data )
		{
			$this->_update->addData($data);
		}
		
		/**
		 *
		 */
		public function formatSql()
		{
			$this->__sqlString = 'UPDATE ' . $this->_update->getTable() . ' SET ';
			$this->__sqlString .= $this->_update->getColumnValues();
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
