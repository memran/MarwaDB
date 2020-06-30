<?php
	
	namespace MarwaDB;
	
	use MarwaDB\Exceptions\InvalidArgumentException;
	
	class QueryBuilder {
		
		/**
		 * @var string
		 */
		protected $_table;
		
		/**
		 * @var string
		 */
		protected $_driver = 'mysql';
		/**
		 * @var array
		 */
		protected $_methods = [];
		/**
		 * @var string
		 */
		protected $_builderName = 'select';
		/**
		 * @var string[]
		 */
		protected $_builderList = ['select', 'insert', 'update', 'delete'];
		/**
		 * @var array
		 */
		protected $_updateOrInsert = [];
		/**
		 * @var array
		 */
		protected $_data = [];
		/**
		 * @var DB
		 */
		protected $_db;
		
		/**
		 * QueryBuilder constructor.
		 *
		 * @param DB $db
		 * @param string $table
		 * @throws InvalidArgumentException
		 */
		public function __construct( DB $db, string $table )
		{
			$this->_db = $db;
			
			if ( empty($table) )
			{
				throw new InvalidArgumentException("Table name is empty", 1);
			}
			$this->_table = $table;
		}
		
		/**
		 * @param array $data
		 * @return mixed
		 * @throws InvalidArgumentException
		 */
		public function insert( array $data )
		{
			$count = 0;
			if ( Util::is_multi($data) )
			{
				foreach ( $data as $key => $value )
				{
					$res = $this->runQuery('insert', $value);
					if ( $res )
					{
						$count++;
					}
				}
			}
			else
			{
				return $this->runQuery('insert', $data);
			}
			
			return $count;
		}
		
		/**
		 * @param string $type
		 * @param array $data
		 * @return array
		 * @throws InvalidArgumentException
		 */
		protected function runQuery( $type, $data )
		{
			$this->setBuilderName($type);
			$this->setData($data);
			
			return $this->_db->raw($this->buildQuery());
		}
		
		/**
		 * @return mixed
		 */
		protected function buildQuery()
		{
			
			/**
			 * SelectBuilder Object based on Driver
			 */
			
			$builder = QueryFactory::getInstance(
				$this->getDriver(),
				$this->getBuilderName()
			);
			
			// Builder Director based on Driver
			$director = BuilderFactory::getInstance(
				$builder,
				$this->getBuilderName(),
				$this->getTable(),
				$this->getDriver()
			);
			
			/**
			 * set list of methods to build sql query
			 */
			if ( $this->getBuilderName() === 'insert' )
			{
				$director->updateOrInsert($this->getUpdateOrInsert());
				$director->setData($this->getdata());
			}
			else
			{
				if ( $this->getBuilderName() === 'update' )
				{
					$director->setData($this->getdata());
					$director->setMethods($this->_methods);
				}
				else
				{
					$director->setMethods($this->_methods);
				}
			}
			//build sql query
			$director->buildQuery();
			$this->reset();
			return $director->getSql();
			
		}
		
		/**
		 *
		 */
		protected function reset()
		{
			$this->_methods=[];
			$this->_data=[];
			$this->_updateOrInsert=[];
		}
		
		/**
		 * @return string
		 */
		public function getDriver() : string
		{
			return ucfirst(strtolower($this->_driver));
		}
		
		/**
		 * @param string $driver
		 * @return $this
		 */
		public function setDriver( string $driver )
		{
			$this->_driver = $driver;
			
			return $this;
		}
		
		/**
		 * @return string
		 */
		protected function getBuilderName() : string
		{
			return $this->_builderName;
		}
		
		/**
		 * @param string $name
		 * @throws InvalidArgumentException
		 */
		protected function setBuilderName( string $name ) : void
		{
			if ( in_array($name, $this->_builderList) )
			{
				$this->_builderName = strtolower($name);
			}
			else
			{
				throw new InvalidArgumentException("Wrong Builder name provided");
			}
		}
		
		/**
		 * @return string
		 */
		public function getTable() : string
		{
			return $this->_table;
		}
		
		/**
		 * @return array
		 */
		protected function getUpdateOrInsert()
		{
			return $this->_updateOrInsert;
		}
		
		/**
		 * @param array $attributes
		 */
		protected function setUpdateOrInsert( array $attributes )
		{
			$this->_updateOrInsert = $attributes;
		}
		
		/**
		 * @return array
		 */
		protected function getData()
		{
			return $this->_data;
		}
		
		/**
		 * @param array $data
		 */
		protected function setData( array $data )
		{
			$this->_data = $data;
		}
		
		/**
		 * @param array $data
		 * @return string
		 * @throws InvalidArgumentException
		 */
		public function insertGetId( array $data )
		{
			if ( !Util::is_multi($data) )
			{
				$this->runQuery('insert', $data);
				
				return $this->_db->getConnection()->getLastId();
			}
			else
			{
				throw new InvalidArgumentException("Multi-dimentional array not supported");
			}
		}
		
		/**
		 * @param array $data
		 * @return array|int
		 * @throws InvalidArgumentException
		 */
		public function update( array $data )
		{
			$this->setBuilderName('update');
			$this->setData($data);
			
			return $this->_db->raw($this->buildQuery());
		}
		
		/**
		 * @param array $attributes
		 * @param array $values
		 * @return array|int
		 * @throws InvalidArgumentException
		 */
		public function updateOrInsert( array $attributes, array $values )
		{
			$this->setBuilderName('insert');
			$this->setData($values);
			$this->setUpdateOrInsert($attributes);
			
			//dump($this->buildQuery());
			return $this->_db->raw($this->buildQuery());
		}
		
		/**
		 * @return array|int
		 * @throws InvalidArgumentException
		 */
		public function delete()
		{
			$this->setBuilderName('delete');
			
			return $this->_db->raw($this->buildQuery());
		}
		
		/**
		 *
		 */
		public function dd()
		{
			$this->dump();
			die;
		}
		
		/**
		 * @param string $type
		 * @throws InvalidArgumentException
		 */
		public function dump( string $type = 'select' )
		{
			$this->setBuilderName($type);
			dump($this->buildQuery());
		}
		
		/**
		 * @return array|int
		 * @throws InvalidArgumentException
		 */
		public function get()
		{
			$this->setBuilderName('select');
			
			return $this->_db->raw($this->buildQuery());
		}
		
		/**
		 * @return mixed
		 */
		public function __toString()
		{
			return $this->buildQuery();
		}
		
		/**
		 * @param string $method
		 * @param mixed $args
		 * @return $this
		 */
		public function __call( $method, $args )
		{
			$this->_methods[ $method ] = $args;
			
			return $this;
		}
		
		/**
		 * @return mixed
		 */
		public function toSql()
		{
			return $this->buildQuery();
		}
	}
