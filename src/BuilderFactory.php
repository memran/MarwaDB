<?php
	
	namespace MarwaDB;
	
	use MarwaDB\Builders\Common\BuilderInterface;
	
	class BuilderFactory {
		
		/**
		 * @param BuilderInterface $instance
		 * @param string $type
		 * @param string $table
		 * @param string $driver
		 * @return mixed
		 */
		public static function getInstance( BuilderInterface $instance, string $type, string $table, string $driver )
		{
			$builder_director = '\\MarwaDB\\Builders\\' . $driver . '\\SqlBuilder';
			
			return new $builder_director($instance, $type, $table);
		}
	}
