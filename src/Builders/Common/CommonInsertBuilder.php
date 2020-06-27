<?php
	
	
	namespace MarwaDB\Builders\Common;
	
	use MarwaDB\Builders\Common\Sql\Insert;
	use MarwaDB\Builders\Common\Sql\Update;
	use MarwaDB\Exceptions\ArrayNotFoundException;
	use MarwaDB\SqlTrait;
	
	class CommonInsertBuilder implements BuilderInterface {
		
		use SqlTrait;
		
		/**
		 * @var string
		 */
		protected $__sqlString;
		/**
		 * @var Insert
		 */
		protected $_insert;
		/**
		 * @var Update
		 */
		protected $_update;
		/**
		 * @var array
		 */
		protected $_data = [];
		
		/**
		 * CommonInsertBuilder constructor.
		 * @param array $data
		 */
		public function __construct( array $data = [] )
		{
			$this->_insert = new Insert();
			
			if ( !empty($data) )
			{
				$this->_data = $data;
			}
		}
		
		/**
		 * @param string $name
		 */
		public function table( string $name )
		{
			$this->_insert->setTable($name);
		}
		
		/**
		 * @param array $data
		 * @throws Sql\InvalidTableException
		 */
		public function setData( array $data )
		{
			$this->_insert->addData($data);
		}
		
		/**
		 * @param array $attributes
		 * @throws ArrayNotFoundException
		 */
		public function updateOrInsert( array $attributes )
		{
			$this->_update = new Update();
			$this->_update->addData($attributes);
		}
		
		/**
		 *
		 */
		public function formatSql()
		{
			$this->__sqlString = 'INSERT INTO ' . $this->_insert->getTable();
			$this->__sqlString .= ' (' . $this->_insert->getCols() . ')';
			$this->__sqlString .= ' VALUES (' . $this->_insert->getValues() . ')';
			
			if ( isset($this->_update) )
			{
				$this->__sqlString .= ' ON DUPLICATE KEY UPDATE ' . $this->_update->getColumnValues();
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
