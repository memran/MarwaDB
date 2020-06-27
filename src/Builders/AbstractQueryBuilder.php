<?php
	/**
	 * @author    Mohammad Emran <memran.dhk@gmail.com>
	 * @copyright 2018
	 *
	 * @see https://www.github.com/memran
	 * @see http://www.memran.me
	 **/
	
	namespace MarwaDB\Builders;
	
	use MarwaDB\Builders\Common\BuilderInterface;
	
	abstract class AbstractQueryBuilder {
		
		/**
		 * AbstractQueryBuilder constructor.
		 * @param BuilderInterface $builder_in
		 * @param string $type
		 * @param string $table
		 */
		abstract public function __construct( BuilderInterface $builder_in, string $type, string $table );
		
		/**
		 * @param array $methods
		 * @return mixed
		 */
		abstract public function setMethods( array $methods );
		
		/**
		 * @return mixed
		 */
		abstract public function buildQuery();
		
		/**
		 * @return mixed
		 */
		abstract public function getSql();
	}
