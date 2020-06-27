<?php
	
	namespace MarwaDB\Builders\Common;
	
	abstract class AbstractQueryBuilder {
		
		/**
		 * AbstractQueryBuilder constructor.
		 * @param BuilderInterface $builder_in
		 */
		abstract public function __construct( BuilderInterface $builder_in );
		
		/**
		 * @return mixed
		 */
		abstract public function buildQuery();
		
		/**
		 * @return mixed
		 */
		abstract public function getSql();
	}
