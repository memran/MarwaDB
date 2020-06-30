<?php
	
	namespace MarwaDB\Builders\Mysql;
	
	use MarwaDB\Builders\AbstractQueryBuilder;
	use MarwaDB\Builders\Common\BuilderInterface;
	use MarwaDB\Builders\Common\BuildQueryTrait;
	use MarwaDB\Exceptions\MethodNotFoundException;
	use Throwable;
	
	class SqlBuilder extends AbstractQueryBuilder {
		use BuildQueryTrait;
		/**
		 * @var BuilderInterface
		 */
		protected $_builder;
		/**
		 * @var string
		 */
		protected $_type;
		
		/**
		 * @var array
		 */
		protected $_methods = [];
		/**
		 * @var string
		 */
		protected $_table;
		
		/**
		 * @var array
		 */
		protected $_data;
		
		/**
		 * @var array
		 */
		protected $_updateOrInsert;
		
		/**
		 * SqlBuilder constructor.
		 * @param BuilderInterface $builder_in
		 * @param string $type
		 * @param string $table
		 */
		public function __construct( BuilderInterface $builder_in, string $type, string $table )
		{
			$this->_builder = $builder_in;
			$this->_type = strtolower($type);
			$this->_table = $table;
		}
		
		
		
		/**
		 *
		 */
		protected function buildSelectQuery()
		{
			if ( !empty($this->getMethods()) )
			{
				foreach ( $this->getMethods() as $key => $value )
				{
					$this->execute($key, $value);
				}
			}
			$this->_builder->formatSql();
		}
		
		/**
		 * @return array
		 */
		public function getMethods()
		{
			return $this->_methods;
		}
		
		/**
		 * @param array $methods
		 */
		public function setMethods( $methods )
		{
			$this->_methods = $methods;
		}
		
		/**
		 * @param string $method
		 * @param mixed $args
		 * @throws MethodNotFoundException
		 */
		private function execute( $method, $args )
		{
			if ( method_exists($this->_builder, $method) )
			{
				try
				{
					call_user_func_array([$this->_builder, $method], $args);
				} catch ( Throwable $th )
				{
					throw new MethodNotFoundException("Error Processing Method Called : {$method}", 1);
				}
			}
		}
		
		/**
		 *
		 */
		protected function buildInsertQuery()
		{
			$this->_builder->setData($this->_data);
			if ( isset($this->_updateOrInsert) )
			{
				$this->_builder->updateOrInsert($this->_updateOrInsert);
			}
			$this->_builder->formatSql();
		}
		
		/**
		 * @throws MethodNotFoundException
		 */
		protected function buildDeleteQuery()
		{
			if ( !empty($this->getMethods()) )
			{
				foreach ( $this->getMethods() as $key => $value )
				{
					$this->execute($key, $value);
				}
			}
			$this->_builder->formatSql();
		}
		
		/**
		 * @throws MethodNotFoundException
		 */
		protected function buildUpdateQuery()
		{
			$this->_builder->setData($this->_data);
			if ( !empty($this->getMethods()) )
			{
				foreach ( $this->getMethods() as $key => $value )
				{
					$this->execute($key, $value);
				}
			}
			
			$this->_builder->formatSql();
		}
		
		/**
		 * @param array $data
		 */
		public function setData( array $data )
		{
			$this->_data = $data;
		}
		
		/**
		 * @param array $data
		 */
		public function updateOrInsert( array $data )
		{
			if ( !empty($data) )
			{
				$this->_updateOrInsert = $data;
			}
		}
		
		/**
		 * @return mixed
		 */
		public function getSql()
		{
			return $this->_builder->getSql();
		}
	}
